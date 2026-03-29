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
    private $cameraIp = '192.168.100.104'; // Update if Pi IP changes
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
            $fishCount = $data['fish_count'] ?? 0;
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

            // Save record in database
            $image = new Image();
            $image->file_path = 'storage/' . $storagePath;
            $image->cage = 'Cage 1'; // can be dynamic later
            $image->count = $fishCount;
            $image->user_id = auth()->id();
            $image->group_id = $groupId;
            $image->capture_date = $todayDate;
            $image->save();

            return response()->json([
                'success' => true,
                'file_name' => $fileName,
                'fish_count' => $fishCount,
                'image_url' => asset('storage/' . $storagePath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error capturing image: ' . $e->getMessage()
            ], 500);
        }
    }
}
