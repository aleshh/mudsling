
@extends('layouts.master')

@section('content')

  <h2>History</h2>

  @foreach ($days as $day => $servings)

    <!-- <h3>{{ $day }}</h3> -->

    @foreach ($servings as $serving)

      @if ($loop->first)
      <div class="history-graph-outer" >
        <div class="history-graph-inner" style="
          width: {{ $serving['percent'] / $maxPercent * 100 }}%;
          @if ($serving['percent'] > 100)
            background-color: #d00;
          @else
            background-color: green;
          @endif
          @if(!$maxConsumptionSet)
            background-color: #666;
          @endif
        ">
          &nbsp;
        </div>
      </div>
        <strong>{{ $day }}:</strong>
        {{ $serving['drinks'] }}
          @if($serving['drinks'] == 1)
            drink
          @else
            drinks
          @endif
           &middot;
        {{ $serving['alcohol'] }}&nbsp;oz.&nbsp;alcohol
        @if($maxConsumptionSet)
        &middot; {{ $serving['percent']}}%&nbsp;of&nbsp;max.&nbsp;goal.
        @endif
        <br><br>
        <div class="details" style="display: unset" >
      @else
        <!-- <div>
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
        </div> -->
      @endif {{-- loop->first --}}

    @endforeach {{-- serving --}}
    </div> {{-- /.details --}}
  @endforeach {{-- day --}}

  @if(!$maxConsumptionSet)
    <p>
      You can <a href="/account">set a maximum daily target</a>.
    </p>
  @endif

@endsection