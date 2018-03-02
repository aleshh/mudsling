<div class="border-bottom">
  <h4>
    {{ $serving->beverage->name }}
  </h4>
  <p>
    {{ $serving->created_at->diffForHumans() }}
  </p>
</div>