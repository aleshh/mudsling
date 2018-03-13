
@extends('layouts.master')

@section('content')

@php

  use Carbon\Carbon;

  $clientTime = new Carbon($_COOKIE['clientTime']);
  $clientOffset = $clientTime->timezone->getName();
  $serverTime = new Carbon();
  $serverTime->setTimezone($clientOffset);

@endphp

<p>
  Now: {{ $serverTime->format('g:i a, D., M. j, Y') }}
</p>

  @foreach ($days as $day => $servings)

    <h3 class="border-bottom">{{ $day }}</h3>

    @foreach ($servings as $serving)
      <div>
        <h4>
          {{ $serving->beverage->name }}
        </h4>
        <p>
          @if($serving->local_time)
            At {{ \Carbon\Carbon::parse($serving->local_time)->format('g:i a') }}
          @else
            Time n/a
          @endif
        </p>
      </div>

    @endforeach
  @endforeach

@endsection