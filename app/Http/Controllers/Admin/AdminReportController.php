<?php
// app/Http/Controllers/Admin/AdminReportController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    /**
     * Show reports dashboard (appointments, payments summary + table).
     */
    public function index(Request $request)
    {
        $type     = $request->get('type', 'appointments'); // appointments | payments
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $status   = $request->get('status');               // optional filter

        $data     = [];
        $summary  = [];

        if ($type === 'appointments') {
            $query = Appointment::query();

            if ($dateFrom) {
                $query->whereDate('appointment_date', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('appointment_date', '<=', $dateTo);
            }
            if ($status) {
                $query->where('status', $status);
            }

            // summary counts
            $summary = [
                'total'      => (clone $query)->count(),
                'pending'    => (clone $query)->where('status', 'pending')->count(),
                'confirmed'  => (clone $query)->where('status', 'confirmed')->count(),
                'completed'  => (clone $query)->where('status', 'completed')->count(),
                'cancelled'  => (clone $query)->where('status', 'cancelled')->count(),
            ];

            $data = $query
                ->with(['patient', 'doctor'])
                ->latest('appointment_date')
                ->latest('appointment_time')
                ->paginate(25)
                ->withQueryString();
        } elseif ($type === 'payments') {
            $query = Payment::query();

            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
            if ($status) {
                $query->where('status', $status);
            }

            $summary = [
                'total'        => (clone $query)->count(),
                'total_amount' => (clone $query)->sum('amount'),
                'paid'         => (clone $query)->where('status', 'paid')->sum('amount'),
                'refunded'     => (clone $query)->where('status', 'refunded')->sum('amount'),
                'failed'       => (clone $query)->where('status', 'failed')->sum('amount'),
            ];

            $data = $query
                ->latest()
                ->paginate(25)
                ->withQueryString();
        }

        return view('admin.reports.index', [
            'type'     => $type,
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
            'status'   => $status,
            'data'     => $data,
            'summary'  => $summary,
        ]);
    }

    /**
     * Export CSV report (appointments / payments).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $type     = $request->get('type', 'appointments');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $status   = $request->get('status');

        $fileName = $type . '_report_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($type, $dateFrom, $dateTo, $status) {
            $handle = fopen('php://output', 'w');

            if ($type === 'appointments') {
                fputcsv($handle, [
                    'ID',
                    'Appointment Number',
                    'Patient Name',
                    'Doctor Name',
                    'Date',
                    'Time',
                    'Status',
                    'Created At',
                ]);

                $query = Appointment::with(['patient', 'doctor']);

                if ($dateFrom) {
                    $query->whereDate('appointment_date', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->whereDate('appointment_date', '<=', $dateTo);
                }
                if ($status) {
                    $query->where('status', $status);
                }

                $query->orderBy('appointment_date')
                    ->orderBy('appointment_time')
                    ->chunk(200, function ($rows) use ($handle) {
                        foreach ($rows as $row) {
                            fputcsv($handle, [
                                $row->id,
                                $row->appointment_number,
                                optional($row->patient)->firstname . ' ' . optional($row->patient)->lastname,
                                'Dr. ' . trim((optional($row->doctor)->firstname . ' ' . optional($row->doctor)->lastname)),
                                $row->appointment_date,
                                $row->appointment_time,
                                $row->status,
                                $row->created_at,
                            ]);
                        }
                    });
            } elseif ($type === 'payments') {
                fputcsv($handle, [
                    'ID',
                    'Reference',
                    'Appointment ID',
                    'User ID',
                    'Amount',
                    'Status',
                    'Method',
                    'Created At',
                ]);

                $query = Payment::query();

                if ($dateFrom) {
                    $query->whereDate('created_at', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $query->whereDate('created_at', '<=', $dateTo);
                }
                if ($status) {
                    $query->where('status', $status);
                }

                $query->orderBy('created_at')
                    ->chunk(200, function ($rows) use ($handle) {
                        foreach ($rows as $row) {
                            fputcsv($handle, [
                                $row->id,
                                $row->reference ?? '',
                                $row->appointment_id ?? '',
                                $row->user_id ?? '',
                                $row->amount ?? 0,
                                $row->status ?? '',
                                $row->payment_method ?? '',
                                $row->created_at,
                            ]);
                        }
                    });
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Export PDF – placeholder (ඔයා Dompdf/ Snappy add කරලා implement කරන්න).
     */
    public function exportPdf(Request $request)
    {
        // later implement with PDF lib
        return back()->with('error', 'PDF export not implemented yet.');
    }
}
