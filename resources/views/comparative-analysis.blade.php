@extends('layouts.app')
@section('title', 'Comparative Analysis')

@section('content')
  <div class="card overview" style="border-left: 5px solid #0088AA;">
    <h3 style="color: #004D73;"><i class="fas fa-balance-scale" style="color: #0088AA; margin-right: 0.5rem;"></i>Manual vs AI Count Comparison</h3>
    <table class="simple-table">
      <thead><tr style="background-color: #E8F4F8; color: #004D73;"><th><i class="fas fa-image" style="color: #0088AA; margin-right: 0.3rem;"></i>Image ID</th><th><i class="fas fa-hand-paper" style="color: #0088AA; margin-right: 0.3rem;"></i>Manual</th><th><i class="fas fa-robot" style="color: #0088AA; margin-right: 0.3rem;"></i>AI</th><th><i class="fas fa-code-branch" style="color: #004D73; margin-right: 0.3rem;"></i>Difference</th></tr></thead>
      <tbody>
        @forelse($comparisons as $c)
          <tr>
            <td>{{ $c->image_id }}</td>
            <td>{{ $c->manual }}</td>
            <td>{{ $c->ai }}</td>
            <td>{{ $c->ai - $c->manual }}</td>
          </tr>
        @empty
          <tr><td colspan="4">No data found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
