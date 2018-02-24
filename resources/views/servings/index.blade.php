
@extends('layouts.master ')

@section('content')

  <h2>Today</h2>

  @foreach ($servings as $serving)

    <div class="show-serving">
      {{ $serving->created_at->diffForHumans() }}
      <br>
      <h3>
        {{ $serving->beverage->name }}
      </h3>
    </div>
    <br>

  @endforeach


  @if($oldServings->count())

    <h2>Older</h2>

    @foreach ($oldServings as $serving)

      <div class="show-serving">
        {{ $serving->created_at->diffForHumans() }}
        <br>
        <h3>
          {{ $serving->beverage->name }}
        </h3>
      </div>
      <br>
    @endforeach

  @endif

@endsection