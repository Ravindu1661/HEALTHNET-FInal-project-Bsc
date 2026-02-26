<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabTest;
use App\Models\LabPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabPackageController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $lab = $this->getLab();
        $packages = LabPackage::with('tests')
            ->where('laboratory_id', $lab->id)
            ->latest()->paginate(12);
        return view('laboratory.packages.index', compact('lab', 'packages'));
    }

    public function create()
    {
        $lab = $this->getLab();
        $tests = LabTest::where('laboratory_id', $lab->id)
            ->where('is_active', true)->get();
        return view('laboratory.packages.create', compact('lab', 'tests'));
    }

    public function store(Request $request)
    {
        $lab = $this->getLab();
        $request->validate([
            'package_name'        => 'required|string|max:255',
            'description'         => 'nullable|string',
            'price'               => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tests'               => 'nullable|array',
        ]);

        $package = LabPackage::create([
            'laboratory_id'       => $lab->id,
            'package_name'        => $request->package_name,
            'description'         => $request->description,
            'price'               => $request->price,
            'discount_percentage' => $request->discount_percentage,
            'is_active'           => $request->boolean('is_active', true),
        ]);

        if ($request->filled('tests')) {
            $package->tests()->sync($request->tests);
        }

        return redirect()->route('laboratory.packages.index')
            ->with('success', 'Package created!');
    }

    public function edit(LabPackage $package)
    {
        $lab = $this->getLab();
        abort_if($package->laboratory_id !== $lab->id, 403);
        $package->load('tests');
        $tests = LabTest::where('laboratory_id', $lab->id)->where('is_active', true)->get();
        return view('laboratory.packages.edit', compact('lab', 'package', 'tests'));
    }

    public function update(Request $request, LabPackage $package)
    {
        $lab = $this->getLab();
        abort_if($package->laboratory_id !== $lab->id, 403);
        $request->validate([
            'package_name' => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        $package->update($request->only(['package_name','description','price','discount_percentage'])
            + ['is_active' => $request->boolean('is_active')]);

        if ($request->has('tests')) {
            $package->tests()->sync($request->tests ?? []);
        }

        return redirect()->route('laboratory.packages.index')
            ->with('success', 'Package updated!');
    }

    public function destroy(LabPackage $package)
    {
        $lab = $this->getLab();
        abort_if($package->laboratory_id !== $lab->id, 403);
        $package->tests()->detach();
        $package->delete();
        return redirect()->route('laboratory.packages.index')
            ->with('success', 'Package deleted!');
    }

    public function toggleStatus(LabPackage $package)
    {
        $lab = $this->getLab();
        abort_if($package->laboratory_id !== $lab->id, 403);
        $package->update(['is_active' => !$package->is_active]);
        return response()->json(['is_active' => $package->is_active]);
    }

    public function addTest(Request $request, LabPackage $package)
    {
        $lab = $this->getLab();
        abort_if($package->laboratory_id !== $lab->id, 403);
        $request->validate(['test_id' => 'required|exists:lab_tests,id']);
        $package->tests()->syncWithoutDetaching([$request->test_id]);
        return back()->with('success', 'Test added to package!');
    }

    public function removeTest(LabPackage $package, LabTest $test)
    {
        $lab = $this->getLab();
        abort_if($package->laboratory_id !== $lab->id, 403);
        $package->tests()->detach($test->id);
        return back()->with('success', 'Test removed!');
    }
}
