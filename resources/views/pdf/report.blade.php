<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Processing Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1b263b;
            padding: 30px 28px;
            line-height: 1.5;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 3px solid #0056b3;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #0056b3;
            margin-bottom: 3px;
            letter-spacing: 0.2px;
        }
        .header .subtitle {
            font-size: 12px;
            color: #64748b;
            font-weight: 400;
        }

        /* ── Filter Meta ── */
        .meta-table { width: 100%; margin-bottom: 16px; border-collapse: collapse; }
        .meta-table td {
            padding: 4px 10px;
            font-size: 11.5px;
            vertical-align: top;
        }
        .meta-table td.label { font-weight: 600; color: #475569; width: 130px; }
        .meta-table td.value { color: #1b263b; }

        /* ── Total Row ── */
        .total-row {
            margin-top: 10px;
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        /* ── Data Table ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .data-table th {
            background: #0056b3;
            color: #fff;
            padding: 8px 7px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .data-table td {
            padding: 7px 7px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: top;
            word-break: break-word;
            line-height: 1.45;
        }
        .data-table tr:nth-child(even) td { background: #f8fafc; }

        /* ── Status Badges ── */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-submitted { background: #eff6ff; color: #2563eb; }
        .badge-received { background: #f0fdf4; color: #16a34a; }
        .badge-in_review { background: #fffbeb; color: #d97706; }
        .badge-forwarded { background: #f5f3ff; color: #7c3aed; }
        .badge-completed { background: #f0fdf4; color: #15803d; }
        .badge-for_pickup { background: #fff7ed; color: #c2410c; }
        .badge-returned { background: #fef2f2; color: #dc2626; }
        .badge-cancelled { background: #f8fafc; color: #64748b; }
        .badge-on_hold { background: #fefce8; color: #a16207; }
        .badge-archived { background: #f1f5f9; color: #475569; }

        .mono {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 22px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DepEd Document Tracking System - Processing Report</h1>
        <div class="subtitle">Generated: {{ $generatedAt }}</div>
    </div>

    <table class="meta-table">
        <tr><td class="label">Office:</td><td class="value">{{ $officeName }}</td></tr>
        <tr><td class="label">Keyword:</td><td class="value">{{ $searchLabel }}</td></tr>
        <tr><td class="label">Status:</td><td class="value">{{ $statusLabel }}</td></tr>
        <tr><td class="label">Document Type:</td><td class="value">{{ $typeLabel }}</td></tr>
        <tr><td class="label">Date Basis:</td><td class="value">{{ $dateFieldLabel }}</td></tr>
        <tr><td class="label">Date Range:</td><td class="value">{{ $dateFromLabel }} - {{ $dateToLabel }}</td></tr>
    </table>

    <div class="total-row">Total Records: {{ $rows->count() }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Subject</th>
                <th>Type</th>
                <th>Submitted By</th>
                <th>Status</th>
                <th>Tagged To</th>
                <th>Submitted At</th>
                <th>Last Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $doc)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="mono">{{ $doc->reference_number ?: $doc->tracking_number }}</td>
                    <td>{{ $doc->subject }}</td>
                    <td>{{ $doc->type }}</td>
                    <td>{{ $doc->sender_name }}</td>
                    <td><span class="badge badge-{{ $doc->status }}">{{ $doc->statusLabel() }}</span></td>
                    <td>{{ $doc->currentHandler?->name ?? 'Unassigned' }}</td>
                    <td>{{ $doc->created_at?->copy()->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                    <td>{{ $doc->last_action_at?->copy()->setTimezone('Asia/Manila')->format('M d, Y h:i A') ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} DepEd Document Tracking System - DOCTRAX | This report is system-generated.
    </div>
</body>
</html>
