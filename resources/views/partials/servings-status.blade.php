<div class="consumption-graph
  @if(\Auth::user()->maximumConsumption == 0)
    consumption-graph-no-max-consumption
  @endif
">
  <div class="consumption-graph-today
    @if($todayPercentage > 100)
      consumption-graph-over-limit
    @endif
  " style="width: {{ $todayPercentage }}%">
  </div>
  <div class="consumption-graph-message">

    @if($todayCount > 0)
      Today: {{ $todayCount }}
        @if ($todayCount == 1)
          drink,
        @else
          drinks,
        @endif
        {{ $todayAlcohol }} oz. alcohol
    @else
        No drinks yet today
    @endif

  </div>
</div>