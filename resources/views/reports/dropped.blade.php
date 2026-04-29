<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropped Students Report</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 0; background: white; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; font-size: 10px; text-transform: uppercase; color: #666; font-weight: bold; }
        .stats-row { display: flex; gap: 15px; margin-bottom: 20px; }
        .stat-card { flex: 1; border: 1px solid #ddd; padding: 12px; border-radius: 6px; text-align: center; }
        .stat-card p { margin: 0; font-size: 8px; font-weight: 900; text-transform: uppercase; color: #888; }
        .stat-card h2 { margin: 4px 0 0; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 9px; }
        th { background: #f4f4f4; border: 1px solid #ddd; padding: 8px; text-align: left; text-transform: uppercase; font-weight: 900; }
        td { border: 1px solid #ddd; padding: 6px; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 8px; text-align: right; color: #999; }
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #f8fafc; padding: 10px; border-bottom: 1px solid #eee; text-align: center; font-size: 12px; font-weight: bold;">
        Tip: Set your printer destination to "Save as PDF" to generate a PDF file.
    </div>

    <div class="header">
        <h1>Dropped Students Registry</h1>
        <p>Official Withdrawal & Penalty Assessment Report</p>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <p>Total Records</p>
            <h2>{{ $officiallyDropped->count() }}</h2>
        </div>
        <div class="stat-card">
            <p>Dropped</p>
            <h2 style="color: #2563eb;">{{ $officiallyDropped->where('drop_status', 'Dropped')->count() }}</h2>
        </div>
        <div class="stat-card">
            <p>Withdrawn</p>
            <h2 style="color: #3b82f6;">{{ $officiallyDropped->where('drop_status', 'Withdrawn')->count() }}</h2>
        </div>
        <div class="stat-card">
            <p>Total Penalties</p>
            <h2 style="color: #3b82f6;">₱ {{ number_format($totalPenalties, 2) }}</h2>
        </div>
        <div class="stat-card">
            <p>Report Date</p>
            <h2>{{ date('M d, Y') }}</h2>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Program / Year</th>
                <th class="text-center">Level</th>
                <th class="text-center">Status</th>
                <th class="text-center">Period</th>
                <th class="text-center">Date</th>
                <th>Reason</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Charge</th>
                <th class="text-right">Refundable</th>
            </tr>
        </thead>
        <tbody>
            @foreach($officiallyDropped as $s)
                <tr>
                    <td style="font-weight: bold; text-transform: uppercase;">{{ $s->name }}</td>
                    <td>{{ $s->course }} ({{ $s->year_level }})</td>
                    <td class="text-center">{{ $s->level }}</td>
                    <td class="text-center">{{ $s->drop_status }}</td>
                    <td class="text-center">{{ str_replace('_', ' ', ucwords($s->drop_period, '_')) }}</td>
                    <td class="text-center">{{ $s->drop_date }}</td>
                    <td>{{ $s->drop_reason }}</td>
                    <td class="text-right">₱ {{ number_format($s->total_paid, 2) }}</td>
                    <td class="text-right" style="color: #2563eb; font-weight: bold;">₱ {{ number_format($s->drop_charge, 2) }}</td>
                    <td class="text-right" style="color: #1d4ed8; font-weight: bold;">₱ {{ number_format($s->net_refundable, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Confidential Academic Record — Generated at {{ date('h:i A') }}
    </div>
</body>
</html>
