
@extends('layouts.master')

@section('content')

    <h2>Beverages</h2>

    @foreach ($beverages as $beverage)
        <div class="border-bottom">
            <a href="beverages/{{$beverage->id}}">
                <h3>{{ $beverage->name }}</h3>
                {{$beverage->size + 0 }} oz., {{$beverage->strength }}%
                ({{ round($beverage->size * $beverage->strength) / 100 }} oz. alcohol)
            </a>
            <br>

            <a class="small-button" href="beverages/{{$beverage->id}}/edit">Edit</a>
            <form class="confirm-submit" method="POST" style="display: inline" action="beverages/{{$beverage->id}}" >
                @method('DELETE')
                @csrf

                <button type="submit" class="small-button delete-button" >Delete</button>
            </form>
        </div>
    @endforeach

    <br>

    <p>
        <a class="submit-button" href="beverages/create">Add a beverage</a>
</p>

@endsection