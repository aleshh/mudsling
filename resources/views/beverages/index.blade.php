
@extends('layouts.master ')

@section('content')

<h2>Beverages</h2>

<ul>
    @foreach ($beverages as $beverage)
        <li>
            <a href="beverages/{{$beverage->id}}">
                <strong>{{ $beverage->name }}</strong>
                ({{ $beverage->size * $beverage->strength / 100}} oz. of alcohol)
            </a>
        </li>
    @endforeach
</ul>

<p><a href="beverages/create">Add a beverage</a></p>

@endsection