<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalArticles'     => Article::count(),
            'totalProjects'     => Project::count(),
            'draftArticles'     => Article::where('status', 'draft')->count(),
            'totalCategories'   => Category::count(),
            'monthlyVisitors'   => $this->countMonthlyVisitors(),
            'unreadMessages'    => 0,
            'newMessagesToday'  => 0,
            'popularArticles'   => 0,
            'averageViews'      => $this->averageArticleViews(),
            'trends'            => [
                'totalArticles'   => $this->growthPercent(Article::count(), $this->previousMonthCount(Article::class)),
                'totalProjects'   => $this->growthPercent(Project::count(), $this->previousMonthCount(Project::class)),
                'monthlyVisitors' => $this->growthPercent($this->countMonthlyVisitors(), $this->countPreviousMonthlyVisitors()),
                'popularArticles' => $this->growthPercent($this->popularArticlesCount(), $this->popularArticlesCount(true)),
            ],
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'visitTrends' => [
                '7'  => $this->visitTrendsData(7),
                '30' => $this->visitTrendsData(30),
                '90' => $this->visitTrendsData(90),
            ],
            'popularArticles' => $this->popularArticles(),
            'recentArticles'  => $this->recentArticles(),
            'recentProjects'  => $this->recentProjects(),
        ]);
    }

    private function countMonthlyVisitors(): int
    {
        // Menghitung unique visitor per ip_hash + session_token di bulan berjalan
        return \DB::table('visits')
            ->whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year)
            ->selectRaw('COUNT(DISTINCT CONCAT(ip_hash, "-", session_token)) as total')
            ->value('total') ?? 0;
    }

    private function averageArticleViews(): int
    {
        $publishedCount = Article::where('status', 'published')->count();
        if ($publishedCount === 0) {
            return 0;
        }

        $totalViews = DB::table('article_views')->count();

        return (int) round($totalViews / $publishedCount);
    }

    private function visitTrendsData(int $days): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $raw = DB::table('visits')
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as total, COUNT(DISTINCT CONCAT(ip_hash, "-", session_token)) as unique_visitors')
            ->where('visited_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $total = [];
        $unique = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d M');
            $total[] = (int) ($raw[$date]->total ?? 0);
            $unique[] = (int) ($raw[$date]->unique_visitors ?? 0);
        }

        return [
            'labels' => $labels,
            'total' => $total,
            'unique' => $unique,
        ];
    }

    private function popularArticles()
    {
        $viewsSub = DB::table('article_views')
            ->select('article_id', DB::raw('COUNT(*) as views'))
            ->groupBy('article_id');

        return Article::select('articles.id', 'articles.title', 'articles.created_at', DB::raw('COALESCE(av.views, 0) as views'))
            ->leftJoinSub($viewsSub, 'av', 'av.article_id', '=', 'articles.id')
            ->where('articles.status', 'published')
            ->orderByDesc('views')
            ->orderByDesc('articles.created_at')
            ->take(5)
            ->get();
    }

    private function recentArticles()
    {
        return Article::select('id', 'title', 'status', 'created_at')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
    }

    private function recentProjects()
    {
        return Project::select('id', 'title', 'is_active', 'created_at')
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($project) {
                $project->status = $project->is_active ? 'published' : 'draft';
                return $project;
            });
    }

    private function previousMonthCount(string $modelClass): int
    {
        $start = now()->subMonthNoOverflow()->startOfMonth();
        $end = now()->subMonthNoOverflow()->endOfMonth();

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = new $modelClass;

        if (!method_exists($model, 'getTable')) {
            return 0;
        }

        return $modelClass::whereBetween('created_at', [$start, $end])->count();
    }

    private function growthPercent(int $current, int $previous): int
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function countPreviousMonthlyVisitors(): int
    {
        $start = now()->subMonthNoOverflow()->startOfMonth();
        $end = now()->subMonthNoOverflow()->endOfMonth();

        return \DB::table('visits')
            ->whereBetween('visited_at', [$start, $end])
            ->selectRaw('COUNT(DISTINCT CONCAT(ip_hash, "-", session_token)) as total')
            ->value('total') ?? 0;
    }

    private function popularArticlesCount(bool $previousMonth = false): int
    {
        $query = DB::table('article_views');

        if ($previousMonth) {
            $start = now()->subMonthNoOverflow()->startOfMonth();
            $end = now()->subMonthNoOverflow()->endOfMonth();
            $query->whereBetween('viewed_at', [$start, $end]);
        } else {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            $query->whereBetween('viewed_at', [$start, $end]);
        }

        return $query->count();
    }
}
