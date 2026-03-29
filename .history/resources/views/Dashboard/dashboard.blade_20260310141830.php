@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
  {{-- Welcome Header Section --}}
  <div class="dashboard-header-section">
    <div class="header-content">
      <h1 class="header-title">Dashboard</h1>
      <p class="header-subtitle">Monitor your fish counting system performance in real time</p>
    </div>
    <div class="header-icon">
      <i class="fas fa-chart-line"></i>
    </div>
  </div>

  {{-- Key Metrics Section --}}
  <div class="metrics-section">
    <h2 class="section-title">Quick Stats</h2>
    <div class="metrics-grid">
      <div class="metric-card">
        <div class="metric-top">
          <div class="metric-icon">
            <i class="fas fa-fish"></i>
          </div>
          <span class="metric-badge">Today</span>
        </div>
        <div class="metric-value">{{ $totalFishToday ?? 0 }}</div>
        <div class="metric-label">Fish Counted</div>
        <div class="metric-trend" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-top:0.5rem; color:#00a86b;">
          <i class="fas fa-arrow-up"></i>
          <span style="font-size:0.9rem;">+12% today</span>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-top">
          <div class="metric-icon">
            <i class="fas fa-camera"></i>
          </div>
          <span class="metric-badge">Total</span>
        </div>
        <div class="metric-value">{{ $totalCaptures ?? 0 }}</div>
        <div class="metric-label">Images Captured</div>
        <div class="metric-trend" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-top:0.5rem; color:#0088aa;">
          <i class="fas fa-database"></i>
          <span style="font-size:0.9rem;">Storage</span>
        </div>
      </div>

      <div class="metric-card">
        <div class="metric-top">
          <div class="metric-icon">
            <i class="fas fa-clock"></i>
          </div>
          <span class="metric-badge">Active</span>
        </div>
        <div class="metric-value">{{ $latestDetectionTime ? $latestDetectionTime->diffForHumans() : 'N/A' }}</div>
        <div class="metric-label">Last Detection</div>
        <div class="metric-trend" style="display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-top:0.5rem; color:#f59e0b;">
          <i class="fas fa-bolt"></i>
          <span style="font-size:0.9rem;">Live</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Main Content Area --}}
  <div class="content-section">
    {{-- Latest Detection --}}
    <div class="content-card detection-card">
      <div class="card-header-custom">
        <div class="header-left">
          <i class="fas fa-water"></i>
          <h2>Latest Detection</h2>
        </div>
        <span class="badge-primary">Live</span>
      </div>
      <div class="card-body-custom">
        <div class="detection-content">
          <div class="detection-stats">
            <div class="stat-circle">
              <div class="stat-number">{{ $latestCount ?? 0 }}</div>
            </div>
            <div class="stat-label">Fish Detected</div>
          </div>

          @if($imageCounts->count() > 0)
            <div class="detection-image">
              <img src="{{ asset($imageCounts->first()->file_path) }}" alt="Latest Detection">
              <div class="image-info">
                <span class="info-id"><i class="fas fa-hashtag"></i> {{ $imageCounts->first()->id }}</span>
                <span class="info-time"><i class="fas fa-calendar"></i> {{ $imageCounts->first()->created_at->format('M d, H:i') }}</span>
              </div>
            </div>
          @else
            <div class="no-data">
              <i class="fas fa-image"></i>
              <p>No recent detections</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Population Chart --}}
    <div class="content-card chart-card">
      <div class="card-header-custom">
        <div class="header-left">
          <i class="fas fa-chart-line"></i>
          <h2>Population Trends</h2>
        </div>
      </div>
      <div class="card-body-custom">
        <div class="chart-controls">
          <label for="dashboardPeriodSelect">
            <i class="fas fa-calendar-alt"></i> Time Period:
          </label>
          <select id="dashboardPeriodSelect">
            <option value="time">Today (Hourly)</option>
            <option value="day">This Month</option>
            <option value="month">This Year</option>
            <option value="year">All Time</option>
          </select>
        </div>
        <div class="chart-wrapper">
          <canvas id="popChart"></canvas>
        </div>
      </div>
    </div>

    {{-- System Status --}}
    <div class="content-card status-card">
      <div class="card-header-custom">
        <div class="header-left">
          <i class="fas fa-cog"></i>
          <h2>System Status</h2>
        </div>
        <span class="badge-success">Active</span>
      </div>
      <div class="card-body-custom">
        <div class="status-list">
          <div class="status-item">
            <div class="status-icon">
              <i class="fas fa-microchip"></i>
            </div>
            <div class="status-text">
              <strong>AI Model:</strong> YOLOv8
            </div>
            <div class="status-indicator active"></div>
          </div>
          <div class="status-item">
            <div class="status-icon">
              <i class="fas fa-crosshairs"></i>
            </div>
            <div class="status-text">
              <strong>Accuracy:</strong> 95%
            </div>
            <div class="status-indicator active"></div>
          </div>
          <div class="status-item">
            <div class="status-icon">
              <i class="fas fa-heartbeat"></i>
            </div>
            <div class="status-text">
              <strong>Uptime:</strong> {{ $systemUptime ?? 'N/A' }}
            </div>
            <div class="status-indicator active"></div>
          </div>
          <div class="status-item">
            <div class="status-icon">
              <i class="fas fa-globe"></i>
            </div>
            <div class="status-text">
              <strong>Timezone:</strong> UTC+8
            </div>
            <div class="status-indicator active"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Chart Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  let popChartInstance = null;

  function updatePopulationChart(period = 'time') {
    fetch(`{{ route('chart-data') }}?period=${period}`)
      .then(res => res.json())
      .then(data => {
        const ctx = document.getElementById('popChart').getContext('2d');

        if (popChartInstance) {
          popChartInstance.destroy();
        }

        popChartInstance = new Chart(ctx, {
          type: 'line',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Total Fish Count',
              data: data.counts,
              borderColor: '#0088AA',
              backgroundColor: 'rgba(0, 136, 170, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.4,
              pointRadius: 5,
              pointBackgroundColor: '#004D73',
              pointBorderColor: 'white',
              pointBorderWidth: 2,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)'
                }
              },
              x: {
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)'
                }
              }
            }
          }
        });
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
    updatePopulationChart('time');

    const periodSelect = document.getElementById('dashboardPeriodSelect');
    if (periodSelect) {
      periodSelect.addEventListener('change', (e) => {
        updatePopulationChart(e.target.value);
      });
    }
  });
</script>
@endsection
