<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Comparison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with fish‑count summaries and comparisons.
     */
    public function index()
    {
        /* --- 1. Recent image counts (table 2.2.2) - all images with file_path --- */
        $imageCounts = Image::latest()
            ->get(['id', 'file_path', 'count', 'cage', 'created_at']);

        /* --- 2. Latest detection (card 2.1) --- */
        $latestCount = $imageCounts->first()->count ?? 0;
        $latestDetectionTime = $imageCounts->first()->created_at ?? null;

        /* --- KPI Calculations --- */
        // Total Fish Today
        $totalFishToday = Image::whereDate('created_at', today())
            ->sum('count');

        // Total Captures
        $totalCaptures = Image::count();

        // System Uptime (using latest capture time)
        $systemUptime = $latestDetectionTime ? $latestDetectionTime->diffForHumans() : 'N/A';

        /* --- 3. Population summary by cage (chart 2.2.1) --- */
        $summaryDataCollection = Image::selectRaw('cage, SUM(count) as total')
            ->groupBy('cage')
            ->get();

        $summaryLabels = $summaryDataCollection->pluck('cage')->toArray();
        $summaryData   = $summaryDataCollection->pluck('total')->toArray();

        /* --- 4. Manual vs AI comparisons (table 3) --- */
        $comparisons = Comparison::latest()
            ->take(10)
            ->get(['image_id', 'manual', 'ai']);

        /* --- 5. Send everything to the view --- */
        return view('Dashboard.dashboard', compact(
            'latestCount',
            'latestDetectionTime',
            'totalFishToday',
            'totalCaptures',
            'systemUptime',
            'imageCounts',
            'summaryLabels',
            'summaryData',
            'comparisons'
        ));
    }
    public function fishCounts()
    {
        $imageCounts = Image::latest()->get(['id', 'file_path', 'count', 'cage', 'created_at', 'group_id', 'capture_date']);
        
        // Group images by date and group_id
        $groups = [];
        foreach ($imageCounts as $img) {
            $date = $img->capture_date ?? $img->created_at->toDateString();
            $groupId = $img->group_id ?? 1;
            $key = $date . '_' . $groupId;
            
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'date' => $date,
                    'group_id' => $groupId,
                    'images' => [],
                    'total_count' => 0
                ];
            }
            
            $groups[$key]['images'][] = $img;
            $groups[$key]['total_count'] += $img->count;
        }
        
        // Sort groups by date (newest first)
        krsort($groups);
        
        return view('fish-counts', compact('groups'));
    }

public function reports()
{
    // Get today's fish count data by hour
    $todayData = Image::whereDate('created_at', today())
        ->selectRaw('HOUR(created_at) as hour, SUM(count) as total_count, COUNT(*) as image_count')
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

    $hourLabels = [];
    $hourCounts = [];
    
    for ($h = 0; $h < 24; $h++) {
        $hourLabels[] = sprintf('%02d:00', $h);
        $hourData = $todayData->firstWhere('hour', $h);
        $hourCounts[] = $hourData ? $hourData->total_count : 0;
    }

    return view('reports', compact('hourLabels', 'hourCounts'));
}

public function chartData(Request $request)
{
    $period = $request->get('period', 'day'); // day, month, year, time

    if ($period === 'time') {
        // By hour today
        $data = Image::whereDate('created_at', today())
            ->selectRaw('HOUR(created_at) as label, SUM(count) as count')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $labels = [];
        $counts = [];
        
        for ($h = 0; $h < 24; $h++) {
            $labels[] = sprintf('%02d:00', $h);
            $item = $data->firstWhere('label', $h);
            $counts[] = $item ? $item->count : 0;
        }
    } elseif ($period === 'day') {
        // By day for current month
        $data = Image::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('DAY(created_at) as label, SUM(count) as count')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $daysInMonth = now()->daysInMonth;
        $labels = [];
        $counts = [];
        
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $labels[] = $d;
            $item = $data->firstWhere('label', $d);
            $counts[] = $item ? $item->count : 0;
        }
    } elseif ($period === 'month') {
        // By month for current year
        $data = Image::whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as label, SUM(count) as count')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $labels = [];
        $counts = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = $monthNames[$m - 1];
            $item = $data->firstWhere('label', $m);
            $counts[] = $item ? $item->count : 0;
        }
    } elseif ($period === 'year') {
        // By year (all available data)
        $data = Image::selectRaw('YEAR(created_at) as label, SUM(count) as count')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $labels = $data->pluck('label')->map(fn($year) => (string)$year)->toArray();
        $counts = $data->pluck('count')->toArray();
    }

    return response()->json([
        'labels' => $labels ?? [],
        'counts' => $counts ?? []
    ]);
}

public function comparativeAnalysis()
{
    $comparisons = Comparison::with('image')
        ->latest()->get(['image_id', 'manual', 'ai']);
    return view('comparative-analysis', compact('comparisons'));
}

public function deleteAllData()
{
    try {
        // Truncate tables to delete all data and reset auto-increment
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('comparisons')->truncate();
        DB::table('images')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return redirect()->back()->with('success', 'All data has been deleted successfully. ID numbers will restart from 1.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting data: ' . $e->getMessage());
    }
}

public function deleteImage($id)
{
    try {
        $image = Image::findOrFail($id);
        
        // Delete the image file if it exists
        if ($image->file_path && \Storage::exists($image->file_path)) {
            \Storage::delete($image->file_path);
        }
        
        // Delete related comparisons
        Comparison::where('image_id', $id)->delete();
        
        // Delete the image record
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting image: ' . $e->getMessage());
    }
}

}

