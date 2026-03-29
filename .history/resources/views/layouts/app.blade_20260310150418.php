<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>

    <!-- ✅ ALLOW EXTERNAL CAMERA STREAMS -->
    <meta http-equiv="Content-Security-Policy"
          content="
            default-src 'self' http: https: data: blob:;
            img-src * data: blob:;
            media-src *;
            connect-src *;
            script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;
            style-src 'self' 'unsafe-inline' https:;
            font-src 'self' https: data:;
          ">

    {{-- Core fonts & CSS --}}
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ filemtime(public_path('css/profile.css')) }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Favicon --}}
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo.jpg') }}">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.2/dist/chart.umd.min.js" defer></script>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f4f6f9;
        }
        .sidebar {
            width: 240px;
            background-color: #0F2D3C;
            color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding-top: 2rem;
        }
        .sidebar-header img {
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
            filter: brightness(1.1) contrast(1.1);
            transition: box-shadow 0.3s ease;
        }
        .sidebar-header img:hover {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
        }
        .sidebar-header h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-style: italic;
            font-size: 1.4rem;
            margin: 0.8rem 0 0 0;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .main-content {
            margin-left: 240px;
            padding: 1.5rem;
        }
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 0.6rem;
            vertical-align: middle;
            font-size: 36px;
        }
        .profile-btn {
            display: flex;
            align-items: center;
            background: transparent;
            border: none;
            color: #fff;
            font: inherit;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.4rem;
            transition: background 0.3s;
        }
        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .profile-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0.4rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            display: none;
            min-width: 12rem;
            z-index: 100;
        }
        .profile-menu.show {
            display: block;
        }
        .profile-info {
            padding: 1rem;
            background: #f9f9f9;
            border-radius: 0.4rem 0.4rem 0 0;
        }
        .profile-info p {
            margin: 0.2rem 0;
            font-size: 1.4rem;
            color: #333;
        }
        .profile-menu hr {
            margin: 0.5rem 0;
            border: none;
            border-top: 1px solid #ddd;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
            gap: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary);
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-size: 1.6rem;
            background: #fff;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(0, 136, 170, 0.25);
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            font-size: 1.6rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: var(--primary);
        }
        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 1rem 2rem;
            border-radius: var(--radius);
            font-size: 1.6rem;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-secondary:hover {
            background: var(--primary);
            color: #fff;
        }
        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            color: #dc3545;
            font-size: 1.4rem;
        }

/* Dashboard Styles */
.dashboard-welcome {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
}
.dashboard-title {
    font-size: 2.4rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 1rem 0;
}
.dashboard-subtitle {
    font-size: 1.2rem;
    color: var(--text-dark);
    margin: 0;
    max-width: 600px;
    margin: 0 auto;
}

.metrics-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}
.metric-card {
    background: #fff;
    border-radius: var(--radius);
    padding: 0.5rem;
    box-shadow: var(--shadow-lg);
    border-left: 4px solid var(--accent);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 77, 115, 0.15);
}
.metric-card.primary {
    border-left-color: var(--primary);
}
.metric-card.accent {
    border-left-color: var(--accent);
}
.metric-card.secondary {
    border-left-color: var(--accent-light);
}
.metric-icon {
    font-size: 3rem;
    color: var(--accent);
    margin-bottom: 1rem;
    display: block;
}
.metric-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 0.5rem 0;
}
.metric-content p {
    color: var(--text-dark);
    margin: 0;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.dashboard-main {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}
.content-card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    border: 1px solid #ddd;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.content-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 77, 115, 0.15);
}
.content-card.featured {
    grid-column: 1 / -1;
}
.card-title {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    color: white;
    padding: 1rem 1.5rem;
    margin: 0;
}
.card-title i {
    font-size: 1.5rem;
    margin-right: 1rem;
}
.card-title h2 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
}

.detection-display {
    padding: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.detection-number {
    flex: 1;
    text-align: center;
}
.number {
    font-size: 4rem;
    font-weight: 700;
    color: var(--primary);
    display: block;
    margin-bottom: 0.5rem;
    line-height: 1;
}
.label {
    font-size: 1.2rem;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}
.detection-image-wrapper {
    flex: 2;
    position: relative;
}
.detection-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: var(--radius);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.detection-image:hover {
    transform: scale(1.02);
}
.image-meta {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    color: white;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 0 0 var(--radius) var(--radius);
}
.image-meta span {
    font-size: 0.9rem;
    font-weight: 500;
}
.no-detection {
    text-align: center;
    padding: 2rem 1.5rem;
    color: var(--text-light);
}
.no-detection i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    display: block;
}

.chart-controls {
    padding: 0 1.5rem;
    margin-bottom: 1rem;
}
.chart-controls label {
    color: var(--primary);
    font-weight: 600;
    margin-right: 1rem;
}
.chart-select {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    background: #fff;
    color: var(--primary);
    font-size: 1rem;
    cursor: pointer;
}
.chart-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 0.2rem rgba(0, 136, 170, 0.25);
}
.chart-container {
    padding: 0 1.5rem 1.5rem 1.5rem;
}

.status-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.125rem;
    padding: 0.5rem;
}
.status-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--bg-light);
    border-radius: var(--radius);
    border-left: 3px solid var(--accent);
}
.status-icon {
    font-size: 2.5rem;
    color: var(--accent);
    min-width: 2.5rem;
}
.status-info h4 {
    margin: 0 0 0.3rem 0;
    color: var(--primary);
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-info p {
    margin: 0;
    color: var(--text-dark);
    font-size: 1.1rem;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .dashboard-main {
        grid-template-columns: 1fr;
    }
    .content-card.featured {
        grid-column: span 1;
    }
    .detection-display {
        flex-direction: column;
        gap: 1.5rem;
    }
    .detection-number {
        order: 2;
    }
    .detection-image-wrapper {
        order: 1;
    }
}

@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2.2rem;
    }
    .metrics-row {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    .number {
        font-size: 4rem;
    }
    .status-grid {
        grid-template-columns: 1fr;
    }
    .status-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
        padding: 1rem;
    }
    .status-icon {
        min-width: auto;
    }
}
    </style>
</head>

<body class="dashboard-page">

    {{-- Sidebar --}}
    <nav class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
            <h2 class="sidebar-title">Fish Count</h2>
        </div>
        <ul>
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('fish-counts') }}"><i class="fas fa-fish"></i> Fish Counts</a></li>
            <li><a href="{{ route('reports') }}"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="{{ route('live-camera') }}"><i class="fas fa-video"></i> Live Camera</a></li>
        </ul>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        <header class="dashboard-header">
            <h1>@yield('title')</h1>

            <div class="profile-dropdown">
                <button class="profile-btn">
                    <i class="fas fa-user-circle profile-avatar"></i>
                    {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="profile-menu">
                    <div class="profile-info">
                        <p><strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong></p>
                        <p>{{ auth()->user()->email }}</p>
                    </div>
                    <hr>
                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            @yield('content')
        </main>
    </div>

    {{-- Profile dropdown script --}}
    <script>
        document.addEventListener('click', e => {
            const dropdown = document.querySelector('.profile-dropdown');
            const btn  = document.querySelector('.profile-btn');
            const menu = document.querySelector('.profile-menu');
            if (!dropdown || !btn || !menu) return;
            if (dropdown.contains(e.target)) {
                if (btn.contains(e.target)) {
                    menu.classList.toggle('show');
                }
            } else {
                menu.classList.remove('show');
            }
        });
    </script>

    {{-- ✅ Include page-specific scripts --}}
    @yield('scripts')

</body>
</html>
