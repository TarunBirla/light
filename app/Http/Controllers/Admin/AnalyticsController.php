<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', '30'); // default 30 days

        $startDate = match ($range) {
            'today' => Carbon::today(),
            'yesterday' => Carbon::yesterday(),
            '7' => Carbon::now()->subDays(7)->startOfDay(),
            '30' => Carbon::now()->subDays(30)->startOfDay(),
            'this_month' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->subDays(30)->startOfDay(),
        };

        $endDate = $range === 'yesterday' ? Carbon::yesterday()->endOfDay() : Carbon::now()->endOfDay();

        // 1. Top KPI Summary Counters
        $totalUniqueVisitors = Visitor::count();
        $filteredUniqueVisitors = Visitor::whereBetween('created_at', [$startDate, $endDate])->count();
        $todayVisitors = Visitor::whereDate('last_visit_at', Carbon::today())->count();
        $totalPageViews = PageView::whereBetween('viewed_at', [$startDate, $endDate])->count();

        // Top Most Visited Page
        $topPage = PageView::select('page_name', 'url', DB::raw('count(*) as total'))
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->groupBy('page_name', 'url')
            ->orderByDesc('total')
            ->first();

        // 2. Timeline Graph Data (Visitors vs Pageviews)
        $dates = [];
        $visitorsData = [];
        $pageViewsData = [];

        $days = ($range === 'today' || $range === 'yesterday') ? 1 : ($range === '7' ? 7 : 30);
        
        if ($range === 'today') {
            // Hour-wise chart for today
            for ($h = 0; $h <= 23; $h++) {
                $hourLabel = sprintf('%02d:00', $h);
                $dates[] = $hourLabel;

                $visitorsData[] = Visitor::whereDate('created_at', Carbon::today())
                    ->whereTime('created_at', '>=', sprintf('%02d:00:00', $h))
                    ->whereTime('created_at', '<=', sprintf('%02d:59:59', $h))
                    ->count();

                $pageViewsData[] = PageView::whereDate('viewed_at', Carbon::today())
                    ->whereTime('viewed_at', '>=', sprintf('%02d:00:00', $h))
                    ->whereTime('viewed_at', '<=', sprintf('%02d:59:59', $h))
                    ->count();
            }
        } else {
            // Day-wise chart
            for ($i = $days - 1; $i >= 0; $i--) {
                $curDate = Carbon::now()->subDays($i)->format('Y-m-d');
                $dates[] = Carbon::now()->subDays($i)->format('d M');

                $visitorsData[] = Visitor::whereDate('created_at', $curDate)->count();
                $pageViewsData[] = PageView::whereDate('viewed_at', $curDate)->count();
            }
        }

        // 3. Top Browsers Breakdown
        $browserStats = Visitor::select('browser', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('browser')
            ->orderByDesc('total')
            ->get();

        // 4. Device Breakdown (Desktop, Mobile, Tablet)
        $deviceStats = Visitor::select('device', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();

        // 5. Top Countries Breakdown
        $countryStats = Visitor::select('country', 'country_code', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country')
            ->groupBy('country', 'country_code')
            ->orderByDesc('total')
            ->limit(7)
            ->get();

        // 6. Top Visited Pages List
        $topPagesList = PageView::select('page_name', 'url', DB::raw('count(*) as total'))
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->groupBy('page_name', 'url')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // 7. Recent Visitors Table (Paginated with search)
        $query = Visitor::withCount('pageViews')->latest('last_visit_at');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('landing_page', 'like', "%{$search}%");
            });
        }

        $visitors = $query->paginate(15)->withQueryString();

        return view('admin.analytics.index', compact(
            'totalUniqueVisitors',
            'filteredUniqueVisitors',
            'todayVisitors',
            'totalPageViews',
            'topPage',
            'dates',
            'visitorsData',
            'pageViewsData',
            'browserStats',
            'deviceStats',
            'countryStats',
            'topPagesList',
            'visitors',
            'range'
        ));
    }

    /**
     * AJAX details of specific visitor journey (All pages visited)
     */
    public function visitorJourney($id)
    {
        $visitor = Visitor::with(['pageViews' => function($q) {
            $q->latest('viewed_at')->limit(100);
        }])->findOrFail($id);

        return response()->json([
            'status'  => true,
            'visitor' => $visitor,
            'pages'   => $visitor->pageViews,
        ]);
    }
}
