<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In - FishCount IoT</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="auth-page auth-login">
  <div class="auth-container">
    <div class="auth-wrapper auth-wrapper-split">
      <!-- Left Column: Welcome Section -->
      <div class="auth-welcome-section">
        <div class="logo-container">
          <img src="{{ asset('images/logo.jpg') }}" alt="FishCount IoT Logo" class="logo">
        </div>
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Sign in to your FishCount IoT account</p>
        <div class="welcome-decoration">
          <i class="fa-solid fa-shield"></i>
        </div>
      </div>

      <!-- Right Column: Form Section -->
      <div class="auth-form-section">
        <!-- Success Message -->
        @if(session('success'))
          <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.submit') }}" class="auth-form">
          @csrf

          <div class="form__group">
            <label for="email" class="form__label">Email Address</label>
            <div class="input-icon">
              <i class="fa-solid fa-envelope"></i>
              <input
                id="email"
                type="email"
                name="email"
                placeholder="you@example.com"
                value="{{ old('email') }}"
                class="form__input @error('email') input-error @enderror"
                required
                autofocus
              >
            </div>
            @error('email')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form__group">
            <label for="password" class="form__label">Password</label>
            <div class="input-icon">
              <i class="fa-solid fa-lock"></i>
              <input
                id="password"
                type="password"
                name="password"
                placeholder="••••••••"
                class="form__input @error('password') input-error @enderror"
                required
              >
            </div>
            @error('password')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary">
            <span>Sign In</span>
            <i class="fa-solid fa-arrow-right"></i>
          </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider">
          <span>Don't have an account?</span>
        </div>

        <!-- Sign Up Link -->
        <a href="{{ route('register') }}" class="btn btn-secondary">
          <span>Create Account</span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>

        <!-- Footer -->
        <div class="auth-footer">
          <p>© 2026 FishCount IoT. All rights reserved.</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
