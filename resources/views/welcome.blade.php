@extends('layouts.master')

@section('content')

    <h2>Welcome to Mudsling</h2>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <p>
        Add beverages and track when you've consumed them to log your daily alcohol intake. Cheers!
    </p>
    <p>
        There's a couple of nice features, like a graph that shows your consupmption relative to a daily goal you can set. Stuff gets saved to a database so you can log in from any machine.
    </p>

    <p>
        <a href="/register" class="btn">Register</a>
        <a href="/login" class="btn btn-secondary">Login</a>
    </p>

    <h3>Warnings</h3>
    <p>
        This is a toy app I'm messing around with to learn Laravel, so it's just for fun and/or alpha testing--your data is likely to get deleted sooner or later.
    </p>
    <p>
        There's lots of stuff missing; check out the <a href="https://github.com/aleshh/mudsling">GitHub repo</a> to see what's on the Todo list.
    </p>
    <p>
        Ugly on purpose.
    </p>

@endsection
