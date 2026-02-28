<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\HealthMetric;
use App\Models\PatientHealthData;
use App\Models\MedicalHistory;
use App\Models\MedicalRecord;
use App\Models\LabOrder;
use App\Models\Doctor;
use Carbon\Carbon;

class HealthPortfolioController extends Controller
{
    // ══════════════════════════════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════════════════════════════
    public function index()
    {
        $user    = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->route('patient.profile')
                ->with('error', 'Please complete your profile first.');
        }

        $pid = $patient->id;

        // ── Latest Health Data ─────────────────────────────────────────
        $healthData   = PatientHealthData::where('patient_id', $pid)
                            ->orderBy('recorded_date', 'desc')->first();
        $latestMetric = HealthMetric::where('patient_id', $pid)
                            ->orderBy('metric_date', 'desc')->first();

        // ── BMI ────────────────────────────────────────────────────────
        $bmi = $bmiCategory = null;
        $bmiColor = '#888';
        $bmiPct   = 0;

        $weight = $healthData?->weight ?? $latestMetric?->weight;
        $height = $healthData?->height ?? $latestMetric?->height;

        if ($weight && $height) {
            $h   = $height / 100;
            $bmi = round($weight / ($h * $h), 1);
            [$bmiCategory, $bmiColor] = match(true) {
                $bmi < 18.5 => ['Underweight', '#3b82f6'],
                $bmi < 25   => ['Normal',       '#22c55e'],
                $bmi < 30   => ['Overweight',   '#f59e0b'],
                default     => ['Obese',        '#ef4444'],
            };
            $bmiPct = min(100, max(0, round((($bmi - 15) / 25) * 100)));
        }

        // ── Age ────────────────────────────────────────────────────────
        $age = $patient->date_of_birth
            ? Carbon::parse($patient->date_of_birth)->age
            : null;

        // ── Health Score ───────────────────────────────────────────────
        $healthScore = $this->calculateHealthScore(
            $patient, $healthData, $latestMetric, $pid
        );

        // ── Medical History & Records ──────────────────────────────────
        $medicalHistory = MedicalHistory::where('patient_id', $pid)
                              ->orderBy('diagnosed_date', 'desc')->get();
        $medicalRecords = MedicalRecord::where('patient_id', $pid)
                              ->with('doctor')
                              ->orderBy('record_date', 'desc')
                              ->limit(8)->get();

        // ── Appointments ───────────────────────────────────────────────
        $completedAppointments = Appointment::where('patient_id', $pid)
                                     ->where('status', 'completed')->count();

        // ── Lab Orders ─────────────────────────────────────────────────
        $labOrders = LabOrder::where('patient_id', $pid)
                        ->orderBy('order_date', 'desc')->limit(6)->get();

        // ── Chart Metrics — last 6 months ──────────────────────────────
        $chartMetrics = HealthMetric::where('patient_id', $pid)
                            ->where('metric_date', '>=',
                                Carbon::now('Asia/Colombo')->subMonths(6))
                            ->orderBy('metric_date')
                            ->get([
                                'metric_date',
                                'weight',
                                'blood_pressure_systolic',
                                'blood_pressure_diastolic',
                                'heart_rate',
                                'blood_sugar',
                            ]);

        // ── Seasonal Tips ──────────────────────────────────────────────
        $seasonalTips = $this->getSeasonalTips(
            (int) Carbon::now('Asia/Colombo')->format('n')
        );

        // ── Recommendations ────────────────────────────────────────────
        $recommendations = $this->getHealthRecommendations(
            $healthData, $latestMetric, $bmi, $bmiCategory, $medicalHistory
        );

        return view('patient.health-portfolio', compact(
            'user', 'patient', 'healthData', 'latestMetric',
            'bmi', 'bmiCategory', 'bmiColor', 'bmiPct',
            'weight', 'height', 'age', 'healthScore',
            'medicalHistory', 'medicalRecords',
            'completedAppointments',
            'labOrders', 'chartMetrics',
            'seasonalTips', 'recommendations'
        ));
    }

    // ══════════════════════════════════════════════════════════════════
    //  SAVE HEALTH DATA
    // ══════════════════════════════════════════════════════════════════
    public function saveHealthData(Request $request)
    {
        $request->validate([
            'weight'                   => 'nullable|numeric|min:20|max:300',
            'height'                   => 'nullable|numeric|min:50|max:250',
            'waist'                    => 'nullable|numeric|min:30|max:200',
            'hip'                      => 'nullable|numeric|min:30|max:200',
            'blood_pressure_systolic'  => 'nullable|integer|min:60|max:250',
            'blood_pressure_diastolic' => 'nullable|integer|min:40|max:150',
            'heart_rate'               => 'nullable|integer|min:30|max:220',
            'temperature'              => 'nullable|numeric|min:34|max:42',
            'blood_sugar'              => 'nullable|numeric|min:40|max:600',
            'blood_sugar_pp'           => 'nullable|numeric|min:40|max:600',
            'cholesterol_total'        => 'nullable|numeric|min:50|max:600',
            'cholesterol_hdl'          => 'nullable|numeric|min:10|max:200',
            'cholesterol_ldl'          => 'nullable|numeric|min:10|max:400',
            'oxygen_saturation'        => 'nullable|integer|min:50|max:100',
            'sleep_hours'              => 'nullable|integer|min:1|max:24',
            'smoking_status'           => 'nullable|in:never,former,current',
            'alcohol_consumption'      => 'nullable|in:none,occasional,moderate,heavy',
            'exercise_frequency'       => 'nullable|in:none,1-2/week,3-4/week,5+/week',
            'diet_type'                => 'nullable|in:omnivore,vegetarian,vegan,other',
            'stress_level'             => 'nullable|in:low,moderate,high,very_high',
            'other_conditions'         => 'nullable|string|max:1000',
            'current_medications'      => 'nullable|string|max:1000',
            'allergies'                => 'nullable|string|max:500',
            'notes'                    => 'nullable|string|max:2000',
        ]);

        $patient = Auth::user()->patient;

        // Boolean fields — default to 0 if not submitted
        $boolFields = [
            'has_diabetes', 'has_hypertension', 'has_heart_disease',
            'has_asthma', 'has_kidney_disease', 'has_thyroid',
            'family_diabetes', 'family_heart_disease',
            'family_hypertension', 'family_cancer',
        ];
        $boolData = [];
        foreach ($boolFields as $field) {
            $boolData[$field] = $request->boolean($field);
        }

        $fillable = array_merge(
            $request->except(array_merge(['_token'], $boolFields)),
            $boolData,
            ['patient_id' => $patient->id]
        );

        PatientHealthData::updateOrCreate(
            ['patient_id' => $patient->id],
            array_merge($fillable, [
                'recorded_date' => Carbon::now('Asia/Colombo')->toDateString(),
            ])
        );

        // Sync to health_metrics for trend charts
        HealthMetric::updateOrCreate(
            [
                'patient_id'  => $patient->id,
                'metric_date' => Carbon::now('Asia/Colombo')->toDateString(),
            ],
            [
                'weight'                   => $request->weight,
                'height'                   => $request->height,
                'blood_pressure_systolic'  => $request->blood_pressure_systolic,
                'blood_pressure_diastolic' => $request->blood_pressure_diastolic,
                'heart_rate'               => $request->heart_rate,
                'temperature'              => $request->temperature,
                'blood_sugar'              => $request->blood_sugar,
            ]
        );

        return back()->with('success', 'Health data saved successfully!');
    }

    // ══════════════════════════════════════════════════════════════════
    //  DOCTOR PROFILE (Recommended Specialist → Book Appointment)
    // ══════════════════════════════════════════════════════════════════
    public function doctorProfile($id)
    {
        $doctor  = Doctor::with(['user', 'schedules', 'reviews'])
                       ->where('status', 'approved')
                       ->findOrFail($id);

        $patient = Auth::user()->patient;

        // Past appointments between this patient & doctor
        $pastAppointments = Appointment::where('patient_id', $patient?->id ?? 0)
                                ->where('doctor_id', $doctor->id)
                                ->orderBy('appointment_date', 'desc')
                                ->limit(5)
                                ->get();

        // Check if patient already left a review
        $existingReview = $doctor->reviews()
                              ->where('patient_id', $patient?->id ?? 0)
                              ->first();

        // Average rating
        $avgRating = $doctor->reviews()->avg('rating') ?? 0;

        // Available schedules (future dates)
        $schedules = $doctor->schedules()
                        ->where('available_date', '>=', today())
                        ->orderBy('available_date')
                        ->limit(14)
                        ->get();

        return view('patient.doctor-profile', compact(
            'doctor',
            'patient',
            'pastAppointments',
            'existingReview',
            'avgRating',
            'schedules'
        ));
    }

    // ══════════════════════════════════════════════════════════════════
    //  HEALTH SCORE (0–100)
    // ══════════════════════════════════════════════════════════════════
    private function calculateHealthScore($patient, $hd, $lm, $pid): array
    {
        $score   = 0;
        $details = [];

        // 1. Profile completeness — 15 pts
        $fields = ['firstname', 'nic', 'date_of_birth', 'gender',
                   'blood_group', 'phone', 'address'];
        $filled  = collect($fields)->filter(fn($f) => !empty($patient->$f))->count();
        $ps      = round(($filled / count($fields)) * 15);
        $score  += $ps;
        $details['profile'] = ['score' => $ps, 'max' => 15, 'label' => 'Profile'];

        // 2. BMI — 20 pts
        $bmiScore = 0;
        $w = $hd?->weight ?? $lm?->weight;
        $h = $hd?->height ?? $lm?->height;
        if ($w && $h) {
            $hm  = $h / 100;
            $bmi = $w / ($hm * $hm);
            $bmiScore = match(true) {
                $bmi >= 18.5 && $bmi < 25 => 20,
                $bmi >= 17   && $bmi < 30 => 12,
                $bmi >= 15   && $bmi < 35 => 6,
                default                   => 2,
            };
        }
        $score  += $bmiScore;
        $details['bmi'] = ['score' => $bmiScore, 'max' => 20, 'label' => 'BMI'];

        // 3. Blood Pressure — 15 pts
        $bpScore = 0;
        $sys = $hd?->blood_pressure_systolic ?? $lm?->blood_pressure_systolic ?? 0;
        if ($sys > 0) {
            $bpScore = match(true) {
                $sys < 120 => 15,
                $sys < 130 => 10,
                $sys < 140 => 5,
                default    => 1,
            };
        }
        $score  += $bpScore;
        $details['bp'] = ['score' => $bpScore, 'max' => 15, 'label' => 'Blood Pressure'];

        // 4. Blood Sugar — 15 pts
        $bsScore = 0;
        $bs = $hd?->blood_sugar ?? $lm?->blood_sugar ?? 0;
        if ($bs > 0) {
            $bsScore = match(true) {
                $bs < 100 => 15,
                $bs < 126 => 8,
                default   => 2,
            };
        }
        $score  += $bsScore;
        $details['sugar'] = ['score' => $bsScore, 'max' => 15, 'label' => 'Blood Sugar'];

        // 5. Lifestyle — 20 pts
        $lsScore = 0;
        if ($hd) {
            if ($hd->smoking_status === 'never')                         $lsScore += 5;
            elseif ($hd->smoking_status === 'former')                    $lsScore += 2;
            if ($hd->alcohol_consumption === 'none')                     $lsScore += 4;
            elseif ($hd->alcohol_consumption === 'occasional')           $lsScore += 2;
            if (in_array($hd->exercise_frequency, ['3-4/week','5+/week'])) $lsScore += 6;
            elseif ($hd->exercise_frequency === '1-2/week')              $lsScore += 3;
            if ($hd->sleep_hours >= 7 && $hd->sleep_hours <= 9)         $lsScore += 5;
            elseif ($hd->sleep_hours >= 6)                               $lsScore += 2;
        }
        $score  += min($lsScore, 20);
        $details['lifestyle'] = ['score' => min($lsScore, 20), 'max' => 20, 'label' => 'Lifestyle'];

        // 6. Conditions — 15 pts (deducted per condition)
        $cScore = 15;
        if ($hd) {
            if ($hd->has_diabetes)       $cScore -= 4;
            if ($hd->has_hypertension)   $cScore -= 3;
            if ($hd->has_heart_disease)  $cScore -= 4;
            if ($hd->has_asthma)         $cScore -= 2;
            if ($hd->has_kidney_disease) $cScore -= 3;
            if ($hd->has_thyroid)        $cScore -= 2;
        }
        $cScore  = max(0, $cScore);
        $score  += $cScore;
        $details['conditions'] = ['score' => $cScore, 'max' => 15, 'label' => 'Conditions'];

        $score = min(100, $score);

        [$level, $color] = match(true) {
            $score >= 80 => ['Excellent', '#22c55e'],
            $score >= 60 => ['Good',      '#3b82f6'],
            $score >= 40 => ['Fair',      '#f59e0b'],
            default      => ['Poor',      '#ef4444'],
        };

        return compact('score', 'level', 'color', 'details');
    }

    // ══════════════════════════════════════════════════════════════════
    //  SEASONAL TIPS
    // ══════════════════════════════════════════════════════════════════
    private function getSeasonalTips(int $month): array
    {
        $tips = [
            1  => [
                'festival'   => 'Thai Pongal / Duruthu Perahera',
                'icon'       => 'fa-sun',
                'color'      => '#f59e0b',
                'food_alert' => 'Pongal, Kiribath — high GI. Diabetics: limit portions.',
                'tips'       => [
                    'Stay hydrated at outdoor events',
                    'Limit jaggery & sweet rice',
                    'Walk after meals',
                    'Wear sunscreen — high UV index',
                ],
            ],
            2  => [
                'festival'   => 'Inter-Monsoon',
                'icon'       => 'fa-heart',
                'color'      => '#ef4444',
                'food_alert' => 'Chocolates & rich desserts — limit if diabetic or overweight.',
                'tips'       => [
                    'Good season for morning exercise',
                    'Dengue risk rising — remove stagnant water',
                    'Schedule your annual health check-up',
                ],
            ],
            3  => [
                'festival'   => 'Maha Shivaratri',
                'icon'       => 'fa-leaf',
                'color'      => '#22c55e',
                'food_alert' => 'Fasting is common — ensure hydration and gradual meal resumption.',
                'tips'       => [
                    'Increase water intake',
                    'Check BP & blood sugar this month',
                    'Consult a doctor before fasting if diabetic',
                ],
            ],
            4  => [
                'festival'   => 'Sinhala & Tamil New Year (Avurudu)',
                'icon'       => 'fa-star',
                'color'      => '#8b5cf6',
                'food_alert' => 'Kavum, Kokis, Kiribath — very high fat & sugar. 1 Kavum ≈ 180 kcal. Diabetics: max 2 pieces/day.',
                'tips'       => [
                    '⚠️ Kavum & Kokis: enjoy max 2 per day',
                    'Pair Kiribath with lunu miris, not sugar',
                    'Hottest month — stay cool and drink coconut water',
                    'Walk 30 min daily to offset festive caloric intake',
                ],
            ],
            5  => [
                'festival'   => 'Vesak Poya',
                'icon'       => 'fa-dharmachakra',
                'color'      => '#f59e0b',
                'food_alert' => 'Dansala street food — hygiene varies. Avoid if you have IBS or food allergies.',
                'tips'       => [
                    'Carry your own water bottle',
                    'Meditation is excellent for mental health this month',
                    'Hydrate well during outdoor walks and lantern festivals',
                ],
            ],
            6  => [
                'festival'   => 'South-West Monsoon Begins',
                'icon'       => 'fa-cloud-rain',
                'color'      => '#3b82f6',
                'food_alert' => 'Rainy season — warm ginger & turmeric drinks boost immunity. Avoid cold beverages.',
                'tips'       => [
                    '⚠️ Dengue peak — eliminate all stagnant water',
                    'Drink warm ginger or turmeric tea daily',
                    'Dry shoes properly — fungal infections rise',
                    'Avoid cold drinks if prone to throat infections',
                ],
            ],
            7  => [
                'festival'   => 'Esala Perahera',
                'icon'       => 'fa-elephant',
                'color'      => '#f59e0b',
                'food_alert' => 'Late-night festival fried foods — avoid heavy meals after 8 PM.',
                'tips'       => [
                    'Carry personal medication to crowded events',
                    'Asthma patients: be cautious of parade dust & smoke',
                    'Stay hydrated at outdoor events',
                ],
            ],
            8  => [
                'festival'   => 'Nikini Poya',
                'icon'       => 'fa-moon',
                'color'      => '#6366f1',
                'food_alert' => 'Monsoon continues — warm cooked foods recommended over raw street food.',
                'tips'       => [
                    'Good time for a dental check-up',
                    'Dengue still active — inspect roof gutters',
                    'Eat calcium-rich foods: jak, gotukola, dried fish',
                ],
            ],
            9  => [
                'festival'   => 'Binara Poya',
                'icon'       => 'fa-wind',
                'color'      => '#64748b',
                'food_alert' => 'Seasonal fruits: jak, papaya, avocado — excellent natural nutrition.',
                'tips'       => [
                    'Add one seasonal fruit to every meal',
                    'Check children\'s vaccination schedules',
                    'Avoid prolonged sitting — protect joint health',
                ],
            ],
            10 => [
                'festival'   => 'Deepavali / Vap Poya',
                'icon'       => 'fa-fire',
                'color'      => '#f59e0b',
                'food_alert' => 'Halwa, Murukku, Laddu — high sugar & fat. BP & diabetic patients: strictly limit.',
                'tips'       => [
                    'Limit sweets to 1–2 small servings per day',
                    '⚠️ Firecracker smoke — asthma patients stay indoors',
                    'Walk 30 min after festive meals to manage blood sugar',
                ],
            ],
            11 => [
                'festival'   => 'Il Poya / Year-End Prep',
                'icon'       => 'fa-leaf',
                'color'      => '#22c55e',
                'food_alert' => 'Year-end stress often leads to emotional eating. Practice mindful eating this month.',
                'tips'       => [
                    'Schedule all pending health screenings before year-end',
                    'Manage work-related year-end stress proactively',
                    'Maintain consistent sleep of 7–8 hours',
                ],
            ],
            12 => [
                'festival'   => 'Christmas & New Year',
                'icon'       => 'fa-gifts',
                'color'      => '#ef4444',
                'food_alert' => 'Christmas cake, wine, party food — very high in calories. Alcohol interacts with many medications.',
                'tips'       => [
                    'Limit alcohol — it interferes with common medications',
                    'Maximum 1 small slice of Christmas cake per day',
                    'Maintain your exercise routine despite celebrations',
                    'Stay hydrated in air-conditioned shopping malls',
                ],
            ],
        ];

        return $tips[$month] ?? $tips[1];
    }

    // ══════════════════════════════════════════════════════════════════
    //  HEALTH RECOMMENDATIONS
    // ══════════════════════════════════════════════════════════════════
    private function getHealthRecommendations($hd, $lm, $bmi, $bmiCat, $history): array
    {
        $recs = [];

        if (!$hd && !$lm) {
            $recs[] = [
                'type'  => 'warning',
                'icon'  => 'fa-circle-exclamation',
                'title' => 'Health Data Needed',
                'text'  => 'Fill in your health data above to receive personalized recommendations tailored to your profile.',
            ];
            return $recs;
        }

        // BMI
        if ($bmi) {
            $recs[] = match($bmiCat) {
                'Underweight' => [
                    'type'    => 'info',
                    'icon'    => 'fa-utensils',
                    'title'   => 'Increase Nutritional Intake',
                    'text'    => "Your BMI is {$bmi} — below the healthy range. Focus on calorie-dense, nutritious foods.",
                    'details' => [
                        'Add eggs, dairy, nuts, legumes, and healthy fats to every meal',
                        'Eat 5–6 small meals per day instead of 3 large ones',
                        'Consider a nutritionist consultation for a weight-gain plan',
                        'Combine with light strength training to build muscle mass',
                    ],
                ],
                'Overweight' => [
                    'type'    => 'warning',
                    'icon'    => 'fa-person-walking',
                    'title'   => 'Weight Management Required',
                    'text'    => "Your BMI is {$bmi} — above the healthy range. Gradual weight loss of 0.5–1 kg/week is recommended.",
                    'details' => [
                        'Walk briskly for at least 45 minutes per day',
                        'Reduce refined carbohydrates: white rice, bread, pastries',
                        'Replace sugary drinks with water or herbal tea',
                        'Track meals using a food diary or mobile app',
                    ],
                ],
                'Obese' => [
                    'type'    => 'danger',
                    'icon'    => 'fa-triangle-exclamation',
                    'title'   => 'Obesity — Medical Attention Needed',
                    'text'    => "Your BMI is {$bmi} — classified as obese. This significantly raises your risk of diabetes, heart disease, and joint problems.",
                    'details' => [
                        'Consult a doctor or endocrinologist urgently',
                        'Reduce daily caloric intake by 500–700 kcal',
                        'Begin with low-impact exercise: walking, swimming',
                        'Completely eliminate fried foods, sugary drinks, and fast food',
                        'Set a realistic goal: losing 5–10% body weight in 3 months',
                    ],
                ],
                default => [
                    'type'    => 'success',
                    'icon'    => 'fa-check-circle',
                    'title'   => 'Healthy BMI — Keep It Up!',
                    'text'    => "Your BMI is {$bmi} — within the healthy range. Maintain your current diet and exercise habits.",
                    'details' => [
                        'Continue exercising 3–5 times per week',
                        'Maintain a balanced diet rich in vegetables and protein',
                        'Schedule an annual health check-up',
                    ],
                ],
            };
        }

        // Blood Pressure
        $sys = $hd?->blood_pressure_systolic ?? $lm?->blood_pressure_systolic ?? 0;
        if ($sys >= 140) {
            $recs[] = [
                'type'    => 'danger',
                'icon'    => 'fa-heart-pulse',
                'title'   => 'High Blood Pressure — Urgent Action Needed',
                'text'    => "Your systolic BP is {$sys} mmHg — Stage 2 hypertension. This requires immediate medical attention.",
                'details' => [
                    'Reduce daily salt intake to less than 5g',
                    'Avoid processed foods, pickles, and canned items',
                    'Take prescribed BP medications without skipping',
                    'Monitor BP twice daily and maintain a log',
                    'Consult a Cardiologist as soon as possible',
                ],
            ];
        } elseif ($sys >= 130) {
            $recs[] = [
                'type'    => 'warning',
                'icon'    => 'fa-heart-pulse',
                'title'   => 'Elevated Blood Pressure',
                'text'    => "Your BP is {$sys} mmHg — slightly above normal. Lifestyle changes can bring it back to the healthy range.",
                'details' => [
                    'Follow the DASH diet: fruits, vegetables, low-fat dairy',
                    'Limit caffeine to 1–2 cups per day',
                    'Exercise 30 minutes per day, 5 days a week',
                    'Practise deep breathing or meditation for 10 min daily',
                ],
            ];
        }

        // Blood Sugar
        $bs = $hd?->blood_sugar ?? $lm?->blood_sugar ?? 0;
        if ($bs >= 126) {
            $recs[] = [
                'type'    => 'danger',
                'icon'    => 'fa-droplet',
                'title'   => 'Diabetic Blood Sugar Range — Urgent',
                'text'    => "Your fasting blood sugar is {$bs} mg/dL — in the diabetic range. Please consult an Endocrinologist immediately.",
                'details' => [
                    'Stop all sugary foods, white rice, and sweetened beverages',
                    'Check blood sugar before and 2 hours after each meal',
                    'Never skip prescribed diabetes medications',
                    'Walk for 30 minutes after every meal',
                    'Get HbA1c tested every 3 months',
                ],
            ];
        } elseif ($bs >= 100) {
            $recs[] = [
                'type'    => 'warning',
                'icon'    => 'fa-droplet',
                'title'   => 'Pre-Diabetic Range — Act Now',
                'text'    => "Your fasting blood sugar is {$bs} mg/dL — pre-diabetic. You can reverse this with lifestyle changes.",
                'details' => [
                    'Reduce refined carbs: white rice, bread, sweets',
                    'Increase dietary fibre: vegetables, lentils, oats',
                    'Walk 30 minutes after each meal',
                    'Check blood sugar every month',
                ],
            ];
        }

        // Smoking
        if ($hd?->smoking_status === 'current') {
            $recs[] = [
                'type'    => 'danger',
                'icon'    => 'fa-smoking-ban',
                'title'   => 'Stop Smoking Immediately',
                'text'    => 'Smoking increases your risk of lung cancer, heart attack, and stroke by 2–4 times.',
                'details' => [
                    'Set a firm quit date and inform your family for support',
                    'Use nicotine replacement: patches, gum, or lozenges',
                    'Ask your doctor about Varenicline (Champix) medication',
                    'Replace the habit with deep breathing or chewing sugar-free gum',
                ],
            ];
        }

        // Stress
        if (in_array($hd?->stress_level, ['high', 'very_high'])) {
            $recs[] = [
                'type'    => 'warning',
                'icon'    => 'fa-brain',
                'title'   => 'High Stress — Prioritise Mental Health',
                'text'    => 'Chronic stress raises cortisol, which increases BP, blood sugar, and weakens your immune system.',
                'details' => [
                    'Practice mindfulness meditation for 10–15 minutes daily',
                    'Regular exercise is the most effective natural stress reliever',
                    'Limit social media and news to 30 minutes per day',
                    'Talk to a trusted friend, family member, or counsellor',
                    'Consider consulting a Psychiatrist if stress persists',
                ],
            ];
        }

        // Sleep
        if ($hd?->sleep_hours && ($hd->sleep_hours < 6 || $hd->sleep_hours > 10)) {
            $recs[] = [
                'type'    => 'warning',
                'icon'    => 'fa-moon',
                'title'   => 'Improve Your Sleep',
                'text'    => "You are sleeping {$hd->sleep_hours} hours per night. The optimal range for adults is 7–9 hours.",
                'details' => [
                    'Set a consistent bedtime and wake-up time every day',
                    'Avoid screens (phone, TV) at least 1 hour before bed',
                    'Keep your bedroom dark, cool (18–22°C), and quiet',
                    'Limit caffeine after 2 PM',
                ],
            ];
        }

        // Alcohol
        if (in_array($hd?->alcohol_consumption, ['moderate', 'heavy'])) {
            $recs[] = [
                'type'    => 'warning',
                'icon'    => 'fa-wine-bottle',
                'title'   => 'Reduce Alcohol Consumption',
                'text'    => 'Moderate to heavy alcohol use raises your risk of liver disease, high BP, and several cancers.',
                'details' => [
                    'Set a weekly alcohol limit and track consumption',
                    'Replace with sparkling water, coconut water, or herbal tea',
                    'Identify social triggers and plan alternatives',
                    'Seek professional help if you feel alcohol-dependent',
                ],
            ];
        }

        // Exercise
        if (in_array($hd?->exercise_frequency, ['none', null, ''])) {
            $recs[] = [
                'type'    => 'warning',
                'icon'    => 'fa-person-running',
                'title'   => 'Start Exercising Regularly',
                'text'    => 'Physical inactivity is a major risk factor for diabetes, heart disease, and obesity.',
                'details' => [
                    'Start with a 20-minute walk every morning',
                    'Build up to 150 minutes of moderate activity per week',
                    'Try yoga, swimming, or cycling for variety',
                    'Take the stairs instead of the lift every day',
                ],
            ];
        }

        // If all looks good
        if (empty($recs)) {
            $recs[] = [
                'type'    => 'success',
                'icon'    => 'fa-star',
                'title'   => '🌟 Excellent Health Profile!',
                'text'    => 'Your health data looks great across all categories. Keep up these excellent habits.',
                'details' => [
                    'Continue exercising regularly — maintain your streak',
                    'Schedule an annual comprehensive health check-up',
                    'Stay hydrated: 8–10 glasses of water daily',
                    'Keep prioritising sleep and stress management',
                ],
            ];
        }

        return $recs;
    }

}
