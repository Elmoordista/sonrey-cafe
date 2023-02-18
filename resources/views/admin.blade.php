<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>TEST DEV - Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="icon" href="{{ url('adminlogo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ mix('css/app.css', config('app.manifest_path')) }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css', config('app.manifest_path')) }}" rel="stylesheet"> --}}
</head>

<body>
    <div id="app">
        <!-- test -->
        <!-- <adminhome></adminhome> -->
    </div>
    <script src="{{ mix('js/app.js', config('app.manifest_path')) }}"></script>
    <script type="text/javascript">
    const cl = console.log
    </script>
</body>

</html>