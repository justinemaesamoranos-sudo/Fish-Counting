@extends('layouts.app')
@section('title','My Profile')

@section('content')
<div class="card">
  <h3>My Profile</h3>
  <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</p>
  <p><strong>Username:</strong> {{ $user->username }}</p>
  <p><strong>Email:</strong> {{ $user->email }}</p>
</div>
@endsection
