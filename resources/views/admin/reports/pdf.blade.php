<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($type) }} Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; }
        th { background: #f3f4f6; font-weight: bold; }
        h2 { margin: 0 0 5px 0; }
        .summary { margin-top: 8px; }
        .summary span { margin-right: 15px; }
    </style>
</head>
<body>
    <h2>{{ ucfirst($type) }} Report</h2>
    <div>
        Date Range:
        <strong>{{ $dateFrom ?: 'N/A' }}</strong> -
        <strong>{{ $dateTo ?: 'N/A' }}</strong>
        @if($status)
            | Status: <strong>{{ ucfirst($status) }}</strong>
        @endif
    </div>

    {{-- Summary --}}
    @if($type === 'appointments')
        <div class="summary">
            <span>Total: {{ $summary['total'] ?? 0 }}</span>
            <span>Pending: {{ $summary['pending'] ?? 0 }}</span>
            <span>Confirmed: {{ $summary['confirmed'] ?? 0 }}</span>
            <span>Completed: {{ $summary['completed'] ?? 0 }}</span>
            <span>Cancelled: {{ $summary['cancelled'] ?? 0 }}</span>
        </div>
    @else
        <div class="summary">
            <span>Total: {{ $summary['total'] ?? 0 }}</span>
            <span>Total Amount: LKR {{ number_format($summary['total_amount'] ?? 0, 2) }}</span>
            <span>Paid: LKR {{ number_format($summary['paid'] ?? 0, 2) }}</span>
            <span>Refunded: LKR {{ number_format($summary['refunded'] ?? 0, 2) }}</span>
            <span>Failed: LKR {{ number_format($summary['failed'] ?? 0, 2) }}</span>
        </div>
    @endif

    {{-- Table --}}
    @if($type === 'appointments')
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Appointment No</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->appointment_number }}</td>
                        <td>{{ optional($row->patient)->firstname }} {{ optional($row->patient)->lastname }}</td>
                        <td>Dr. {{ optional($row->doctor)->firstname }} {{ optional($row->doctor)->lastname }}</td>
                        <td>{{ $row->appointment_date }}</td>
                        <td>{{ $row->appointment_time }}</td>
                        <td>{{ ucfirst($row->status) }}</td>
                        <td>{{ $row->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reference</th>
                    <th>User ID</th>
                    <th>Appointment</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->reference ?? '-' }}</td>
                        <td>{{ $row->user_id ?? '-' }}</td>
                        <td>{{ $row->appointment_id ?? '-' }}</td>
                        <td>{{ number_format($row->amount ?? 0, 2) }}</td>
                        <td>{{ ucfirst($row->status ?? '-') }}</td>
                        <td>{{ $row->payment_method ?? '-' }}</td>
                        <td>{{ $row->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
