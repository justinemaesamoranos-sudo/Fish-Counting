<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Account - FishCount IoT</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="auth-page auth-register">
  <div class="auth-container">
    <div class="auth-wrapper auth-wrapper-split">
      <!-- Left Column: Welcome Section -->
      <div class="auth-welcome-section">
        <div class="logo-container">
          <img src="{{ asset('images/logo.jpg') }}" alt="FishCount IoT Logo" class="logo">
        </div>
        <h1 class="auth-title">Create Your Account</h1>
        <p class="auth-subtitle">Join FishCount IoT to start monitoring</p>
        <div class="welcome-decoration">
          <i class="fa-solid fa-rocket"></i>
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

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register.submit') }}" class="auth-form">
          @csrf

          <div class="form__group">
            <label for="first_name" class="form__label">First Name</label>
            <div class="input-icon">
              <i class="fa-solid fa-user"></i>
              <input
                id="first_name"
                type="text"
                name="first_name"
                placeholder="First Name"
                value="{{ old('first_name') }}"
                class="form__input @error('first_name') input-error @enderror"
                required
              >
            </div>
            @error('first_name')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form__group">
            <label for="middle_name" class="form__label">Middle Name <span class="optional">(Optional)</span></label>
            <div class="input-icon">
              <i class="fa-solid fa-user"></i>
              <input
                id="middle_name"
                type="text"
                name="middle_name"
                placeholder="Middle Name (Optional)"
                value="{{ old('middle_name') }}"
                class="form__input @error('middle_name') input-error @enderror"
              >
            </div>
            @error('middle_name')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form__group">
            <label for="last_name" class="form__label">Last Name</label>
            <div class="input-icon">
              <i class="fa-solid fa-user"></i>
              <input
                id="last_name"
                type="text"
                name="last_name"
                placeholder="Last Name"
                value="{{ old('last_name') }}"
                class="form__input @error('last_name') input-error @enderror"
                required
              >
            </div>
            @error('last_name')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form__group">
            <label for="username" class="form__label">Username</label>
            <div class="input-icon">
              <i class="fa-solid fa-user"></i>
              <input
                id="username"
                type="text"
                name="username"
                placeholder="Username"
                value="{{ old('username') }}"
                class="form__input @error('username') input-error @enderror"
                required
              >
            </div>
            @error('username')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form__group">
            <label for="email" class="form__label">Email Address</label>
            <div class="input-icon">
              <i class="fa-solid fa-envelope"></i>
              <input
                id="email"
                type="email"
                name="email"
                placeholder="Email"
                value="{{ old('email') }}"
                class="form__input @error('email') input-error @enderror"
                required
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
                placeholder="Password"
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

          <div class="form__group">
            <label for="password_confirmation" class="form__label">Confirm Password</label>
            <div class="input-icon">
              <i class="fa-solid fa-lock"></i>
              <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="Confirm Password"
                class="form__input @error('password_confirmation') input-error @enderror"
                required
              >
            </div>
            @error('password_confirmation')
              <div class="error-message">
                <i class="fa-solid fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary btn-lg">
            <span>Register</span>
            <i class="fa-solid fa-arrow-right"></i>
          </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider">
          <span>Already have an account?</span>
        </div>

        <!-- Sign In Link -->
        <a href="{{ route('login') }}" class="btn btn-secondary">
          <span>Login here</span>
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