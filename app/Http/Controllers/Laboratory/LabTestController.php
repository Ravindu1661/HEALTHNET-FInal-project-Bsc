<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabTestController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $lab = $this->getLab();
        $query = LabTest::where('laboratory_id', $lab->id);

        if ($request->filled('category'))
            $query->where('test_category', $request->category);
        if ($request->filled('status'))
            $query->where('is_active', $request->status == 'active');
        if ($request->filled('search'))
            $query->where('test_name', 'like', '%' . $request->search . '%');

        $tests = $query->orderBy('test_category')->orderBy('test_name')->paginate(15);
        $categories = LabTest::where('laboratory_id', $lab->id)
            ->distinct()->pluck('test_category')->filter();

        return view('laboratory.tests.index', compact('lab', 'tests', 'categories'));
    }

    public function create()
    {
        $lab = $this->getLab();
        $categories = LabTest::where('laboratory_id', $lab->id)
            ->distinct()->pluck('test_category')->filter();
        return view('laboratory.tests.create', compact('lab', 'categories'));
    }

    public function store(Request $request)
    {
        $lab = $this->getLab();
        $request->validate([
            'test_name'     => 'required|string|max:255',
            'test_category' => 'nullable|string|max:100',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'duration_hours'=> 'nullable|integer|min:0',
            'requirements'  => 'nullable|string',
            'is_active'     => 'boolean',
        ]);

        LabTest::create([
            'laboratory_id' => $lab->id,
            'test_name'     => $request->test_name,
            'test_category' => $request->test_category,
            'description'   => $request->description,
            'price'         => $request->price,
            'duration_hours'=> $request->duration_hours,
            'requirements'  => $request->requirements,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('laboratory.tests.index')
            ->with('success', 'Test added successfully!');
    }

    public function edit(LabTest $test)
    {
        $lab = $this->getLab();
        abort_if($test->laboratory_id !== $lab->id, 403);
        $categories = LabTest::where('laboratory_id', $lab->id)
            ->distinct()->pluck('test_category')->filter();
        return view('laboratory.tests.edit', compact('lab', 'test', 'categories'));
    }

    public function update(Request $request, LabTest $test)
    {
        $lab = $this->getLab();
        abort_if($test->laboratory_id !== $lab->id, 403);
        $request->validate([
            'test_name'     => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
        ]);
        $test->update($request->only([
            'test_name','test_category','description','price','duration_hours','requirements'
        ]) + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('laboratory.tests.index')
            ->with('success', 'Test updated!');
    }

    public function destroy(LabTest $test)
    {
        $lab = $this->getLab();
        abort_if($test->laboratory_id !== $lab->id, 403);
        $test->delete();
        return redirect()->route('laboratory.tests.index')
            ->with('success', 'Test deleted!');
    }

    public function toggleStatus(LabTest $test)
    {
        $lab = $this->getLab();
        abort_if($test->laboratory_id !== $lab->id, 403);
        $test->update(['is_active' => !$test->is_active]);
        return response()->json(['status' => $test->is_active ? 'active' : 'inactive']);
    }
}
