
const inv = new Vue({
    el: '#ctrl_lugar',
    store: store,
    data: {
        tipousuario: "administrador",
        usuario: "NA",
        lugar: {
            descripcion: null
        },
        lugares: [],
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
    },
    computed: {
        isFormEmpty: function () {
            return !(this.lugar.descripcion);
        }
    },
    methods: {
        ...Vuex.mapMutations(['credencialesUpdate']),
        onSubmit: function () {
            this.$http.post('nuevo_lugar', this.lugar).then(response => {
                this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
                this.obtenerLugares();
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.lugar.descripcion = null;
        },
        obtenerLugares: function () {
            this.$http.get('listar_lugar').then(response => {
                this.lugares = response.body; 
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        verificarUsuario: function () {
            var tmp = [];
            this.$http.get('verificar_usuario').then(response => {
                tmp = response.body;
                if (tmp.estatus === 'OK') {
                    let cred = {
                        usuario: tmp.usuario,
                        isAdmin: tmp.isAdmin
                    };
                    
                    this.credencialesUpdate(cred);
                    
                } else {
                    this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
                }
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        }
    },
    created: function() {
        this.verificarUsuario();
        this.obtenerLugares();
    }
});