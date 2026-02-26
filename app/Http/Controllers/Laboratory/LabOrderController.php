<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LabOrderController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $lab = $this->getLab();
        $query = LabOrder::with(['patient.user', 'items'])
            ->where('laboratory_id', $lab->id);

        if ($request->filled('status'))
            $query->where('status', $request->status);
        if ($request->filled('payment'))
            $query->where('payment_status', $request->payment);
        if ($request->filled('home') && $request->home === '1')
            $query->where('home_collection', true);
        if ($request->filled('search'))
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('patient.user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'));
            });
        if ($request->filled('date'))
            $query->whereDate('collection_date', $request->date);

        $orders = $query->latest()->paginate(15);

        $counts = [
            'all'              => LabOrder::where('laboratory_id', $lab->id)->count(),
            'pending'          => LabOrder::where('laboratory_id', $lab->id)->where('status','pending')->count(),
            'sample_collected' => LabOrder::where('laboratory_id', $lab->id)->where('status','sample_collected')->count(),
            'processing'       => LabOrder::where('laboratory_id', $lab->id)->where('status','processing')->count(),
            'completed'        => LabOrder::where('laboratory_id', $lab->id)->where('status','completed')->count(),
            'cancelled'        => LabOrder::where('laboratory_id', $lab->id)->where('status','cancelled')->count(),
            'home'             => LabOrder::where('laboratory_id', $lab->id)->where('home_collection',true)->count(),
            'unpaid'           => LabOrder::where('laboratory_id', $lab->id)->where('payment_status','unpaid')->count(),
        ];

        return view('laboratory.orders.index', compact('lab', 'orders', 'counts'));
    }

    public function show(LabOrder $order)
    {
        $lab = $this->getLab();
        abort_if($order->laboratory_id !== $lab->id, 403);
        $order->load(['patient.user', 'items.test', 'items.package', 'doctor']);
        return view('laboratory.orders.show', compact('lab', 'order'));
    }

    public function markCollected(LabOrder $order)
    {
        $lab = $this->getLab();
        abort_if($order->laboratory_id !== $lab->id, 403);
        $order->update(['status' => 'sample_collected']);
        $this->notify($order, 'Sample Collected', '🧪 Sample collected for Order #'.$order->order_number.'. Processing will begin shortly.');
        return back()->with('success', 'Marked as Sample Collected!');
    }

    public function markProcessing(LabOrder $order)
    {
        $lab = $this->getLab();
        abort_if($order->laboratory_id !== $lab->id, 403);
        $order->update(['status' => 'processing']);
        $this->notify($order, 'Processing', '🔬 Your lab order #'.$order->order_number.' is now being processed.');
        return back()->with('success', 'Marked as Processing!');
    }

    public function markComplete(Request $request, LabOrder $order)
    {
        $lab = $this->getLab();
        abort_if($order->laboratory_id !== $lab->id, 403);

        $reportPath = $order->report_file;
        if ($request->hasFile('report_file')) {
            $request->validate(['report_file' => 'file|mimes:pdf|max:10240']);
            $reportPath = $request->file('report_file')
                ->store('lab-reports/'.$lab->id, 'public');
        }

        $order->update([
            'status'             => 'completed',
            'report_file'        => $reportPath,
            'report_uploaded_at' => now(),
        ]);

        $this->notify($order, 'Report Ready', '✅ Your lab report for Order #'.$order->order_number.' is ready! Login to download.');
        return back()->with('success', 'Order completed and report uploaded!');
    }

    public function uploadReport(Request $request, LabOrder $order)
    {
        $lab = $this->getLab();
        abort_if($order->laboratory_id !== $lab->id, 403);
        $request->validate(['report_file' => 'required|file|mimes:pdf|max:10240']);

        $path = $request->file('report_file')->store('lab-reports/'.$lab->id, 'public');
        $order->update(['report_file' => $path, 'report_uploaded_at' => now()]);

        $this->notify($order, 'Report Uploaded', '📄 Lab report for Order #'.$order->order_number.' has been uploaded.');
        return back()->with('success', 'Report uploaded!');
    }

    public function cancel(Request $request, LabOrder $order)
    {
        $lab = $this->getLab();
        abort_if($order->laboratory_id !== $lab->id, 403);
        $order->update(['status' => 'cancelled']);
        $this->notify($order, 'Order Cancelled', '❌ Lab Order #'.$order->order_number.' has been cancelled by the laboratory.');
        return back()->with('success', 'Order cancelled!');
    }

    private function notify(LabOrder $order, string $title, string $message): void
    {
        try {
            if ($order->patient && $order->patient->user_id) {
                DB::table('notifications')->insert([
                    'notifiable_type' => \App\Models\User::class,
                    'notifiable_id'   => $order->patient->user_id,
                    'type'            => 'lab_order',
                    'title'           => $title . ' — ' . $order->order_number,
                    'message'         => $message,
                    'related_type'    => 'lab_order',
                    'related_id'      => $order->id,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        } catch (\Exception $e) {}
    }
}
