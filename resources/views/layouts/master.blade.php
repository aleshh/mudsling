<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mudsling</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="/css/styles.css">

    <script type="text/javascript">
    	// prevents links from apps from oppening in mobile safari
			// this javascript must be the first script in your <head>
      // https://gist.github.com/kylebarrow/1042026
      (function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(chref=d.href).replace(e.href,"").indexOf("#")&&(!/^[a-z\+\.\-]+:/i.test(chref)||chref.indexOf(e.protocol+"//"+e.host)===0)&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone");
    </script>
</head>
<body>
  <div class="cragle"></div>
  <header>
    <div class="container">
      <!-- <h1><a href="/home">Cannonball</a></h1> -->

      <nav>
        <div class="cannon">
            <a href="/">
            <img src="/images/pint-glass-transparent.png">
          </a>
          </div>
        @auth
          <a href="/drink">Drink!</a>
          <a href="/servings">History</a>
          <!-- <a href="/about">About</a> -->
          <!-- <a href="{{ route('logout') }}">Logout</a> -->
          <a href="/account">Account</a>
        @else
          <a href="{{ route('register') }}">Register</a>
          <a href="{{ route('login') }}">Login</a>
        @endauth
      </nav>
    </div>
  </header>

  <main>
    <div class="container">
      @yield('content')
    </div>
  </main>

  @auth
    @include('partials.servings-status')
  @endauth

  <script>
    document.cookie = "clientTime=" + new Date() + ";max-age=31536000";
  </script>

</body>
</html>