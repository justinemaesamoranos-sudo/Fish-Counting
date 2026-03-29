@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<style>
  .profile-edit-container {
    max-width: 900px;
    margin: 0 auto;
  }

  .profile-edit-header {
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    color: white;
    padding: 2.5rem 2rem;
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 0;
  }

  .profile-edit-header-icon {
    font-size: 3rem;
    background: rgba(255, 255, 255, 0.2);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #E8F4F8;
  }

  .profile-edit-header-content h1 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #fff;
  }

  .profile-edit-header-content p {
    font-size: 1rem;
    color: #E8F4F8;
    margin: 0.5rem 0 0 0;
  }

  .profile-edit-card {
    background: white;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 16px rgba(0, 77, 115, 0.1);
    overflow: hidden;
  }

  .profile-edit-form {
    padding: 2.5rem;
  }

  .alert-success {
    background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%);
    border-left: 4px solid #4CAF50;
    color: #2E7D32;
    padding: 1.2rem;
    border-radius: 6px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    font-weight: 500;
  }

  .alert-success::before {
    content: "✓";
    font-size: 1.5rem;
    font-weight: bold;
  }

  .profile-section {
    margin-bottom: 2.5rem;
  }

  .profile-section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #004D73;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #E8F4F8;
    display: flex;
    align-items: center;
    gap: 0.8rem;
  }

  .profile-section-title i {
    color: #0088AA;
    font-size: 1.3rem;
  }

  .profile-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .profile-form-group {
    display: flex;
    flex-direction: column;
  }

  .profile-form-group label {
    font-size: 1rem;
    font-weight: 600;
    color: #004D73;
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .profile-form-group label i {
    color: #0088AA;
    font-size: 0.95rem;
  }

  .profile-form-group input {
    padding: 1rem;
    border: 2px solid #E8F4F8;
    border-radius: 6px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f9fafb;
    font-family: 'Roboto', sans-serif;
  }

  .profile-form-group input:focus {
    outline: none;
    border-color: #0088AA;
    background: white;
    box-shadow: 0 0 0 3px rgba(0, 136, 170, 0.1);
  }

  .profile-form-group input::placeholder {
    color: #999;
  }

  .profile-form-group small.error {
    color: #E57373;
    font-size: 0.9rem;
    margin-top: 0.5rem;
    font-weight: 500;
  }

  .profile-form-full {
    grid-column: 1 / -1;
  }

  .profile-form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 2px solid #E8F4F8;
  }

  .btn-save {
    flex: 1;
    padding: 1.1rem 2rem;
    background: linear-gradient(135deg, #0088AA 0%, #006B8F 100%);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 136, 170, 0.3);
  }

  .btn-save:active {
    transform: translateY(0);
  }

  .btn-cancel {
    flex: 1;
    padding: 1.1rem 2rem;
    background: #F5F5F5;
    color: #004D73;
    border: 2px solid #E8F4F8;
    border-radius: 6px;
    font-size: 1.05rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
  }

  .btn-cancel:hover {
    background: #E8F4F8;
    border-color: #0088AA;
    color: #0088AA;
    transform: translateY(-2px);
  }

  @media (max-width: 768px) {
    .profile-edit-header {
      flex-direction: column;
      text-align: center;
      padding: 2rem;
    }

    .profile-edit-form {
      padding: 1.5rem;
    }

    .profile-form-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .profile-form-actions {
      flex-direction: column;
    }

    .profile-edit-header h1 {
      font-size: 1.8rem;
    }
  }
</style>

<div class="profile-edit-container">
  <div class="profile-edit-card">
    <div class="profile-edit-header">
      <div class="profile-edit-header-icon">
        <i class="fas fa-user-edit"></i>
      </div>
      <div class="profile-edit-header-content">
        <h1>Edit Your Profile</h1>
        <p>Update your personal information and account settings</p>
      </div>
    </div>

    <div class="profile-edit-form">
      {{-- success flash --}}
      @if(session('success'))
        <div class="alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <!-- Personal Information Section -->
        <div class="profile-section">
          <div class="profile-section-title">
            <i class="fas fa-id-card"></i>
            Personal Information
          </div>
          <div class="profile-form-grid">
            <div class="profile-form-group">
              <label for="first_name"><i class="fas fa-user"></i>First Name</label>
              <input type="text" name="first_name" id="first_name"
                     value="{{ old('first_name', $user->first_name) }}" required>
              @error('first_name') <small class="error">{{ $message }}</small> @enderror
            </div>
            <div class="profile-form-group">
              <label for="middle_name"><i class="fas fa-user"></i>Middle Name</label>
              <input type="text" name="middle_name" id="middle_name"
                     value="{{ old('middle_name', $user->middle_name) }}" placeholder="Optional">
              @error('middle_name') <small class="error">{{ $message }}</small> @enderror
            </div>
            <div class="profile-form-group">
              <label for="last_name"><i class="fas fa-user"></i>Last Name</label>
              <input type="text" name="last_name" id="last_name"
                     value="{{ old('last_name', $user->last_name) }}" required>
              @error('last_name') <small class="error">{{ $message }}</small> @enderror
            </div>
            <div class="profile-form-group">
              <label for="username"><i class="fas fa-at"></i>Username</label>
              <input type="text" name="username" id="username"
                     value="{{ old('username', $user->username) }}" required>
              @error('username') <small class="error">{{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        <!-- Contact Information Section -->
        <div class="profile-section">
          <div class="profile-section-title">
            <i class="fas fa-envelope"></i>
            Contact Information
          </div>
          <div class="profile-form-grid">
            <div class="profile-form-group profile-form-full">
              <label for="email"><i class="fas fa-envelope"></i>Email Address</label>
              <input type="email" name="email" id="email"
                     value="{{ old('email', $user->email) }}" required>
              @error('email') <small class="error">{{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        <!-- Security Section -->
        <div class="profile-section">
          <div class="profile-section-title">
            <i class="fas fa-lock"></i>
            Security & Password
          </div>
          <div class="profile-form-grid">
            <div class="profile-form-group">
              <label for="password"><i class="fas fa-lock"></i>New Password</label>
              <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
              @error('password') <small class="error">{{ $message }}</small> @enderror
            </div>
            <div class="profile-form-group">
              <label for="password_confirmation"><i class="fas fa-lock"></i>Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password">
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="profile-form-actions">
          <button type="submit" class="btn-save">
            <i class="fas fa-check-circle"></i>
            Save Changes
          </button>
          <a href="{{ route('dashboard') }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
