<div class="border-bottom">
  <h4>
    {{ $serving->beverage->name }}
  </h4>
  <p>
    At {{ \Carbon\Carbon::parse($serving->local_time)->format('g:i a') }}
  </p>
</div>