<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;     // ඔබට තියෙන real model name එකට adjust කරන්න
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Base query
        $query = Payment::query();

        // Filters
        if ($status = $request->get('status')) {
            $query->where('payment_status', $status);
        }

        if ($method = $request->get('method')) {
            $query->where('payment_method', $method);
        }

        if ($from = $request->get('from')) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Pagination
        $payments = $query->orderByDesc('created_at')->paginate(15);

        // Summary cards
        $summary = [
            'total'     => Payment::where('payment_status', 'completed')->sum('amount'),
            'thisMonth' => Payment::where('payment_status', 'completed')
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->sum('amount'),
            'pending'   => Payment::where('payment_status', 'pending')->sum('amount'),
            'refunded'  => Payment::where('payment_status', 'refunded')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}
