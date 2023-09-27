
const panel = new Vue({
    el: '#ctrl_camara',
    store: store,
    data: {
        tipousuario: "administrador",
        usuario: "NA",
        sessioninit: false,
        mqttclient: null,
        camaras: [],
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
    },
    mounted: function () {
        
    },
    methods: {
        ...Vuex.mapMutations(['credencialesUpdate']),
        obtenerCamaras: function () {
            this.$http.get('listar_camara').then(response => {
                if (Array.isArray(response.body)) {
                    this.camaras = response.body;
                }
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
    created: function () {
        this.verificarUsuario();
        this.obtenerCamaras();
    }
});