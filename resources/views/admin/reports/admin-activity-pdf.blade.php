<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aktivitas Admin</title>
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
            border-bottom: 3px solid #8b5cf6;
            padding-bottom: 20px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #7c3aed;
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
            border-left: 4px solid #8b5cf6;
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
            background-color: #8b5cf6;
            color: white;
            font-weight: bold;
        }
        
        .stats-cell.data {
            background-color: white;
        }
        
        .highlight {
            background-color: #ede9fe;
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
            background-color: #8b5cf6;
            color: white;
            font-weight: bold;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .summary-box {
            background-color: #faf5ff;
            border: 1px solid #a855f7;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .summary-title {
            font-weight: bold;
            color: #7c3aed;
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="school-name">{{ $schoolProfile->school_name ?? 'Sekolah' }}</div>
        <div class="report-title">Laporan Aktivitas Admin</div>
        <div class="report-period">
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
        </div>
        <div style="font-size: 10px; color: #9ca3af; margin-top: 5px;">
            Generated on {{ $generatedAt->format('d F Y H:i:s') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="section">
        <div class="section-title">Ringkasan Aktivitas</div>
        <div class="summary-box">
            <div class="summary-title">Total Aktivitas: {{ number_format($data['total_activities']) }}</div>
            <div>Periode laporan: {{ \Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1 }} hari</div>
            <div>Rata-rata per hari: {{ \Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1 > 0 ? number_format($data['total_activities'] / (\Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1), 2) : 0 }} aktivitas</div>
        </div>
    </div>

    <!-- Login Statistics -->
    @if($data['login_stats']->count() > 0)
    <div class="section">
        <div class="section-title">Statistik Login Admin</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Admin</th>
                    <th>Jumlah Login</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['login_stats'] as $login)
                <tr>
                    <td>{{ $login->name }}</td>
                    <td>{{ number_format($login->total) }}</td>
                    <td>{{ $data['total_activities'] > 0 ? number_format(($login->total / $data['total_activities']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Activity Breakdown -->
    @if($data['activity_breakdown']->count() > 0)
    <div class="section">
        <div class="section-title">Jenis Aktivitas</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Jenis Aktivitas</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['activity_breakdown'] as $activity)
                <tr>
                    <td>{{ ucfirst($activity->action) }}</td>
                    <td>{{ number_format($activity->total) }}</td>
                    <td>{{ $data['total_activities'] > 0 ? number_format(($activity->total / $data['total_activities']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Daily Activity -->
    @if($data['daily_activity']->count() > 0)
    <div class="section">
        <div class="section-title">Aktivitas Harian</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Aktivitas</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['daily_activity'] as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d F Y') }}</td>
                    <td>{{ number_format($day->total) }}</td>
                    <td>{{ $data['total_activities'] > 0 ? number_format(($day->total / $data['total_activities']) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Admin Performance -->
    @if($data['admin_performance']->count() > 0)
    <div class="section">
        <div class="section-title">Performa Admin</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Admin</th>
                    <th>Total Aktivitas</th>
                    <th>Persentase</th>
                    <th>Ranking</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['admin_performance'] as $index => $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ number_format($admin->total_activities) }}</td>
                    <td>{{ $data['total_activities'] > 0 ? number_format(($admin->total_activities / $data['total_activities']) * 100, 2) : 0 }}%</td>
                    <td>
                        @if($index == 0)
                            <span style="color: #f59e0b; font-weight: bold;">🥇 #1</span>
                        @elseif($index == 1)
                            <span style="color: #6b7280; font-weight: bold;">🥈 #2</span>
                        @elseif($index == 2)
                            <span style="color: #cd7f32; font-weight: bold;">🥉 #3</span>
                        @else
                            #{{ $index + 1 }}
                        @endif
                    </td>
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
