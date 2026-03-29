@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<style>
    .ep-page {
        padding: 1.75rem 2rem;
        background: #f0f4f8;
        min-height: 100vh;
    }

    .dashboard-content { display: block !important; padding: 0 !important; }

    /* ── Banner ── */
    .ep-banner {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 60%, #4DB8D5 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 32px rgba(0,77,115,0.2);
        position: relative;
        overflow: hidden;
    }

    .ep-banner::after {
        content: '';
        position: absolute;
        right: -50px; top: -50px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    .ep-banner-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        position: relative;
        z-index: 1;
    }

    .ep-banner-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        border: 2px solid rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .ep-banner-text h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.2rem 0;
    }

    .ep-banner-text p {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.78);
        margin: 0;
    }

    .ep-banner-icon {
        font-size: 3rem;
        color: rgba(255,255,255,0.12);
        position: relative;
        z-index: 1;
    }

    /* ── Layout ── */
    .ep-layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    /* ── Sidebar card ── */
    .ep-sidebar {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        border: 1px solid rgba(0,136,170,0.08);
        overflow: hidden;
    }

    .ep-sidebar-head {
        background: linear-gradient(135deg, #004D73, #0088AA);
        padding: 1.5rem 1rem;
        text-align: center;
    }

    .ep-sidebar-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 700;
        color: #fff;
        margin: 0 auto 0.75rem;
    }

    .ep-sidebar-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.2rem;
    }

    .ep-sidebar-email {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.7);
        margin: 0;
        word-break: break-all;
    }

    .ep-sidebar-nav {
        padding: 0.75rem;
    }

    .ep-nav-item {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.7rem 0.85rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #004D73;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .ep-nav-item:hover, .ep-nav-item.active {
        background: #e8f4f8;
        border-color: rgba(0,136,170,0.15);
    }

    .ep-nav-item.active { color: #0088AA; }

    .ep-nav-item i {
        width: 16px;
        text-align: center;
        color: #0088AA;
        font-size: 0.85rem;
    }

    /* ── Main form card ── */
    .ep-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,77,115,0.07);
        border: 1px solid rgba(0,136,170,0.08);
        overflow: hidden;
    }

    .ep-card-head {
        background: linear-gradient(135deg, #004D73 0%, #0088AA 100%);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #fff;
    }

    .ep-card-head i { font-size: 1rem; }

    .ep-card-head h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .ep-card-body { padding: 1.75rem; }

    /* ── Flash ── */
    .ep-alert {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.9rem 1.1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.88rem;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .ep-alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #4caf50;
    }

    /* ── Section ── */
    .ep-section {
        margin-bottom: 1.75rem;
    }

    .ep-section-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid #f0f4f8;
    }

    .ep-section-title i { color: #0088AA; font-size: 0.8rem; }

    /* ── Form grid ── */
    .ep-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.1rem;
    }

    .ep-grid-full { grid-column: 1 / -1; }

    /* ── Field ── */
    .ep-field label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #004D73;
        margin-bottom: 0.45rem;
        letter-spacing: 0.2px;
    }

    .ep-input-wrap {
        position: relative;
    }

    .ep-input-wrap i {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 0.85rem;
        pointer-events: none;
        transition: color 0.2s;
    }

    .ep-field input {
        width: 100%;
        padding: 0.7rem 0.9rem 0.7rem 2.4rem;
        border: 2px solid #e8f4f8;
        border-radius: 9px;
        font-size: 0.88rem;
        font-family: 'Roboto', sans-serif;
        color: #1a1a1a;
        background: #fafcfd;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .ep-field input:focus {
        outline: none;
        border-color: #0088AA;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,136,170,0.1);
    }

    .ep-field input:focus + i,
    .ep-input-wrap:focus-within i {
        color: #0088AA;
    }

    .ep-field input::placeholder { color: #bbb; }

    .ep-field .ep-error {
        display: block;
        font-size: 0.75rem;
        color: #e53935;
        margin-top: 0.35rem;
        font-weight: 500;
    }

    /* ── Divider ── */
    .ep-divider {
        height: 1px;
        background: #f0f4f8;
        margin: 1.75rem 0;
    }

    /* ── Actions ── */
    .ep-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.9rem;
        padding-top: 0.5rem;
    }

    .ep-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.4rem;
        border-radius: 9px;
        border: 2px solid #e8f4f8;
        background: #fff;
        color: #004D73;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .ep-btn-back:hover {
        border-color: #0088AA;
        background: #e8f4f8;
    }

    .ep-btn-save {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.6rem;
        border-radius: 9px;
        border: none;
        background: linear-gradient(135deg, #004D73, #0088AA);
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 3px 10px rgba(0,136,170,0.3);
        letter-spacing: 0.2px;
    }

    .ep-btn-save:hover {
        background: linear-gradient(135deg, #003D5C, #006B8F);
        box-shadow: 0 5px 16px rgba(0,136,170,0.4);
        transform: translateY(-1px);
    }

    @media (max-width: 800px) {
        .ep-page { padding: 1rem; }
        .ep-layout { grid-template-columns: 1fr; }
        .ep-grid { grid-template-columns: 1fr; }
        .ep-grid-full { grid-column: 1; }
    }
</style>

<div class="ep-page">

    {{-- Banner --}}
    <div class="ep-banner">
        <div class="ep-banner-left">
            <div class="ep-banner-avatar">
                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
            </div>
            <div class="ep-banner-text">
                <h1>Edit Profile</h1>
                <p>Manage your account information and security settings</p>
            </div>
        </div>
        <div class="ep-banner-icon"><i class="fas fa-user-edit"></i></div>
    </div>

    <div class="ep-layout">

        {{-- Sidebar --}}
        <div class="ep-sidebar">
            <div class="ep-sidebar-head">
                <div class="ep-sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                </div>
                <p class="ep-sidebar-name">{{ $user->first_name }} {{ $user->last_name }}</p>
                <p class="ep-sidebar-email">{{ $user->email }}</p>
            </div>
            <div class="ep-sidebar-nav">
                <div class="ep-nav-item active">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </div>
                <a href="{{ route('dashboard') }}" class="ep-nav-item" style="text-decoration:none;">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
        </div>

        {{-- Form card --}}
        <div class="ep-card">
            <div class="ep-card-head">
                <i class="fas fa-sliders-h"></i>
                <h2>Account Settings</h2>
            </div>
            <div class="ep-card-body">

                @if(session('success'))
                    <div class="ep-alert ep-alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    {{-- Personal Info --}}
                    <div class="ep-section">
                        <div class="ep-section-title">
                            <i class="fas fa-id-card"></i> Personal Information
                        </div>
                        <div class="ep-grid">
                            <div class="ep-field">
                                <label for="first_name">First Name</label>
                                <div class="ep-input-wrap">
                                    <input type="text" name="first_name" id="first_name"
                                           value="{{ old('first_name', $user->first_name) }}"
                                           placeholder="First name" required>
                                    <i class="fas fa-user"></i>
                                </div>
                                @error('first_name')<span class="ep-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ep-field">
                                <label for="middle_name">Middle Name <span style="font-weight:400;color:#bbb;">(optional)</span></label>
                                <div class="ep-input-wrap">
                                    <input type="text" name="middle_name" id="middle_name"
                                           value="{{ old('middle_name', $user->middle_name) }}"
                                           placeholder="Middle name">
                                    <i class="fas fa-user"></i>
                                </div>
                                @error('middle_name')<span class="ep-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ep-field">
                                <label for="last_name">Last Name</label>
                                <div class="ep-input-wrap">
                                    <input type="text" name="last_name" id="last_name"
                                           value="{{ old('last_name', $user->last_name) }}"
                                           placeholder="Last name" required>
                                    <i class="fas fa-user"></i>
                                </div>
                                @error('last_name')<span class="ep-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ep-field">
                                <label for="username">Username</label>
                                <div class="ep-input-wrap">
                                    <input type="text" name="username" id="username"
                                           value="{{ old('username', $user->username) }}"
                                           placeholder="Username" required>
                                    <i class="fas fa-at"></i>
                                </div>
                                @error('username')<span class="ep-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="ep-divider"></div>

                    {{-- Contact --}}
                    <div class="ep-section">
                        <div class="ep-section-title">
                            <i class="fas fa-envelope"></i> Contact Information
                        </div>
                        <div class="ep-grid">
                            <div class="ep-field ep-grid-full">
                                <label for="email">Email Address</label>
                                <div class="ep-input-wrap">
                                    <input type="email" name="email" id="email"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Email address" required>
                                    <i class="fas fa-envelope"></i>
                                </div>
                                @error('email')<span class="ep-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="ep-divider"></div>

                    {{-- Security --}}
                    <div class="ep-section" style="margin-bottom:0;">
                        <div class="ep-section-title">
                            <i class="fas fa-lock"></i> Security
                        </div>
                        <div class="ep-grid">
                            <div class="ep-field">
                                <label for="password">New Password</label>
                                <div class="ep-input-wrap">
                                    <input type="password" name="password" id="password"
                                           placeholder="Leave blank to keep current">
                                    <i class="fas fa-key"></i>
                                </div>
                                @error('password')<span class="ep-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ep-field">
                                <label for="password_confirmation">Confirm Password</label>
                                <div class="ep-input-wrap">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           placeholder="Confirm new password">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="ep-actions" style="margin-top:1.75rem;">
                        <a href="{{ route('dashboard') }}" class="ep-btn-back">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="ep-btn-save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
