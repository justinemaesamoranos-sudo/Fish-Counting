<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — FishCount IoT</title>
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo.jpg') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --primary: #004D73; --primary-mid: #0088AA; --primary-light: #4DB8D5; --danger: #e53935; --success: #2e7d32; font-size: 16px; }
        html, body { width: 100%; min-height: 100%; }
        body { font-family: 'Roboto', sans-serif; background: linear-gradient(135deg, #071e2b 0%, #0a2a3d 50%, #0c3350 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; position: relative; overflow-x: hidden; }
        body::before { content: ''; position: fixed; inset: 0; background: radial-gradient(ellipse at 20% 50%, rgba(0,136,170,0.12) 0%, transparent 60%), radial-gradient(ellipse at 80% 20%, rgba(0,77,115,0.15) 0%, transparent 50%); pointer-events: none; z-index: 0; }
        .auth-container { width: 100%; max-width: 960px; position: relative; z-index: 1; }
        .auth-wrapper-split { display: grid; grid-template-columns: 340px 1fr; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 32px 80px rgba(0,0,0,0.35); animation: fadeUp 0.5s cubic-bezier(0.4,0,0.2,1); }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .auth-welcome-section { background: linear-gradient(160deg, #071e2b 0%, #0a2a3d 45%, #0d3550 100%); padding: 3rem 2.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative; overflow: hidden; gap: 1.25rem; }
        .auth-welcome-section::before { content: ''; position: absolute; top: -80px; right: -80px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(0,136,170,0.18) 0%, transparent 70%); pointer-events: none; }
        .auth-welcome-section::after { content: ''; position: absolute; bottom: -60px; left: -60px; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(0,136,170,0.12) 0%, transparent 70%); pointer-events: none; }
        .logo-container { position: relative; z-index: 2; }
        .logo { width: 88px; height: 88px; border-radius: 20px; object-fit: cover; box-shadow: 0 8px 24px rgba(0,0,0,0.4), 0 0 0 3px rgba(0,136,170,0.5); animation: float 4s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        .auth-title { font-family: 'Playfair Display', serif; font-weight: 900; font-style: italic; font-size: 2rem; color: #fff; letter-spacing: 0.5px; position: relative; z-index: 2; line-height: 1.2; }
        .auth-subtitle { font-size: 0.9rem; color: rgba(255,255,255,0.7); line-height: 1.6; position: relative; z-index: 2; max-width: 240px; }
        .auth-features { display: flex; flex-direction: column; gap: 0.65rem; width: 100%; position: relative; z-index: 2; margin-top: 0.5rem; }
        .auth-feature { display: flex; align-items: center; gap: 0.75rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.65rem 0.9rem; text-align: left; }
        .auth-feature i { width: 28px; height: 28px; border-radius: 7px; background: linear-gradient(135deg, #0088AA, #006B8F); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #fff; flex-shrink: 0; }
        .auth-feature span { font-size: 0.82rem; color: rgba(255,255,255,0.8); font-weight: 500; }
        .welcome-decoration { position: absolute; font-size: 120px; opacity: 0.04; bottom: -10px; right: -10px; color: #fff; pointer-events: none; z-index: 1; }
        .auth-form-section { padding: 2rem 2.25rem; display: flex; flex-direction: column; justify-content: flex-start; overflow-y: auto; max-height: 100vh; }
        .auth-form-heading { margin-bottom: 1.25rem; }
        .auth-form-heading h2 { font-size: 1.4rem; font-weight: 700; color: #004D73; margin-bottom: 0.2rem; }
        .auth-form-heading p { font-size: 0.82rem; color: #888; }
        .alert { display: flex; align-items: center; gap: 0.65rem; padding: 0.85rem 1rem; border-radius: 10px; font-size: 0.88rem; font-weight: 500; margin-bottom: 1.25rem; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #4caf50; }
        .auth-form { display: flex; flex-direction: column; gap: 0.7rem; margin-bottom: 1rem; }
        .form__group { display: flex; flex-direction: column; }
        .form__label { font-size: 0.78rem; font-weight: 700; color: #004D73; margin-bottom: 0.3rem; }
        .optional { font-weight: 400; color: #aaa; }
        .input-icon { display: flex; align-items: center; width: 100%; background: #f7fbfc; border: 2px solid #e8f4f8; border-radius: 10px; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; }
        .input-icon:focus-within { border-color: #0088AA; background: #fff; box-shadow: 0 0 0 3px rgba(0,136,170,0.1); }
        .input-icon.input-error-wrap { border-color: #e53935; background: #fff5f5; }
        .input-icon i { padding: 0 0.5rem 0 0.9rem; color: #0088AA; font-size: 0.9rem; pointer-events: none; transition: color 0.2s; flex-shrink: 0; }
        .input-icon:focus-within > i { color: #004D73; }
        .pw-toggle { flex-shrink: 0; background: none; border: none; cursor: pointer; color: #0088AA; font-size: 0.88rem; padding: 0 0.75rem; display: flex; align-items: center; transition: color 0.2s; }
        .pw-toggle:hover { color: #004D73; }
        .form__input { flex: 1; min-width: 0; padding: 0.68rem 0.5rem 0.68rem 0.3rem; font-size: 0.88rem; font-family: 'Roboto', sans-serif; color: #1a1a1a; background: transparent; border: none; outline: none; border-radius: 10px; -webkit-appearance: none; }
        .form__input::placeholder { color: #bbb; }
        .form__input.input-error { color: #c62828; }
        .error-message { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; color: #e53935; margin-top: 0.25rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.78rem 1.5rem; font-size: 0.92rem; font-weight: 700; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; text-decoration: none; width: 100%; font-family: 'Roboto', sans-serif; }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: linear-gradient(135deg, #004D73, #0088AA); color: #fff; box-shadow: 0 4px 14px rgba(0,136,170,0.35); }
        .btn-primary:hover { background: linear-gradient(135deg, #003D5C, #006B8F); }
        .btn-secondary { background: #f7fbfc; color: #004D73; border: 2px solid #e8f4f8; }
        .btn-secondary:hover { background: #e8f4f8; border-color: #0088AA; }
        .btn i { font-size: 0.8rem; transition: transform 0.2s; }
        .btn:hover i { transform: translateX(3px); }
        .auth-divider { display: flex; align-items: center; gap: 0.75rem; margin: 0.85rem 0; color: #aaa; font-size: 0.78rem; font-weight: 500; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: #e8f4f8; }
        .auth-footer { text-align: center; margin-top: 1rem; padding-top: 0.85rem; border-top: 1px solid #f0f4f8; font-size: 0.72rem; color: #bbb; }
        @media (max-width: 780px) { .auth-wrapper-split { grid-template-columns: 1fr; } .auth-welcome-section { padding: 2rem 1.5rem; } .auth-features { display: none; } }
        @media (max-width: 480px) { body { padding: 1rem; } .auth-form-section { padding: 2rem 1.5rem; } .auth-title { font-size: 1.6rem; } }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper-split">

            {{-- Left panel --}}
            <div class="auth-welcome-section">
                <div class="logo-container">
                    <img src="{{ asset('images/logo.jpg') }}" alt="FishCount IoT" class="logo">
                </div>
                <h1 class="auth-title">Fish Counting</h1>
                <p class="auth-subtitle">Real-time fish monitoring powered by AI and IoT</p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <i class="fas fa-fish"></i>
                        <span>AI-powered fish counting</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-video"></i>
                        <span>Live camera monitoring</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-chart-line"></i>
                        <span>Population trend analytics</span>
                    </div>
                </div>

                <div class="welcome-decoration"><i class="fas fa-fish"></i></div>
            </div>

            {{-- Right panel --}}
            <div class="auth-form-section">
                <div class="auth-form-heading">
                    <h2>Welcome back</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="auth-form">
                    @csrf

                    <div class="form__group">
                        <label for="email" class="form__label">Email Address</label>
                        <div class="input-icon @error('email') input-error-wrap @enderror">
                            <i class="fas fa-envelope"></i>
                            <input id="email" type="email" name="email"
                                   placeholder="@gmail.com"
                                   value="{{ old('email') }}"
                                   class="form__input"
                                   required autofocus>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form__group">
                        <label for="password" class="form__label">Password</label>
                        <div class="input-icon @error('password') input-error-wrap @enderror">
                            <i class="fas fa-lock"></i>
                            <input id="password" type="password" name="password"
                                   placeholder="••••••••"
                                   class="form__input has-toggle"
                                   required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password', this)" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-divider"><span>Don't have an account?</span></div>

                <a href="{{ route('register') }}" class="btn btn-secondary">
                    <span>Create Account</span>
                    <i class="fas fa-arrow-right"></i>
                </a>

                <div class="auth-footer">© 2026 FishCount IoT. All rights reserved.</div>
            </div>

        </div>
    </div>
    <script>
    function togglePw(id, btn) {
        var input = document.getElementById(id);
        var icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>
</body>
</html>
