<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <title>Boîte mail</title>
    </head>
    <body class="parallax">

    
        
            <div id="app" class="pt-5">
                
                @yield('contenu')
                
            </div>
 
    <script src="/js/app.js"></script>
    </body>
</html>