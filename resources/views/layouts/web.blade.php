<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet' />

    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Round|Material+Icons+Outlined" rel="stylesheet">
    <link href="/images/icon.jpg" rel="shortcut icon">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{config('app.name')}}</title>
</head>

<body>
    <div id="app">
        <v-app style="display:none">
            <router-view />
        </v-app>
    </div>
</body>
<script src="{{ mix('/js/appweb.js') }}"></script>
<script src='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js'></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
</html>