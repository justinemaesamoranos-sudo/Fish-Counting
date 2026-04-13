@extends('layouts.app')
@section('title', 'Live Camera')

@section('content')
<style>
    .lc-page {
        padding: 1.75rem 2rem;
        background: #f0f4f8;
        min-height: 100vh;
    }

    .dashboard-content { display: block !important; padding: 0 !important; }

    /* ── Banner ── */
    .lc-banner {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 60%, #4DB8D5 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 32px rgba(0,77,115,0.2);
        position: relative;
        overflow: hidden;
    }

    .lc-banner::after {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    .lc-banner-text h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.25rem 0;
    }

    .lc-banner-text p {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    /* Live indicator in banner */
    .lc-live-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 20px;
        padding: 0.45rem 1rem;
        color: #fff;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }

    .lc-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 6px rgba(74,222,128,0.8);
        animation: livepulse 1.5s infinite;
    }

    @keyframes livepulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.6; transform: scale(0.85); }
    }

    /* ── Main card ── */
    .lc-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        border: 1px solid rgba(0,136,170,0.08);
        overflow: hidden;
    }

    .lc-card-head {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .lc-card-head-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fff;
    }

    .lc-card-head-left i { font-size: 1.1rem; }

    .lc-card-head-left h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
    }

    /* ── Two-column body ── */
    .lc-body {
        display: grid;
        grid-template-columns: 1fr 260px;
    }

    /* ── Stream column ── */
    .lc-stream-col {
        padding: 1.25rem;
        border-right: 1px solid #e8f4f8;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    #liveFeed {
        width: 100%;
        max-height: calc(100vh - 230px);
        object-fit: contain;
        border-radius: 10px;
        border: 2px solid #e8f4f8;
        background: #000;
        display: block;
        transition: opacity 0.3s;
    }

    /* Stream offline banner */
    #stream-error {
        display: none;
        background: #fff5f5;
        color: #c62828;
        border-left: 4px solid #ef5350;
        border-radius: 8px;
        padding: 0.7rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        align-items: center;
        gap: 0.6rem;
    }

    /* ── Controls column ── */
    .lc-controls-col {
        padding: 1.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        background: #fafcfd;
    }

    /* ── Auto capture status ── */
    .lc-auto-status {
        width: 100%;
        background: linear-gradient(135deg, #e8f4f8, #d0e8f0);
        border-radius: 12px;
        border: 1px solid #b8dce8;
        padding: 1rem;
        text-align: center;
    }

    .lc-auto-status-lbl {
        font-size: 0.7rem;
        font-weight: 700;
        color: #0088AA;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 0.5rem;
    }

    .lc-auto-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: #004D73;
    }

    .lc-auto-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 6px rgba(74,222,128,0.8);
        animation: livepulse 1.5s infinite;
    }

    .lc-auto-dot.capturing {
        background: #f59e0b;
        box-shadow: 0 0 6px rgba(245,158,11,0.8);
    }

    /* ── Loader ── */
    #capture-loader {
        display: none;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #0088AA;
    }

    .lc-spinner {
        width: 15px;
        height: 15px;
        border: 2px solid #0088AA;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Fish count display ── */
    .lc-count-box {
        width: 100%;
        background: linear-gradient(135deg, #e8f4f8, #d0e8f0);
        border-radius: 12px;
        border: 1px solid #b8dce8;
        padding: 1.1rem 1rem;
        text-align: center;
    }

    .lc-count-lbl {
        font-size: 0.7rem;
        font-weight: 700;
        color: #0088AA;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 0.4rem;
    }

    #fish-count-display {
        font-size: 2.2rem;
        font-weight: 800;
        color: #00c853;
        text-shadow: 0 0 10px rgba(0,200,83,0.5);
        line-height: 1;
        transition: opacity 0.3s;
    }

    /* ── Flash messages ── */
    #flash-messages {
        width: 100%;
    }

    #flash-messages .alert {
        border-radius: 8px;
        padding: 0.75rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 500;
        margin: 0;
        transition: opacity 0.4s;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    #flash-messages .alert-success {
        background: #e8f4f8;
        color: #004D73;
        border-left: 3px solid #0088AA;
    }

    #flash-messages .alert-danger {
        background: #fff5f5;
        color: #c62828;
        border-left: 3px solid #ef5350;
    }

    /* ── Divider ── */
    .lc-divider {
        width: 100%;
        height: 1px;
        background: #e8f4f8;
    }

    /* ── Responsive ── */
    @media (max-width: 700px) {
        .lc-page { padding: 1rem; }
        .lc-body { grid-template-columns: 1fr; }
        .lc-stream-col { border-right: none; border-bottom: 1px solid #e8f4f8; }
        .lc-controls-col { padding: 1.25rem; }
    }
</style>

<div class="lc-page">

    {{-- Banner --}}
    <div class="lc-banner">
        <div class="lc-banner-text">
            <h1><i class="fas fa-video" style="margin-right:0.5rem;opacity:0.85;"></i>Live Camera</h1>
            <p>Real-time fish monitoring and capture system</p>
        </div>
        <div class="lc-live-badge">
            <span class="lc-live-dot"></span>
            LIVE
        </div>
    </div>

    {{-- Main card --}}
    <div class="lc-card">
        <div class="lc-card-head">
            <div class="lc-card-head-left">
                <i class="fas fa-camera"></i>
                <h2>Live Stream</h2>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:rgba(255,255,255,0.75);">
                <i class="fas fa-circle" style="color:#4ade80; font-size:0.55rem;"></i>
                Raspberry Pi Camera
            </div>
        </div>

        <div class="lc-body">

            {{-- Stream --}}
            <div class="lc-stream-col">
                <img id="liveFeed" src="{{ $cameraUrl }}" alt="Live Fish Camera Feed">
                <div id="stream-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    Stream offline — retrying connection...
                </div>
            </div>

            {{-- Controls --}}
            <div class="lc-controls-col">

                <div class="lc-auto-status">
                    <div class="lc-auto-status-lbl">Auto Capture</div>
                    <div class="lc-auto-indicator">
                        <span class="lc-auto-dot" id="auto-dot"></span>
                        <span id="auto-status-text">Monitoring...</span>
                    </div>
                </div>

                <span id="capture-loader">
                    <div class="lc-spinner"></div>
                    Capturing...
                </span>

                <div class="lc-divider"></div>

                <div class="lc-count-box">
                    <div class="lc-count-lbl">Detected Fish Count</div>
                    <div id="fish-count-display">0</div>
                </div>

                <div id="flash-messages"></div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loader           = document.getElementById('capture-loader');
    const fishCountDisplay = document.getElementById('fish-count-display');
    const flashDiv         = document.getElementById('flash-messages');
    const liveFeed         = document.getElementById('liveFeed');
    const streamError      = document.getElementById('stream-error');
    const autoDot          = document.getElementById('auto-dot');
    const autoStatusText   = document.getElementById('auto-status-text');

    let streamRetryTimer = null;
    let isCapturing      = false;

    liveFeed.addEventListener('error', function () {
        streamError.style.display = 'flex';
        liveFeed.style.opacity    = '0.25';
        clearTimeout(streamRetryTimer);
        streamRetryTimer = setTimeout(function() {
            liveFeed.src = "{{ $cameraUrl }}?" + Date.now();
        }, 3000);
    });

    liveFeed.addEventListener('load', function () {
        streamError.style.display = 'none';
        liveFeed.style.opacity    = '1';
        clearTimeout(streamRetryTimer);
    });

    function showFlash(type, message) {
        flashDiv.innerHTML = `
            <div class="alert alert-${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>`;
        const alert = flashDiv.querySelector('.alert');
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() { flashDiv.innerHTML = ''; }, 400);
        }, 4000);
    }

    function doCapture() {
        if (isCapturing) return;
        isCapturing = true;

        autoDot.classList.add('capturing');
        autoStatusText.textContent   = 'Capturing...';
        loader.style.display         = 'inline-flex';
        fishCountDisplay.style.opacity = '0.4';

        const controller = new AbortController();
        const timeout    = setTimeout(function() { controller.abort(); }, 15000);

        fetch("{{ route('live-camera.capture') }}", {
            method:  'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept':       'application/json'
            },
            signal: controller.signal
        })
        .then(function(res) {
            clearTimeout(timeout);
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(function(data) {
            if (data.status === 'success') {
                fishCountDisplay.textContent   = data.fish_count ?? 0;
                fishCountDisplay.style.opacity = '1';
                showFlash('success', 'Auto-captured. Fish count: ' + (data.fish_count ?? 0));
            } else {
                fishCountDisplay.style.opacity = '1';
                showFlash('danger', 'Capture failed: ' + (data.message ?? 'Unknown error'));
            }
        })
        .catch(function(err) {
            clearTimeout(timeout);
            fishCountDisplay.style.opacity = '1';
            const msg = err.name === 'AbortError'
                ? 'Capture timed out. Check that the Pi is reachable.'
                : 'Error: ' + err.message;
            showFlash('danger', msg);
        })
        .finally(function() {
            loader.style.display = 'none';
            autoDot.classList.remove('capturing');
            autoStatusText.textContent = 'Monitoring...';
            // cooldown 10 seconds before next auto-capture
            setTimeout(function() { isCapturing = false; }, 10000);
        });
    }

    // Poll the Pi every 3 seconds for fish count, auto-capture when fish detected
    function pollFishCount() {
        fetch("{{ $fishCountUrl }}", { signal: AbortSignal.timeout(5000) })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                const count = data.fish_count ?? 0;
                fishCountDisplay.textContent   = count;
                fishCountDisplay.style.opacity = '1';
                if (count > 0) {
                    doCapture();
                }
            })
            .catch(function() {
                // silently ignore poll errors
            });
    }

    setInterval(pollFishCount, 3000);
});
</script>
@endsection
