<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — FishCount IoT</title>
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo.jpg') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
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
                <p class="auth-subtitle">Join the smart fish monitoring platform</p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <i class="fas fa-fish"></i>
                        <span>AI-powered fish countinf</span>
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
                    <h2>Create your account</h2>
                    <p>Fill in the details below to get started</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}" class="auth-form">
                    @csrf

                    <div class="form__group">
                        <label for="first_name" class="form__label">First Name</label>
                        <div class="input-icon @error('first_name') input-error-wrap @enderror">
                            <i class="fas fa-user"></i>
                            <input id="first_name" type="text" name="first_name"
                                   placeholder="First name" value="{{ old('first_name') }}"
                                   class="form__input" required>
                        </div>
                        @error('first_name')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form__group">
                        <label for="middle_name" class="form__label">Middle Name <span class="optional">(optional)</span></label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input id="middle_name" type="text" name="middle_name"
                                   placeholder="Middle name" value="{{ old('middle_name') }}"
                                   class="form__input">
                        </div>
                        @error('middle_name')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form__group">
                        <label for="last_name" class="form__label">Last Name</label>
                        <div class="input-icon @error('last_name') input-error-wrap @enderror">
                            <i class="fas fa-user"></i>
                            <input id="last_name" type="text" name="last_name"
                                   placeholder="Last name" value="{{ old('last_name') }}"
                                   class="form__input" required>
                        </div>
                        @error('last_name')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form__group">
                        <label for="username" class="form__label">Username</label>
                        <div class="input-icon @error('username') input-error-wrap @enderror">
                            <i class="fas fa-at"></i>
                            <input id="username" type="text" name="username"
                                   placeholder="Choose a username" value="{{ old('username') }}"
                                   class="form__input" required>
                        </div>
                        @error('username')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form__group">
                        <label for="email" class="form__label">Email Address</label>
                        <div class="input-icon @error('email') input-error-wrap @enderror">
                            <i class="fas fa-envelope"></i>
                            <input id="email" type="email" name="email"
                                   placeholder="you@example.com" value="{{ old('email') }}"
                                   class="form__input" required>
                        </div>
                        @error('email')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form__group">
                        <label for="password" class="form__label">Password</label>
                        <div class="input-icon @error('password') input-error-wrap @enderror">
                            <i class="fas fa-lock"></i>
                            <input id="password" type="password" name="password"
                                   placeholder="Create a password"
                                   class="form__input has-toggle" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password', this)" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')<div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="form__group">
                        <label for="password_confirmation" class="form__label">Confirm Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   placeholder="Repeat your password"
                                   class="form__input has-toggle" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <span>Create Account</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-divider"><span>Already have an account?</span></div>

                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <span>Sign In</span>
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
