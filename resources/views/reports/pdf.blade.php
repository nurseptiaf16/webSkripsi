<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pertumbuhan Pelanggan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page {
            size: A4 portrait;
            margin: 14mm 12mm;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #0f172a;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e11d48;
            margin-bottom: 16px;
        }
        .header-table td {
            vertical-align: middle;
            padding-bottom: 12px;
        }
        .header-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
        }
        .header-title {
            font-size: 18px;
            font-weight: 700;
            color: #e11d48;
            margin-bottom: 2px;
        }
        .header-meta {
            font-size: 10px;
            color: #64748b;
        }
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 16px;
        }
        .summary-cell {
            border: 1px solid #e2e8f0;
            border-top: 3px solid #e11d48;
            border-radius: 8px;
            padding: 10px 8px;
            text-align: center;
        }
        .summary-cell .label {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .summary-cell .value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }
        .chart-wrap {
            margin: 14px 0 12px;
            page-break-inside: avoid;
        }
        .chart-image {
            width: 100%;
            max-height: 90mm;
            object-fit: contain;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            table-layout: fixed;
        }
        thead {
            display: table-header-group;
        }
        th {
            background: #f1f5f9;
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 7px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            word-wrap: break-word;
        }
        tr:nth-child(even) td {
            background: #f8fafc;
        }
        .col-no { width: 6%; }
        .col-period { width: 22%; }
        .col-actual { width: 13%; }
        .col-forecast { width: 13%; }
        .col-growth { width: 12%; }
        .col-percent { width: 17%; }
        .col-accuracy { width: 17%; }
        .positive { color: #16a34a; }
        .negative { color: #dc2626; }
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-good {
            background: #f0fdf4;
            color: #16a34a;
        }
        .badge-medium {
            background: #fffbeb;
            color: #d97706;
        }
        .badge-bad {
            background: #fef2f2;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="header-title">Laporan Pertumbuhan Pelanggan</div>
                <div class="header-meta">OLT: {{ $oltName }}</div>
                <div class="header-meta">Dicetak pada: {{ date('d F Y, H:i') }} WIB</div>
            </td>
            <td style="width: 72px; text-align: right;">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo" class="header-logo">
                @endif
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <div class="label">Total Pelanggan</div>
                <div class="value">{{ $summary['total'] }}</div>
            </td>
            <td class="summary-cell">
                <div class="label">Rata-rata Pertumbuhan</div>
                <div class="value">{{ $summary['avgGrowth'] }}%</div>
            </td>
            <td class="summary-cell">
                <div class="label">MAPE</div>
                <div class="value">{{ $summary['mape'] }}%</div>
            </td>
            <td class="summary-cell">
                <div class="label">Kategori Akurasi</div>
                <div class="value">
                    <span class="badge {{ $summary['mape'] < 10 ? 'badge-good' : ($summary['mape'] < 20 ? 'badge-medium' : 'badge-bad') }}">
                        {{ $summary['kategori'] }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    @if(!empty($chartImage) && $chartImage !== 'data:,')
        <div class="chart-wrap">
            <div class="section-title">Grafik Pertumbuhan Pelanggan</div>
            <img src="{{ $chartImage }}" class="chart-image" alt="Grafik Pertumbuhan">
        </div>
    @endif

    <table class="report-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-period">Periode</th>
                <th class="col-actual">Actual</th>
                <th class="col-forecast">Forecast</th>
                <th class="col-growth">Growth</th>
                <th class="col-percent">Pertumbuhan (%)</th>
                <th class="col-accuracy">Akurasi (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row['periode'] }}</td>
                    <td>{{ $row['actual'] }}</td>
                    <td>{{ $row['forecast'] }}</td>
                    <td class="{{ $row['growth'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $row['growth'] >= 0 ? '+' : '' }}{{ $row['growth'] }}
                    </td>
                    <td class="{{ $row['percent'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $row['percent'] >= 0 ? '+' : '' }}{{ $row['percent'] }}%
                    </td>
                    <td>{{ $row['akurasi'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dibuat otomatis oleh sistem FiberOptic Monitor
    </div>

</body>
</html>
