<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Foreign key checks disable ───────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ─── Tables truncate (order matters) ──────────────────────
        DB::table('medical_centres')->truncate();
        DB::table('pharmacies')->truncate();
        DB::table('laboratories')->truncate();
        DB::table('hospitals')->truncate();
        DB::table('doctor_workplaces')->truncate();
        DB::table('doctor_schedules')->truncate();
        DB::table('doctors')->truncate();
        DB::table('patients')->truncate();
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ─── Common password ───────────────────────────────────────
        $password = Hash::make('helthnet2026');

        $this->command->info('🔄 Seeding started...');

        // ==============================================================
        // 1. ADMIN
        // ==============================================================
        DB::table('users')->insert([
            'email'             => 'admin@healthnet.lk',
            'password'          => $password,
            'user_type'         => 'admin',
            'status'            => 'active',
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $adminId = DB::table('users')->where('email', 'admin@healthnet.lk')->value('id');
        $this->command->info("✅ Admin created (ID: $adminId)");

        // ==============================================================
        // 2. PATIENTS (5)
        // ==============================================================
        $patients = [
            [
                'email'      => 'nimala.perera@healthnet.lk',
                'first_name' => 'Nimala',
                'last_name'  => 'Perera',
                'nic'        => '198805123456',
                'dob'        => '1988-05-12',
                'gender'     => 'female',
                'blood'      => 'B+',
                'phone'      => '0712345678',
                'address'    => 'No.45, Kandy Road, Kelaniya',
                'city'       => 'Kelaniya',
                'province'   => 'Western',
                'postal'     => '11300',
                'ec_name'    => 'Sunil Perera',
                'ec_phone'   => '0718887766',
            ],
            [
                'email'      => 'kamali.desilva@healthnet.lk',
                'first_name' => 'Kamali',
                'last_name'  => 'De Silva',
                'nic'        => '199210089023',
                'dob'        => '1992-10-08',
                'gender'     => 'female',
                'blood'      => 'O+',
                'phone'      => '0723456789',
                'address'    => 'No.12, Galle Road, Matara',
                'city'       => 'Matara',
                'province'   => 'Southern',
                'postal'     => '81000',
                'ec_name'    => 'Ruwan De Silva',
                'ec_phone'   => '0729990001',
            ],
            [
                'email'      => 'sanduni.wije@healthnet.lk',
                'first_name' => 'Sanduni',
                'last_name'  => 'Wijesinghe',
                'nic'        => '200002152344',
                'dob'        => '2000-02-15',
                'gender'     => 'female',
                'blood'      => 'A+',
                'phone'      => '0734567890',
                'address'    => 'No.78, Temple Road, Nugegoda',
                'city'       => 'Nugegoda',
                'province'   => 'Western',
                'postal'     => '10250',
                'ec_name'    => 'Pradeep Wijesinghe',
                'ec_phone'   => '0740001122',
            ],
            [
                'email'      => 'ravindu.jayawardena@healthnet.lk',
                'first_name' => 'Ravindu',
                'last_name'  => 'Jayawardena',
                'nic'        => '199507231890',
                'dob'        => '1995-07-23',
                'gender'     => 'male',
                'blood'      => 'AB+',
                'phone'      => '0745678901',
                'address'    => 'No.15, Peradeniya Road, Kandy',
                'city'       => 'Kandy',
                'province'   => 'Central',
                'postal'     => '20000',
                'ec_name'    => 'Priyanka Jayawardena',
                'ec_phone'   => '0751234567',
            ],
            [
                'email'      => 'dulani.bandara@healthnet.lk',
                'first_name' => 'Dulani',
                'last_name'  => 'Bandara',
                'nic'        => '199312301234',
                'dob'        => '1993-12-30',
                'gender'     => 'female',
                'blood'      => 'O-',
                'phone'      => '0756789012',
                'address'    => 'No.22, Main Street, Kurunegala',
                'city'       => 'Kurunegala',
                'province'   => 'North Western',
                'postal'     => '60000',
                'ec_name'    => 'Nimal Bandara',
                'ec_phone'   => '0762345678',
            ],
        ];

        foreach ($patients as $i => $p) {
            $userId = DB::table('users')->insertGetId([
                'email'             => $p['email'],
                'password'          => $password,
                'user_type'         => 'patient',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('patients')->insert([
                'user_id'                 => $userId,
                'first_name'              => $p['first_name'],
                'last_name'               => $p['last_name'],
                'nic'                     => $p['nic'],
                'date_of_birth'           => $p['dob'],
                'gender'                  => $p['gender'],
                'blood_group'             => $p['blood'],
                'phone'                   => $p['phone'],
                'address'                 => $p['address'],
                'city'                    => $p['city'],
                'province'                => $p['province'],
                'postal_code'             => $p['postal'],
                'emergency_contact_name'  => $p['ec_name'],
                'emergency_contact_phone' => $p['ec_phone'],
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);

            $this->command->info("  ✅ Patient " . ($i + 1) . ": {$p['first_name']} {$p['last_name']}");
        }

        // ==============================================================
        // 3. DOCTORS (5)
        // ==============================================================
        $doctors = [
            [
                'email'      => 'dr.samantha.fernando@healthnet.lk',
                'slmc'       => 'SLMC-20045',
                'first_name' => 'Samantha',
                'last_name'  => 'Fernando',
                'spec'       => 'Cardiology',
                'quals'      => 'MBBS (Colombo), MD (Cardiology), MRCP (UK)',
                'exp'        => 12,
                'phone'      => '0754321098',
                'fee'        => 5000.00,
                'bio'        => 'Senior cardiologist specializing in interventional cardiology and heart failure management.',
            ],
            [
                'email'      => 'dr.anura.ruwan@healthnet.lk',
                'slmc'       => 'SLMC-30078',
                'first_name' => 'Anura',
                'last_name'  => 'Ruwan',
                'spec'       => 'Neurology',
                'quals'      => 'MBBS (Peradeniya), MD (Neurology)',
                'exp'        => 9,
                'phone'      => '0765432109',
                'fee'        => 4500.00,
                'bio'        => 'Neurologist with expertise in stroke management, epilepsy and headache disorders.',
            ],
            [
                'email'      => 'dr.priyanthi.jayasena@healthnet.lk',
                'slmc'       => 'SLMC-40112',
                'first_name' => 'Priyanthi',
                'last_name'  => 'Jayasena',
                'spec'       => 'Gynaecology',
                'quals'      => 'MBBS, MS (Obs & Gynae)',
                'exp'        => 15,
                'phone'      => '0776543210',
                'fee'        => 4000.00,
                'bio'        => 'Experienced gynaecologist and obstetrician providing comprehensive women\'s health care.',
            ],
            [
                'email'      => 'dr.rohan.wickrama@healthnet.lk',
                'slmc'       => 'SLMC-50234',
                'first_name' => 'Rohan',
                'last_name'  => 'Wickramasinghe',
                'spec'       => 'Orthopaedics',
                'quals'      => 'MBBS, MS (Ortho), FRCS',
                'exp'        => 11,
                'phone'      => '0787654321',
                'fee'        => 4500.00,
                'bio'        => 'Orthopaedic surgeon specializing in joint replacement, sports injuries and trauma surgery.',
            ],
            [
                'email'      => 'dr.malini.senanayake@healthnet.lk',
                'slmc'       => 'SLMC-60345',
                'first_name' => 'Malini',
                'last_name'  => 'Senanayake',
                'spec'       => 'Dermatology',
                'quals'      => 'MBBS, MD (Dermatology)',
                'exp'        => 7,
                'phone'      => '0798765432',
                'fee'        => 3500.00,
                'bio'        => 'Dermatologist specializing in skin disorders, cosmetic dermatology and allergy management.',
            ],
        ];

        foreach ($doctors as $i => $d) {
            $userId = DB::table('users')->insertGetId([
                'email'             => $d['email'],
                'password'          => $password,
                'user_type'         => 'doctor',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('doctors')->insert([
                'user_id'          => $userId,
                'status'           => 'approved',
                'slmc_number'      => $d['slmc'],
                'first_name'       => $d['first_name'],
                'last_name'        => $d['last_name'],
                'specialization'   => $d['spec'],
                'qualifications'   => $d['quals'],
                'experience_years' => $d['exp'],
                'phone'            => $d['phone'],
                'consultation_fee' => $d['fee'],
                'bio'              => $d['bio'],
                'rating'           => 0.00,
                'total_ratings'    => 0,
                'approved_by'      => $adminId,
                'approved_at'      => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            $this->command->info("  ✅ Doctor " . ($i + 1) . ": Dr. {$d['first_name']} {$d['last_name']}");
        }

        // ==============================================================
        // 4. HOSPITALS (5)
        // ==============================================================
        $hospitals = [
            [
                'email'    => 'kandy.hospital@healthnet.lk',
                'name'     => 'Kandy Teaching Hospital',
                'type'     => 'government',
                'reg'      => 'HOS-2002',
                'phone'    => '0812222261',
                'address'  => 'Kandy Teaching Hospital, Kandy',
                'city'     => 'Kandy',
                'province' => 'Central',
                'postal'   => '20000',
                'lat'      => 7.29301,
                'lng'      => 80.63501,
                'desc'     => 'Major tertiary care teaching hospital in the Central Province offering specialist care, trauma and maternity services.',
            ],
            [
                'email'    => 'galle.hospital@healthnet.lk',
                'name'     => 'Karapitiya Teaching Hospital',
                'type'     => 'government',
                'reg'      => 'HOS-3003',
                'phone'    => '0912222204',
                'address'  => 'Karapitiya, Galle',
                'city'     => 'Galle',
                'province' => 'Southern',
                'postal'   => '80000',
                'lat'      => 6.05295,
                'lng'      => 80.22097,
                'desc'     => 'Southern Province\'s main teaching hospital providing cardiology, oncology and neurology specialist services.',
            ],
            [
                'email'    => 'asiri.hospital@healthnet.lk',
                'name'     => 'Asiri Central Hospital',
                'type'     => 'private',
                'reg'      => 'HOS-4004',
                'phone'    => '0112466100',
                'address'  => 'No.114, Norris Canal Road, Colombo 10',
                'city'     => 'Colombo',
                'province' => 'Western',
                'postal'   => '01000',
                'lat'      => 6.91720,
                'lng'      => 79.86733,
                'desc'     => 'Leading private multi-specialty hospital providing advanced surgical, medical and diagnostic services.',
            ],
            [
                'email'    => 'jaffna.hospital@healthnet.lk',
                'name'     => 'Jaffna Teaching Hospital',
                'type'     => 'government',
                'reg'      => 'HOS-5005',
                'phone'    => '0212222261',
                'address'  => 'Hospital Road, Jaffna',
                'city'     => 'Jaffna',
                'province' => 'Northern',
                'postal'   => '40000',
                'lat'      => 9.66845,
                'lng'      => 80.00736,
                'desc'     => 'Main government teaching hospital in the Northern Province providing specialist care and emergency services.',
            ],
            [
                'email'    => 'nawaloka.hospital@healthnet.lk',
                'name'     => 'Nawaloka Hospital',
                'type'     => 'private',
                'reg'      => 'HOS-6006',
                'phone'    => '0114306306',
                'address'  => 'No.23, Deshamanya H.K. Dharmadasa Mawatha, Colombo 02',
                'city'     => 'Colombo',
                'province' => 'Western',
                'postal'   => '00200',
                'lat'      => 6.90853,
                'lng'      => 79.86209,
                'desc'     => 'One of Sri Lanka\'s largest private hospitals offering comprehensive specialist care and advanced diagnostics.',
            ],
        ];

        foreach ($hospitals as $i => $h) {
            $userId = DB::table('users')->insertGetId([
                'email'             => $h['email'],
                'password'          => $password,
                'user_type'         => 'hospital',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('hospitals')->insert([
                'user_id'             => $userId,
                'status'              => 'approved',
                'name'                => $h['name'],
                'type'                => $h['type'],
                'registration_number' => $h['reg'],
                'phone'               => $h['phone'],
                'email'               => $h['email'],
                'address'             => $h['address'],
                'city'                => $h['city'],
                'province'            => $h['province'],
                'postal_code'         => $h['postal'],
                'latitude'            => $h['lat'],
                'longitude'           => $h['lng'],
                'description'         => $h['desc'],
                'rating'              => 0.00,
                'total_ratings'       => 0,
                'approved_by'         => $adminId,
                'approved_at'         => now(),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            $this->command->info("  ✅ Hospital " . ($i + 1) . ": {$h['name']}");
        }

        // ==============================================================
        // 5. LABORATORIES (5)
        // ==============================================================
        $labs = [
            [
                'email'    => 'durdans.lab@healthnet.lk',
                'name'     => 'Durdans Laboratory Services',
                'reg'      => 'LAB-3001',
                'phone'    => '0112140000',
                'address'  => 'No.3, Alfred Place, Colombo 03',
                'city'     => 'Colombo',
                'province' => 'Western',
                'postal'   => '00300',
                'hours'    => 'Mon-Fri: 07:00-19:00 | Sat: 07:00-14:00',
                'desc'     => 'Accredited clinical diagnostic laboratory offering pathology, biochemistry and microbiology services.',
                'services' => '["Full Blood Count","Lipid Profile","HbA1c","Thyroid Function","Liver Function Tests","Urine Analysis","PCR Tests"]',
            ],
            [
                'email'    => 'lancet.lab@healthnet.lk',
                'name'     => 'Lancet Laboratories',
                'reg'      => 'LAB-4002',
                'phone'    => '0112508400',
                'address'  => 'No.28, Barnes Place, Colombo 07',
                'city'     => 'Colombo',
                'province' => 'Western',
                'postal'   => '00700',
                'hours'    => 'Mon-Sat: 06:30-18:00',
                'desc'     => 'ISO-certified diagnostic laboratory offering full range pathology and molecular diagnostic services.',
                'services' => '["Blood Tests","Urine Analysis","CT Scan","MRI","Allergy Tests","Cancer Markers","Microbiology"]',
            ],
            [
                'email'    => 'hemas.lab@healthnet.lk',
                'name'     => 'Hemas Diagnostics',
                'reg'      => 'LAB-5003',
                'phone'    => '0112343450',
                'address'  => 'No.75, Braybrooke Place, Colombo 02',
                'city'     => 'Colombo',
                'province' => 'Western',
                'postal'   => '00200',
                'hours'    => 'Mon-Fri: 07:00-17:00 | Sat: 07:00-12:00',
                'desc'     => 'Modern diagnostic laboratory providing fast turnaround for clinical and wellness testing.',
                'services' => '["CBC","ESR","Blood Sugar Tests","Kidney Function","Cardiac Markers","Hormone Tests","X-Ray"]',
            ],
            [
                'email'    => 'kandy.lab@healthnet.lk',
                'name'     => 'Kandy Medical Diagnostics',
                'reg'      => 'LAB-6004',
                'phone'    => '0812225678',
                'address'  => 'No.15, Colombo Street, Kandy',
                'city'     => 'Kandy',
                'province' => 'Central',
                'postal'   => '20000',
                'hours'    => 'Mon-Sat: 07:30-17:00',
                'desc'     => 'Leading diagnostic laboratory in the Central Province with rapid turnaround for routine and specialist tests.',
                'services' => '["Full Blood Count","Urine Analysis","Lipid Profile","Blood Sugar","Thyroid Tests","ECG","Echo Cardiogram"]',
            ],
            [
                'email'    => 'galle.lab@healthnet.lk',
                'name'     => 'Southern Diagnostics Galle',
                'reg'      => 'LAB-7005',
                'phone'    => '0912234567',
                'address'  => 'No.8, Wakwella Road, Galle',
                'city'     => 'Galle',
                'province' => 'Southern',
                'postal'   => '80000',
                'hours'    => 'Mon-Fri: 07:00-17:00 | Sat: 07:00-13:00',
                'desc'     => 'Fully equipped diagnostic centre providing clinical laboratory and radiology services in Southern Province.',
                'services' => '["Blood Tests","Urine Analysis","X-Ray","Ultrasound Scan","Blood Sugar","Liver Function","Pathology"]',
            ],
        ];

        foreach ($labs as $i => $l) {
            $userId = DB::table('users')->insertGetId([
                'email'             => $l['email'],
                'password'          => $password,
                'user_type'         => 'laboratory',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('laboratories')->insert([
                'user_id'             => $userId,
                'status'              => 'approved',
                'name'                => $l['name'],
                'registration_number' => $l['reg'],
                'phone'               => $l['phone'],
                'email'               => $l['email'],
                'address'             => $l['address'],
                'city'                => $l['city'],
                'province'            => $l['province'],
                'postal_code'         => $l['postal'],
                'operating_hours'     => $l['hours'],
                'description'         => $l['desc'],
                'services'            => $l['services'],
                'rating'              => 0.00,
                'total_ratings'       => 0,
                'approved_by'         => $adminId,
                'approved_at'         => now(),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            $this->command->info("  ✅ Laboratory " . ($i + 1) . ": {$l['name']}");
        }

        // ==============================================================
        // 6. PHARMACIES (5)
        // ==============================================================
        $pharmacies = [
            [
                'email'     => 'keells.pharmacy.kandy@healthnet.lk',
                'name'      => 'Keells Pharmacy – Kandy',
                'reg'       => 'PHA-2002',
                'ph_name'   => 'Priyanka Bandara',
                'ph_license'=> 'PL-20456',
                'phone'     => '0812345001',
                'address'   => 'No.24, Dalada Veediya, Kandy',
                'city'      => 'Kandy',
                'province'  => 'Central',
                'postal'    => '20000',
            ],
            [
                'email'     => 'osusala.colombo@healthnet.lk',
                'name'      => 'Osusala Pharmacy – Colombo',
                'reg'       => 'PHA-3003',
                'ph_name'   => 'Chaminda Rathnayake',
                'ph_license'=> 'PL-30567',
                'phone'     => '0112699001',
                'address'   => 'No.100, Norris Canal Road, Colombo 10',
                'city'      => 'Colombo',
                'province'  => 'Western',
                'postal'    => '01000',
            ],
            [
                'email'     => 'hemas.pharmacy.galle@healthnet.lk',
                'name'      => 'Hemas Pharmacy – Galle',
                'reg'       => 'PHA-4004',
                'ph_name'   => 'Samanthi Perera',
                'ph_license'=> 'PL-40678',
                'phone'     => '0912234001',
                'address'   => 'No.45, Main Street, Galle Fort',
                'city'      => 'Galle',
                'province'  => 'Southern',
                'postal'    => '80000',
            ],
            [
                'email'     => 'medicare.kurunegala@healthnet.lk',
                'name'      => 'Medicare Pharmacy – Kurunegala',
                'reg'       => 'PHA-5005',
                'ph_name'   => 'Dilrukshi Fernando',
                'ph_license'=> 'PL-50789',
                'phone'     => '0372225001',
                'address'   => 'No.18, Colombo Road, Kurunegala',
                'city'      => 'Kurunegala',
                'province'  => 'North Western',
                'postal'    => '60000',
            ],
            [
                'email'     => 'meddis.nugegoda@healthnet.lk',
                'name'      => 'Meddis Pharmacy – Nugegoda',
                'reg'       => 'PHA-6006',
                'ph_name'   => 'Ruwan Jayasuriya',
                'ph_license'=> 'PL-60890',
                'phone'     => '0112818001',
                'address'   => 'No.55, High Level Road, Nugegoda',
                'city'      => 'Nugegoda',
                'province'  => 'Western',
                'postal'    => '10250',
            ],
        ];

        foreach ($pharmacies as $i => $p) {
            $userId = DB::table('users')->insertGetId([
                'email'             => $p['email'],
                'password'          => $password,
                'user_type'         => 'pharmacy',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('pharmacies')->insert([
                'user_id'             => $userId,
                'status'              => 'approved',
                'name'                => $p['name'],
                'registration_number' => $p['reg'],
                'pharmacist_name'     => $p['ph_name'],
                'pharmacist_license'  => $p['ph_license'],
                'phone'               => $p['phone'],
                'email'               => $p['email'],
                'address'             => $p['address'],
                'city'                => $p['city'],
                'province'            => $p['province'],
                'postal_code'         => $p['postal'],
                'delivery_available'  => 1,
                'rating'              => 0.00,
                'total_ratings'       => 0,
                'approved_by'         => $adminId,
                'approved_at'         => now(),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            $this->command->info("  ✅ Pharmacy " . ($i + 1) . ": {$p['name']}");
        }

        // ==============================================================
        // 7. MEDICAL CENTRES (5)
        // ==============================================================
        $centres = [
            [
                'email'    => 'kandy.medcentre@healthnet.lk',
                'name'     => 'Kandy City Medical Centre',
                'reg'      => 'MC-5002',
                'phone'    => '0812233445',
                'address'  => 'No.88, Peradeniya Road, Kandy',
                'city'     => 'Kandy',
                'province' => 'Central',
                'postal'   => '20000',
                'desc'     => 'Primary care centre offering GP clinics, specialist consultations and basic diagnostics in central Kandy.',
            ],
            [
                'email'    => 'nugegoda.medcentre@healthnet.lk',
                'name'     => 'Nugegoda Health Centre',
                'reg'      => 'MC-6003',
                'phone'    => '0112818234',
                'address'  => 'No.34, High Level Road, Nugegoda',
                'city'     => 'Nugegoda',
                'province' => 'Western',
                'postal'   => '10250',
                'desc'     => 'Modern outpatient centre providing GP, dental and paediatric consultations.',
            ],
            [
                'email'    => 'matara.medcentre@healthnet.lk',
                'name'     => 'Matara Medical Centre',
                'reg'      => 'MC-7004',
                'phone'    => '0412222678',
                'address'  => 'No.10, Anagarika Dharmapala Mawatha, Matara',
                'city'     => 'Matara',
                'province' => 'Southern',
                'postal'   => '81000',
                'desc'     => 'Southern Province medical centre specialising in general medicine, gynaecology and dermatology clinics.',
            ],
            [
                'email'    => 'kurunegala.medcentre@healthnet.lk',
                'name'     => 'Kurunegala LifeCare Centre',
                'reg'      => 'MC-8005',
                'phone'    => '0372200789',
                'address'  => 'No.22, Rajapihilla Road, Kurunegala',
                'city'     => 'Kurunegala',
                'province' => 'North Western',
                'postal'   => '60000',
                'desc'     => 'Outpatient medical centre providing GP, orthopaedic and physiotherapy services.',
            ],
            [
                'email'    => 'ratnapura.medcentre@healthnet.lk',
                'name'     => 'Ratnapura Medical Centre',
                'reg'      => 'MC-9006',
                'phone'    => '0452224567',
                'address'  => 'No.5, Bandaranayake Mawatha, Ratnapura',
                'city'     => 'Ratnapura',
                'province' => 'Sabaragamuwa',
                'postal'   => '70000',
                'desc'     => 'General medical centre providing primary care and specialist visiting clinics in Sabaragamuwa Province.',
            ],
        ];

        foreach ($centres as $i => $c) {
            $userId = DB::table('users')->insertGetId([
                'email'             => $c['email'],
                'password'          => $password,
                'user_type'         => 'medical_centre',
                'status'            => 'active',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            DB::table('medical_centres')->insert([
                'user_id'             => $userId,
                'status'              => 'approved',
                'owner_doctor_id'     => null,
                'name'                => $c['name'],
                'registration_number' => $c['reg'],
                'phone'               => $c['phone'],
                'email'               => $c['email'],
                'address'             => $c['address'],
                'city'                => $c['city'],
                'province'            => $c['province'],
                'postal_code'         => $c['postal'],
                'description'         => $c['desc'],
                'rating'              => 0.00,
                'total_ratings'       => 0,
                'approved_by'         => $adminId,
                'approved_at'         => now(),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);

            $this->command->info("  ✅ Medical Centre " . ($i + 1) . ": {$c['name']}");
        }

        // ─── Done ─────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('🔑 Password for all accounts: 0771717599');
    }
}
