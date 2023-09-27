
const inv = new Vue({
    el: '#ctrl_invitado',
    store: store,
    data: {
        tipousuario: "administrador",
        usuario: "NA",
        invitado: {
            usuario: "",
            clave: ""
        },
        invitados: [],
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
            return !(this.invitado.usuario.length > 0 && this.invitado.clave.length > 0);
        }
    },
    methods: {
        ...Vuex.mapMutations(['credencialesUpdate']),
        onSubmit: function () {
            this.$http.post('nuevo_invitado', this.invitado).then(response => {
                this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
                this.obtenerInvitados();
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.limpiar();
        },
        obtenerInvitados: function () {
            this.$http.post('listar_invitado').then(response => {
                this.invitados = response.body;
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
        },
        onGenContrasenia: function () {
            this.invitado.clave = "";
            var caracteres = "TqfS4WLIi9Ul8EZBXxa3NJK06R2sYQMrtVAOnHv5yozkePbDjwG7hpcFdg1muC";
            for (var i = 0; i < 8; i++) {
                this.invitado.clave += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
        },
        classItem: function(logeado) {
            return {
                'border-success': (logeado === "SI"),
                'border-danger': (logeado === "NO")
            };
        },
        classItemBadge: function(logeado) {
            return {
                'badge-success': (logeado === "SI"),
                'badge-danger': (logeado === "NO")
            };
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
            this.invitado.usuario = "";
            this.invitado.clave = "";
        }
    },
    created: function() {
        this.verificarUsuario();
        this.obtenerInvitados();
    }
});