@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<style>
    /* ── Page base ── */
    .dash {
        padding: 1.75rem 2rem;
        background: #f0f4f8;
        min-height: 100vh;
    }

    /* Override the layout's grid on the main wrapper */
    .dashboard-content {
        display: block !important;
        padding: 0 !important;
    }

    /* ── Welcome banner ── */
    .dash-banner {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 60%, #4DB8D5 100%);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 32px rgba(0, 77, 115, 0.2);
        position: relative;
        overflow: hidden;
    }

    .dash-banner::after {
        content: '';
        position: absolute;
        right: -60px;
        top: -60px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    .dash-banner::before {
        content: '';
        position: absolute;
        right: 80px;
        bottom: -80px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
        pointer-events: none;
    }

    .banner-text h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.35rem 0;
        letter-spacing: -0.3px;
    }

    .banner-text p {
        font-size: 0.92rem;
        color: rgba(255,255,255,0.82);
        margin: 0;
    }

    .banner-icon {
        font-size: 3.5rem;
        color: rgba(255,255,255,0.18);
        position: relative;
        z-index: 1;
    }

    /* ── Stat cards row ── */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,136,170,0.08);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #004D73, #0088AA);
        border-radius: 14px 14px 0 0;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(0,77,115,0.13);
    }

    .stat-icon-wrap {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        background: linear-gradient(135deg, #004D73, #0088AA);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-info {
        flex: 1;
    }

    .stat-info .val {
        font-size: 1.9rem;
        font-weight: 700;
        color: #004D73;
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .stat-info .lbl {
        font-size: 0.8rem;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        background: #e8f4f8;
        color: #0088AA;
        align-self: flex-start;
    }

    /* ── Main grid ── */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto auto;
        gap: 1.25rem;
    }

    /* ── Card base ── */
    .panel {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        border: 1px solid rgba(0,136,170,0.08);
        transition: box-shadow 0.25s;
    }

    .panel:hover {
        box-shadow: 0 8px 28px rgba(0,77,115,0.12);
    }

    .panel-head {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-head-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fff;
    }

    .panel-head-left i { font-size: 1.1rem; }

    .panel-head-left h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
    }

    .pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.28rem 0.7rem;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.4);
        color: #fff;
        background: rgba(255,255,255,0.15);
        letter-spacing: 0.3px;
    }

    .pill-green {
        background: rgba(16,185,129,0.25);
        border-color: rgba(16,185,129,0.5);
    }

    .panel-body {
        padding: 1.5rem;
    }

    /* ── Detection panel ── */
    .detection-panel { grid-column: 1; }

    .detection-inner {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .fish-circle {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        border: 3px solid #0088AA;
        background: linear-gradient(135deg, rgba(0,136,170,0.08), rgba(0,77,115,0.08));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fish-circle .num {
        font-size: 2.2rem;
        font-weight: 700;
        color: #004D73;
        line-height: 1;
    }

    .fish-circle .sub {
        font-size: 0.65rem;
        color: #0088AA;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-top: 0.2rem;
    }

    .detection-img-wrap {
        flex: 1;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .detection-img-wrap img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .img-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        padding: 0.6rem 0.8rem;
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: rgba(255,255,255,0.9);
    }

    .no-data-state {
        text-align: center;
        padding: 2rem;
        color: #ccc;
    }

    .no-data-state i { font-size: 2.5rem; margin-bottom: 0.5rem; display: block; }
    .no-data-state p { font-size: 0.9rem; margin: 0; }

    /* ── Status panel ── */
    .status-panel { grid-column: 2; }

    .status-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .status-row {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 0.85rem 1rem;
        background: #f7fbfc;
        border-radius: 10px;
        border: 1px solid rgba(0,136,170,0.08);
        transition: background 0.2s;
    }

    .status-row:hover { background: #edf6f9; }

    .s-icon {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        background: linear-gradient(135deg, #004D73, #0088AA);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .s-text {
        flex: 1;
        font-size: 0.88rem;
        color: #333;
    }

    .s-text strong { color: #004D73; }

    .s-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10b981;
        flex-shrink: 0;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* ── Chart panel ── */
    .chart-panel {
        grid-column: 1 / -1;
    }

    .chart-toolbar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .chart-toolbar label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #004D73;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .chart-toolbar select {
        padding: 0.45rem 0.9rem;
        border: 2px solid #e8f4f8;
        border-radius: 8px;
        color: #004D73;
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        background: #fff;
        transition: border-color 0.2s;
    }

    .chart-toolbar select:focus,
    .chart-toolbar select:hover {
        border-color: #0088AA;
        outline: none;
    }

    .chart-area {
        position: relative;
        height: 300px;
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .stat-row { grid-template-columns: 1fr 1fr; }
        .main-grid { grid-template-columns: 1fr; }
        .detection-panel, .status-panel, .chart-panel { grid-column: 1; }
    }

    @media (max-width: 600px) {
        .dash { padding: 1rem; }
        .stat-row { grid-template-columns: 1fr; }
        .detection-inner { flex-direction: column; }
        .dash-banner { flex-direction: column; gap: 1rem; text-align: center; }
    }
</style>

<div class="dash">

    {{-- Banner --}}
    <div class="dash-banner">
        <div class="banner-text">
            <h1>Welcome back, {{ auth()->user()->first_name }}</h1>
            <p>Here's what's happening with your fish counting system today.</p>
        </div>
        <div class="banner-icon">
            <i class="fas fa-fish"></i>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon-wrap"><i class="fas fa-fish"></i></div>
            <div class="stat-info">
                <div class="val">{{ $totalFishToday ?? 0 }}</div>
                <div class="lbl">Fish Counted Today</div>
            </div>
            <span class="stat-badge">Today</span>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap"><i class="fas fa-camera"></i></div>
            <div class="stat-info">
                <div class="val">{{ $totalCaptures ?? 0 }}</div>
                <div class="lbl">Total Captures</div>
            </div>
            <span class="stat-badge">All Time</span>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="val" style="font-size:1.1rem; padding-top:0.3rem;">
                    {{ $latestDetectionTime ? $latestDetectionTime->diffForHumans() : 'N/A' }}
                </div>
                <div class="lbl">Last Detection</div>
            </div>
            <span class="stat-badge">Live</span>
        </div>
    </div>

    {{-- Main grid --}}
    <div class="main-grid">

        {{-- Latest Detection --}}
        <div class="panel detection-panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <i class="fas fa-water"></i>
                    <h2>Latest Detection</h2>
                </div>
                <span class="pill">Live</span>
            </div>
            <div class="panel-body">
                <div class="detection-inner">
                    <div class="fish-circle">
                        <span class="num">{{ $latestCount ?? 0 }}</span>
                        <span class="sub">Fish</span>
                    </div>

                    @if($imageCounts->count() > 0)
                        <div class="detection-img-wrap">
                            <img src="{{ asset($imageCounts->first()->file_path) }}" alt="Latest Detection">
                            <div class="img-overlay">
                                <span><i class="fas fa-hashtag"></i> {{ $imageCounts->first()->id }}</span>
                                <span><i class="fas fa-calendar"></i> {{ $imageCounts->first()->created_at->format('M d, H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="no-data-state">
                            <i class="fas fa-image"></i>
                            <p>No recent detections</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- System Status --}}
        <div class="panel status-panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <i class="fas fa-server"></i>
                    <h2>System Status</h2>
                </div>
                <span class="pill pill-green">Active</span>
            </div>
            <div class="panel-body">
                <div class="status-list">
                    <div class="status-row">
                        <div class="s-icon"><i class="fas fa-microchip"></i></div>
                        <div class="s-text"><strong>AI Model:</strong> YOLOv8</div>
                        <div class="s-dot"></div>
                    </div>
    
                    <div class="status-row">
                        <div class="s-icon"><i class="fas fa-heartbeat"></i></div>
                        <div class="s-text"><strong>Uptime:</strong> {{ $systemUptime ?? 'N/A' }}</div>
                        <div class="s-dot"></div>
                    </div>
                    <div class="status-row">
                        <div class="s-icon"><i class="fas fa-globe"></i></div>
                        <div class="s-text"><strong>Timezone:</strong> UTC+8</div>
                        <div class="s-dot"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Population Chart --}}
        <div class="panel chart-panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <i class="fas fa-chart-line"></i>
                    <h2>Fish Count Analytics</h2>
                </div>
            </div>
            <div class="panel-body">
                <div class="rp-toolbar" style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem; flex-wrap:wrap;">
                    <label style="font-size:0.88rem; font-weight:600; color:#004D73; display:flex; align-items:center; gap:0.4rem;">
                        <i class="fas fa-calendar-alt"></i> View by:
                    </label>
                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                        <button class="dash-pill active" data-period="time">Today (Hourly)</button>
                        <button class="dash-pill" data-period="day">This Month</button>
                        <button class="dash-pill" data-period="month">This Year</button>
                        <button class="dash-pill" data-period="year">All Time</button>
                    </div>
                </div>
                <canvas id="popChart"></canvas>
                <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:#e8f4f8; border-radius:10px; overflow:hidden; margin-top:1.25rem; border:1px solid #e8f4f8;">
                    <div style="background:#f7fbfc; padding:0.9rem 1rem; text-align:center;">
                        <div style="font-size:11px; font-weight:700; color:#0088AA; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.3rem;">Peak Count</div>
                        <div id="dashSumPeak" style="font-size:1.5rem; font-weight:700; color:#004D73; line-height:1;">0</div>
                    </div>
                    <div style="background:#f7fbfc; padding:0.9rem 1rem; text-align:center;">
                        <div style="font-size:11px; font-weight:700; color:#0088AA; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.3rem;">Total Fish</div>
                        <div id="dashSumTotal" style="font-size:1.5rem; font-weight:700; color:#004D73; line-height:1;">0</div>
                    </div>
                    <div style="background:#f7fbfc; padding:0.9rem 1rem; text-align:center;">
                        <div style="font-size:11px; font-weight:700; color:#0088AA; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.3rem;">Average</div>
                        <div id="dashSumAvg" style="font-size:1.5rem; font-weight:700; color:#004D73; line-height:1;">0</div>
                    </div>
                    <div style="background:#f7fbfc; padding:0.9rem 1rem; text-align:center;">
                        <div style="font-size:11px; font-weight:700; color:#0088AA; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.3rem;">Data Points</div>
                        <div id="dashSumPoints" style="font-size:1.5rem; font-weight:700; color:#004D73; line-height:1;">0</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .dash-pill {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid #e8f4f8;
        background: #fff;
        color: #0088AA;
        transition: all 0.2s;
        font-family: 'Roboto', sans-serif;
    }
    .dash-pill:hover { border-color: #0088AA; background: #e8f4f8; }
    .dash-pill.active {
        background: linear-gradient(135deg, #004D73, #0088AA);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 3px 10px rgba(0,136,170,0.3);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var dashChartInstance = null;

    function updateDashChart(period) {
        document.querySelectorAll('.dash-pill').forEach(function(p) {
            p.classList.toggle('active', p.dataset.period === period);
        });

        fetch("{{ route('chart-data') }}?period=" + period)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var counts  = (data.counts || []).map(Number);
                var labels  = data.labels || [];
                var nonZero = counts.filter(function(v) { return v > 0; });
                var peak    = counts.length ? Math.max.apply(null, counts) : 0;
                var total   = counts.reduce(function(a, b) { return a + b; }, 0);
                var avg     = nonZero.length ? (total / nonZero.length).toFixed(1) : 0;

                document.getElementById('dashSumPeak').textContent   = peak;
                document.getElementById('dashSumTotal').textContent  = total;
                document.getElementById('dashSumAvg').textContent    = avg;
                document.getElementById('dashSumPoints').textContent = nonZero.length;

                var canvas = document.getElementById('popChart');
                var ctx    = canvas.getContext('2d');
                if (dashChartInstance) dashChartInstance.destroy();

                var gradient = ctx.createLinearGradient(0, 0, 0, 320);
                gradient.addColorStop(0, 'rgba(0,136,170,0.18)');
                gradient.addColorStop(1, 'rgba(0,136,170,0)');

                dashChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Fish Count',
                            data: counts,
                            borderColor: '#0088AA',
                            backgroundColor: gradient,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: '#004D73',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    font: { size: 13 },
                                    color: '#004D73',
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                }
                            },
                            tooltip: {
                                backgroundColor: '#004D73',
                                titleColor: '#fff',
                                bodyColor: 'rgba(255,255,255,0.85)',
                                padding: 10,
                                cornerRadius: 8,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Fish Count', color: '#004D73', font: { size: 12, weight: '600' } },
                                grid: { color: 'rgba(0,0,0,0.04)' },
                                ticks: { color: '#888', font: { size: 11 } }
                            },
                            x: {
                                title: { display: true, text: 'Time Period', color: '#004D73', font: { size: 12, weight: '600' } },
                                grid: { display: false },
                                ticks: { color: '#888', font: { size: 11 } }
                            }
                        }
                    }
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateDashChart('time');
        document.querySelectorAll('.dash-pill').forEach(function(pill) {
            pill.addEventListener('click', function() { updateDashChart(this.dataset.period); });
        });
    });
</script>

@endsection
