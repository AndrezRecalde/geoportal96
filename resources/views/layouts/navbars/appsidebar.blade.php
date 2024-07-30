<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main" style="z-index:10;margin-top:-27px">
    <div class="" style="width: 100%;">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <v-list-item-group style="width: 100%;">
                <v-list-group style="width: 100%;" :value="true" color="#3949AB" prepend-icon="bubble_chart">
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title title="Proyectos" style="font-size:14px"><strong>Proyectos</strong></v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item class="{{ Request::is('dashboard/proyectos') ? 'v-list-item--active' : '' }} " link href="/dashboard/proyectos">
                        <v-list-item-title title="Proyectos" style="font-size:11px">
                            <v-icon right class="mx-4 ">bubble_chart</v-icon><strong> Proyectos</strong>
                        </v-list-item-title>
                    </v-list-item>
                </v-list-group>
                <hr class="my-1">
                <v-list-item-group>
                    <v-list-item link @click="logout">
                        <v-icon left class="mr-5">logout</v-icon>
                        <strong style="font-size:14px">Cerrar sesión</strong>
                    </v-list-item>
                </v-list-item-group>
            </v-list-item-group>

        </div>
    </div>
</nav>