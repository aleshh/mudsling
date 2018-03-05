
@extends('layouts.master')

@section('content')

<h2>Add a Beverage</h2>

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
    <input value="{{ old('size', $beverage->size) + 0 }}" type="text" name="size" id="size" placeholder="Ounces">
  </div>

  <div>
    <label for="strength">Strength</label>
    <input  value="{{ old('strength', $beverage->strength) + 0 }}" type="text" name="strength" id="strength" placeholder="% alcohol">
  </div>

  <button type="submit" class="submit-button">Submit</button>
</form>

@include('partials.errors')

@endsection