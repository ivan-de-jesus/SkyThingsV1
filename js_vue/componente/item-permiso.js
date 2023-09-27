
Vue.component('item-permiso', {
    props:{
        topico: Object,
        invitado: String
    },
    data: function () {
        return {
            conceder: {
                invitadoid: "",
                topicoid: "",
                permitir: 0
            },
            permiso: {
                topicoid: 0,
                invitadoid: 0
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
        classCard: function () {
            return {
                'bg-danger': (this.topico.permitido == null || this.topico.permitido == 0), 
                'bg-success': (this.topico.permitido != null && this.topico.permitido == 1)
            };
        },
        classBtn: function () {
            return {
                "fa-lock text-danger": (this.topico.permitido != null && this.topico.permitido == 1), 
                "fa-unlock text-success": (this.topico.permitido == null || this.topico.permitido == 0)
            };
        }
    },
    methods: {
        permisoSet: function (p_topico) {
            this.conceder.invitadoid = this.invitado;
            this.conceder.topicoid = p_topico.id;
            this.conceder.permitir = ((p_topico.permitido == null || p_topico.permitido == 0) ? 1 : 0);
            if (this.verificar()) {
                this.$http.post('setear_permiso_topico', this.conceder).then(respuesta => {
                    this.$respuestaParse(respuesta, this.mensaje, this.opcionesSnotify);
                    this.estatusPermiso();
                }, error => {
                    this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
                });
            }
        },
        estatusPermiso: function () {
            this.permiso.topicoid = this.conceder.topicoid;
            this.permiso.invitadoid = this.conceder.invitadoid;
            this.$http.post('get_permiso_topico', this.permiso).then(respuesta => {
                var tmp = respuesta.body;
                this.topico.permitido = tmp.permitido;
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        verificar: function () {
            return (this.conceder.invitadoid.length > 0 && 
                    this.conceder.topicoid.length > 0 &&
                    (this.conceder.permitir == 0 || this.conceder.permitir == 1));
        }
    },
    template:[
        '<div class="col-xs-12 col-sm-4 col-lg-3">',
            '<div class="card p-a m-b text-white" v-bind:class="classCard">',
                '<div class="card-body">',
                    '<div class="row">',
                        '<div class="col-sm-12">',
                            '<div class="card-text"> {{ topico.etiqueta }} </div>',
                        '</div>',
                        '<div class="col-sm-12">',
                            '<small class="card-text text-muted" style="word-wrap: break-word;"> {{ topico.nombre }}</small>',
                        '</div>',
                    '</div>',
                    '<button type="button" class="btn btn-xs btn-secondary float-right" v-on:click="permisoSet(topico)">',
                        '{{ (topico.permitido == null || topico.permitido == 0) ? "Conceder" : "Revocar" }} ',
                        '<i class="fa" v-bind:class="classBtn"></i>',
                    '</button>',
                '</div>',
            '</div>',
        '</div>'
    ].join('')
});