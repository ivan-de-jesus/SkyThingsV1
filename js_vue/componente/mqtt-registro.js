
Vue.component('mqtt-registro', {
    data: function () {
        return {
            mqtt: {
                usuario: "",
                clave: "",
                claveconf: ""
            },
            mensaje: {
                cargando: false,
                tipo: 'default',
                texto: null
            },
            opcionesSnotify: {
                timeout: 2000,
                showProgressBar: true,
                closeOnClick: true,
                pauseOnHover: true
            }
        };
    },
    computed: {
        isFormEmpty: function () {
            return !(this.mqtt.usuario.length > 0 && this.mqtt.clave.length > 0 && this.mqtt.claveconf.length > 0);
        }
    },
    methods: {
        onSubmit: function () {
            this.$http.post('nuevo_mqtt', this.mqtt).then(response => {
                this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.limpiar();
        },
        limpiar: function () {
            this.mqtt.usuario = "";
            this.mqtt.clave = "";
            this.mqtt.claveconf = "";
            $('#modalMqtt').modal('hide');
        }
    },
    template: [
        '<div id="modalMqtt" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">',
            '<div role="document" class="modal-dialog">',
                '<div class="modal-content">',
                    '<div class="modal-header">',
                        '<h5 id="exampleModalLabel" class="modal-title">Usuario mqtt</h5>',
                        '<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>',
                    '</div>',
                    '<div class="modal-body">',
                        '<form v-on:submit.prevent="onSubmit" v-on:reset.prevent="limpiar" accept-charset="utf-8">',
                            '<div class="form-group">',
                                '<label>Usuario MQTT</label>',
                                '<input v-model="mqtt.usuario" name="user_mqtt" type="text" class="form-control" required>',
                            '</div>',
                            '<div class="form-group">',
                                '<label>Contraseña MQTT</label>',
                                '<input v-model="mqtt.clave" name="pass_mqtt" type="password" class="form-control" required>',
                            '</div>',
                            '<div class="form-group">',
                                '<label>Repita la contraseña MQTT</label>',
                                '<input v-model="mqtt.claveconf" name="pass_mqtt_r" type="password" class="form-control" required>',
                            '</div>',
                            '<button type="reset" class="btn btn-secondary">Cerrar</button>',
                            '<button type="submit" v-bind:disabled="isFormEmpty" class="btn btn-danger">Guardar</button>',
                        '</form>',
                    '</div>',
                '</div>',
            '</div>',
        '</div>'
    ].join('')
});