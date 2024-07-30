<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet' />

    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Round|Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <style>
            .v-application,body {
                font-family: 'Poppins', sans-serif !important;
            }
        </style>
    <title>{{config('app.name')}}</title>
</head>
<body>
<div id="app">
<v-app style="display:none">
        <router-view />
    </v-app>
</div>
</body>
<script src="{{ mix('/js/app.js') }}"></script>
</html>
</html>