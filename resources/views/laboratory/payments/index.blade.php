@extends('laboratory.layouts.app')
@section('title','Payments')
@section('page-title','Payments & Billing')
@section('page-subtitle','Track all payment transactions')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @php
        $cards = [
            ['label'=>'Total Revenue',  'value'=>$summary['total'],      'icon'=>'fa-coins',      'color'=>'teal',   'badge'=>'All Time'],
            ['label'=>'This Month',     'value'=>$summary['this_month'], 'icon'=>'fa-calendar',   'color'=>'blue',   'badge'=>'Month'],
            ['label'=>'Pending',        'value'=>$summary['pending'],    'icon'=>'fa-clock',      'color'=>'yellow', 'badge'=>'Awaiting'],
            ['label'=>'Refunded',       'value'=>$summary['refunded'],   'icon'=>'fa-undo',       'color'=>'red',    'badge'=>'Refunds'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-start justify-between mb-3">
            <div class="bg-{{ $card['color'] }}-100 p-2.5 rounded-xl">
                <i class="fas {{ $card['icon'] }} text-{{ $card['color'] }}-600 text-lg"></i>
            </div>
            <span class="text-xs text-{{ $card['color'] }}-600 bg-{{ $card['color'] }}-50 px-2 py-1 rounded-full font-medium">{{ $card['badge'] }}</span>
        </div>
        <p class="text-xl font-bold text-gray-900">Rs. {{ number_format($card['value'], 0) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">All</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                <option value="pending"   {{ request('status')=='pending'  ?'selected':'' }}>Pending</option>
                <option value="failed"    {{ request('status')=='failed'   ?'selected':'' }}>Failed</option>
                <option value="refunded"  {{ request('status')=='refunded' ?'selected':'' }}>Refunded</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Method</label>
            <select name="method" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">All</option>
                <option value="cash"         {{ request('method')=='cash'        ?'selected':'' }}>Cash</option>
                <option value="card"         {{ request('method')=='card'        ?'selected':'' }}>Card</option>
                <option value="online"       {{ request('method')=='online'      ?'selected':'' }}>Online</option>
                <option value="bank_transfer"{{ request('method')=='bank_transfer'?'selected':'' }}>Bank Transfer</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
        </div>
        <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition">Filter</button>
        <a href="{{ route('laboratory.payments.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Payment #</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Patient</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Related</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Method</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 font-semibold text-gray-900">{{ $payment->payment_number }}</td>
                    <td class="px-5 py-4 text-gray-700">{{ $payment->payer->name ?? 'N/A' }}</td>
                    <td class="px-5 py-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ ucfirst($payment->related_type) }} #{{ $payment->related_id }}</span>
                    </td>
                    <td class="px-5 py-4 font-semibold text-gray-900">Rs. {{ number_format($payment->amount, 2) }}</td>
                    <td class="px-5 py-4 capitalize text-gray-600">{{ str_replace('_',' ',$payment->payment_method) }}</td>
                    <td class="px-5 py-4 text-gray-500">{{ $payment->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                            @if($payment->payment_status==='completed') bg-green-100 text-green-700
                            @elseif($payment->payment_status==='pending')   bg-yellow-100 text-yellow-700
                            @elseif($payment->payment_status==='failed')    bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs max-w-[150px] truncate">{{ $payment->notes ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-16 text-center">
                    <i class="fas fa-credit-card text-gray-200 text-5xl mb-3 block"></i>
                    <p class="text-gray-400">No payments found</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $payments->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
