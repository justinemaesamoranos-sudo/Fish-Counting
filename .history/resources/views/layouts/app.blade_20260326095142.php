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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.2/dist/chart.umd.min.js"></script>

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
            transition: width 0.3s ease;
            overflow: hidden;
            z-index: 101;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar.collapsed .sidebar-header h2 {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-header img {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }
        
        .sidebar.collapsed ul li a span {
            display: none;
        }
        
        .sidebar.collapsed ul li a i {
            margin-right: 0;
        }
        
        .sidebar-expand-btn {
            display: none;
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 136, 170, 0.3);
            z-index: 1000;
        }
        
        .sidebar.collapsed .sidebar-expand-btn {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-expand-btn:hover {
            background: linear-gradient(135deg, #006B8F 0%, #004D73 100%);
            transform: translateX(-50%) scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 136, 170, 0.4);
        }
        
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar ul li {
            padding: 0;
        }
        
        .sidebar ul li a {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #fff;
            text-decoration: none;
            padding: 1.2rem 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 1.1rem;
        }
        
        .sidebar ul li a i {
            font-size: 1.4rem;
            min-width: 1.4rem;
            transition: all 0.3s ease;
        }
        
        .sidebar ul li a:hover {
            background-color: rgba(0, 136, 170, 0.2);
            border-left-color: #0088AA;
            padding-left: 1.2rem;
        }
        
        .sidebar ul li a.active {
            background-color: rgba(0, 136, 170, 0.3);
            border-left-color: #0088AA;
            font-weight: 600;
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
            transition: margin-left 0.3s ease;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 70px;
        }
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 0.6rem;
            vertical-align: middle;
            font-size: 36px;
        }
        .sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem 0.8rem;
            border-radius: 0.4rem;
            transition: background 0.3s;
            margin-right: 1rem;
        }
        
        .sidebar-toggle:hover {
            background: rgba(0, 0, 0, 0.2);
        }
        
        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            font: inherit;
            cursor: pointer;
            padding: 0.45rem 0.9rem 0.45rem 0.5rem;
            border-radius: 30px;
            transition: all 0.25s;
            white-space: nowrap;
        }
        .profile-btn:hover {
            background: rgba(255,255,255,0.18);
            border-color: rgba(255,255,255,0.35);
        }

        .profile-btn-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0088AA, #4DB8D5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: #fff;
            flex-shrink: 0;
            font-weight: 700;
        }

        .profile-btn-name {
            font-size: 0.88rem;
            font-weight: 600;
        }

        .profile-btn-chevron {
            font-size: 0.65rem;
            opacity: 0.7;
            transition: transform 0.25s;
        }

        .profile-btn.open .profile-btn-chevron {
            transform: rotate(180deg);
        }

        .profile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: none;
            z-index: 99;
            backdrop-filter: blur(2px);
        }

        .profile-overlay.show { display: block; }

        /* ── Slide-in panel ── */
        .profile-panel {
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            width: 320px;
            background: #fff;
            box-shadow: -8px 0 40px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            z-index: 100;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .profile-panel.show { transform: translateX(0); }

        /* Close button */
        .profile-panel-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            z-index: 2;
        }

        .profile-panel-close:hover { background: rgba(255,255,255,0.35); }

        /* Header section */
        .profile-panel-header {
            background: linear-gradient(135deg, #004D73 0%, #0088AA 100%);
            padding: 2.5rem 1.5rem 2rem;
            text-align: center;
            position: relative;
            flex-shrink: 0;
        }

        .profile-panel-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #0088AA;
        }

        .profile-panel-avatar {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
            border: 3px solid rgba(255,255,255,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.2rem;
            color: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        .profile-panel-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 0.3rem;
            letter-spacing: -0.2px;
        }

        .profile-panel-email {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.75);
            margin: 0;
        }

        /* Role badge */
        .profile-panel-role {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.75rem;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        /* Content */
        .profile-panel-content {
            flex: 1;
            padding: 2.5rem 1.25rem 1.25rem;
            overflow-y: auto;
        }

        .profile-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 0.6rem 0.25rem;
        }

        .profile-panel-item {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.85rem 1rem;
            margin-bottom: 0.4rem;
            background: #f7fbfc;
            border-radius: 10px;
            color: #004D73;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .profile-panel-item:hover {
            background: #e8f4f8;
            border-color: rgba(0,136,170,0.15);
            transform: translateX(3px);
        }

        .profile-panel-item-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, #004D73, #0088AA);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .profile-panel-divider {
            height: 1px;
            background: #e8f4f8;
            margin: 1rem 0;
        }

        /* Logout */
        .profile-panel-logout {
            padding: 1rem 1.25rem 1.5rem;
            flex-shrink: 0;
        }

        .profile-panel-logout form { margin: 0; }

        .profile-panel-logout button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.85rem;
            background: linear-gradient(135deg, #e53935, #c62828);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 3px 10px rgba(198,40,40,0.3);
            letter-spacing: 0.3px;
        }

        .profile-panel-logout button:hover {
            background: linear-gradient(135deg, #c62828, #b71c1c);
            box-shadow: 0 5px 16px rgba(198,40,40,0.45);
            transform: translateY(-1px);
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
    .sidebar-toggle {
        display: inline-flex;
    }
    .sidebar {
        width: 240px;
    }
    .main-content {
        margin-left: 0;
    }
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
    .profile-btn span {
        display: none;
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
            <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="{{ route('fish-counts') }}"><i class="fas fa-fish"></i> <span>Fish Counts</span></a></li>
            <li><a href="{{ route('reports') }}"><i class="fas fa-chart-bar"></i> <span>Reports</span></a></li>
            <li><a href="{{ route('live-camera') }}"><i class="fas fa-video"></i> <span>Live Camera</span></a></li>
        </ul>
        <button class="sidebar-expand-btn" onclick="expandSidebar()" title="Expand Sidebar">
            <i class="fas fa-chevron-right"></i>
        </button>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        <header class="dashboard-header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>@yield('title')</h1>
            </div>

            <div class="profile-dropdown">
                <button class="profile-btn" id="profileBtn">
                    <div class="profile-btn-avatar">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                    </div>
                    <span class="profile-btn-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <i class="fas fa-chevron-down profile-btn-chevron"></i>
                </button>
            </div>
            <div class="profile-overlay" onclick="closeProfilePanel()"></div>
            <div class="profile-panel">
                <button class="profile-panel-close" onclick="closeProfilePanel()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="profile-panel-header">
                    <div class="profile-panel-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="profile-panel-name">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
                    <p class="profile-panel-email">{{ auth()->user()->email }}</p>
                    <div class="profile-panel-role">
                        <i class="fas fa-shield-alt"></i> Fish Cage Operator
                    </div>
                </div>
                <div class="profile-panel-content">
                    <p class="profile-section-label">Account</p>
                    <a href="{{ route('profile') }}" class="profile-panel-item" onclick="closeProfilePanel()">
                        <div class="profile-panel-item-icon"><i class="fas fa-user-edit"></i></div>
                        <span>Edit Profile</span>
                        <i class="fas fa-chevron-right" style="margin-left:auto; font-size:0.7rem; opacity:0.4;"></i>
                    </a>
                    <div class="profile-panel-divider"></div>
                </div>
                <div class="profile-panel-logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            @yield('content')
        </main>
    </div>

    {{-- Sidebar and Profile Panel Scripts --}}
    <script>
        // Sidebar Toggle Function
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebar && mainContent) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        }
        
        // Expand Sidebar Function
        function expandSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebar && mainContent) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', false);
            }
        }
        
        // Restore sidebar state on page load
        window.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                const sidebar = document.querySelector('.sidebar');
                const mainContent = document.querySelector('.main-content');
                if (sidebar && mainContent) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('sidebar-collapsed');
                }
            }
            
            // Highlight active nav item
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar ul li a').forEach(link => {
                const href = new URL(link.href, window.location.origin).pathname;
                if (href === currentPath) {
                    link.classList.add('active');
                }
            });
            
            // Add click handler to collapse sidebar when a nav link is clicked (on desktop)
            document.querySelectorAll('.sidebar ul li a').forEach(link => {
                link.addEventListener('click', () => {
                    const sidebar = document.querySelector('.sidebar');
                    // Collapse sidebar if not already collapsed (desktop behavior)
                    if (sidebar && !sidebar.classList.contains('collapsed')) {
                        sidebar.classList.add('collapsed');
                        document.querySelector('.main-content').classList.add('sidebar-collapsed');
                        localStorage.setItem('sidebarCollapsed', true);
                    }
                });
            });
        });
        
        function openProfilePanel() {
            const panel = document.querySelector('.profile-panel');
            const overlay = document.querySelector('.profile-overlay');
            const btn = document.getElementById('profileBtn');
            if (panel && overlay) {
                panel.classList.add('show');
                overlay.classList.add('show');
                btn && btn.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeProfilePanel() {
            const panel = document.querySelector('.profile-panel');
            const overlay = document.querySelector('.profile-overlay');
            const btn = document.getElementById('profileBtn');
            if (panel && overlay) {
                panel.classList.remove('show');
                overlay.classList.remove('show');
                btn && btn.classList.remove('open');
                document.body.style.overflow = 'auto';
            }
        }
        
        document.querySelector('.profile-btn').addEventListener('click', openProfilePanel);
        
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeProfilePanel();
            }
        });
    </script>

    {{-- ✅ Include page-specific scripts --}}
    @yield('scripts')

</body>
</html>
