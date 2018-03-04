
@extends('layouts.master')

@section('content')

<h2>Add a Beverage</h2>

<form method="POST" class="input-form" action="/beverages" >

  @if($beverage->id)
    <p>patch!</p>
    @method('PATCH')
    <input type="hidden" value="{{ $beverage->id }}" name="id" id="id">
  @endif

  @csrf

  <select  value="{{ $beverage->category }}" name="category" id="category">
    <option value="Beer">Beer</option>
    <option value="Wine">Wine</option>
    <option value="Liquor">Liquor</option>
    <option value="Cocktail">Cocktail</option>
  </select>

  <div>
    <label for="name">Name</label>
    <input
      type="text"
      name="name"
      id="name"
      placeholder="Name of Drink"
      value="{{ $beverage->name }}">
  </div>

  <div>
    <label for="size">Size</label>
    <input value="{{ $beverage->size }}" type="text" name="size" id="size" placeholder="Ounces">
  </div>

  <div>
    <label for="strength">Strength</label>
    <input  value="{{ $beverage->strength }}" type="text" name="strength" id="strength" placeholder="% alcohol">
  </div>

  <button type="submit" class="submit-button">Submit</button>
</form>

@include('partials.errors')

@endsection