<template>
  <div>
    <PanelContent>
      <template v-slot:content>
        <v-row>
          <v-col class="ma-1" cols="12" md="12">
            <h2>Proyectos</h2>
          </v-col>
          <v-col class="ml-1" cols="12" md="2">
            <v-btn class="info mb-5" @click="nuevo"
              ><v-icon left dark>add</v-icon>Nuevo</v-btn
            >
          </v-col>
        </v-row>
        <TableContent :headers="headers" :items="items" :loading="loadingTable">
          <template v-slot:btnAcciones="{ item }">
            <Menu
              :small="true"
              :icon="true"
              :clase="'fourth'"
              :color="'white'"
              :_item="item"
              :icon_name="'more_vert'"
              :list_menu="listar_menu_tabla(item)"
            ></Menu>
          </template>
        </TableContent>
      </template>
    </PanelContent>
    <DialogSimple>
      <template v-slot:bodycardNuevo>
        <v-form ref="form" class="pt-5" v-model="valid" lazy-validation>
          <v-row>
            <v-col cols="12" md="12">
              <v-text-field
                :rules="validations.proyecto"
                label="Proyecto"
                v-model="input.proyecto"
                required
                outlined
                dense
              ></v-text-field>
              <v-textarea
								filled
								rows="2"
								:rules="validations.descripcion"
								v-model="input.descripcion"
								label="Descripción"
							></v-textarea>
              <v-text-field
                type="number"
                :rules="validations.presupuesto"
                label="Presupuesto"
                v-model="input.presupuesto"
                required
                outlined
                dense
              ></v-text-field>
              <v-row>
                <v-col cols="12" md="6" class="pb-0">
                  <v-select
                    :rules="validations.canton_id"
                    outlined
                    v-model="input.canton_id"
                    required
                    :items="itemsCanton"
                    item-text="nombre"
                    item-value="id"
                    label="Cantones"
                    @change="cargarParroquias"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6" class="pb-0">
                  <v-select
                    :rules="validations.parroquia_id"
                    outlined
                    v-model="input.parroquia_id"
                    required
                    :items="itemsParroquia"
                    item-text="nombre"
                    item-value="id"
                    label="Parroquia"
                    @change="cargarRecinto"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6" class="pt-2 pb-0">
                  <v-select
                    :rules="validations.recinto_id"
                    outlined
                    v-model="input.recinto_id"
                    required
                    :items="itemsRecinto"
                    item-text="nombre"
                    item-value="id"
                    label="Recinto"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6" class="pt-2 pb-0">
                  <v-select
                    :rules="validations.estado_id"
                    outlined
                    v-model="input.estado_id"
                    required
                    :items="itemsEstado"
                    item-text="nombre"
                    item-value="id"
                    label="Estado"
                    dense
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <v-select
                    :rules="validations.comunidad_id"
                    outlined
                    v-model="input.comunidad_id"
                    required
                    :items="itemsComunidad"
                    item-text="nombre"
                    item-value="id"
                    label="Comunidad"
                    dense
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    :rules="validations.obra_id"
                    outlined
                    v-model="input.obra_id"
                    required
                    :items="itemsObra"
                    item-text="nombre"
                    item-value="id"
                    label="Obra"
                    dense
                  ></v-select>
                </v-col>
              </v-row>
              <v-text-field
                :rules="validations.responsable"
                label="Responsable"
                v-model="input.responsable"
                required
                outlined
                dense
              ></v-text-field>
              <v-row>
                <v-col cols="12" md="6">
                  <v-text-field
                    :rules="validations.latitud"
                    label="Latitud"
                    v-model="input.latitud"
                    required
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <v-text-field
                    :rules="validations.longitud"
                    label="Longitud"
                    v-model="input.longitud"
                    required
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-text-field
                type="datetime-local"
                :rules="validations.fecha_inicio"
                label="Fecha inicio"
                v-model="input.fecha_inicio"
                required
                outlined
                dense
              ></v-text-field>

              <v-text-field
                type="datetime-local"
                :rules="validations.fecha_fin"
                label="Fecha fin"
                v-model="input.fecha_fin"
                required
                outlined
                dense
              ></v-text-field>
              <template v-for="(item, index) in files">
                <div :key="index">
                  <v-text-field
                    dense
                    outlined
                    label="Imagen"
                    readonly
                    required
                    :rules="validations['adjunto_nombre' + index]"
                    @click="selectAdjunto('adjunto'+index)"
                    v-model="item.adjunto_nombre"
                    prepend-inner-icon="attach_file"
                  ></v-text-field>
                  <span
                    class="error--text"
                    v-if="
                      item.adjunto_size > $store.state.config.adjunto_limite_maximo
                    "
                    >{{
                      item.loadingFile
                        ? "Cargando Archivo..."
                        : "El tamaño del documento debe ser menor o igual a 5Mb"
                    }}</span
                  >
                  <input
                    :id="'adjunto_file'+index"
                    type="file"
                    style="display: none"
                    :ref="'adjunto'+index"
                    accept="image/*"
                    @change="guardarAdjunto($event,item,index)"
                  />
                </div>
              </template>
            </v-col>
          </v-row>
        </v-form>
      </template>
      <template v-slot:buttonsNuevo>
        <v-btn
          color="primary"
          :loading="loading"
          :disabled="loading"
          @click="modificando ? editar() : guardar()"
          >{{ modificando ? "Modificar" : "Guardar" }}</v-btn
        >
        <v-btn @click="dialogClose('dialogSimple')">Cancelar</v-btn>
      </template>
    </DialogSimple>
  </div>
</template>

<script>
import PanelContent from "@/components/components/PanelContent";
import TableContent from "@/components/components/TableContent";
import DialogSimple from "@/components/components/DialogSimple";
import Menu from "@/components/components/Menu";

var swal__;
var toast__;
var self;
export default {
  components: {
    PanelContent,
    TableContent,
    DialogSimple,
    Menu,
  },
  data() {
    return {
      loadingTable: false,
      valid: false,
      loading: false,
      modificando: false,
      input: {
        canton_id: "",
        parroquia_id: "",
        recinto_id: "",
        estado_id: "",
        comunidad_id: "",
        obra_id: "",
        proyecto: "",
        descripcion:"",
        presupuesto: "",
        responsable: "",
        latitud: "",
        longitud: "",
        fecha_inicio: "",
        fecha_fin: "",
      },
      validations: {
        canton_id: [(v) => (v != null && v != "") || "El campo es obligatorio"],
        parroquia_id: [
          (v) => (v != null && v != "") || "El campo es obligatorio",
        ],
        recinto_id: [
          (v) => (v != null && v != "") || "El campo es obligatorio",
        ],
        estado_id: [(v) => (v != null && v != "") || "El campo es obligatorio"],
        comunidad_id: [
          (v) => (v != null && v != "") || "El campo es obligatorio",
        ],
        obra_id: [(v) => (v != null && v != "") || "El campo es obligatorio"],
        responsable: [
          (v) => (v != null && v != "") || "El campo es obligatorio",
        ],
        proyecto: [(v) => (v != null && v != "") || "El campo es obligatorio"],
        descripcion: [(v) => (v != null && v != "" ? v.length<=200 : true) || "Ha sobrepasado el máximo de 50 caracteres"],
        /* presupuesto: [
          (v) => (v != null && v != "") || "El campo es obligatorio",
        ], */
        latitud: [(v) => (v != null && v != "") || "El campo es obligatorio"],
        longitud: [(v) => (v != null && v != "") || "El campo es obligatorio"],
        /* fecha_inicio: [
          (v) => (v != null && v != "") || "El campo es obligatorio",
        ], */
        /* fecha_fin: [(v) => (v != null && v != "") || "El campo es obligatorio"], */
        // adjunto_nombre: [
        //   (v) => (v != null && v != "") || "El campo es obligatorio",
        // ],
      },
      urls: {
        cargarAll: "/dashboard/proyectos/cargar",
        cargarCantones: "/dashboard/cantones/cargar",
        cargarParroquias: "/dashboard/parroquias/cargar",
        cargarRecintos: "/dashboard/recintos/cargar",
        cargarEstados: "/dashboard/estados/cargar",
        cargarComunidades: "/dashboard/comunidades/cargar",
        cargarObras: "/dashboard/obras/cargar",
        guardar: "/dashboard/proyecto/guardar",
        eliminar: "/dashboard/proyecto/eliminar",
        cargar: "/dashboard/proyecto/buscar",
        editar: "/dashboard/proyecto/editar",
      },
      headers: [
        { text: "Acciones", value: "action", sortable: false },
        { text: "#", align: "left", value: "id" },
        { text: "Proyecto", align: "left", value: "proyecto" },
        { text: "Presupuesto", align: "left", value: "presupuesto" },
        { text: "Canton", align: "left", value: "canton.nombre" },
        { text: "Parroquia", align: "left", value: "parroquia.nombre" },
        { text: "Recinto", align: "left", value: "recinto.nombre" },
        { text: "Estado", align: "left", value: "estado.estado" },
        { text: "Comunidad", align: "left", value: "comunidad.comunidad" },
        { text: "Obra", align: "left", value: "obra.obra" },
        { text: "Responsable", align: "left", value: "responsable" },
        //{ text: "Descripcion", align: "left", value: "descripcion" },

        { text: "Lat", align: "left", value: "latitud" },
        { text: "Long", align: "left", value: "longitud" },
        {
          text: "Fecha Inicio",
          align: "left",
          value: "fecha_inicio",
          width: "170px",
        },
        {
          text: "Fecha Fin",
          align: "left",
          value: "fecha_fin",
          width: "170px",
        },
      ],
      items: [],
      categorias: [],
      itemsCanton: [],
      itemsParroquia: [],
      itemsRecinto: [],
      itemsEstado: [],
      itemsComunidad: [],
      itemsObra: [],

      files: [
        {
          adjunto_nombre: null,
          adjunto_url: null,
          adjunto_file: null,
          adjunto_size: null,
          loadingFile: false,
        },
        {
          adjunto_nombre: null,
          adjunto_url: null,
          adjunto_file: null,
          adjunto_size: null,
          loadingFile: false,
        },
      ],
    };
  },
  created() {
    self = this;
    swal__ = this.$store.getters.getSwal;
    toast__ = this.$store.getters.getToastDefault;
    // this.files.some((item,index) => {
    //   this.validations["adjunto_nombre" + index] = [];
    //   this.validations["adjunto_nombre" + index].push(
    //     (v) => (v != null && v != "") || "El campo es obligatorio"
    //   );
    // });
    this.cargarAll();
    this.cargarCantones();
    this.cargarEstados();
    this.cargarComunidades();
    this.cargarObras();
  },
  methods: {
    listar_menu_tabla(item) {
      return [
        {
          click: this.cargar,
          icono: "edit",
          class: "edit",
          nombre: "Modificar",
        },

        {
          click: this.eliminar,
          icono: "cancel",
          class: "delete",
          nombre: "Eliminar",
        },
      ];
    },
    dialogClose(tipo) {
      this.$store.commit("closeDialog", { tipo: tipo, value: false });
    },
    cargarCantones() {
      axios
        .get(this.urls.cargarCantones)
        .then((response) => {
          var data = response.data;
          console.log("data.estado", data.estado);
          if (data.estado == "success") {
            this.itemsCanton = data.data.cantones;
            // this.itemsCanton.unshift({ id: null, nombre: "TODOS" });
          }
        })
        .catch((errors) => {});
    },
    async cargarParroquias() {
      let valor = {
        canton_id: this.input.canton_id,
      };
      axios
        .post(this.urls.cargarParroquias, valor)
        .then((response) => {
          var data = response.data;
          console.log("data.estado", data.estado);
          if (data.estado == "success") {
            this.itemsParroquia = data.data.parroquias;
          }
        })
        .catch((errors) => {});
    },
    async cargarRecinto() {
      let valor = {
        parroquia_id: this.input.parroquia_id,
      };
      axios
        .post(this.urls.cargarRecintos, valor)
        .then((response) => {
          var data = response.data;
          console.log("data.estado", data.estado);
          if (data.estado == "success") {
            this.itemsRecinto = data.data.recintos;
          }
        })
        .catch((errors) => {});
    },
    cargarEstados() {
      axios
        .get(this.urls.cargarEstados)
        .then((response) => {
          var data = response.data;
          console.log("data.estado", data.estado);
          if (data.estado == "success") {
            this.itemsEstado = data.data.estados;
            this.itemsCanton.unshift({ id: null, nombre: "TODOS" });
          }
        })
        .catch((errors) => {});
    },
    cargarComunidades() {
      axios
        .get(this.urls.cargarComunidades)
        .then((response) => {
          var data = response.data;
          console.log("data.estado", data.estado);
          if (data.estado == "success") {
            this.itemsComunidad = data.data.comunidades;
            // this.itemsCanton.unshift({ id: null, nombre: "TODOS" });
          }
        })
        .catch((errors) => {});
    },
    cargarObras() {
      axios
        .get(this.urls.cargarObras)
        .then((response) => {
          var data = response.data;
          console.log("data.estado", data.estado);
          if (data.estado == "success") {
            this.itemsObra = data.data.obras;
            // this.itemsCanton.unshift({ id: null, nombre: "TODOS" });
          }
        })
        .catch((errors) => {});
    },
    cargarAll() {
      this.loadingTable = true;
      axios
        .get(this.urls.cargarAll)
        .then((response) => {
          var data = response.data;
          this.items = data.data.proyectos;
          this.loadingTable = false;
        })
        .catch((errors) => {});
    },
    async cargar(item) {
      this.resetValues();
      let valores = { id: item.id };
      this.modificando = true;
      axios
        .post(this.urls.cargar, valores)
        .then(async (response) => {
          var data = response.data;
          this.input = data;
          await this.cargarParroquias();
          await this.cargarRecinto();
          data.imagen.some((item,index)=>{
            this.files[index].adjunto_nombre = item.url;
          })

          this.$store.commit("openDialogSimple", {
            openDialog: true,
            bodycard: "bodycardNuevo",
            buttons: "buttonsNuevo",
            titulo: "Modificar",
            maxwidth: "500px",
          });
          setTimeout(function () {
            if (!this.$refs.form.validate()) return;
          }, 250);
        })
        .catch((errors) => {});
    },
    editar() {
      if (!this.$refs.form.validate()) return;

      var formData = new FormData();
      this.files.some((item,index)=>{
        if (item.adjunto_file != null && item.adjunto_file != "") {
          formData.append("adjunto_file[]", item.adjunto_file);
          formData.append("adjunto_nombre[]", item.adjunto_nombre);
          formData.append("imagen_id[]", typeof this.input.imagen[index] !== 'undefined' ? this.input.imagen[index].id : null);
        }
      })
      formData.append("id_canton", this.input.canton_id);
      formData.append("id_parroquia", this.input.parroquia_id);
      formData.append("id_recinto", this.input.recinto_id);
      formData.append("id_estado", this.input.estado_id);
      formData.append("id_comunidad", this.input.comunidad_id);
      formData.append("id_obra", this.input.obra_id);
      formData.append("proyecto", this.input.proyecto);
      formData.append("descripcion", this.input.descripcion ?? '');
      formData.append("presupuesto", this.input.presupuesto ?? '');
      formData.append("responsable", this.input.responsable);
      formData.append("latitud", this.input.latitud);
      formData.append("longitud", this.input.longitud);
      formData.append("fecha_inicio", this.input.fecha_inicio ?? '');
      formData.append("fecha_fin", this.input.fecha_fin ?? '');
      formData.append("id", this.input.id);
      this.loading = true;

      axios
        .post(this.urls.editar, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((response) => {
          var data = response.data;
          toast__.fire({ icon: data.estado, title: data.mensaje });
        })
        .catch((errors) => {
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        })
        .finally(() => {
          this.cargarAll();
          this.dialogClose("dialogSimple");
          this.loading = false;
        });
    },
    guardar() {
      if (!this.$refs.form.validate()) return;
      var formData = new FormData();
      this.files.some((item)=>{
        if (item.adjunto_file != null && item.adjunto_file != "") {
          formData.append("adjunto_file[]", item.adjunto_file);
          formData.append("adjunto_nombre[]", item.adjunto_nombre);
        }
      })
      formData.append("id_canton", this.input.canton_id);
      formData.append("id_parroquia", this.input.parroquia_id);
      formData.append("id_recinto", this.input.recinto_id);
      formData.append("id_estado", this.input.estado_id);
      formData.append("id_comunidad", this.input.comunidad_id);
      formData.append("id_obra", this.input.obra_id);
      formData.append("proyecto", this.input.proyecto);
      formData.append("descripcion", this.input.descripcion ?? '');
      formData.append("presupuesto", this.input.presupuesto ?? '');
      formData.append("responsable", this.input.responsable);
      formData.append("latitud", this.input.latitud);
      formData.append("longitud", this.input.longitud);
      formData.append("fecha_inicio", this.input.fecha_inicio ?? '');
      formData.append("fecha_fin", this.input.fecha_fin ?? '');

      this.loading = true;
      axios
        .post(this.urls.guardar, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
        .then((response) => {
          var data = response.data;
          toast__.fire({
            icon: data.estado,
            title: data.mensaje,
          });
        })
        .catch((errors) => {
          swal__.fire("ERROR!", "ha ocurrido un error: " + errors, "error");
        })
        .finally(() => {
          this.cargarAll();
          this.dialogClose("dialogSimple");
          this.loading = false;
        });
    },
    eliminar(item) {
      let valores = {
        id: item.id,
      };
      swal__
        .fire({
          title: "¿Desea eliminar el registro: " + item.proyecto + "?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#18abb8",
          cancelButtonColor: "#ED303C",
          confirmButtonText: "Si",
          cancelButtonText: "No",
        })
        .then((result) => {
          if (result.value) {
            axios
              .delete(this.urls.eliminar, { data: valores })
              .then((response) => {
                var data = response.data;

                toast__.fire({
                  icon: data.estado,
                  title: data.mensaje,
                });
              })
              .catch((errors) => {
                swal__.fire(
                  "ERROR!",
                  "ha ocurrido un error: " + errors,
                  "error"
                );
              })
              .finally(() => {
                this.cargarAll();
              });
          }
        });
    },
    nuevo() {
      this.resetValues();
      this.$store.commit("openDialogSimple", {
        openDialog: true,
        bodycard: "bodycardNuevo",
        buttons: "buttonsNuevo",
        titulo: "Registrar",
        maxwidth: "500px",
      });
      setTimeout(() => {
        if (!this.$refs.form.validate()) return;
      }, 250);
    },
    selectAdjunto(adjunto) {
      this.$refs[adjunto][0].click();
    },
    guardarAdjunto(e,item,index) {
      item.loadingFile = true;
      item.loading = true;
      const files = e.target.files;

      if (files[0] !== undefined) {
        item.adjunto_size = files[0].size; //bytes
        if (
          item.adjunto_size > this.$store.state.config.adjunto_limite_maximo
        ) {
          return;
        }
        item.adjunto_nombre = files[0].name;

        if (item.adjunto_nombre.lastIndexOf(".") <= 0) {
          return;
        }

        if (
          !this.$store.state.soloImagenes(
            item.adjunto_nombre.split(".")[
              item.adjunto_nombre.split(".").length - 1
            ]
          )
        ) {
          this.resetAdjunto(item,index);
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", (e) => {
          item.adjunto_url = fr.result;
          item.adjunto_file = files[0]; // this is an image file that can be sent to server...
          item.loadingFile = false;
          self.loading = false;
        });
      } else {
        item.adjunto_nombre = "";
        item.adjunto_file = null;
        item.adjunto_url = null;
        item.loadingFile = false;
        item.loading = false;
      }
    },
    resetAdjunto(item,index) {
      item.adjunto_nombre = "";
      item.adjunto_file = null;
      item.adjunto_url = null;
      item.loadingFile = false;
      item.adjunto_size = 0;
      this.loading = false;
      if (document.querySelector("#adjunto_file"+index) != null) {
        document.querySelector("#adjunto_file"+index).value = "";
      }
    },
    resetAdjuntos() {
      this.files.some((item,index)=>{
        item.adjunto_nombre = "";
        item.adjunto_file = null;
        item.adjunto_url = null;
        item.loadingFile = false;
        item.adjunto_size = 0;
        this.loading = false;
        if (document.querySelector("#adjunto_file"+index) != null) {
          document.querySelector("#adjunto_file"+index).value = "";
        }
      })
    },
    resetValues() {
      this.input = {};
      this.modificando = false;
      this.itemsParroquia = [];
      this.itemsRecinto = [];
      this.resetAdjuntos();
    },
  },
};
</script>

<style></style>
