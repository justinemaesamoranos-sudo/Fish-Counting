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
    private $cameraIp;
    private $cameraPort;

    public function __construct()
    {
        $this->cameraIp   = env('FLASK_CAMERA_IP', '192.168.254.104');
        $this->cameraPort = env('FLASK_CAMERA_PORT', 5000);
    }

    /**
     * Show the live camera view.
     */
    public function index()
    {
        // Use Laravel's own proxy route for the stream
        // so ngrok bypass header is sent automatically
        $cameraUrl = route('live-camera.stream');
        return view('LiveCamera.index', compact('cameraUrl'));
    }

    public function stream()
    {
        $port = $this->cameraPort;
        $ip   = $this->cameraIp;

        if ($port == 443 || $port == 80) {
            $streamUrl = "https://{$ip}/video_feed";
        } else {
            $streamUrl = "http://{$ip}:{$port}/video_feed";
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $streamUrl, [
            'stream'  => true,
            'headers' => ['ngrok-skip-browser-warning' => 'true'],
            'timeout' => 0,
        ]);

        $body = $response->getBody();

        return response()->stream(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(1024);
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type'      => 'multipart/x-mixed-replace; boundary=frame',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function capture(Request $request)
    {
        $port = $this->cameraPort;
        $ip   = $this->cameraIp;

        if ($port == 443 || $port == 80) {
            $flaskBaseUrl    = "https://{$ip}";
        } else {
            $flaskBaseUrl    = "http://{$ip}:{$port}";
        }

        $flaskCaptureUrl = "{$flaskBaseUrl}/capture";

        try {
            // Send POST request to Flask
            $response = Http::timeout(30)
                ->withHeaders(['ngrok-skip-browser-warning' => 'true'])
                ->post($flaskCaptureUrl);

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

            $fileName  = $data['image'] ?? 'unknown.jpg';
            $fishCount = $data['fish_count'] ?? 0;
            $imageUrl  = $data['image_url'] ?? "{$flaskBaseUrl}/captured_images/{$fileName}";

            // Get or create group ID for today
            $todayDate = now()->toDateString();
            $lastGroup = Image::whereDate('capture_date', $todayDate)
                ->orderBy('group_id', 'desc')
                ->first();

            $groupId = $lastGroup ? $lastGroup->group_id : Image::max('group_id') + 1;
            if (!$groupId || $groupId <= 0) {
                $groupId = 1;
            }

            // Save the Flask image URL directly — no local storage needed
            $image = new Image();
            $image->file_path = $imageUrl;
            $image->cage = 'Cage 1';
            $image->count = $fishCount;
            $image->user_id = auth()->id();
            $image->group_id = $groupId;
            $image->capture_date = $todayDate;
            $image->save();

            return response()->json([
                'status' => 'success',
                'file_name' => $fileName,
                'fish_count' => $fishCount,
                'image_url' => $imageUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error capturing image: ' . $e->getMessage()
            ], 500);
        }
    }
}
