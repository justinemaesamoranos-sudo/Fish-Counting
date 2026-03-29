@extends('layouts.app') {{-- or your dashboard layout --}}

@section('title', 'Live Camera')

@section('content')
<div class="card overview">
  <h3>Live Camera Feed</h3>
  <div class="camera-container">
    <img src="{{ url('/camera-feed') }}" alt="Live Camera Feed">
  </div>
</div>
@endsection
