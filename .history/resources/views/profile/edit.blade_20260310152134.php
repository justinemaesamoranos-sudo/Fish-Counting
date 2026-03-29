@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<style>
  .edit-profile-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0;
  }

  .profile-header-section {
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 136, 170, 0.2);
  }

  .profile-header-icon {
    font-size: 3.5rem;
    background: rgba(255, 255, 255, 0.2);
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }

  .profile-header-content h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
  }

  .profile-header-content p {
    margin: 0.5rem 0 0 0;
    font-size: 1rem;
    opacity: 0.9;
  }

  .edit-profile-form {
    background: white;
    padding: 2.5rem;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 2px 8px rgba(0, 88, 170, 0.1);
  }

  .success-alert {
    background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%);
    color: #2E7D32;
    padding: 1.2rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    border-left: 5px solid #4CAF50;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 1.05rem;
    font-weight: 500;
    animation: slideDown 0.4s ease;
  }

  .success-alert i {
    font-size: 1.4rem;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .form-section {
    margin-bottom: 2.5rem;
  }

  .form-section-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #004D73;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #E8F4F8;
  }

  .form-section-title i {
    color: #0088AA;
    font-size: 1.3rem;
  }

  .form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }

  .form-group {
    margin-bottom: 0;
  }

  .form-group label {
    display: block;
    margin-bottom: 0.7rem;
    color: #004D73;
    font-weight: 600;
    font-size: 1.05rem;
  }

  .form-group input {
    width: 100%;
    padding: 1.1rem;
    border: 2px solid #E8F4F8;
    border-radius: 8px;
    font-size: 1.05rem;
    background: #fff;
    font-family: 'Roboto', sans-serif;
    transition: all 0.3s ease;
  }

  .form-group input:focus {
    outline: none;
    border-color: #0088AA;
    box-shadow: 0 0 0 4px rgba(0, 136, 170, 0.1);
    background: #F8FEFF;
  }

  .form-group input::placeholder {
    color: #999;
  }

  .form-group small.error {
    display: block;
    color: #E57373;
    font-size: 0.95rem;
    margin-top: 0.5rem;
    font-weight: 500;
  }

  .form-grid-full {
    grid-column: 1 / -1;
  }

  @media (max-width: 768px) {
    .form-grid-2 {
      grid-template-columns: 1fr;
    }
    .profile-header-section {
      flex-direction: column;
      text-align: center;
    }
    .edit-profile-form {
      padding: 1.5rem;
    }
  }

  .form-actions {
    display: flex;
    gap: 1.2rem;
    margin-top: 2.5rem;
    justify-content: flex-end;
  }

  .btn-save {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    color: white;
    border: none;
    padding: 1.1rem 2rem;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 136, 170, 0.2);
  }

  .btn-save:hover {
    background: linear-gradient(135deg, #006B8F 0%, #004D73 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 136, 170, 0.3);
  }

  .btn-save i {
    font-size: 1.2rem;
  }

  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    background: white;
    color: #0088AA;
    border: 2px solid #0088AA;
    padding: 1.05rem 1.8rem;
    border-radius: 8px;
    font-size: 1.05rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-back:hover {
    background: #F0F9FB;
    border-color: #004D73;
    color: #004D73;
    transform: translateY(-2px);
  }

  .btn-back i {
    font-size: 1.2rem;
  }
</style>

<div class="edit-profile-container">
  <div class="profile-header-section">
    <div class="profile-header-icon">
      <i class="fas fa-user-edit"></i>
    </div>
    <div class="profile-header-content">
      <h1>Edit Your Profile</h1>
      <p>Update your account information and security settings</p>
    </div>
  </div>

  <div class="edit-profile-form">
    {{-- success flash --}}
    @if(session('success'))
      <div class="success-alert">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf

      {{-- Personal Information Section --}}
      <div class="form-section">
        <h2 class="form-section-title">
          <i class="fas fa-id-card"></i>
          Personal Information
        </h2>
        <div class="form-grid-2">
          <div class="form-group">
            <label for="first_name"><i class="fas fa-user"></i> First Name</label>
            <input type="text" name="first_name" id="first_name"
                   value="{{ old('first_name', $user->first_name) }}" required>
            @error('first_name') <small class="error">{{ $message }}</small> @enderror
          </div>
          <div class="form-group">
            <label for="middle_name"><i class="fas fa-user"></i> Middle Name</label>
            <input type="text" name="middle_name" id="middle_name"
                   value="{{ old('middle_name', $user->middle_name) }}">
            @error('middle_name') <small class="error">{{ $message }}</small> @enderror
          </div>
          <div class="form-group">
            <label for="last_name"><i class="fas fa-user"></i> Last Name</label>
            <input type="text" name="last_name" id="last_name"
                   value="{{ old('last_name', $user->last_name) }}" required>
            @error('last_name') <small class="error">{{ $message }}</small> @enderror
          </div>
          <div class="form-group">
            <label for="username"><i class="fas fa-at"></i> Username</label>
            <input type="text" name="username" id="username"
                   value="{{ old('username', $user->username) }}" required>
            @error('username') <small class="error">{{ $message }}</small> @enderror
          </div>
        </div>
      </div>

      {{-- Contact Information Section --}}
      <div class="form-section">
        <h2 class="form-section-title">
          <i class="fas fa-envelope"></i>
          Contact Information
        </h2>
        <div class="form-grid-2">
          <div class="form-group form-grid-full">
            <label for="email"><i class="fas fa-at"></i> Email Address</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email', $user->email) }}" required>
            @error('email') <small class="error">{{ $message }}</small> @enderror
          </div>
        </div>
      </div>

      {{-- Security Section --}}
      <div class="form-section">
        <h2 class="form-section-title">
          <i class="fas fa-lock"></i>
          Security
        </h2>
        <div class="form-grid-2">
          <div class="form-group">
            <label for="password"><i class="fas fa-key"></i> New Password</label>
            <input type="password" name="password" id="password"
                   placeholder="Leave blank to keep current password">
            @error('password') <small class="error">{{ $message }}</small> @enderror
          </div>
          <div class="form-group">
            <label for="password_confirmation"><i class="fas fa-key"></i> Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   placeholder="Leave blank to keep current password">
          </div>
        </div>
      </div>

      {{-- Form Actions --}}
      <div class="form-actions">
        <a href="{{ route('dashboard') }}" class="btn-back">
          <i class="fas fa-arrow-left"></i>
          <span>Back to Dashboard</span>
        </a>
        <button type="submit" class="btn-save">
          <i class="fas fa-save"></i>
          <span>Save Changes</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
