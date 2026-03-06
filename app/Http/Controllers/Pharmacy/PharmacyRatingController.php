<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;
use App\Models\PharmacyOrder;

class PharmacyRatingController extends Controller
{
    private function pharmacy()
    {
        return Auth::user()->pharmacy;
    }

    /* ─────────────────────────────────────────
     |  INDEX
     ─────────────────────────────────────────*/
    public function index(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        $query = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pid)
            ->with('patient.user');

        // Filter: star rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter: reply status
        if ($request->filled('reply_status')) {
            if ($request->reply_status === 'replied') {
                $query->whereNotNull('reply');
            } elseif ($request->reply_status === 'not_replied') {
                $query->whereNull('reply');
            }
        }

        // Filter: related type
        if ($request->filled('related_type')) {
            $query->where('related_type', $request->related_type);
        }

        // Filter: search
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('review', 'like', $search)
                  ->orWhereHas('patient', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', $search)
                         ->orWhere('last_name',  'like', $search);
                  });
            });
        }

        $ratings = $query->latest()->paginate(15)->withQueryString();

        /* ── Statistics (status column නැත — ඉවත් කළා) ── */
        $base = fn() => Rating::where('ratable_type', 'pharmacy')
                               ->where('ratable_id', $pid);

        $totalRatings    = $base()->count();
        $averageRating   = $base()->avg('rating') ?? 0;
        $withReviewCount = $base()->whereNotNull('review')->where('review', '!=', '')->count();
        $repliedCount    = $base()->whereNotNull('reply')->count();
        $notRepliedCount = $base()->whereNull('reply')->count();

        // Star Distribution
        $distRaw = $base()
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $starData = [];
        for ($i = 5; $i >= 1; $i--) {
            $starData[$i] = $distRaw->get($i, 0);
        }

        // Monthly Trend (last 6 months)
        $monthlyTrend = $base()
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at)  as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*)          as count'),
                DB::raw('AVG(rating)       as avg_rating')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('pharmacy.ratings.index', compact(
            'ratings',
            'totalRatings', 'averageRating',
            'withReviewCount', 'repliedCount', 'notRepliedCount',
            'starData', 'monthlyTrend'
        ));
    }

    /* ─────────────────────────────────────────
     |  SHOW
     ─────────────────────────────────────────*/
    public function show(Rating $rating)
    {
        if ($rating->ratable_type !== 'pharmacy' ||
            $rating->ratable_id  !== $this->pharmacy()->id) {
            abort(403, 'Unauthorized action.');
        }

        $rating->load('patient.user');

        $relatedOrder = null;
        if ($rating->related_type === 'prescriptionorder' && $rating->related_id) {
            $relatedOrder = PharmacyOrder::with('items')->find($rating->related_id);
        }

        return view('pharmacy.ratings.show', compact('rating', 'relatedOrder'));
    }

    /* ─────────────────────────────────────────
     |  REPLY
     ─────────────────────────────────────────*/
    public function reply(Request $request, Rating $rating)
    {
        if ($rating->ratable_type !== 'pharmacy' ||
            $rating->ratable_id  !== $this->pharmacy()->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'reply' => 'required|string|min:5|max:1000',
        ]);

        $rating->update([
            'reply'      => $request->reply,
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Reply successfully submitted!');
    }
}
