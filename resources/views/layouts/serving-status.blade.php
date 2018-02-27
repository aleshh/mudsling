@if($todayCount > 0)
  <div class="servings-status">
  {{ $todayCount }} drinks so far today, {{ $todayAlcohol }} oz. of alcohol
  </div>
@else
  <div class="servings-status">
    No drinks yet today
  </div>
@endif