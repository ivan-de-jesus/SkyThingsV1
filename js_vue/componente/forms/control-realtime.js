
Vue.component('control-realtime', {
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
                tipo: 'realtime',
                propiedades: {
                    tipodato: "num"
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
            this.topico.propiedades.tipodato = "num";
        }
    },
    template: [
        '<div class="box">',
            '<div class="box-header"><h3>Tiempo real</h3></div>',
            '<div class="box-body">',
                '<span class="text-muted">Id&oacute;neo para la obtencion de datos de sensores, por ejemplo de temperatura o de movimiento.</span>',
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
                                'Tipo de datos a recibir<br>',
                                '<small class="text-muted">Especifica la forma de mostrar los datos, si elije el tipo de datos "Números" se muestra una gráfica de lo contrario solo se mostrará el dato recibido como texto.</small>',
                            '</div>',
                            '<div class="col-2">',
                                '<div class="form-check">',
                                    '<input class="form-check-input" id="tipodato1" name="tipodato" type="radio" value="num" v-model="topico.propiedades.tipodato">',
                                    '<label class="form-check-label" for="tipodato1">Números</label>',
                                '</div>',
                                '<div class="form-check">',
                                    '<input class="form-check-input" id="tipodato2" name="tipodato" type="radio" value="tex" v-model="topico.propiedades.tipodato">',
                                    '<label class="form-check-label" for="tipodato2">Texto</label>',
                                '</div>',
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