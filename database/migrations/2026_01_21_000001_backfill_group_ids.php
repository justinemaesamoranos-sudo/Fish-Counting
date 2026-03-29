<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all images and group them by date
        $images = DB::table('images')->orderBy('created_at')->get();
        
        $groupMap = [];
        $currentGroupId = 1;
        $previousDate = null;
        
        foreach ($images as $image) {
            $captureDate = substr($image->created_at, 0, 10); // Extract YYYY-MM-DD from timestamp
            
            // Check if date changed
            if ($previousDate && $previousDate !== $captureDate) {
                $currentGroupId++;
            }
            
            $previousDate = $captureDate;
            $groupMap[$image->id] = ['group_id' => $currentGroupId, 'capture_date' => $captureDate];
        }
        
        // Update each image with group_id and capture_date
        foreach ($groupMap as $imageId => $data) {
            DB::table('images')
                ->where('id', $imageId)
                ->update([
                    'group_id' => $data['group_id'],
                    'capture_date' => $data['capture_date']
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset group_id and capture_date to null
        DB::table('images')->update([
            'group_id' => null,
            'capture_date' => null
        ]);
    }
};
