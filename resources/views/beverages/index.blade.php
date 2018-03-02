
@extends('layouts.master')

@section('content')

    <h2>Beverages</h2>

    @foreach ($beverages as $beverage)
        <a href="beverages/{{$beverage->id}}">
            <div class="border-bottom">
                <h3>{{ $beverage->name }}</h3>
                {{$beverage->size }} oz., {{$beverage->strength }}%
                ({{ $beverage->size * $beverage->strength / 100}} oz. alcohol)
            </div>
        </a>
    @endforeach

    <br>

    <p>
        <a class="submit-button" href="beverages/create">Add a beverage</a>
</p>

@endsection