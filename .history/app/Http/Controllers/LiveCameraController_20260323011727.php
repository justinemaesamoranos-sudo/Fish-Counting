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
    private $cameraIp = '192.168.254.104'; // Update if Pi IP changes
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
            $fishCount = isset($data['fish_count']) ? intval($data['fish_count']) : 0;
            $imageUrl = $data['image_url'] ?? "http://{$this->cameraIp}:{$this->cameraPort}/captured_images/{$fileName}";

            \Log::debug('LiveCamera capture result', ['file' => $fileName, 'fish_count' => $fishCount, 'response' => $data]);

            

            // Fetch the image from Flask
            $imageResponse = Http::timeout(30)->get($imageUrl);

            if (!$imageResponse->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch image from Flask server.'
                ], 500);
            }

            $imageData = $imageResponse->body();

            // Create image resource from the captured image data
            $image = imagecreatefromstring($imageData);
            
            if ($image !== false) {
                // Get image dimensions
                $width = imagesx($image);
                $height = imagesy($image);
                
                // Allocate colors for the text
                $textColor = imagecolorallocate($image, 255, 255, 255); // White text
                $bgColor = imagecolorallocate($image, 0, 136, 170); // Background color (teal #0088AA)
                
                // Set up the text to display
                $fishCountText = "Fish Count: " . $fishCount;
                
                // Determine font size based on image width
                $fontSize = max(1, min(5, $width / 150)); // Scale font size
                
                // Calculate text position (bottom right corner with padding)
                $textWidth = imagefontwidth($fontSize) * strlen($fishCountText);
                $textHeight = imagefontheight($fontSize);
                $padding = 20;
                $x = $width - $textWidth - $padding;
                $y = $height - $textHeight - $padding;
                
                // Draw background rectangle for better readability
                $bgPadding = 10;
                imagefilledrectangle($image, $x - $bgPadding, $y - $bgPadding, $x + $textWidth + $bgPadding, $y + $textHeight + $bgPadding, $bgColor);
                
                // Add text shadow for better visibility
                $shadowColor = imagecolorallocate($image, 0, 0, 0);
                imagestring($image, $fontSize, $x + 1, $y + 1, $fishCountText, $shadowColor);
                
                // Draw the text
                imagestring($image, $fontSize, $x, $y, $fishCountText, $textColor);
                
                // Save the modified image back to a string
                ob_start();
                imagejpeg($image, null, 90); // 90% quality
                $imageData = ob_get_contents();
                ob_end_clean();
                
                // Free memory
                imagedestroy($image);
            }

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

            // Keep the latest detection count in session so the page stays consistent on refresh.
            session([
                'last_image' => $fileName,
                'fish_count' => $fishCount,
            ]);

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
