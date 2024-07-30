<template>
  <div>
    <div v-if="gettingLocation">
      <i>Getting your location...</i>
    </div>
    <v-row>
      <v-col cols="12" style="padding-bottom: 0px">
        <v-form
          style="
            position: absolute;
            z-index: 10;
            background-color: white;
            opacity: 0.8;
            padding: 15px !important;
            margin: 10px;
            border-radius: 4px;
            width: 300px;
          "
          ref="form"
          class="pt-5 pl-3 pr-1"
          v-model="valid"
          lazy-validation
        >
          <strong>Filtros de búsqueda</strong>
          <v-select
            class="pt-3"
            outlined
            v-model="input.canton_id"
            required
            :items="itemsCanton"
            item-text="nombre"
            item-value="id"
            label="Canton"
            @change="cargarFiltroParroquias"
            dense
          ></v-select>
          <v-select
            outlined
            v-model="input.parroquia_id"
            required
            :items="itemsParroquia"
            item-text="nombre"
            item-value="id"
            label="Parroquia"
            @change="cargarFiltroRecinto"
            dense
          ></v-select>
          <v-select
            outlined
            v-model="input.recinto_id"
            required
            :items="itemsRecinto"
            item-text="nombre"
            item-value="id"
            label="Recinto"
            dense
          ></v-select>

          <v-select
            outlined
            v-model="input.obra_id"
            required
            :items="itemsObra"
            item-text="obra"
            item-value="id"
            label="Obra"
            dense
          ></v-select>

          <v-text-field
            label="Nombre del proyecto"
            v-model="input.proyecto"
            required
            outlined
            dense
          ></v-text-field>
          <v-btn
            depressed
            color="primary"
            :loading="loading"
            :disabled="loading"
            @click="buscar()"
            ><v-icon left>search</v-icon> Buscar</v-btn
          >
        </v-form>

        <v-alert
          @click="showModalTotalProyectos"
          style="
            cursor: pointer;
            position: absolute;
            z-index: 10;
            padding: 15px !important;
            margin: 10px;
            border-radius: 4px;
            width: 300px;
            opacity: 0.7;
            bottom: 15px;
            right: 5px;
          "
          dismissible
          dense
          color="success"
          border="left"
          elevation="2"
          colored-border
          icon="expand_less"
        >
          <strong>Total proyectos: </strong>{{ itemsProyectos.length }}
        </v-alert>
        <div id="map" style="width: 100wv; height: 100vh"></div>
      </v-col>
    </v-row>
    <DialogSimple>
      <template v-slot:bodycardTotalProyectos>
        <v-row class="mt-1">
          <v-col cols="12" style="display: flex">
            <div
              style="width: 200px; height: 200px"
              id="chartTotalProyectos"
            ></div>
            <div>
              <h1>
                {{
                  itemsProyectos.filter((item) => item.id_estado === 4).length
                }}/{{ itemsProyectos.length }}
              </h1>
              <br />
              <div>
                <div
                  v-for="(item, index) in chartModalTotalProyectosAgrupado"
                  :key="index"
                >
                  <strong>{{ item.name }}:&nbsp;{{ item.value }}</strong>
                </div>
              </div>
            </div>
          </v-col>
        </v-row>
      </template>
      <template v-slot:buttonsTotalProyectos>
        <v-btn @click="dialogClose('dialogSimple2')" small>Cerrar</v-btn>
      </template>
      <template v-slot:bodycardDetalle>
        <v-row class="mt-1">
          <v-col cols="12" >

            <div class="mb-5" >
              <span class="text-md-body-1" style="font-weight: bold">Descripción:  </span>
              <div class="text-md-body-1" style="display:inline">  {{
                proyecto_seleccionado.descripcion
              }}</div>
            </div>

            <v-divider></v-divider>

            <v-simple-table dense>
            <template>
              <thead>
                <tr>
                  <th class="text-left">Presupuesto</th>
                  <th class="text-left">Cantón</th>
                  <th class="text-left">Parroquia</th>
                  <th class="text-left">Recinto</th>
                  <th class="text-left">Estado</th>
                  <th class="text-left">Obra</th>

                </tr>
              </thead>
                <tbody>
                  <tr
                      :key="proyecto_seleccionado.proyecto">

                  <td v-if="proyecto_seleccionado.presupuesto" >{{ $store.state.numberWithCommas(proyecto_seleccionado.presupuesto) }}</td>
                  <td v-else>S/N</td>
                  <td>{{ proyecto_seleccionado.canton.nombre }}</td>
                  <td>{{ proyecto_seleccionado.parroquia.nombre }}</td>
                  <td>{{ proyecto_seleccionado.recinto.nombre }}</td>
                  <td>{{ proyecto_seleccionado.estado.estado }}</td>
                  <td>{{ proyecto_seleccionado.obra.obra }}</td>


                  </tr>
                </tbody>


            </template>
            </v-simple-table>



            <br />
            <strong>Galería de imágenes</strong>
            <v-divider></v-divider>
            <v-img
              class="pb-10"
              style="cursor: pointer"
              v-for="(item, index) in proyecto_seleccionado.imagen"
              :key="index"
              contain
              :src="item.image"
              @click="showImagen(item)"
            ></v-img>
          </v-col>
        </v-row>
      </template>
      <template v-slot:buttonsDetalle>
        <v-btn @click="dialogClose('dialogSimple')" small>Cerrar</v-btn>
      </template>
    </DialogSimple>
    <v-navigation-drawer
      v-model="drawer"
      absolute
      left
      :permanent="drawer"
      width="378px"
      style="z-index: 10"
    >
      <v-row class="mt-1">
        <v-col cols="12">
          <v-btn
            @click="drawer = false"
            small
            style="margin-left: 19px; margin-bottom: 10px"
            ><v-icon left>arrow_back</v-icon> Cerrar
          </v-btn>
        </v-col>
        <v-col cols="12" v-if="itemsProyectos.length > 0">
          <v-card
            v-for="(item, index) in itemsProyectos"
            :key="index"
            @click="markerOnClick(item)"
            class="my-5 mx-5"
            max-width="350"
          >
            <v-row>
              <v-col cols="12" lg="6" md="7" sm="6">
                <div style="text-align: center" class="pa-3">
                  <p>{{ item.proyecto }}</p>
                  <v-alert small color="warning" style="font-size:11px;padding:4px">{{item.estado.estado}}</v-alert>
                </div>
              </v-col>
              <v-col cols="12" lg="6" md="5" sm="6"  class="pa-0">
                <v-img class="mx-auto"
                  contain
                  max-width="130"
                  height="150"
                  :src="
                    typeof item.imagen[0] == 'undefined'
                      ? ''
                      : item.imagen[0].image
                  "
                ></v-img>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
        <v-col v-else>
          <v-alert class="pa-5 ma-3" dense text>
            No existen proyectos para la búsqueda
          </v-alert>
        </v-col>
      </v-row>
    </v-navigation-drawer>
  </div>
</template>

<script>
import DialogSimple from "@/components/components/DialogSimple";

var self;
var map;
export default {
  components: {
    DialogSimple,
  },
  data() {
    return {
      drawer: false,
      valid: false,
      loading: false,
      gettingLocation: false,
      proyecto_seleccionado: {},
      location: {
        coords: {
          latitude: "",
          longitude: "",
        },
      },
      lastlocation: {
        coords: {
          latitude: "",
          longitude: "",
        },
      },
      input: {
        canton_id: null,
        recinto_id: null,
        parroquia_id: null,
        obra_id: null,
        proyecto: null,
      },
      itemsCanton: [],
      itemsRecinto: [],
      itemsParroquia: [],
      itemsObra: [],
      itemsProyectos: [],
      arrmarkers: [],
      chartModalTotalProyectosAgrupado: [],
      urls: {
        cargarFiltros: "/cargarfiltros",
        cargarParroquias: "/cargarparroquias",
        cargarRecintos: "/cargarrecintos",
        cargarObras: "/cargarobras",
        cargarProyectos: "/cargarproyectos",
      },
      validations: {},
    };
  },
  computed: {},
  created() {
    this.cargarFiltros();
    this.cargarFiltroObra();
  },
  mounted() {
    self = this;
    setTimeout(() => {
      self.initMap();
    }, 500);
  },
  methods: {
    dialogClose(tipo) {
      this.$store.commit("closeDialog", { tipo: tipo, value: false });
    },
    cargarFiltros() {
      axios
        .post(this.urls.cargarFiltros)
        .then((response) => {
          var data = response.data;
          if (data.estado == "success") {
            /* console.log(data) */
            this.itemsCanton = data.data.cantones;
            this.itemsCanton.unshift({ id: null, nombre: "TODOS" });
            this.cargarProyectos();
          }
        })
        .catch((errors) => {});
    },
    cargarFiltroParroquias() {
      let valor = {
        canton_id: this.input.canton_id,
      };
      axios
        .post(this.urls.cargarParroquias, valor)
        .then((response) => {
          var data = response.data;
          if (data.estado == "success") {
            this.itemsParroquia = data.data.parroquias;
            this.itemsParroquia.unshift({ id: null, nombre: "TODOS" });
            this.input.parroquia_id = null;
            this.input.recinto_id = null;

          }
        })
        .catch((errors) => {});
    },
    cargarFiltroRecinto() {
      let valor = {
        parroquia_id: this.input.parroquia_id,
      };
      axios
        .post(this.urls.cargarRecintos, valor)
        .then((response) => {
          var data = response.data;
          /* console.log("data.estado", data.estado); */
          if (data.estado == "success") {
            this.itemsRecinto = data.data.recintos;
            this.itemsRecinto.unshift({ id: null, nombre: "TODOS" });
          }
        })
        .catch((errors) => {});
    },

    cargarFiltroObra() {
      axios
        .post(this.urls.cargarObras)
        .then((response) => {
          var data = response.data;
          if (data.estado == "success") {
            /* console.log(data) */
            this.itemsObra = data.data.obras;
            this.itemsObra.unshift({ id: null, obra: "TODOS" });
          }
        })
        .catch((errors) => {});
    },

    async cargarProyectos() {
      if (!this.$refs.form.validate()) return;
      await axios
        .post(this.urls.cargarProyectos, this.input)
        .then((response) => {
          var data = response.data;
          if (data.estado == "success") {
            this.itemsProyectos = data.data.proyectos;
          }
        })
        .catch((errors) => {});
    },
    buscar() {
      if (!this.$refs.form.validate()) return;
      this.loading = true;
      this.cargarProyectos().then(() => {
        this.loading = false;
        if (this.itemsProyectos.length > 0) {
          this.resetMarkers();
          this.itemsProyectos.some((item) => {
            this.setMarker(item);
          });
        }
      });
      this.drawer = true;
    },
    markerOnClick(item) {
      /* console.log(item.proyecto); */
      this.proyecto_seleccionado = item;
      this.$store.commit("openDialogSimple", {
        openDialog: true,
        bodycard: "bodycardDetalle",
        buttons: "buttonsDetalle",
        titulo: item.proyecto,
        maxwidth: "800px",
      });
    },
    setMarker(item) {
      /* console.log("item", item); */
      let color;
      switch (item.estado.id) {
        case 1:
          //1. pre contractual #FD9602
          color = '#B57E08'     /* Amarillo*/
          break;
        case 2:
          //2. por iniciar #FA3D46
          color = '#FD9602'     /* Naranja */
          break;
        case 3:
           //3. ejecución
          color = '#E62A17'     /* Rojo */
          break;
        case 4:
           //4. obra fisica terminada
          color = '#0352FA'      /* Azul */
          break;
        default:
          color = '#555555';
          break;
      }
      const marker = new mapboxgl.Marker({
        color: color,
        scale:0.7
      });
      this.arrmarkers.push(marker);
      marker
        .setLngLat([item.longitud, item.latitud]) // starting position [lng, lat]
        .addTo(map);
      marker.getElement().addEventListener("click", () => {
        self.markerOnClick(item);
      });
    },
    async initMap() {

      /* this.cargarProyectos().then(() => {
        this.loading = false;
        if (this.itemsProyectos.length > 0) {
          this.resetMarkers();
          this.itemsProyectos.some((item) => {
            this.setMarker(item);
          });
        }
      }); */
      mapboxgl.accessToken =
        "pk.eyJ1IjoiY3JpenJlYyIsImEiOiJja3o1cWo0MGUwc3Z3MnByMTliaGcxdDMxIn0.NsVpQviN4kwQQPeymUW6LQ";
      map = new mapboxgl.Map({
        container: "map", // container ID
        style: "mapbox://styles/mapbox/streets-v11", // style URL
        center: [-79.5, 0.6316,], // starting position [lng, lat]
        zoom: 8, // starting zoom
      });
      map.on("load", () => {
        map.addSource("states", {
          type: "geojson",
          data: "/assets/geo.json",
        });
        map.addLayer({
          id: "state-fills",
          type: "fill",
          source: "states",
          layout: {},
          paint: {
            "fill-color": "#18EC10",
            "fill-opacity": [
              "case",
              ["boolean", ["feature-state", "hover"], false],
              1,
              0.5,
            ],
          },
        });
        map.addLayer({
          id: "state-borders",
          type: "line",
          source: "states",
          layout: {},
          paint: {
            "line-color": "#0F9D0A",
            "line-width": 2,
          },
        });
        const popup = new mapboxgl.Popup({
          closeButton: false,
          closeOnClick: false,
        });
        map.on("mousemove", "state-fills", (e) => {
          // Change the cursor style as a UI indicator.
          let canton_id = e.features[0].id;
          let nombre_canton = e.features[0].properties.DPA_DESCAN;
          let arrTotalProyectosAgrupado = [];
          let html = "";
          this.itemsProyectos.some((item) => {
            /* console.log(item.id_canton, canton_id); */
            if (item.id_canton == canton_id) {
              if (arrTotalProyectosAgrupado[item.obra.obra] == null) {
                arrTotalProyectosAgrupado[item.obra.obra] = 0;
              }
              arrTotalProyectosAgrupado[item.obra.obra]++;
            }
          });
          html += `<strong>${nombre_canton}</strong><br>`;
          Object.keys(arrTotalProyectosAgrupado).some((item) => {
            html += `<strong>${item}:&nbsp;${arrTotalProyectosAgrupado[item]}</strong><br>`;
          });
          popup.setLngLat(e.lngLat).setHTML(html).addTo(map);
        });

        map.on("mouseleave", "state-fills", () => {
          map.getCanvas().style.cursor = "";
          popup.remove();
        });

        map.addControl(
          new mapboxgl.GeolocateControl({
            positionOptions: {
              enableHighAccuracy: true,
            },
            // When active the map will receive updates to the device's location as it changes.
            trackUserLocation: true,
            // Draw an arrow next to the location dot to indicate which direction the device is heading.
            showUserHeading: true,
          })
        );
      });
    },
    async locateMe() {
      this.gettingLocation = true;
      try {
        this.gettingLocation = false;
        let location = await this.getLocation();
        if (
          location.coords.latitude != this.lastlocation.coords.latitude ||
          location.coords.longitude != this.lastlocation.coords.longitude
        ) {
          this.location = location;
          this.lastlocation = location;
        }
      } catch (e) {
        this.gettingLocation = false;
        this.errorStr = e.message;
      }
    },
    async getLocation() {
      return new Promise((resolve, reject) => {
        if (!("geolocation" in navigator)) {
          reject(new Error("Geolocation is not available."));
        }

        navigator.geolocation.getCurrentPosition(
          (pos) => {
            resolve(pos);
          },
          (err) => {
            reject(err);
          }
        );
      });
    },
    showImagen(item) {
      window.open(item.image, "_blank");
    },
    showModalTotalProyectos() {
      let arrTotalProyectosAgrupado = [];

      this.$store.commit("openDialogSimple2", {
        openDialog: true,
        bodycard: "bodycardTotalProyectos",
        buttons: "buttonsTotalProyectos",
        titulo: "Total proyectos filtrados",
        maxwidth: "400px",
      });
      setTimeout(() => {
        this.initChartTotalProyectos();
        this.chartModalTotalProyectosAgrupado = [];
        this.itemsProyectos.some((item) => {
          if (arrTotalProyectosAgrupado[item.obra.obra] == null) {
            arrTotalProyectosAgrupado[item.obra.obra] = 0;
          }
          arrTotalProyectosAgrupado[item.obra.obra]++;
        });
        Object.keys(arrTotalProyectosAgrupado).some((item) => {
          this.chartModalTotalProyectosAgrupado.push({
            name: item,
            value: arrTotalProyectosAgrupado[item],
          });
        });
      }, 500);
    },
    initChartTotalProyectos() {
      let proyectos_terminados = this.itemsProyectos.filter(
        (item) => item.id_estado === 4
      ).length;
      var colors = Highcharts.getOptions().colors,
        categories = ["Terminados", "Total"],
        data = [
          {
            y: proyectos_terminados,
            color: colors[2],
          },
          {
            y: this.itemsProyectos.length,
            color: colors[1],
          },
        ],
        browserData = [],
        i,
        j,
        dataLen = data.length,
        drillDataLen,
        brightness;

      // Build the data arrays
      for (i = 0; i < dataLen; i += 1) {
        // add browser data
        browserData.push({
          name: categories[i],
          y: data[i].y,
          color: data[i].color,
        });
      }

      // Create the chart
      Highcharts.chart("chartTotalProyectos", {
        chart: {
          type: "pie",
        },
        title: {
          text: "",
        },

        credits: {
          enabled: false,
        },
        plotOptions: {
          pie: {
            shadow: false,
            center: ["50%"],
            dataLabels: {
              enabled: true,
              format: "<b>{point.name}</b><br>{point.y:,.0f}",
              distance: -50,
              filter: {
                property: "percentage",
                operator: ">",
                value: 4,
              },
            },
          },
        },
        tooltip: {
          pointFormat: "<b>{point.y:,.0f}</b>",
        },
        series: [
          {
            name: "Proyectos",
            data: browserData,
            size: "100%",
          },
        ],
        // responsive: {
        //   rules: [
        //     {
        //       condition: {
        //         maxWidth: 400,
        //       },
        //       chartOptions: {
        //         series: [
        //           {},
        //           {
        //             id: "versions",
        //             dataLabels: {
        //               enabled: true,
        //             },
        //           },
        //         ],
        //       },
        //     },
        //   ],
        // },
      });
    },
    resetValues() {},
    resetMarkers() {
      let markers = this.arrmarkers;
      for (let index = 0; index < markers.length; index++) {
        const element = markers[index];
        element.remove();
      }
      this.arrmarkers = [];
    },
  },
};
</script>

<style></style>
