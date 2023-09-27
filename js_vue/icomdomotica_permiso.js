
const permiso = new Vue({
    el: '#ctrl_permiso',
    store: store,
    data: {
        tipousuario: "administrador",
        usuario: "NA",
        bloqueo: false,
        invitados: [],
        lugares: [],
        permisos: [],
        consulta: {
            idlugar: "",
            idinvitado: ""
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
    },
    computed: {
        isFormEmpty: function () {
            return !(this.consulta.idlugar.length > 0 && this.consulta.idinvitado.length > 0);
        },
        classBtnConsulta: function () {
            return {
                'btn-primary': !this.bloqueo,
                'btn-secondary': this.bloqueo
            };
        }
    },
    methods: {
        ...Vuex.mapMutations(['credencialesUpdate']),
        consultar: function () {
            if (!this.bloqueo) {
                this.obtenerPermisos();
            } else {
                this.bloqueo = false;
                this.permisos = [];
            }
        },
        obtenerInvitados: function () {
            this.$http.post('listar_invitado').then(response => {
                this.invitados = response.body;
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        obtenerLugares: function () {
            this.$http.get('listar_lugar').then(response => {
                if (Array.isArray(response.body)) {
                    this.lugares = response.body;
                } 
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        obtenerPermisos: function () {
            this.permisos = [];
            this.$http.post('listar_permiso_topico', this.consulta).then(response => {
                if (Array.isArray(response.body)) {
                    this.permisos = response.body;
                    this.bloqueo = true;
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
        },
        limpiar: function () {
            this.consulta.idlugar = 0;
            this.consulta.idinvitado = 0;
            this.permisos = [];
        }
    },
    created: function() {
        this.verificarUsuario();
        this.obtenerLugares();
        this.obtenerInvitados();
    }
});