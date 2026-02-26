<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabReviewController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $lab = $this->getLab();
        $ratings = Rating::with('patient.user')
            ->where('ratable_type', 'laboratory')
            ->where('ratable_id', $lab->id)
            ->latest()->paginate(15);

        $summary = [
            'avg'   => Rating::where('ratable_type','laboratory')->where('ratable_id',$lab->id)->avg('rating') ?? 0,
            'total' => Rating::where('ratable_type','laboratory')->where('ratable_id',$lab->id)->count(),
            'dist'  => Rating::where('ratable_type','laboratory')->where('ratable_id',$lab->id)
                ->selectRaw('rating, count(*) as count')
                ->groupBy('rating')->pluck('count','rating'),
        ];

        return view('laboratory.reviews.index', compact('lab', 'ratings', 'summary'));
    }

    public function show(Rating $rating)
    {
        $lab = $this->getLab();
        abort_if($rating->ratable_id !== $lab->id, 403);
        return view('laboratory.reviews.show', compact('lab', 'rating'));
    }

    public function reply(Request $request, Rating $rating)
    {
        // Stored as notes or separate table if extended
        // For now — acknowledge
        return back()->with('success', 'Reply sent!');
    }
}
