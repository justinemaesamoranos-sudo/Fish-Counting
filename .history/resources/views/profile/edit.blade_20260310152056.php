@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="card">
  <h3>Edit Profile</h3>

  {{-- success flash --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}">
    @csrf

    <div class="form-grid">
      <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name"
               value="{{ old('first_name', $user->first_name) }}" required>
        @error('first_name') <small class="error">{{ $message }}</small> @enderror
      </div>
      <div class="form-group">
        <label for="middle_name">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name"
               value="{{ old('middle_name', $user->middle_name) }}">
        @error('middle_name') <small class="error">{{ $message }}</small> @enderror
      </div>
      <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name"
               value="{{ old('last_name', $user->last_name) }}" required>
        @error('last_name') <small class="error">{{ $message }}</small> @enderror
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username"
               value="{{ old('username', $user->username) }}" required>
        @error('username') <small class="error">{{ $message }}</small> @enderror
      </div>
    </div>

    <div class="form-group">
      <label for="email">Email Address</label>
      <input type="email" name="email" id="email"
             value="{{ old('email', $user->email) }}" required>
      @error('email') <small class="error">{{ $message }}</small> @enderror
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password">
        @error('password') <small class="error">{{ $message }}</small> @enderror
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation">
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn-primary">Save Changes</button>
      <a href="{{ route('dashboard') }}" class="btn-secondary">← Back to Dashboard</a>
    </div>
  </form>
</div>
@endsection
