
Vue.component('control-range', {
    props: {
        dispositivos: Array
    },
    data: function () {
        return {
            dispositivoindex: -1,
            topico: {
                dispositivoid: "-1",
                etiqueta: '',
                numseriedisp: '',
                tipo: 'range',
                propiedades: {
                    addswitch: false,
                    estadoswitch: "OFF"
                }
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
            return !(this.topico.etiqueta.length > 0 && 
                    this.dispositivoindex > -1 );
        }
    },
    methods: {
        ...Vuex.mapMutations(['refrescarTopicosUpdate']),
        onSubmit: function () {
            let tmp = this.dispositivos[this.dispositivoindex];
            this.topico.dispositivoid = tmp.id;
            this.topico.numseriedisp = tmp.serie;
            
            this.$http.post('nuevo_topico', this.topico).then(response => {
                this.refrescarTopicosUpdate();
                this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.limpiar();
        },
        limpiar: function () {
            this.dispositivoindex = -1;
            this.topico.dispositivoid = "-1";
            this.topico.etiqueta = '';
            this.topico.propiedades.addswitch = false;
        }
    },
    template: [
        '<div class="box">',
            '<div class="box-header"><h3>Rango</h3></div>',
            '<div class="box-body">',
                '<span class="text-muted">Id&oacute;neo para control de intencidades, por ejemplo la variaci&oacute;n de la luz de un foco o la velocidad de un ventilador.</span>',
                '<form v-on:submit.prevent="onSubmit" v-on:reset.prevent="limpiar" accept-charset="utf-8">',
                    '<div class="form-group">',
                        '<label>Etiqueta</label>',
                        '<input type="text" v-model="topico.etiqueta" class="form-control">',
                    '</div>',
                    '<div class="form-group">',
                        '<label>Dispositivo</label>',
                        '<select v-model="dispositivoindex" class="form-control">',
                            '<option disabled value="-1">Seleccione un dispositivo</option>',
                            '<option ',
                                'v-for="(item, index) in dispositivos" ',
                                'v-bind:value="index" ',
                                'v-bind:key="index" ',
                                'class="text-dark">',
                                '{{ item.serie }} ( {{ item.lugar }} )',
                            '</option>',
                        '</select>',
                    '</div>',
                    '<div class="container mb-2">',
                        '<p>Opciones</p>',
                        '<div class="row justify-content-between">',
                            '<div class="col-10">',
                                'Incluir switch de encendido<br>',
                                '<small class="text-muted">Util por si es necesario encender el dispositivo antes de empezar a manipular las intencidades</small>',
                            '</div>',
                            '<div class="col-2">',
                                '<label class="ui-switch ui-switch-md info m-t-xs"><input type="checkbox" v-model="topico.propiedades.addswitch"><i></i></label>',
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