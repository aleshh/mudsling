<div class="consumption-graph">
  <div class="consumption-graph-today" style="width: {{ $todayPercentage }}%">
  </div>
  <div class="consumption-graph-message">

    @if($todayCount > 0)
      {{ $todayCount }} drinks so far today, {{ $todayAlcohol }} oz. alcohol
    @else
        No drinks yet today
    @endif

  </div>
</div>