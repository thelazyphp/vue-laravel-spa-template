<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('agency-parser/css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <div id="app"></div>

        <!-- Scripts -->
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('JANDEX_API_KEY') }}"></script>
        <script src="{{ asset('agency-parser/js/main.js') }}"></script>
    </body>
</html>
