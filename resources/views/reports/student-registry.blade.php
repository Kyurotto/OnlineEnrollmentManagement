<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Population Registry - Report</title>
    <style>
        @page { size: A4 landscape; margin: 1cm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 0; background: white; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; font-size: 10px; text-transform: uppercase; color: #666; font-weight: bold; }
        .filters { margin-bottom: 20px; font-size: 10px; display: flex; justify-content: space-between; font-weight: bold; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10px; }
        th { background: #f4f4f4; border: 1px solid #ddd; padding: 10px; text-align: left; text-transform: uppercase; font-weight: 900; }
        td { border: 1px solid #ddd; padding: 8px; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; border: 1px solid #ddd; }
        .footer { margin-top: 30px; font-size: 9px; text-align: right; color: #999; }
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
        <h1>Student Population Registry</h1>
        <p>Official Academic Personas & Operational Status Report</p>
    </div>

    <div class="filters">
        <div>
            <span>Filter: {{ ucfirst($filter) }}</span> | 
            <span>Program: {{ $courseFilter }}</span> | 
            <span>Level: {{ $levelFilter }}</span>
        </div>
        <div>
            Generated on: {{ date('F d, Y h:i A') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email Address</th>
                <th>Program</th>
                <th class="text-center">Level</th>
                <th class="text-center">Year/Section</th>
                <th class="text-center">Type</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td style="font-weight: bold; text-transform: uppercase;">{{ $student->last_name }}, {{ $student->first_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->course_code }}</td>
                    <td class="text-center">{{ strtoupper($student->level ?? 'N/A') }}</td>
                    <td class="text-center">
                        @php
                            $section = 'N/A';
                            if (!empty($student->year_level)) {
                                $parts = explode('|', $student->year_level);
                                $section = trim($parts[0]);
                            }
                            echo $section;
                        @endphp
                    </td>
                    <td class="text-center">{{ ucfirst(strtolower($student->student_type ?? 'New')) }}</td>
                    <td class="text-center">
                        @if($student->is_regular === null)
                            Not Audited
                        @elseif($student->is_regular)
                            Regular
                        @else
                            Irregular
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total Records: {{ count($students) }} students — End of Report
    </div>
</body>
</html>
