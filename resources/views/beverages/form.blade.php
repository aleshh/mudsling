
@extends('layouts.master')

@section('content')

@if($beverage->id)
  <h2>Edit Beverage</h2>
@else
  <h2>What ya drinking?</h2>
@endif

<form method="POST" class="input-form" action="/beverages" >

  @if($beverage->id)
    @method('PATCH')
    <input type="hidden" value="{{ $beverage->id }}" name="id" id="id">
  @endif

  @csrf

  <select name="category" id="category">

    @foreach(['Beer', 'Wine', 'Liquor', 'Cocktail'] as $c)
      <option value="{{ $c }}"
        @if(old('category', $beverage->category) == $c)
          selected="selected"
        @endif
      >{{ $c }}</option>
    @endforeach
  </select>

  <div>
    <label for="name">Name</label>
    <input
      type="text"
      name="name"
      id="name"
      placeholder="Name of Drink"
      value="{{ old('name', $beverage->name) }}">
  </div>

  <div>
    <label for="size">Size</label>
    <input value="@php
        // this will show the placeholder if value is 0
        $val = old('size', $beverage->size) + 0;
        if ($val) echo($val);
      @endphp" type="text" name="size" id="size" placeholder="Ounces">
  </div>

  <div>
    <label for="strength">Strength</label>
    <input  value="@php
        // this will show the placeholder if value is 0
        $val = old('strength', $beverage->strength) + 0;
        if ($val) echo($val);
      @endphp" type="text" name="strength" id="strength" placeholder="% alcohol">
  </div>

@if($beverage->id)
  <button type="submit" class="btn">Save</button>
@else
  <button type="submit" name="action" value="saveAndDrink" class="btn">Save and Drink one!</button>
  <button type="submit" name="action" value="saveDontDrink" class="btn btn-small btn-secondary">Just save for later</button>
@endif

</form>

@include('partials.errors')

@endsection