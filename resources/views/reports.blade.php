@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<style>
    .rp-page {
        padding: 1.75rem 2rem;
        background: #f0f4f8;
        min-height: 100vh;
    }

    .dashboard-content { display: block !important; padding: 0 !important; }

    /* ── Banner ── */
    .rp-banner {
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

    .rp-banner::after {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    .rp-banner-text h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.25rem 0;
    }

    .rp-banner-text p {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.8);
        margin: 0;
    }

    .rp-banner-icon {
        font-size: 3.2rem;
        color: rgba(255,255,255,0.15);
        position: relative;
        z-index: 1;
    }

    /* ── Chart card ── */
    .rp-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        border: 1px solid rgba(0,136,170,0.08);
        overflow: hidden;
        transition: box-shadow 0.25s;
    }

    .rp-card:hover {
        box-shadow: 0 8px 28px rgba(0,77,115,0.12);
    }

    .rp-card-head {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .rp-card-head-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fff;
    }

    .rp-card-head-left i { font-size: 1.1rem; }

    .rp-card-head-left h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
    }

    .rp-card-body {
        padding: 1.5rem 1.75rem;
    }

    /* ── Toolbar ── */
    .rp-toolbar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .rp-toolbar label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #004D73;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .rp-toolbar select {
        padding: 0.5rem 1rem;
        border: 2px solid #e8f4f8;
        border-radius: 8px;
        color: #004D73;
        font-size: 0.88rem;
        font-weight: 500;
        cursor: pointer;
        background: #fff;
        transition: border-color 0.2s;
        outline: none;
    }

    .rp-toolbar select:hover,
    .rp-toolbar select:focus {
        border-color: #0088AA;
    }

    /* ── Period pills ── */
    .rp-period-pills {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .rp-pill {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid #e8f4f8;
        background: #fff;
        color: #0088AA;
        transition: all 0.2s;
    }

    .rp-pill:hover {
        border-color: #0088AA;
        background: #e8f4f8;
    }

    .rp-pill.active {
        background: linear-gradient(135deg, #004D73, #0088AA);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 3px 10px rgba(0,136,170,0.3);
    }

    /* ── Chart area ── */
    .rp-chart-wrap {
        position: relative;
        height: 380px;
        width: 100%;
    }

    /* ── Summary stats ── */
    .rp-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: #e8f4f8;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 1.5rem;
        border: 1px solid #e8f4f8;
    }

    .rp-sum-item {
        background: #f7fbfc;
        padding: 1rem 1.25rem;
        text-align: center;
        transition: background 0.2s;
    }

    .rp-sum-item:hover { background: #edf6f9; }

    .rp-sum-lbl {
        font-size: 0.72rem;
        font-weight: 700;
        color: #0088AA;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.35rem;
    }

    .rp-sum-val {
        font-size: 1.5rem;
        font-weight: 700;
        color: #004D73;
        line-height: 1;
    }

    @media (max-width: 700px) {
        .rp-page { padding: 1rem; }
        .rp-summary { grid-template-columns: repeat(2, 1fr); }
        .rp-chart-wrap { height: 260px; }
    }
</style>

<div class="rp-page">

    {{-- Banner --}}
    <div class="rp-banner">
        <div class="rp-banner-text">
            <h1><i class="fas fa-chart-line" style="margin-right:0.5rem;opacity:0.85;"></i>Reports</h1>
            <p>Fish population analytics and historical trends</p>
        </div>
        <div class="rp-banner-icon">
            <i class="fas fa-chart-area"></i>
        </div>
    </div>

    {{-- Chart card --}}
    <div class="rp-card">
        <div class="rp-card-head">
            <div class="rp-card-head-left">
                <i class="fas fa-chart-line"></i>
                <h2>Fish Count Analytics</h2>
            </div>
        </div>
        <div class="rp-card-body">

            <div class="rp-toolbar">
                <label><i class="fas fa-calendar-alt"></i> View by:</label>
                <div class="rp-period-pills">
                    <button class="rp-pill active" data-period="time">Today (Hourly)</button>
                    <button class="rp-pill" data-period="day">This Month</button>
                    <button class="rp-pill" data-period="month">This Year</button>
                    <button class="rp-pill" data-period="year">All Time</button>
                </div>
            </div>

            <div class="rp-chart-wrap">
                <canvas id="todayChart"></canvas>
            </div>

            <div class="rp-summary">
                <div class="rp-sum-item">
                    <div class="rp-sum-lbl">Peak Count</div>
                    <div class="rp-sum-val" id="sumPeak">—</div>
                </div>
                <div class="rp-sum-item">
                    <div class="rp-sum-lbl">Total Fish</div>
                    <div class="rp-sum-val" id="sumTotal">—</div>
                </div>
                <div class="rp-sum-item">
                    <div class="rp-sum-lbl">Average</div>
                    <div class="rp-sum-val" id="sumAvg">—</div>
                </div>
                <div class="rp-sum-item">
                    <div class="rp-sum-lbl">Data Points</div>
                    <div class="rp-sum-val" id="sumPoints">—</div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var chartInstance = null;
    var currentPeriod = 'time';

    function updateChart(period) {
        currentPeriod = period;

        // Update pill active state
        document.querySelectorAll('.rp-pill').forEach(function(p) {
            p.classList.toggle('active', p.dataset.period === period);
        });

        fetch("{{ route('chart-data') }}?period=" + period)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var counts = (data.counts || []).map(Number);
                var labels = data.labels || [];

                // Update summary stats
                var nonZero = counts.filter(function(v) { return v > 0; });
                var peak    = counts.length ? Math.max.apply(null, counts) : 0;
                var total   = counts.reduce(function(a, b) { return a + b; }, 0);
                var avg     = nonZero.length ? (total / nonZero.length).toFixed(1) : 0;

                document.getElementById('sumPeak').textContent   = peak;
                document.getElementById('sumTotal').textContent  = total;
                document.getElementById('sumAvg').textContent    = avg;
                document.getElementById('sumPoints').textContent = nonZero.length;

                var canvas = document.getElementById('todayChart');
                var ctx    = canvas.getContext('2d');

                if (chartInstance) chartInstance.destroy();

                // Gradient fill
                var gradient = ctx.createLinearGradient(0, 0, 0, 340);
                gradient.addColorStop(0, 'rgba(0,136,170,0.18)');
                gradient.addColorStop(1, 'rgba(0,136,170,0)');

                chartInstance = new Chart(ctx, {
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
                        maintainAspectRatio: false,
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
                                callbacks: {
                                    label: function(ctx) {
                                        return '  Fish: ' + ctx.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Fish Count',
                                    color: '#004D73',
                                    font: { size: 12, weight: '600' }
                                },
                                grid: { color: 'rgba(0,0,0,0.04)' },
                                ticks: { color: '#888', font: { size: 11 } }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Time Period',
                                    color: '#004D73',
                                    font: { size: 12, weight: '600' }
                                },
                                grid: { display: false },
                                ticks: { color: '#888', font: { size: 11 } }
                            }
                        }
                    }
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateChart('time');

        document.querySelectorAll('.rp-pill').forEach(function(pill) {
            pill.addEventListener('click', function() {
                updateChart(this.dataset.period);
            });
        });
    });
</script>
@endsection
