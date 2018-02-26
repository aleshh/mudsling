@extends('layouts.app')

@section('content')

    <h2>Welcome</h2>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <p>You'll have to <a href="/login">login</a> to continue.</p>

@endsection
