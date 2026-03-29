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
        grid-template-columns: 1fr;
        gap: 2rem;
        align-items: start;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
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
        background: #000;
        min-height: 200px;
    }

    /* Stream offline banner */
    #stream-error {
        display: none;
        background: #FFE8E8;
        color: #8B0000;
        border-left: 4px solid #FF6B6B;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        align-items: center;
        gap: 0.5rem;
    }

    .capture-section {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #0088AA;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Flash messages — now inside the capture section */
    #flash-messages .alert {
        border-radius: 8px;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 500;
        margin: 0;
        transition: opacity 0.4s ease;
    }

    #flash-messages .alert-success {
        background-color: #E8F4F8;
        color: #004D73;
        border-left: 4px solid #0088AA;
    }

    #flash-messages .alert-danger {
        background-color: #FFE8E8;
        color: #8B0000;
        border-left: 4px solid #FF6B6B;
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
        width: 100%;
    }

    #capture-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 136, 170, 0.4);
    }

    #capture-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    #capture-loader {
        color: #0088AA;
        font-weight: 600;
        display: none;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
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

    .fish-count-display {
        background: linear-gradient(135deg, #E8F4F8 0%, #D0E8F0 100%);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #0088AA;
        text-align: center;
    }

    .fish-count-display p {
        margin: 0 0 0.5rem 0;
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
        transition: opacity 0.3s ease;
    }
</style>

<div class="live-camera-container">
    <div class="camera-header">
        <h2>
            <i class="fas fa-video" style="color: #0088AA; margin-right: 0.5rem;"></i>
            Live Fish Camera Feed
        </h2>
        <p>Real-time monitoring and capture system</p>
    </div>

    <div class="camera-grid">
        <div class="camera-feed-card">
            <div class="camera-feed-header">
                <h3><i class="fas fa-camera"></i> Live Stream</h3>
            </div>
            <div class="camera-feed-body">

                {{-- MJPEG stream with error/retry handling --}}
                <img
                    id="liveFeed"
                    src="{{ $cameraUrl }}"
                    alt="Live Fish Camera Feed"
                >
                <div id="stream-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    Stream offline — retrying connection...
                </div>

                <div class="capture-section">
                    <button id="capture-btn">
                        <i class="fas fa-camera-retro" style="margin-right: 0.5rem;"></i>
                        Capture Image
                    </button>

                    <span id="capture-loader">
                        <div class="capture-loader-spinner"></div>
                        Capturing...
                    </span>

                    {{-- Flash messages inside capture section so they're always visible --}}
                    <div id="flash-messages"></div>

                    <div class="fish-count-display">
                        <p>Detected Fish Count</p>
                        <div id="fish-count-display">0</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const captureBtn       = document.getElementById('capture-btn');
    const loader           = document.getElementById('capture-loader');
    const fishCountDisplay = document.getElementById('fish-count-display');
    const flashDiv         = document.getElementById('flash-messages');
    const liveFeed         = document.getElementById('liveFeed');
    const streamError      = document.getElementById('stream-error');

    // ── Live feed reconnect on error ─────────────────────────────────────
    let streamRetryTimer = null;

    liveFeed.addEventListener('error', function () {
        streamError.style.display = 'flex';
        liveFeed.style.opacity    = '0.3';
        clearTimeout(streamRetryTimer);
        streamRetryTimer = setTimeout(() => {
            // Force reload by appending a cache-busting timestamp
            liveFeed.src = "{{ $cameraUrl }}?" + Date.now();
        }, 3000);
    });

    liveFeed.addEventListener('load', function () {
        streamError.style.display = 'none';
        liveFeed.style.opacity    = '1';
        clearTimeout(streamRetryTimer);
    });

    // ── Flash message helper ──────────────────────────────────────────────
    function showFlash(type, message) {
        flashDiv.innerHTML = `
            <div class="alert alert-${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"
                   style="margin-right: 0.5rem;"></i>
                ${message}
            </div>`;

        // Auto-dismiss after 4 seconds
        const alert = flashDiv.querySelector('.alert');
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => flashDiv.innerHTML = '', 400);
        }, 4000);
    }

    // ── Capture button ────────────────────────────────────────────────────
    captureBtn.addEventListener('click', function () {
        captureBtn.disabled           = true;
        loader.style.display          = 'inline-flex';
        flashDiv.innerHTML            = '';

        // Show dash while waiting so old count isn't misleading
        fishCountDisplay.textContent  = '—';
        fishCountDisplay.style.opacity = '0.5';

        // Abort if Flask takes longer than 15 seconds
        const controller = new AbortController();
        const timeout    = setTimeout(() => controller.abort(), 15000);

        fetch("{{ route('live-camera.capture') }}", {
            method:  'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept':       'application/json'
            },
            signal: controller.signal
        })
        .then(res => {
            clearTimeout(timeout);
            if (!res.ok) throw new Error(`Server error: ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('Capture response:', data);

            // Flask returns { status: "success", fish_count: N }
            // Fix: was checking data.success (always undefined)
            if (data.status === 'success') {
                fishCountDisplay.textContent   = data.fish_count ?? 0;
                fishCountDisplay.style.opacity = '1';
                showFlash('success', 'Image captured successfully! Fish count updated.');
            } else {
                fishCountDisplay.textContent   = '0';
                fishCountDisplay.style.opacity = '1';
                showFlash('danger', 'Capture failed: ' + (data.message ?? 'Unknown error'));
            }
        })
        .catch(err => {
            clearTimeout(timeout);
            fishCountDisplay.textContent   = '0';
            fishCountDisplay.style.opacity = '1';

            const msg = err.name === 'AbortError'
                ? 'Capture timed out. Check that the Pi is reachable on the network.'
                : 'Error capturing image: ' + err.message;

            showFlash('danger', msg);
            console.error('Capture error:', err);
        })
        .finally(() => {
            loader.style.display  = 'none';
            captureBtn.disabled   = false;
        });
    });
});
</script>
@endsection