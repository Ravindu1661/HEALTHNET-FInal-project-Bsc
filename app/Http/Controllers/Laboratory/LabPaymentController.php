<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabPaymentController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $lab = $this->getLab();
        $query = Payment::with('payer')
            ->where('payee_type', 'laboratory')
            ->where('payee_id', $lab->id);

        if ($request->filled('status'))
            $query->where('payment_status', $request->status);
        if ($request->filled('method'))
            // $query->where('payment_method', $request->method);
        if ($request->filled('from'))
            $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))
            $query->whereDate('created_at', '<=', $request->to);

        $payments = $query->latest()->paginate(15);

        $summary = [
            'total'     => Payment::where('payee_type','laboratory')->where('payee_id',$lab->id)->where('payment_status','completed')->sum('amount'),
            'pending'   => Payment::where('payee_type','laboratory')->where('payee_id',$lab->id)->where('payment_status','pending')->sum('amount'),
            'refunded'  => Payment::where('payee_type','laboratory')->where('payee_id',$lab->id)->where('payment_status','refunded')->sum('amount'),
            'this_month'=> Payment::where('payee_type','laboratory')->where('payee_id',$lab->id)->where('payment_status','completed')->whereMonth('created_at',now()->month)->sum('amount'),
        ];

        return view('laboratory.payments.index', compact('lab', 'payments', 'summary'));
    }

    public function show(Payment $payment)
    {
        $lab = $this->getLab();
        abort_if($payment->payee_id !== $lab->id || $payment->payee_type !== 'laboratory', 403);
        return view('laboratory.payments.show', compact('lab', 'payment'));
    }
}
