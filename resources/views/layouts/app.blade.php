<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cannonball</title>

    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
  <div class="cragle"></div>
  <header>
    <div class="container">
      <!-- <h1><a href="/home">Cannonball</a></h1> -->

      <nav>
        @auth
          <a href="/drink">Drink!</a>
          <a href="/servings">Today</a>
          <div class="cannon">C</div>
          <a href="/beverages">Beverages</a>
          <!-- <a href="/about">About</a> -->
          <!-- <a href="{{ route('logout') }}">Logout</a> -->
          <a href="/account">Account</a>
        @else
          <a href="{{ route('login') }}">Login</a>
          <div class="cannon">C</div>
          <a href="{{ route('register') }}">Register</a>
        @endauth
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      @yield('content')
    </div>
  </main>

  <div class="consumption-graph">
    <div class="consumption-graph-today">
    </div>
    <div class="consumption-graph-message">
      @include('layouts.serving-status')
    </div>
  </div>

</body>
</html>