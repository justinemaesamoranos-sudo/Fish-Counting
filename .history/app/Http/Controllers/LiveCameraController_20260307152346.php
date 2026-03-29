<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class LiveCameraController extends Controller
{
    /**
     * Raspberry Pi Flask camera IP and port
     */
    private $cameraIp = '192.168.100.24'; // Update if Pi IP changes
    private $cameraPort = 5000;

    /**
     * Show the live camera view.
     */
    public function index()
    {
        $cameraUrl = "http://{$this->cameraIp}:{$this->cameraPort}/video_feed";
        return view('LiveCamera.index', compact('cameraUrl'));
    }

    /**
     * Capture a single frame from the Raspberry Pi Flask camera.
     */
    public function capture(Request $request)
    {
        $flaskCaptureUrl = "http://{$this->cameraIp}:{$this->cameraPort}/capture";

        try {
            // Send POST request to Flask
            $response = Http::timeout(30)->post($flaskCaptureUrl);

            if (!$response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Flask capture request failed. Check if Flask server is running.'
                ], 500);
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== 'success') {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Unknown error from Flask capture.'
                ], 500);
            }

            $fileName = $data['image'] ?? 'unknown.jpg';
            $detectedFishCount = $data['fish_count'] ?? 0;
            $imageUrl = $data['image_url'] ?? "http://{$this->cameraIp}:{$this->cameraPort}/captured_images/{$fileName}";

            // Fetch the image from Flask
            $imageResponse = Http::timeout(30)->get($imageUrl);

            if (!$imageResponse->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch image from Flask server.'
                ], 500);
            }

            $imageData = $imageResponse->body();

            // Save image in Laravel storage
            $storagePath = 'captures/' . $fileName;
            Storage::disk('public')->put($storagePath, $imageData);

            // Get or create group ID for today
            $todayDate = now()->toDateString(); // YYYY-MM-DD
            $lastGroup = Image::whereDate('capture_date', $todayDate)
                ->orderBy('group_id', 'desc')
                ->first();
            
            $groupId = $lastGroup ? $lastGroup->group_id : Image::max('group_id') + 1;
            if (!$groupId || $groupId <= 0) {
                $groupId = 1;
            }

            // Use corrected count if provided, otherwise use detected count
            $correctedFishCount = $request->input('corrected_count', $detectedFishCount);

            // Save record in database
            $image = new Image();
            $image->file_path = 'storage/' . $storagePath;
            $image->cage = 'Cage 1'; // can be dynamic later
            $image->count = $correctedFishCount;
            $image->detected_count = $detectedFishCount;
            $image->user_id = auth()->id();
            $image->group_id = $groupId;
            $image->capture_date = $todayDate;
            $image->save();

            return response()->json([
                'success' => true,
                'file_name' => $fileName,
                'detected_fish_count' => $detectedFishCount,
                'corrected_fish_count' => $correctedFishCount,
                'image_url' => asset('storage/' . $storagePath),
                'image_id' => $image->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error capturing image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the corrected fish count for a captured image.
     */
    public function updateCount(Request $request, $imageId)
    {
        $request->validate([
            'corrected_count' => 'required|integer|min:0'
        ]);

        try {
            $image = Image::findOrFail($imageId);
            $image->count = $request->input('corrected_count');
            $image->save();

            return response()->json([
                'success' => true,
                'message' => 'Fish count updated successfully.',
                'corrected_count' => $image->count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating count: ' . $e->getMessage()
            ], 500);
        }
    }
}
