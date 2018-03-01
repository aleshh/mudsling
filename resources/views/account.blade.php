
@extends('layouts.app')

@section('content')

<h2>Account</h2>

  <form method="POST" class="input-form border-bottom" action="/account" >

    @csrf

    <div>
      <label for="maximumConsumption">Daily Maximum Alcohol Target</label>
      <input value="{{ \Auth::user()->maximumConsumption }}" type="text" name="maximumConsumption" id="maximumConsumption" placeholder="in Oz">
    </div>

    <button type="submit" class="submit-button">Submit</button>
  </form>

  <h3>Logout</h3>
  <p>
    <a class="submit-button" href="/logout">Logout</a>
  </p>

@endsection