<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Case Brief - Case #{{ $case->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .firm-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
        }
        .document-title {
            font-size: 18px;
            font-weight: normal;
            color: #7f8c8d;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            width: 30%;
            text-align: left;
            padding: 8px 0;
            color: #7f8c8d;
            font-weight: normal;
            vertical-align: top;
        }
        table td {
            width: 70%;
            padding: 8px 0;
            font-weight: bold;
            vertical-align: top;
        }
        .status-badge {
            background-color: #ecf0f1;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .signature-block {
            margin-top: 80px;
            width: 40%;
            float: right;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
        }
        .date-generated {
            margin-top: 60px;
            font-size: 12px;
            color: #95a5a6;
            clear: both;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="firm-name">LexLanka - Legal Practice Management</h1>
        <h2 class="document-title">Official Case Brief</h2>
    </div>

    <div class="section-title">Client Details</div>
    <table>
        <tr>
            <th>Client Name:</th>
            <td>{{ $case->client->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>National ID (NIC):</th>
            <td>{{ $case->client->nic ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Contact:</th>
            <td>
                {{ $case->client->phone ?? 'No Phone' }} 
                @if($case->client->email)
                    | {{ $case->client->email }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Address:</th>
            <td>{{ $case->client->address ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">Case Details</div>
    <table>
        <tr>
            <th>Case Number:</th>
            <td>#{{ str_pad($case->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <th>Case Type:</th>
            <td>{{ $case->case_type }}</td>
        </tr>
        <tr>
            <th>Current Status:</th>
            <td><span class="status-badge">{{ str_replace('_', ' ', $case->status) }}</span></td>
        </tr>
        <tr>
            <th>Date Opened:</th>
            <td>{{ $case->created_at->format('F d, Y') }}</td>
        </tr>
        <tr>
            <th>Assigned Attorney:</th>
            <td>{{ $case->assignedAttorney->name ?? 'Unassigned' }}</td>
        </tr>
    </table>

    <div class="signature-block">
        <div class="signature-line"></div>
        <div>Attorney Signature</div>
        <div style="margin-top: 5px; font-weight: bold;">
            {{ $case->assignedAttorney->name ?? '_____________________' }}
        </div>
    </div>

    <div class="date-generated">
        Generated on: {{ now()->format('F d, Y \a\t H:i:s') }}
    </div>

</body>
</html>
