<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{config('app.name')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Round|Material+Icons+Outlined" rel="stylesheet">
        <link rel="stylesheet" href="/css/app.css">
        <link href="/images/icon.jpg" rel="shortcut icon">

        <style>
            .v-application,body {
                font-family: 'Poppins', sans-serif !important;
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="app">
            <v-app style="display:none">
                @auth()
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
                <v-navigation-drawer app v-model="drawer">
                    @include('layouts.navbars.appsidebar')
                </v-navigation-drawer>
                <v-app-bar app>
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
                    <strong>{{config('app.name')}}</strong>
                </v-app-bar>
                <v-main>
                    <v-container fluid>
                    <router-view />
                        <input type="hidden" id="usuario_id" data-readonly="{{(\Auth::check())?auth()->user()->id :'' }}">
                    </v-container>
                </v-main>
            </v-app>
        </div>
    </body>
    <script src="{{ mix('js/app.js') }}"></script>

</html>
