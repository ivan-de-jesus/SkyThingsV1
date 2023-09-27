
Vue.component('control-camera', {
    data: function () {
        return {
            camara: {
                etiqueta: '',
                ip: '',
                port: ''
            },
            mensaje: {
                cargando: false,
                tipo: 'default',
                texto: null
            },
            opcionesSnotify: {
                timeout: 5000,
                showProgressBar: true,
                closeOnClick: true,
                pauseOnHover: true
            }
        };
    },
    computed: {
        isFormEmpty: function () {
            return !(this.camara.etiqueta.length > 0 && 
                    this.camara.ip.length > 0 );
        }
    },
    methods: {
        ...Vuex.mapMutations(['refrescarCamarasUpdate']),
        onSubmit: function () {
            this.$http.post('nueva_camara', this.camara).then(response => {
                this.refrescarCamarasUpdate();
                this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.limpiar();
        },
        limpiar: function () {
            this.camara.etiqueta = '';
            this.camara.ip = '';
            this.camara.port = '';
        }
    },
    template: [
        '<div class="box">',
            '<div class="box-header"><h3>Camara de seguridad</h3></div>',
            '<div class="box-body">',
                '<form v-on:submit.prevent="onSubmit" v-on:reset.prevent="limpiar" accept-charset="utf-8">',
                    '<div class="row">',
                        '<div class="col-12">',
                            '<div class="form-group">',
                                '<label>Etiqueta</label>',
                                '<input type="text" v-model="camara.etiqueta" class="form-control">',
                            '</div>',
                        '</div>',
                        '<div class="col-8">',
                            '<div class="form-group">',
                                '<label>IP</label>',
                                '<input type="text" v-model="camara.ip" class="form-control">',
                            '</div>',
                        '</div>',
                        '<div class="col-4">',
                            '<div class="form-group">',
                                '<label>Puerto</label>',
                                '<input type="text" v-model="camara.port" class="form-control">',
                            '</div>',
                        '</div>',
                    '</div>',
                    '<button type="reset" class="btn btn-secondary">Limpiar</button>',
                    '<button type="submit" v-bind:disabled="isFormEmpty" class="btn btn-primary">Aceptar</button>',
                '</form>',
            '</div>',
        '</div>'
    ].join('')
});