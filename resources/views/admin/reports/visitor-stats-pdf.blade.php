<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Statistik Kunjungan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 18px;
            color: #374151;
            margin-bottom: 10px;
        }
        
        .report-period {
            font-size: 14px;
            color: #6b7280;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding: 8px 12px;
            background-color: #f3f4f6;
            border-left: 4px solid #3b82f6;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
        }
        
        .stats-cell.header {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }
        
        .stats-cell.data {
            background-color: white;
        }
        
        .highlight {
            background-color: #dbeafe;
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #d1d5db;
        }
        
        .table th {
            background-color: #3b82f6;
            color: white;
            font-weight: bold;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .summary-box {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .summary-title {
            font-weight: bold;
            color: #0369a1;
            margin-bottom: 10px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="school-name">{{ $schoolProfile->school_name ?? 'Sekolah' }}</div>
        <div class="report-title">Laporan Statistik Kunjungan Website</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
        </div>
        <div style="font-size: 10px; color: #9ca3af; margin-top: 5px;">
            Generated on {{ $generatedAt->format('d F Y H:i:s') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-title">Ringkasan Statistik</div>
        <div class="summary-box">
            <div class="summary-title">Total Kunjungan: {{ number_format($data['total_visitors']) }}</div>
            <div>Rata-rata per hari: {{ number_format($data['average_daily'], 2) }} pengunjung</div>
            <div>Periode laporan: {{ \Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1 }} hari</div>
        </div>
    </div>

    <!-- Monthly Comparison -->
    <div class="section">
        <div class="section-title">Perbandingan Bulanan</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Bulan Ini</div>
                <div class="stats-cell header">Bulan Lalu</div>
                <div class="stats-cell header">Perubahan</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell data highlight">{{ number_format($data['monthly_comparison']['current']) }}</div>
                <div class="stats-cell data">{{ number_format($data['monthly_comparison']['last']) }}</div>
                <div class="stats-cell data {{ $data['monthly_comparison']['percentage_change'] >= 0 ? 'highlight' : '' }}">
                    {{ $data['monthly_comparison']['percentage_change'] >= 0 ? '+' : '' }}{{ $data['monthly_comparison']['percentage_change'] }}%
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Visitors Table -->
    <div class="section">
        <div class="section-title">Data Kunjungan Harian</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Pengunjung</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['daily'] as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d F Y') }}</td>
                    <td>{{ number_format($day->total) }}</td>
                    <td>{{ $data['total_visitors'] > 0 ? number_format(($day->total / $data['total_visitors']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Weekly Summary -->
    @if($data['weekly']->count() > 0)
    <div class="section">
        <div class="section-title">Ringkasan Mingguan</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Minggu</th>
                    <th>Rentang Tanggal</th>
                    <th>Jumlah Pengunjung</th>
                    <th>Rata-rata per Hari</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['weekly'] as $week)
                <tr>
                    <td>{{ $week->week_index }}</td>
                    <td>{{ \Carbon\Carbon::parse($week->week_start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($week->week_end)->format('d M Y') }}</td>
                    <td>{{ number_format($week->total) }}</td>
                    <td>{{ $week->days > 0 ? number_format($week->total / $week->days, 2) : '0.00' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Hourly Distribution -->
    @if($data['hourly']->count() > 0)
    <div class="section">
        <div class="section-title">Distribusi Kunjungan per Jam (WIB)</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Jam</th>
                    <th>Jumlah Pengunjung</th>
                    <th>Rata-rata per Hari</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['hourly'] as $hour)
                <tr>
                    <td>{{ $hour->hour }}:00 - {{ $hour->hour }}:59</td>
                    <td>{{ number_format($hour->total) }}</td>
                    <td>{{ number_format($hour->avg_per_day, 2) }}</td>
                    <td>{{ $data['total_visitors'] > 0 ? number_format(($hour->total / $data['total_visitors']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem Galeri Sekolah</p>
        <p>{{ $schoolProfile->school_name ?? 'Sekolah' }} - {{ $generatedAt->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
