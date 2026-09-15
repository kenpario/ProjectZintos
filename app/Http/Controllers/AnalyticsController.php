<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{

    // Available date ranges for the filter dropdown
    protected $dateRanges = ['today', 'weekly', 'monthly', 'custom'];

    public function index(Request $request)
    {
        if (! Auth::user()->group?->is_admin) {
            abort(403, 'Unauthorized Action!');
        }
        // 1. Get and process the selected date range
        $range = $request->input('date_range', 'weekly');
        list($period, $startDate, $endDate) = $this->getAnalyticsPeriod($request, $range);

        // 2. Fetch all required data from Google Analytics API
        $data = $this->fetchAnalyticsData($period);

        // 3. Prepare Charts
        $charts = $this->prepareCharts($data);
        // 4. Return to the view
        return view('analytics.index', array_merge($data, [
            'charts'       => $charts,
            'currentRange' => $range,
            'dateRanges'   => $this->dateRanges,
            'startDate'    => $startDate->toDateString(),
            'endDate'      => $endDate->toDateString(),
        ]));
    }

    // --- Helper for Date Filtering ---
    private function getAnalyticsPeriod(Request $request, $range): array
    {
        $startDate = Carbon::today();
        $endDate = Carbon::today();

        switch ($range) {
            case 'weekly':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                break;
            case 'monthly':
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date', $startDate));
                $endDate = Carbon::parse($request->input('end_date', $endDate));
                break;
        }

        $period = Period::create($startDate, $endDate);
        return [$period, $startDate, $endDate];
    }

    // --- Data Fetching Method ---
    private function fetchAnalyticsData(Period $period): array
    {
        // Fetch data in parallel where possible
        $totalVisitorsAndPageViews = Analytics::fetchTotalVisitorsAndPageViews($period);
        $visitsTrend = $this->getVisitsTrend($period);
        $usersTrend = $this->getUsersTrend($period);

        $data = [
            // Info Boxes
            'total_visits' => $totalVisitorsAndPageViews->sum('screenPageViews') ?? 0,
            'total_users'  => $usersTrend->sum('totalUsers') ?? 0,
            'new_users' => $usersTrend->sum('newUsers') ?? 0,
            'bounce_rate' => $this->calculateBounceRate($period),
            'avg_session_duration' => $this->getAvgSessionDuration($period),
            // Charts Data
            'visits_by_day'  => $visitsTrend,
            'top_pages'      => Analytics::fetchMostVisitedPages($period)->take(10),
            'user_countries' => $this->getUserData($period, ['country'])->take(6),
            'top_referrers'  => $this->getTopReferrers($period)->take(5),
            'top_browsers'   => $this->getUserData($period, ['browser'])->take(5),
            'top_devices'    => $this->getUserData($period, ['deviceCategory'])->take(5),
            'visits_and_users_trend' => $this->getVisitsAndUsersTrend($period),
        ];

        return $data;
    }

    // --- Individual Data Fetching Helpers ---

    private function getVisitsAndUsersTrend(Period $period): Collection
    {
        return Analytics::get($period, ['totalUsers', 'screenPageViews'], ['date'])
            ->map(function ($item) {
                return [
                    'date' => $item['date']->format('Y-m-d'),
                    'totalUsers' => $item['totalUsers'],
                    'screenPageViews' => $item['screenPageViews'],
                ];
            });
    }

    private function getVisitsTrend(Period $period): Collection
    {
        return Analytics::get($period, ['screenPageViews'], ['date'])
            ->mapWithKeys(function ($item) {
                return [$item['date']->format('Y-m-d') => $item['screenPageViews']];
            });
    }

    private function getUserData(Period $period, array $dimensions): Collection
    {
        return Analytics::get($period, ['screenPageViews'], $dimensions)
            ->mapWithKeys(function ($item) use ($dimensions) {
                return [$item[$dimensions[0]] => $item['screenPageViews']];
            })
            ->sortByDesc(fn($views) => $views);
    }

    private function getTopReferrers(Period $period): Collection
    {
        return Analytics::fetchTopReferrers($period);
    }

    private function calculateBounceRate(Period $period): float
    {
        $data = Analytics::get($period, ['bounceRate']);
        return isset($data[0]['bounceRate']) ? round((float) $data[0]['bounceRate'] * 100, 2) : 0.0;
    }

    private function getAvgSessionDuration(Period $period): string
    {
        $data = Analytics::get($period, ['averageSessionDuration']);
        $avgSeconds = isset($data[0]['averageSessionDuration']) ? (float) $data[0]['averageSessionDuration'] : 0;
        return gmdate("H:i:s", (int) $avgSeconds);
    }

    private function getUsersTrend(Period $period): Collection
    {
        return Analytics::get($period, ['totalUsers', 'newUsers'], ['date'])
            ->map(function ($item) {
                return [
                    'date' => $item['date']->format('Y-m-d'),
                    'totalUsers' => $item['totalUsers'],
                    'newUsers' => $item['newUsers'],
                ];
            });
    }

    // --- Chart Preparation Method ---

    private function prepareCharts(array $data): array
    {
        $charts = [];
        $colors = ['#3498db', '#e74c3c', '#2ecc71', '#f1c40f', '#CD49E4', '#1abc9c', '#798686', '#FF1491', '#C5E845', '#0F0E20'];

        // Line Chart: Visits Trend
        $lineChart = new Chart;
        $lineChart->title('Visits Trend Over Time');
        $lineChart->labels($data['visits_by_day']->keys()->all());
        $lineChart->options(['responsive' => true, 'maintainAspectRatio' => false]);
        $lineChart->dataset('Page Views', 'line', $data['visits_by_day']->values()->all())
            ->backgroundColor('rgba(52, 152, 219, 0.2)')
            ->color('#3498db');
        $charts['line'] = $lineChart;

        // Bar Chart: Top 10 Most Visited Pages
        $barChart = new Chart;
        $barChart->title('Top 10 Most Visited Pages');
        $barChart->labels($data['top_pages']->pluck('pageTitle')->all());
        $barChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'xAxes' => [[
                    'ticks' => [
                        'display' => false
                    ]
                ]]
            ]
        ]);
        $barChart->dataset('Page Views', 'bar', $data['top_pages']->pluck('screenPageViews')->all())
            ->backgroundColor($colors);
        $charts['bar'] = $barChart;

        // Bar Chart: Visits by Country
        $countryBarChart = new Chart;
        $countryBarChart->title('Visits by Country');
        $countryBarChart->labels($data['user_countries']->keys()->all());
        $countryBarChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'xAxes' => [['ticks' => ['beginAtZero' => true]]],
                'yAxes' => [['ticks' => ['beginAtZero' => true]]]
            ]
        ]);
        $countryBarChart->dataset('Page Views', 'bar', $data['user_countries']->values()->all())
            ->backgroundColor(array_slice($colors, 0, count($data['user_countries'])));
        $charts['country_bar'] = $countryBarChart;

        // Pie Chart: Top Referrers
        $referrerPieChart = new Chart;
        $referrerPieChart->title('Top 5 Referrers');
        $referrerPieChart->labels($data['top_referrers']->pluck('pageReferrer')->all());
        $referrerPieChart->options(['responsive' => true, 'maintainAspectRatio' => false]);
        $referrerPieChart->dataset('Views', 'pie', $data['top_referrers']->pluck('screenPageViews')->all())
            ->backgroundColor(array_slice($colors, 0, count($data['top_referrers'])));
        $charts['referrer_pie'] = $referrerPieChart;

        // Bar Chart: Top Browsers
        $browserBarChart = new Chart;
        $browserBarChart->title('Top 5 Browsers');
        $browserBarChart->labels($data['top_browsers']->keys()->all());
        $browserBarChart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'xAxes' => [['ticks' => ['beginAtZero' => true]]],
                'yAxes' => [['ticks' => ['beginAtZero' => true]]]
            ]
        ]);
        $browserBarChart->dataset('Views', 'bar', $data['top_browsers']->values()->all())
            ->backgroundColor(array_slice($colors, 0, count($data['top_browsers'])));
        $charts['browser_bar'] = $browserBarChart;

        // Doughnut Chart: Top Devices
        $deviceDoughnutChart = new Chart;
        $deviceDoughnutChart->title('Top Devices');
        $deviceDoughnutChart->labels($data['top_devices']->keys()->all());
        $deviceDoughnutChart->options(['responsive' => true, 'maintainAspectRatio' => false]);
        $deviceDoughnutChart->dataset('Views', 'doughnut', $data['top_devices']->values()->all())
            ->backgroundColor(array_slice($colors, 0, count($data['top_devices'])));
        $charts['device_doughnut'] = $deviceDoughnutChart;

        // Line Chart: Visits Trend (Total Visitors vs. Unique Visitors)
        $visitsUsersTrendChart = new Chart;
        $visitsUsersTrendChart->title('Visits Trend: Total vs. Unique Visitors');
        $visitsUsersTrendChart->labels($data['visits_and_users_trend']->pluck('date')->all());
        $visitsUsersTrendChart->options(['responsive' => true, 'maintainAspectRatio' => false]);

        $visitsUsersTrendChart->dataset('Total Visitors', 'line', $data['visits_and_users_trend']->pluck('screenPageViews')->all())
            ->backgroundColor('rgba(52, 152, 219, 0.2)') // Blue
            ->color('#3498db');

        $visitsUsersTrendChart->dataset('Unique Visitors', 'line', $data['visits_and_users_trend']->pluck('totalUsers')->all())
            ->backgroundColor('rgba(231, 76, 60, 0.2)') // Red
            ->color('#e74c3c');
        $charts['visits_users_trend_line'] = $visitsUsersTrendChart;

        return $charts;
    }
}
