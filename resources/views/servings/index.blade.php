
@extends('layouts.master')

@section('content')

  <h2>Today</h2>

  @foreach ($servings as $serving)

    <div class="border-bottom">
      <h4>
        {{ $serving->beverage->name }}
      </h4>
      <p>
        {{ $serving->created_at->diffForHumans() }}
      </p>
    </div>

  @endforeach


  @if($oldServings->count())

    <h2 class="border-bottom" >Older</h2>

    @foreach ($oldServings as $serving)

      <div class="border-bottom">
        <h4>
          {{ $serving->beverage->name }}
        </h4>
        <p>
          {{ $serving->created_at->diffForHumans() }}
        </p>
      </div>

    @endforeach

  @endif

@endsection