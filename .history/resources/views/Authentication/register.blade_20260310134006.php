<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
  <!-- ✅ Inserted: Font Awesome CDN for icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body class="auth-page">
  <div class="wrapper">
    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo">
    <h2>Register</h2>

    @if(session('success'))
      <div class="info" style="background: #e0ffee; padding: 1rem; border-radius: 0.4rem; margin-bottom: 1.6rem; color: #006644;">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
      @csrf

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-user"></i>
          <input
            type="text"
            name="first_name"
            placeholder="First Name"
            value="{{ old('first_name') }}"
            class="form__input @error('first_name') focus:border-red-500 @enderror"
            required
          >
        </div>
        @error('first_name')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-user"></i>
          <input
            type="text"
            name="middle_name"
            placeholder="Middle Name (Optional)"
            value="{{ old('middle_name') }}"
            class="form__input @error('middle_name') focus:border-red-500 @enderror"
          >
        </div>
        @error('middle_name')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-user"></i>
          <input
            type="text"
            name="last_name"
            placeholder="Last Name"
            value="{{ old('last_name') }}"
            class="form__input @error('last_name') focus:border-red-500 @enderror"
            required
          >
        </div>
        @error('last_name')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-user"></i>
          <input
            type="text"
            name="username"
            placeholder="Username"
            value="{{ old('username') }}"
            class="form__input @error('username') focus:border-red-500 @enderror"
            required
          >
        </div>
        @error('username')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-envelope"></i>
          <input
            type="email"
            name="email"
            placeholder="Email"
            value="{{ old('email') }}"
            class="form__input @error('email') focus:border-red-500 @enderror"
            required
          >
        </div>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-lock"></i>
          <input
            type="password"
            name="password"
            placeholder="Password"
            class="form__input @error('password') focus:border-red-500 @enderror"
            required
          >
        </div>
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form__group">
        <div class="input-icon">
          <i class="fa-solid fa-lock"></i>
          <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm Password"
            class="form__input @error('password_confirmation') focus:border-red-500 @enderror"
            required
          >
        </div>
        @error('password_confirmation')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn">Register</button>
    </form>

    <div class="info">
      Already have an account? <a href="{{ route('login') }}">Login here</a>
    </div>
  </div>
</body>
