
const control = new Vue({
    el: '#ctrl_control',
    store: store,
    data: {
        tipousuario: "administrador",
        usuario: "NA",
        topicos: [],
        dispositivos: [],
        camaras: [],
        msj: [],
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
    },
    computed: {
        ...Vuex.mapState(['refrescarTopicos', 'refrescarCamaras', 'credenciales'])
    },
    watch: {
        refrescarTopicos: function (val) {
            if (val) {
                this.obtenerTopicos();
                this.refrescarTopicosUpdate();
            }
        },
        refrescarCamaras: function (val) {
            if (val) {
                this.obtenerCamaras();
                this.refrescarCamarasUpdate();
            }
        }
    },
    methods: {
        ...Vuex.mapMutations(['refrescarTopicosUpdate', 'refrescarCamarasUpdate', 'credencialesUpdate']),
        obtenerDispositivos: function () {
            this.$http.get('listar_dispositivo').then(response => {
                this.dispositivos = response.body; 
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        obtenerTopicos: function () {
            this.$http.get('listar_topico').then(response => {
                this.topicos = response.body; 
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        obtenerCamaras: function () {
            this.$http.get('listar_camara').then(response => {
                this.camaras = response.body;
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
        this.obtenerTopicos();
        this.obtenerDispositivos();
        this.obtenerCamaras();
    }
});