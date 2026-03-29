<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FishCount;
use Illuminate\Support\Facades\DB;

class FishCountController extends Controller
{
    /**
     * Store a new fish count
     */
    public function store(Request $request)
    {
        $request->validate([
            'fish_count' => 'required|integer'
        ]);

        FishCount::create([
            'count' => $request->fish_count,
            'recorded_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Fish count stored successfully'
        ]);
    }

    /**
     * Reset all fish count data and auto-increment ID
     */
    public function reset()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fish_counts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with(
            'success',
            'All fish count records have been deleted and IDs reset.'
        );
    }
}
