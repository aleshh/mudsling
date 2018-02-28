@extends('layouts.app')

@section('content')

<h2>Login</h2>

<form class="input-form" method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group row">
        <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="checkbox">
        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember">Remember Me</label>
    </div>

    <button type="submit" class="btn btn-primary">
        Login
    </button>

    <br><br>
    <p>
        <a class="btn btn-link" href="{{ route('password.request') }}">
            Forgot Your Password?
        </a>
    </p>
</form>


@endsection
