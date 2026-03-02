<?php

namespace App\Http\Controllers\MedicalCentre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MedicalCentreReviewController extends Controller
{
    private function getMedicalCentre()
    {
        return DB::table('medical_centres')
            ->where('user_id', Auth::id())
            ->first();
    }

    // Check කරනවා ratings table ගාව reply column තියෙනවද කියලා
    private function hasReplyColumn(): bool
    {
        return Schema::hasColumn('ratings', 'reply');
    }

    // ─────────────────────────────────────────
    // Index
    // ─────────────────────────────────────────
    public function index(Request $request)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $search   = $request->input('search', '');
        $rating   = $request->input('rating', '');
        $status   = $request->input('status', '');
        $hasReply = $this->hasReplyColumn();

        $query = DB::table('ratings')
            ->leftJoin('patients', 'ratings.patient_id', '=', 'patients.id')
            ->leftJoin('users', 'patients.user_id', '=', 'users.id')
            ->where('ratings.ratable_type', 'medicalcentre')
            ->where('ratings.ratable_id', $mc->id)
            ->select(
                'ratings.*',
                'users.email as reviewer_email',
                'patients.first_name',
                'patients.last_name',
                'patients.profile_image as reviewer_photo'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ratings.review', 'like', "%{$search}%")
                  ->orWhere('patients.first_name', 'like', "%{$search}%")
                  ->orWhere('patients.last_name', 'like', "%{$search}%");
            });
        }

        if ($rating) {
            $query->where('ratings.rating', (int)$rating);
        }

        if ($hasReply) {
            if ($status === 'replied') {
                $query->whereNotNull('ratings.reply');
            } elseif ($status === 'pending') {
                $query->whereNull('ratings.reply');
            }
        }

        $reviews = $query->orderByDesc('ratings.created_at')->paginate(12);

        // Base query helper
        $base = fn() => DB::table('ratings')
            ->where('ratable_type', 'medicalcentre')
            ->where('ratable_id', $mc->id);

        $total   = $base()->count();
        $average = round($base()->avg('rating') ?? 0, 1);

        $stats = [
            'total'     => $total,
            'average'   => $average,
            'five_star' => $base()->where('rating', 5)->count(),
            'four_star' => $base()->where('rating', 4)->count(),
            'replied'   => $hasReply ? $base()->whereNotNull('reply')->count() : 0,
            'pending'   => $hasReply ? $base()->whereNull('reply')->count()    : 0,
        ];

        // Rating distribution
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $base()->where('rating', $i)->count();
            $distribution[$i] = [
                'count'   => $count,
                'percent' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        return view('medical_centre.reviews.index', compact(
            'mc', 'reviews', 'stats', 'distribution',
            'search', 'rating', 'status', 'hasReply'
        ));
    }

    // ─────────────────────────────────────────
    // Show
    // ─────────────────────────────────────────
    public function show($id)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $review = DB::table('ratings')
            ->leftJoin('patients', 'ratings.patient_id', '=', 'patients.id')
            ->leftJoin('users', 'patients.user_id', '=', 'users.id')
            ->where('ratings.id', $id)
            ->where('ratings.ratable_type', 'medicalcentre')
            ->where('ratings.ratable_id', $mc->id)
            ->select(
                'ratings.*',
                'users.email as reviewer_email',
                'patients.first_name',
                'patients.last_name',
                'patients.profile_image as reviewer_photo'
            )
            ->first();

        if (!$review) {
            return redirect()->route('medical_centre.reviews')
                ->with('error', 'Review not found.');
        }

        $replyCol = $this->hasReplyColumn();

        return view('medical_centre.reviews.show', compact('mc', 'review', 'replyCol'));
    }

    // ─────────────────────────────────────────
    // Reply
    // ─────────────────────────────────────────
    public function reply(Request $request, $id)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $request->validate([
            'reply' => 'required|string|min:5|max:1000',
        ]);

        $review = DB::table('ratings')
            ->where('id', $id)
            ->where('ratable_type', 'medicalcentre')
            ->where('ratable_id', $mc->id)
            ->first();

        if (!$review) {
            return redirect()->route('medical_centre.reviews')
                ->with('error', 'Review not found.');
        }

        if ($this->hasReplyColumn()) {
            DB::table('ratings')->where('id', $id)->update([
                'reply'      => $request->input('reply'),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('medical_centre.reviews.show', $id)
            ->with('success', 'Reply posted successfully.');
    }

    // ─────────────────────────────────────────
    // Delete Reply
    // ─────────────────────────────────────────
    public function deleteReply($id)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $review = DB::table('ratings')
            ->where('id', $id)
            ->where('ratable_type', 'medicalcentre')
            ->where('ratable_id', $mc->id)
            ->first();

        if (!$review) {
            return redirect()->route('medical_centre.reviews')
                ->with('error', 'Review not found.');
        }

        if ($this->hasReplyColumn()) {
            DB::table('ratings')->where('id', $id)->update([
                'reply'      => null,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('medical_centre.reviews.show', $id)
            ->with('success', 'Reply removed successfully.');
    }
}
