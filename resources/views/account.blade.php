
@extends('layouts.master')

@section('content')

  <h2>{{ \Auth::user()->name }}</h2>

  <form method="POST" class="input-form border-bottom" action="/account" >

    @method('PATCH')
    @csrf

    <label for="maximumConsumption">Daily Maximum Alcohol Target (oz.)</label>
    <input
      value="{{ \Auth::user()->maximumConsumption }}"
      type="text"
      name="maximumConsumption"
      id="maximumConsumption"
      placeholder="in Oz">

    <p style="margin-bottom: 0">
      For example, three 12oz. beers with 5% alcohol:<br>
      3 × 12 × .05 = 1.8
    </p>

    <!-- <label >Local Time</label>
    <input value="{{ $hour }}"   type="text" name="userHour"   style="text-align:center;display:inline-block;width: 50px;"> :
    <input value="{{ $minute }}" type="text" name="userMinute" style="text-align:center;display:inline-block;width: 50px;">
    {{ $amPm }}

    <input value="{{ $hour }}"   type="hidden" name="originalHour"   >
    <input value="{{ $minute }}" type="hidden" name="originalMinute" >
    <input value="{{ $amPm }}" type="hidden" name="originalAmPm" >
    <br> -->

    <button type="submit" class="submit-button">Submit</button>
  </form>

  <div class="border-bottom">
    <h2>Beverages</h2>
    <p>
      <a href="/beverages">View, edit, or delete your beverages</a>
    </p>
  </div>

  <h2>Logged in</h2>
  <p>
    <a class="submit-button" href="/logout">Logout</a>
  </p>

@endsection