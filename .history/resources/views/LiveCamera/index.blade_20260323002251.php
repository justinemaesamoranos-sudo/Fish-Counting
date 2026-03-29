@extends('layouts.app')

@section('title', 'Live Camera')

@section('content')
<style>
    .live-camera-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #e8f1f5 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .camera-header {
        text-align: center;
        margin-bottom: 3rem;
        color: #004D73;
    }

    .camera-header h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .camera-header p {
        font-size: 1.1rem;
        color: #0088AA;
        font-weight: 500;
    }

    .camera-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    @media (max-width: 1024px) {
        .camera-grid {
            grid-template-columns: 1fr;
        }
    }

    .camera-feed-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 88, 170, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .camera-feed-header {
        background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
        padding: 1.5rem;
        color: white;
        border-bottom: 4px solid #004D73;
    }

    .camera-feed-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .camera-feed-body {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    #liveFeed {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 3px solid #E8F4F8;
    }

    .capture-controls {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    #capture-btn {
        background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
        border: none;
        color: white;
        padding: 0.75rem 2.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 136, 170, 0.3);
    }

    #capture-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 136, 170, 0.4);
    }

    #capture-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    #capture-loader {
        color: #0088AA;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .capture-loader-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #0088AA;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .capture-section {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #0088AA;
    }

    .last-capture-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 88, 170, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .last-capture-header {
        background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
        padding: 1.5rem;
        color: white;
        border-bottom: 4px solid #004D73;
    }

    .last-capture-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .last-capture-body {
        padding: 2rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .last-capture-body img {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 3px solid #E8F4F8;
        max-height: 400px;
        object-fit: contain;
    }

    .fish-count-display {
        background: linear-gradient(135deg, #E8F4F8 0%, #D0E8F0 100%);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #0088AA;
    }

    .fish-count-display p {
        margin: 0;
        font-size: 0.9rem;
        color: #0088AA;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #fish-count-display {
        font-size: 3.2rem;
        color: #1aff1a;
        font-weight: 800;
        text-shadow: 0 0 8px rgba(26, 255, 26, 0.7);
        display: block;
        margin-top: 0.5rem;
    }

    .fish-count-display p {
        font-weight: 700;
        letter-spacing: 0.08em;
        color: #004D73;
    }

    .fish-count-display {
        border-radius: 0.65rem;
        box-shadow: inset 0 0 0 1px rgba(0,77,115,0.15);
        background: linear-gradient(135deg, rgba(232,248,252,0.8), rgba(216,241,248,0.6));
        padding: 0.8rem 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #0088AA;
    }

    .empty-state i {
        font-size: 3rem;
        opacity: 0.5;
        margin-bottom: 1rem;
    }

    #flash-messages {
        margin-bottom: 2rem;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 1rem;
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 500;
    }

    .alert-success {
        background-color: #E8F4F8;
        color: #004D73;
        border-left: 4px solid #0088AA;
    }

    .alert-danger {
        background-color: #FFE8E8;
        color: #8B0000;
        border-left: 4px solid #FF6B6B;
    }
</style>

<div class="live-camera-container">
    <div class="camera-header">
        <h2><i class="fas fa-video" style="color: #0088AA; margin-right: 0.5rem;"></i>Live Fish Camera Feed</h2>
        <p>Real-time monitoring and capture system</p>
    </div>

    {{-- Flash Messages --}}
    <div id="flash-messages"></div>

    <div class="camera-grid">
        {{-- Live MJPEG Feed --}}
        <div class="camera-feed-card">
            <div class="camera-feed-header">
                <h3><i class="fas fa-camera"></i>Live Stream</h3>
            </div>
            <div class="camera-feed-body">
                <img
                    id="liveFeed"
                    src="{{ $cameraUrl }}"
                    alt="Live Fish Camera Feed"
                >
                <div class="capture-section">
                    <button id="capture-btn" class="btn" style="width: 100%;">
                        <i class="fas fa-camera-retro" style="margin-right: 0.5rem;"></i>Capture Image
                    </button>
                    <span id="capture-loader" style="display:none; justify-content: center; margin-top: 1rem;">
                        <div class="capture-loader-spinner"></div>
                        Capturing...
                    </span>
                </div>
            </div>
        </div>

        {{-- Last Captured Image --}}
        <div class="last-capture-card">
            <div class="last-capture-header">
                <h3><i class="fas fa-images"></i>Last Capture</h3>
            </div>
            <div class="last-capture-body" id="last-capture">
                @if (session('last_image'))
                    <img src="{{ asset('storage/captures/' . session('last_image')) }}" data-fish-count="{{ session('fish_count') }}">
                    <div class="fish-count-display">
                        <p>Detected Fish Count</p>
                        <div id="fish-count-display">{{ session('fish_count', 0) }}</div>  {{-- This line --}}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No captures yet</p>
                        <small>Click "Capture Image" to get started</small>
    </div>
                @endif 
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const captureBtn = document.getElementById('capture-btn');
    const loader = document.getElementById('capture-loader');
    const lastCaptureDiv = document.getElementById('last-capture');
    const flashMessagesDiv = document.getElementById('flash-messages');

    if (!captureBtn) {
        console.error("Capture button not found!");
        return;
    }

    captureBtn.addEventListener('click', function () {
        // Disable button & show loader
        captureBtn.disabled = true;
        loader.style.display = 'flex';

        // Clear previous flash messages
        flashMessagesDiv.innerHTML = '';

        fetch("{{ route('live-camera.capture') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            console.log('capture response', data);
            loader.style.display = 'none';
            captureBtn.disabled = false;

            if (data.success) {
                // Build the HTML for display
                let html = `
                    <img style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border: 3px solid #E8F4F8; max-height: 400px; object-fit: contain;" src="${data.image_url}" data-fish-count="${data.fish_count}">
                    <div class="fish-count-display">
                        <p>Detected Fish Count</p>
                        <div id="fish-count-display">${data.fish_count}</div>
                    </div>
                `;

                lastCaptureDiv.innerHTML = html;

                // Update the global count sentinel if it's present through session-based page load fallback
                const globalFishCount = document.getElementById('fish-count-display');
                if (globalFishCount) {
                    globalFishCount.textContent = data.fish_count;
                }

                // Show success flash
                flashMessagesDiv.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>Image captured successfully!</div>`;
            } else {
                flashMessagesDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>Capture failed: ${data.message}</div>`;
            }
        })
        .catch(err => {
            loader.style.display = 'none';
            captureBtn.disabled = false;
            flashMessagesDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>Error: ${err.message}</div>`;
            console.error("Capture error:", err);
        });
    });
});
</script>
@endsection
