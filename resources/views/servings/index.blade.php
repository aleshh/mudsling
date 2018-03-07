
@extends('layouts.master')

@section('content')

<p>
  Now: {{ now()->format('g:i a, D., M. j, Y') }}
</p>

  @if($servings->count())

    <h2 class="border-bottom">Today</h2>

    @foreach ($servings as $serving)

      @include('partials.serving-listing', $serving)

    @endforeach

  @endif

  @if($oldServings->count())

    <h2 class="border-bottom" >Before Today</h2>

    @foreach ($oldServings as $serving)

      @include('partials.serving-listing', $serving)

    @endforeach

  @endif

@endsection