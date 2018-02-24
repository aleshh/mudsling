
@extends('layouts.master')

@section('content')

<h2>Add a Beverage</h2>

<form method="POST" action="/beverages" >

  @csrf

  <div class="input-form">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Type of Drink">
  </div>

  <div class="input-form">
    <label for="category">Category</label>
    <input type="text" name="category" id="category" placeholder="Beer/Wine/Whiskey/etc.">
  </div>

  <div class="input-form">
    <label for="size">Size</label>
    <input type="text" name="size" id="size" placeholder="Ounces">
  </div>

  <div class="input-form">
    <label for="strength">Strength</label>
    <input type="text" name="strength" id="strength" placeholder="% alcohol">
  </div>

  <button type="submit" class="submit-button">Submit</button>
</form>

@include('layouts.errors')

@endsection