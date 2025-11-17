<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\NewsComment;
use App\Models\GalleryFavorite;
use App\Models\GalleryLike;
use App\Models\GalleryComment;

class ReportController extends Controller
{
    /**
     * Display reports index page
     */
    public function index()
    {
        $admin = auth('admin')->user();
        
        // Basic stats for overview
        $stats = [
            'total_visitors' => DB::table('visits')->count(),
            'visitors_this_month' => DB::table('visits')->whereMonth('created_at', now()->month)->count(),
            'visitors_last_month' => DB::table('visits')->whereMonth('created_at', now()->subMonth()->month)->count(),
            'total_news' => News::count(),
            'total_galleries' => Gallery::count(),
            'total_admins' => Admin::count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Export visitor statistics report
     */
    public function exportVisitorStats(Request $request)
    {
        $admin = auth('admin')->user();
        
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get visitor statistics
        $visitorStats = $this->getVisitorStatistics($startDate, $endDate);
        
        // Get school profile for branding
        $schoolProfile = \App\Models\SchoolProfile::first();

        if ($request->format === 'pdf') {
            return $this->exportVisitorStatsPDF($visitorStats, $schoolProfile, $startDate, $endDate);
        } else {
            return $this->exportVisitorStatsExcel($visitorStats, $schoolProfile, $startDate, $endDate);
        }
    }

    /**
     * Export content statistics report
     */
    public function exportContentStats(Request $request)
    {
        $admin = auth('admin')->user();
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get content statistics
        $contentStats = $this->getContentStatistics($startDate, $endDate);
        $schoolProfile = \App\Models\SchoolProfile::first();

        if ($request->format === 'pdf') {
            return $this->exportContentStatsPDF($contentStats, $schoolProfile, $startDate, $endDate);
        } else {
            return $this->exportContentStatsExcel($contentStats, $schoolProfile, $startDate, $endDate);
        }
    }

    /**
     * Export admin activity report
     */
    public function exportAdminActivity(Request $request)
    {
        $admin = auth('admin')->user();
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get admin activity statistics
        $activityStats = $this->getAdminActivityStatistics($startDate, $endDate);
        $schoolProfile = \App\Models\SchoolProfile::first();

        if ($request->format === 'pdf') {
            return $this->exportAdminActivityPDF($activityStats, $schoolProfile, $startDate, $endDate);
        } else {
            return $this->exportAdminActivityExcel($activityStats, $schoolProfile, $startDate, $endDate);
        }
    }

    /**
     * Get visitor statistics data
     */
    private function getVisitorStatistics($startDate, $endDate)
    {
        // Daily visitors
        $dailyVisitors = DB::table('visits')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly comparison
        $currentMonth = DB::table('visits')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = DB::table('visits')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        // Weekly breakdown
        $weeklyVisitors = DB::table('visits')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('TIMESTAMPDIFF(WEEK, ?, created_at) + 1 as week_index, COUNT(*) as total', [$startDate])
            ->groupBy('week_index')
            ->orderBy('week_index')
            ->get()
            ->map(function($row) use ($startDate, $endDate) {
                $start = \Carbon\Carbon::parse($startDate);
                $periodEnd = \Carbon\Carbon::parse($endDate);
                $weekStart = $start->copy()->addWeeks(($row->week_index ?? 1) - 1);
                $weekEnd = $weekStart->copy()->addDays(6);
                if ($weekEnd->greaterThan($periodEnd)) {
                    $weekEnd = $periodEnd->copy();
                }
                $days = $weekStart->diffInDays($weekEnd) + 1;
                return (object) [
                    'week_index' => $row->week_index,
                    'total' => $row->total,
                    'week_start' => $weekStart->toDateString(),
                    'week_end' => $weekEnd->toDateString(),
                    'days' => $days,
                ];
            });

        // Hourly distribution
        $periodDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
        $hourlyRaw = DB::table('visits')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('total', 'hour')
            ->toArray();

        $hourlyVisitors = collect(range(0, 23))->map(function($h) use ($hourlyRaw, $periodDays) {
            $total = (int) ($hourlyRaw[$h] ?? 0);
            return (object) [
                'hour' => $h,
                'total' => $total,
                'avg_per_day' => $periodDays > 0 ? round($total / $periodDays, 2) : 0,
            ];
        });

        return [
            'daily' => $dailyVisitors,
            'monthly_comparison' => [
                'current' => $currentMonth,
                'last' => $lastMonth,
                'percentage_change' => $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2) : 0
            ],
            'weekly' => $weeklyVisitors,
            'hourly' => $hourlyVisitors,
            'total_visitors' => $dailyVisitors->sum('total'),
            'average_daily' => $dailyVisitors->count() > 0 ? round($dailyVisitors->sum('total') / $dailyVisitors->count(), 2) : 0
        ];
    }

    /**
     * Get content statistics data
     */
    private function getContentStatistics($startDate, $endDate)
    {
        // News statistics (ringkasan)
        $newsStats = [
            'total' => News::whereBetween('created_at', [$startDate, $endDate])->count(),
            'published' => News::whereBetween('created_at', [$startDate, $endDate])->where('is_published', true)->count(),
            'draft' => News::whereBetween('created_at', [$startDate, $endDate])->where('is_published', false)->count(),
            'by_category' => News::with('newsCategory')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('news_category_id, count(*) as total')
                ->groupBy('news_category_id')
                ->get()
        ];

        // Gallery statistics (ringkasan)
        $galleryStats = [
            'total' => Gallery::whereBetween('created_at', [$startDate, $endDate])->count(),
            'published' => Gallery::whereBetween('created_at', [$startDate, $endDate])->where('is_published', true)->count(),
            'draft' => Gallery::whereBetween('created_at', [$startDate, $endDate])->where('is_published', false)->count(),
            'by_category' => Gallery::with('kategori')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('kategori_id, count(*) as total')
                ->groupBy('kategori_id')
                ->get()
        ];

        // Detail per item - News
        $newsItems = News::with('newsCategory')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('id','title','news_category_id','is_published','created_at','views')
            ->orderByDesc('created_at')
            ->get();

        $newsCommentsCounts = DB::table('news_comments')
            ->selectRaw('news_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('news_id')
            ->pluck('total','news_id');

        $newsItemsTransformed = $newsItems->map(function($n) use ($newsCommentsCounts) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'category' => optional($n->newsCategory)->name ?? optional($n->newsCategory)->nama ?? '-',
                'status' => $n->is_published ? 'Published' : 'Draft',
                'created_at' => $n->created_at,
                'views' => (int) ($n->views ?? 0),
                'comments' => (int) ($newsCommentsCounts[$n->id] ?? 0),
            ];
        });

        // Berita per kategori agregat (total, published, draft, comments)
        $newsByCategoryAgg = $newsItemsTransformed
            ->groupBy(function($item){ return $item['category'] ?: '-'; })
            ->map(function($items){
                return [
                    'category' => $items->first()['category'] ?: '-',
                    'total' => $items->count(),
                    'published' => $items->where('status','Published')->count(),
                    'draft' => $items->where('status','Draft')->count(),
                    'total_comments' => $items->sum('comments'),
                ];
            })
            ->values();

        // Detail per item - Gallery
        $galleryItems = Gallery::with('kategori')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('id','title','kategori_id','is_published','created_at','views')
            ->orderByDesc('created_at')
            ->get();

        $galleryCommentsCounts = DB::table('gallery_comments')
            ->selectRaw('gallery_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('gallery_id')
            ->pluck('total','gallery_id');

        $galleryLikesCounts = DB::table('gallery_likes')
            ->selectRaw('gallery_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('gallery_id')
            ->pluck('total','gallery_id');

        $galleryFavoritesCounts = DB::table('gallery_favorites')
            ->selectRaw('gallery_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('gallery_id')
            ->pluck('total','gallery_id');

        $galleryItemsTransformed = $galleryItems->map(function($g) use ($galleryCommentsCounts, $galleryLikesCounts, $galleryFavoritesCounts) {
            return [
                'id' => $g->id,
                'title' => $g->title,
                'category' => optional($g->kategori)->nama ?? optional($g->kategori)->name ?? '-',
                'status' => $g->is_published ? 'Published' : 'Draft',
                'created_at' => $g->created_at,
                'views' => (int) ($g->views ?? 0),
                'likes' => (int) ($galleryLikesCounts[$g->id] ?? 0),
                'comments' => (int) ($galleryCommentsCounts[$g->id] ?? 0),
                'favorites' => (int) ($galleryFavoritesCounts[$g->id] ?? 0),
            ];
        });

        // Galeri per kategori agregat (dengan like/comment/favorite, views=sum)
        $galleryByCategoryAgg = $galleryItemsTransformed
            ->groupBy(function($item){ return $item['category'] ?: '-'; })
            ->map(function($items){
                return [
                    'category' => $items->first()['category'] ?: '-',
                    'total' => $items->count(),
                    'total_views' => $items->sum('views'),
                    'total_likes' => $items->sum('likes'),
                    'total_comments' => $items->sum('comments'),
                    'total_favorites' => $items->sum('favorites'),
                ];
            })
            ->values();

        // Konten terpopuler (gabungan) - top 5
        $popularCombined = collect();
        $popularCombined = $popularCombined
            ->merge($newsItemsTransformed->map(function($n){
                return [
                    'type' => 'news',
                    'id' => $n['id'],
                    'title' => $n['title'],
                    'views' => $n['views'],
                    'likes' => 0,
                    'comments' => $n['comments'],
                    'engagement' => $n['comments'],
                ];
            }))
            ->merge($galleryItemsTransformed->map(function($g){
                $eng = ($g['likes'] + $g['comments'] + $g['favorites']);
                return [
                    'type' => 'gallery',
                    'id' => $g['id'],
                    'title' => $g['title'],
                    'views' => $g['views'],
                    'likes' => $g['likes'],
                    'comments' => $g['comments'],
                    'engagement' => $eng,
                ];
            }))
            ->sort(function($a,$b){
                // Prioritaskan views (semua 0 sekarang), fallback engagement
                if ($a['views'] === $b['views']) {
                    return $b['engagement'] <=> $a['engagement'];
                }
                return $b['views'] <=> $a['views'];
            })
            ->take(5)
            ->values();

        // Admin activity for content (ringkasan)
        $adminContentActivity = DB::table('activity_logs')
            ->join('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->whereBetween('activity_logs.created_at', [$startDate, $endDate])
            ->whereIn('activity_logs.action', ['created', 'updated', 'deleted'])
            ->selectRaw('admins.name, activity_logs.action, count(*) as total')
            ->groupBy('admins.name', 'activity_logs.action')
            ->get();

        return [
            'news' => array_merge($newsStats, [
                'items' => $newsItemsTransformed,
                'by_category_agg' => $newsByCategoryAgg,
            ]),
            'galleries' => array_merge($galleryStats, [
                'items' => $galleryItemsTransformed,
                'by_category_agg' => $galleryByCategoryAgg,
            ]),
            'popular' => $popularCombined,
            'admin_activity' => $adminContentActivity,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    /**
     * Get admin activity statistics
     */
    private function getAdminActivityStatistics($startDate, $endDate)
    {
        // Login statistics
        $loginStats = DB::table('activity_logs')
            ->join('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->whereBetween('activity_logs.created_at', [$startDate, $endDate])
            ->where('activity_logs.action', 'login')
            ->selectRaw('admins.name, count(*) as total')
            ->groupBy('admins.name')
            ->get();

        // Activity breakdown
        $activityBreakdown = DB::table('activity_logs')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->get();

        // Daily activity
        $dailyActivity = DB::table('activity_logs')
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Admin performance
        $adminPerformance = DB::table('activity_logs')
            ->join('admins', 'activity_logs.admin_id', '=', 'admins.id')
            ->whereBetween('activity_logs.created_at', [$startDate, $endDate])
            ->selectRaw('admins.name, count(*) as total_activities')
            ->groupBy('admins.name')
            ->orderBy('total_activities', 'desc')
            ->get();

        return [
            'login_stats' => $loginStats,
            'activity_breakdown' => $activityBreakdown,
            'daily_activity' => $dailyActivity,
            'admin_performance' => $adminPerformance,
            'total_activities' => $dailyActivity->sum('total'),
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ];
    }

    /**
     * Export visitor statistics to PDF
     */
    private function exportVisitorStatsPDF($data, $schoolProfile, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.reports.visitor-stats-pdf', [
            'data' => $data,
            'schoolProfile' => $schoolProfile,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()
        ]);

        $filename = 'laporan-statistik-kunjungan-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export visitor statistics to Excel
     */
    private function exportVisitorStatsExcel($data, $schoolProfile, $startDate, $endDate)
    {
        // This would require Laravel Excel package
        // For now, return a simple CSV
        $csvData = [];
        // Header Info
        $csvData[] = ['Laporan Statistik Kunjungan Website'];
        $csvData[] = ['Sekolah', $schoolProfile->school_name ?? 'Sekolah'];
        $csvData[] = ['Periode', $startDate . ' s/d ' . $endDate];
        $csvData[] = ['Generated At', now()->format('Y-m-d H:i:s')];
        $csvData[] = [];

        // Summary
        $periodDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
        $csvData[] = ['Ringkasan'];
        $csvData[] = ['Total Kunjungan', (int) ($data['total_visitors'] ?? 0)];
        $csvData[] = ['Rata-rata per Hari', (float) ($data['average_daily'] ?? 0)];
        $csvData[] = ['Periode (hari)', $periodDays];
        $csvData[] = [];

        // Monthly Comparison
        $csvData[] = ['Perbandingan Bulanan'];
        $csvData[] = ['Bulan Ini', 'Bulan Lalu', 'Perubahan %'];
        $csvData[] = [
            (int) ($data['monthly_comparison']['current'] ?? 0),
            (int) ($data['monthly_comparison']['last'] ?? 0),
            (float) ($data['monthly_comparison']['percentage_change'] ?? 0),
        ];
        $csvData[] = [];

        // Daily Visitors Table
        $csvData[] = ['Data Kunjungan Harian'];
        $csvData[] = ['Tanggal', 'Jumlah Pengunjung', 'Persentase'];
        foreach ($data['daily'] as $day) {
            $percentage = ($data['total_visitors'] ?? 0) > 0 ? round(($day->total / $data['total_visitors']) * 100, 2) : 0;
            $csvData[] = [$day->date, $day->total, $percentage];
        }
        $csvData[] = [];

        // Weekly Summary Table
        if (($data['weekly'] ?? collect())->count() > 0) {
            $csvData[] = ['Ringkasan Mingguan'];
            $csvData[] = ['Minggu', 'Rentang Tanggal', 'Jumlah Pengunjung', 'Rata-rata per Hari'];
            foreach ($data['weekly'] as $week) {
                $avg = ($week->days ?? 0) > 0 ? round($week->total / $week->days, 2) : 0;
                $csvData[] = [
                    $week->week_index ?? $week->week ?? '-',
                    (isset($week->week_start) && isset($week->week_end)) ? ($week->week_start . ' - ' . $week->week_end) : '-',
                    $week->total,
                    $avg,
                ];
            }
            $csvData[] = [];
        }

        // Hourly Distribution Table (WIB)
        if (($data['hourly'] ?? collect())->count() > 0) {
            $csvData[] = ['Distribusi per Jam (WIB)'];
            $csvData[] = ['Jam', 'Jumlah Pengunjung', 'Rata-rata per Hari', 'Persentase dari Total'];
            foreach ($data['hourly'] as $hour) {
                $percentage = ($data['total_visitors'] ?? 0) > 0 ? round(($hour->total / $data['total_visitors']) * 100, 2) : 0;
                $csvData[] = [sprintf('%02d:00-%02d:59', $hour->hour, $hour->hour), $hour->total, $hour->avg_per_day, $percentage];
            }
        }

        $filename = 'laporan-statistik-kunjungan-' . $startDate . '-to-' . $endDate . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export content statistics to PDF
     */
    private function exportContentStatsPDF($data, $schoolProfile, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.reports.content-stats-pdf', [
            'data' => $data,
            'schoolProfile' => $schoolProfile,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()
        ]);

        $filename = 'laporan-statistik-konten-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export content statistics to Excel
     */
    private function exportContentStatsExcel($data, $schoolProfile, $startDate, $endDate)
    {
        // Mirror PDF layout with structured CSV sections
        $csvData = [];

        // Header
        $csvData[] = ['Laporan Statistik Konten'];
        $csvData[] = ['Sekolah', $schoolProfile->school_name ?? 'Sekolah'];
        $csvData[] = ['Periode', $startDate . ' s/d ' . $endDate];
        $csvData[] = ['Generated At', now()->format('Y-m-d H:i:s')];
        $csvData[] = [];

        // 1) Summary Statistics
        $totalContent = (int) (($data['news']['total'] ?? 0) + ($data['galleries']['total'] ?? 0));
        $totalPublished = (int) (($data['news']['published'] ?? 0) + ($data['galleries']['published'] ?? 0));
        $totalDraft = (int) (($data['news']['draft'] ?? 0) + ($data['galleries']['draft'] ?? 0));
        $pubPct = $totalContent > 0 ? round(($totalPublished / $totalContent) * 100, 2) : 0;
        $csvData[] = ['Ringkasan Statistik Konten'];
        $csvData[] = ['Total Konten', $totalContent];
        $csvData[] = ['Berita', (int) ($data['news']['total'] ?? 0)];
        $csvData[] = ['Galeri', (int) ($data['galleries']['total'] ?? 0)];
        $csvData[] = ['Dipublikasikan (Total)', $totalPublished];
        $csvData[] = ['Draft (Total)', $totalDraft];
        $csvData[] = ['Persentase Publikasi Keseluruhan (%)', $pubPct];
        $csvData[] = [];

        // 2) News Statistics (grid + items)
        $csvData[] = ['Statistik Berita'];
        $csvData[] = ['Total Berita', 'Dipublikasikan', 'Draft', 'Persentase Publikasi (%)'];
        $newsTotal = (int) ($data['news']['total'] ?? 0);
        $newsPub = (int) ($data['news']['published'] ?? 0);
        $newsDraft = (int) ($data['news']['draft'] ?? 0);
        $newsPct = $newsTotal > 0 ? round(($newsPub / $newsTotal) * 100, 2) : 0;
        $csvData[] = [$newsTotal, $newsPub, $newsDraft, $newsPct];

        // News items table
        $csvData[] = [];
        $csvData[] = ['Daftar Berita'];
        $csvData[] = ['No','Judul','Kategori','Status','Tanggal Publish','Jumlah Pembaca','Jumlah Komentar'];
        foreach (($data['news']['items'] ?? collect()) as $i => $item) {
            $csvData[] = [
                $i + 1,
                $item['title'],
                $item['category'],
                $item['status'],
                optional($item['created_at'])->format('Y-m-d'),
                (int) ($item['views'] ?? 0),
                (int) ($item['comments'] ?? 0),
            ];
        }
        $csvData[] = [];

        // 3) Gallery Statistics (grid)
        $csvData[] = ['Statistik Galeri'];
        $csvData[] = ['Total Galeri', 'Dipublikasikan', 'Draft', 'Persentase Publikasi (%)'];
        $galTotal = (int) ($data['galleries']['total'] ?? 0);
        $galPub = (int) ($data['galleries']['published'] ?? 0);
        $galDraft = (int) ($data['galleries']['draft'] ?? 0);
        $galPct = $galTotal > 0 ? round(($galPub / $galTotal) * 100, 2) : 0;
        $csvData[] = [$galTotal, $galPub, $galDraft, $galPct];
        $csvData[] = [];

        // 4) Galleries by Category aggregate
        if (($data['galleries']['by_category_agg'] ?? collect())->count() > 0) {
            $csvData[] = ['Galeri per Kategori'];
            $csvData[] = ['Kategori','Jumlah Galeri','Total Views','Total Like','Total Komentar','Total Disimpan','Persentase dari Total Galeri (%)'];
            foreach ($data['galleries']['by_category_agg'] as $row) {
                $pct = $galTotal > 0 ? round((($row['total'] ?? 0) / $galTotal) * 100, 2) : 0;
                $csvData[] = [
                    $row['category'] ?? '-',
                    (int) ($row['total'] ?? 0),
                    (int) ($row['total_views'] ?? 0),
                    (int) ($row['total_likes'] ?? 0),
                    (int) ($row['total_comments'] ?? 0),
                    (int) ($row['total_favorites'] ?? 0),
                    $pct,
                ];
            }
            $csvData[] = [];
        }

        // 5) Popular Content (Top 5)
        if (($data['popular'] ?? collect())->count() > 0) {
            $csvData[] = ['Konten Terpopuler (Top 5)'];
            $csvData[] = ['Peringkat','Jenis','Judul','Views','Like','Komentar'];
            foreach ($data['popular'] as $i => $p) {
                $csvData[] = [
                    $i + 1,
                    $p['type'] === 'news' ? 'Berita' : 'Galeri',
                    $p['title'],
                    (int) ($p['views'] ?? 0),
                    (int) ($p['likes'] ?? 0),
                    (int) ($p['comments'] ?? 0),
                ];
            }
            $csvData[] = [];
        }

        // 6) Admin Activity on Content
        if (($data['admin_activity'] ?? collect())->count() > 0) {
            $csvData[] = ['Aktivitas Admin pada Konten'];
            $csvData[] = ['Admin','Aksi','Jumlah'];
            foreach ($data['admin_activity'] as $act) {
                $csvData[] = [
                    $act->name,
                    ucfirst($act->action),
                    (int) $act->total,
                ];
            }
        }

        $filename = 'laporan-statistik-konten-' . $startDate . '-to-' . $endDate . '.csv';

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export admin activity to PDF
     */
    private function exportAdminActivityPDF($data, $schoolProfile, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.reports.admin-activity-pdf', [
            'data' => $data,
            'schoolProfile' => $schoolProfile,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()
        ]);

        $filename = 'laporan-aktivitas-admin-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export admin activity to Excel
     */
    private function exportAdminActivityExcel($data, $schoolProfile, $startDate, $endDate)
    {
        // Mirror PDF layout with structured CSV
        $csvData = [];

        // Header
        $csvData[] = ['Laporan Aktivitas Admin'];
        $csvData[] = ['Sekolah', $schoolProfile->school_name ?? 'Sekolah'];
        $csvData[] = ['Periode', $startDate . ' s/d ' . $endDate];
        $csvData[] = ['Generated At', now()->format('Y-m-d H:i:s')];
        $csvData[] = [];

        // Summary
        $periodDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
        $avgPerDay = $periodDays > 0 ? round(($data['total_activities'] ?? 0) / $periodDays, 2) : 0;
        $csvData[] = ['Ringkasan Aktivitas'];
        $csvData[] = ['Total Aktivitas', (int) ($data['total_activities'] ?? 0)];
        $csvData[] = ['Periode (hari)', $periodDays];
        $csvData[] = ['Rata-rata per Hari', $avgPerDay];
        $csvData[] = [];

        // Login Statistics
        if (($data['login_stats'] ?? collect())->count() > 0) {
            $csvData[] = ['Statistik Login Admin'];
            $csvData[] = ['Nama Admin','Jumlah Login','Persentase (%)'];
            foreach ($data['login_stats'] as $login) {
                $pct = ($data['total_activities'] ?? 0) > 0 ? round(($login->total / $data['total_activities']) * 100, 2) : 0;
                $csvData[] = [$login->name, (int) $login->total, $pct];
            }
            $csvData[] = [];
        }

        // Activity Breakdown
        if (($data['activity_breakdown'] ?? collect())->count() > 0) {
            $csvData[] = ['Jenis Aktivitas'];
            $csvData[] = ['Jenis','Jumlah','Persentase (%)'];
            foreach ($data['activity_breakdown'] as $ab) {
                $pct = ($data['total_activities'] ?? 0) > 0 ? round(($ab->total / $data['total_activities']) * 100, 2) : 0;
                $csvData[] = [ucfirst($ab->action), (int) $ab->total, $pct];
            }
            $csvData[] = [];
        }

        // Daily Activity
        if (($data['daily_activity'] ?? collect())->count() > 0) {
            $csvData[] = ['Aktivitas Harian'];
            $csvData[] = ['Tanggal','Jumlah Aktivitas','Persentase (%)'];
            foreach ($data['daily_activity'] as $day) {
                $pct = ($data['total_activities'] ?? 0) > 0 ? round(($day->total / $data['total_activities']) * 100, 2) : 0;
                $csvData[] = [optional(\Carbon\Carbon::parse($day->date))->format('Y-m-d'), (int) $day->total, $pct];
            }
            $csvData[] = [];
        }

        // Admin Performance
        if (($data['admin_performance'] ?? collect())->count() > 0) {
            $csvData[] = ['Performa Admin'];
            $csvData[] = ['Nama Admin','Total Aktivitas','Persentase (%)','Ranking'];
            foreach ($data['admin_performance'] as $idx => $admin) {
                $pct = ($data['total_activities'] ?? 0) > 0 ? round(($admin->total_activities / $data['total_activities']) * 100, 2) : 0;
                $csvData[] = [$admin->name, (int) $admin->total_activities, $pct, $idx + 1];
            }
        }

        $filename = 'laporan-aktivitas-admin-' . $startDate . '-to-' . $endDate . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
