<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ URL::asset('public/css/navbar.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('public/css/display.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('public/css/easy-autocomplete.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('public/css/easy-autocomplete.themes.min.css') }}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <title>{{ config('app.name', 'Stat App') }}</title>
    @yield('styles')
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
    @include('inc.navbar')
    <main class="page-content">
      <div class="info-container">
        @yield('content')
      </div>

    </main>
    <!-- page-content" -->
  </div>
  <script type="text/javascript" src="{{ URL::asset('public/js/jquery.easy-autocomplete.min.js') }}"></script>
  <script type="text/javascript" src="{{ URL::asset('public/js/navbar.js') }}"></script>
  @yield('scripts')

</body>
</html>
