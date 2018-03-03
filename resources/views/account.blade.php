
@extends('layouts.master')

@section('content')

<h2>{{ \Auth::user()->name }}</h2>

  <form method="POST" class="input-form border-bottom" action="/account" >

    @method('PATCH')

    @csrf

    <label for="maximumConsumption">Daily Maximum Alcohol Target</label>
    <input value="{{ \Auth::user()->maximumConsumption }}" type="text" name="maximumConsumption" id="maximumConsumption" placeholder="in Oz">

    <button type="submit" class="submit-button">Submit</button>
  </form>

  <h3>Logout</h3>
  <p>
    <a class="submit-button" href="/logout">Logout</a>
  </p>

@endsection