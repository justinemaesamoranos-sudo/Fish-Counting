@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<style>
  .profile-edit-container {
    max-width: 700px;
    margin: 0 auto;
  }

  .profile-edit-header {
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    padding: 3rem 2rem;
    border-radius: 12px 12px 0 0;
    color: white;
    margin-bottom: 0;
    position: relative;
  }

  .profile-edit-header h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .profile-edit-header i {
    font-size: 2.5rem;
    color: #E8F4F8;
  }

  .profile-edit-header p {
    font-size: 1.1rem;
    margin: 0.8rem 0 0 0;
    color: #E8F4F8;
    opacity: 0.95;
  }

  .profile-edit-card {
    background: white;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 8px 24px rgba(0, 77, 115, 0.15);
    overflow: hidden;
  }

  .profile-edit-body {
    padding: 2.5rem;
  }

  .alert-success {
    background: #D4EDDA;
    color: #155724;
    padding: 1.2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 1.05rem;
    border-left: 4px solid #28A745;
  }

  .alert-success i {
    font-size: 1.4rem;
    color: #28A745;
  }

  .profile-form-section {
    margin-bottom: 2.5rem;
  }

  .profile-form-section:last-of-type {
    margin-bottom: 0;
  }

  .section-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #004D73;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #E8F4F8;
  }

  .section-title i {
    color: #0088AA;
    font-size: 1.4rem;
  }

  .form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }

  .form-group-enhanced {
    display: flex;
    flex-direction: column;
  }

  .form-group-enhanced label {
    font-size: 1.05rem;
    font-weight: 600;
    color: #004D73;
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
  }

  .form-group-enhanced label i {
    color: #0088AA;
    font-size: 1rem;
  }

  .form-group-enhanced input {
    padding: 0.9rem 1rem;
    border: 2px solid #E8F4F8;
    border-radius: 8px;
    font-size: 1.05rem;
    font-family: 'Roboto', sans-serif;
    background: #f9fafb;
    transition: all 0.3s ease;
  }

  .form-group-enhanced input:focus {
    outline: none;
    border-color: #0088AA;
    background: white;
    box-shadow: 0 0 8px rgba(0, 136, 170, 0.2);
  }

  .form-group-enhanced input::placeholder {
    color: #999;
  }

  .form-group-enhanced .error {
    color: #E57373;
    font-size: 0.95rem;
    margin-top: 0.4rem;
    font-weight: 500;
  }

  .form-section-full {
    margin-bottom: 2rem;
  }

  .form-grid-2.full {
    grid-template-columns: 1fr;
  }

  .form-actions-enhanced {
    display: flex;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 2px solid #E8F4F8;
  }

  .btn-save {
    flex: 1;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 136, 170, 0.3);
    background: linear-gradient(135deg, #006B8F 0%, #004D73 100%);
  }

  .btn-back-enhanced {
    padding: 1rem 2rem;
    background: #E8F4F8;
    color: #004D73;
    border: 2px solid #004D73;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
  }

  .btn-back-enhanced:hover {
    background: #004D73;
    color: #E8F4F8;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 77, 115, 0.2);
  }

  @media (max-width: 768px) {
    .profile-edit-body {
      padding: 1.5rem;
    }

    .form-grid-2 {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .profile-edit-header {
      padding: 2rem 1.5rem;
    }

    .profile-edit-header h1 {
      font-size: 1.8rem;
    }

    .form-actions-enhanced {
      flex-direction: column;
    }

    .btn-save,
    .btn-back-enhanced {
      width: 100%;
    }
  }
</style>

<div class="profile-edit-container">
  <div class="profile-edit-header">
    <h1>
      <i class="fas fa-user-edit"></i>
      Edit Your Profile
    </h1>
    <p>Update your personal information and account details</p>
  </div>

  <div class="profile-edit-card">
    <div class="profile-edit-body">
      {{-- success flash --}}
      @if(session('success'))
        <div class="alert-success">
          <i class="fas fa-check-circle"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        {{-- Personal Information Section --}}
        <div class="profile-form-section">
          <h2 class="section-title">
            <i class="fas fa-id-card"></i>
            Personal Information
          </h2>
          <div class="form-grid-2">
            <div class="form-group-enhanced">
              <label for="first_name">
                <i class="fas fa-user"></i>
                First Name
              </label>
              <input type="text" name="first_name" id="first_name"
                     value="{{ old('first_name', $user->first_name) }}" required>
              @error('first_name') <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
            </div>
            <div class="form-group-enhanced">
              <label for="middle_name">
                <i class="fas fa-user"></i>
                Middle Name
              </label>
              <input type="text" name="middle_name" id="middle_name"
                     value="{{ old('middle_name', $user->middle_name) }}">
              @error('middle_name') <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
            </div>
            <div class="form-group-enhanced">
              <label for="last_name">
                <i class="fas fa-user"></i>
                Last Name
              </label>
              <input type="text" name="last_name" id="last_name"
                     value="{{ old('last_name', $user->last_name) }}" required>
              @error('last_name') <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
            </div>
            <div class="form-group-enhanced">
              <label for="username">
                <i class="fas fa-at"></i>
                Username
              </label>
              <input type="text" name="username" id="username"
                     value="{{ old('username', $user->username) }}" required>
              @error('username') <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        {{-- Contact Information Section --}}
        <div class="profile-form-section">
          <h2 class="section-title">
            <i class="fas fa-envelope"></i>
            Contact Information
          </h2>
          <div class="form-grid-2 full">
            <div class="form-group-enhanced">
              <label for="email">
                <i class="fas fa-envelope"></i>
                Email Address
              </label>
              <input type="email" name="email" id="email"
                     value="{{ old('email', $user->email) }}" required>
              @error('email') <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        {{-- Security Section --}}
        <div class="profile-form-section">
          <h2 class="section-title">
            <i class="fas fa-lock"></i>
            Security & Password
          </h2>
          <div class="form-grid-2">
            <div class="form-group-enhanced">
              <label for="password">
                <i class="fas fa-key"></i>
                New Password
              </label>
              <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
              @error('password') <small class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
            </div>
            <div class="form-group-enhanced">
              <label for="password_confirmation">
                <i class="fas fa-key"></i>
                Confirm Password
              </label>
              <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your new password">
            </div>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="form-actions-enhanced">
          <button type="submit" class="btn-save">
            <i class="fas fa-save"></i>
            Save Changes
          </button>
          <a href="{{ route('dashboard') }}" class="btn-back-enhanced">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
