
Vue.component('encabezado', {
    template: [
        '<div class="app-header white box-shadow">',
            '<div class="navbar navbar-toggleable-sm flex-row align-items-center">',
                '<a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">',
                    '<i class="material-icons">&#xe5d2;</i>',
                '</a>',
                '<div class="mb-0 h5 no-wrap" id="pageTitle"> {{ titulo }} </div>',
                '<div class="collapse navbar-collapse" id="collapse"></div>',
                '<ul class="nav navbar-nav ">',
                    '<li class="nav-item">',
                        '<a href="" v-on:click.prevent="logout" class="nav-link "><i class="fa fa-sign-out"></i> Salir</a>',
                    '</li>',
                '</ul>',
            '</div>',
        '</div>'
    ].join(''),
    props: {
        titulo: String
    },
    data: function () {
        return {
            respuesta: {
                estatus: '',
                texto: ''
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
    methods: {
        logout: function() {
            this.limpiar();
            this.$http.post('salir').then(response => {
                this.respuesta = response.body;
                if (this.respuesta.estatus === 'SESSION_NULL') {
                    this.borrarDataStorage();
                    window.location.href = this.$base_url;
                } else {
                    this.$respuestaParse(response,  this.mensaje, this.opcionesSnotify);
                }
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        limpiar: function () {
            this.respuesta.estatus = '';
            this.respuesta.texto = '';
        },
        borrarDataStorage: function () {
            if (this.$almacenDisponible()) {
                localStorage.removeItem('tipo_usr');
                localStorage.removeItem('usuario');
            }
        }
    }
});