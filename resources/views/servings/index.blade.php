
@extends('layouts.master')

@section('content')

    <form class="confirm-submit" method="POST" action="servings" >
      @method('DELETE')
      @csrf

      <button type="submit" class="btn btn-small btn-delete" >Undo last drink</button>
    </form>

  <h2>History</h2>

  @foreach ($days as $day => $servings)

    <div class="day border-top" >
      @foreach ($servings as $serving)

        @if ($loop->first)

          <div class="show-hide">
            <i data-feather="chevron-down" class="shadow"></i>
            <i data-feather="chevron-down"></i>
          </div>

          <strong>
            {{ App\Serving::nameOfDay($day) }}
          </strong>

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

          <div class="details" style="display: none" >

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

        @else {{-- $serving->first (i.e., end of header, start of servings) --}}
          <div>
          <br>
            <strong>
              {{ $serving->beverage->name }}
            </strong>
            <br>
            At {{ \Carbon\Carbon::parse($serving->local_time)->format('g:i a') }}
          </div>
        @endif {{-- loop->first --}}

      @endforeach {{-- serving --}}
      </div> {{-- /.details --}}
    </div> {{-- /.day --}}
  @endforeach {{-- day --}}

  @if(!$maxConsumptionSet)
    <p>
      You can <a href="/account">set a maximum daily target</a>.
    </p>
  @endif

  <script>
    $(function() {
      $('.show-hide').click(function() {
        $(this).parent().find('.details').slideToggle();

        if($(this).hasClass('rotate')) {
            $(this).removeClass('rotate').addClass('revert');
        } else {
            $(this).addClass('rotate').removeClass('revert');
        }
      })
    });
  </script>

@endsection