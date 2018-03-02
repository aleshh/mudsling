@extends('layouts.master')

@section('content')

    <h2>Dashboard</h2>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        You are logged in!
    </div>
@endsection
