<div class="border-bottom">
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