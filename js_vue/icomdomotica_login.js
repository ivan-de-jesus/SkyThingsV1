
const login = new Vue({
    el: '#ctrl_login',
    data: {
        sesion: {
            usuario: "",
            clave: "",
            tipo: 'administrador'
        },
        respuesta: [],
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
            return !(this.sesion.usuario.length > 0 && this.sesion.clave.length > 0);
        }
    },
    methods: {
        onSubmit: function () {
            this.$http.post('ingresar', this.sesion).then(response => {
                this.respuesta = response.body;
                if (this.respuesta.estatus === 'OK') {
                    if (this.respuesta.hasOwnProperty('primerlogin')) {
                        if (this.respuesta.primerlogin == '1') {
                            window.location.href = this.$base_url;
                        } else {
                            $('#modalCambio').modal('show');
                        }
                    } else {
                        window.location.href = this.$base_url;
                    }
                } else {
                    this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
                }
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.sesion.usuario = "";
            this.sesion.clave = "";
        }
    }
});

const cambio_clave = new Vue({
    el: '#ctrl_cambio',
    data: {
        invitado: {
            usuario: "",
            actual: "",
            nueva: "",
            nuevaconf: ""
        },
        mensaje: {
            cargando: false,
            tipo: 'default',
            texto: null
        }
    },
    computed: {
        isFormEmpty: function () {
            return !(this.invitado.usuario.length > 0 && this.invitado.actual.length > 0 && this.invitado.nueva.length > 0 && this.invitado.nuevaconf.length > 0);
        }
    },
    methods: {
        onSubmit: function () {
            var tmp = [];
            this.$http.post('cambio_clave_invitado', this.invitado).then(response => {
                tmp = response.body;
                if (tmp.estatus === "OK") {
                    this.$snotify.success("Ingrese nuevamente", tmp.estatus, {
                        timeout: 5000,
                        closeOnClick: true,
                        showProgressBar: true
                    });
                    $("#modalCambio").modal("hide");
                } else {
                    this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
                }
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.invitado.usuario = "";
            this.invitado.actual = "";
            this.invitado.nueva = "";
            this.invitado.nuevaconf = "";
        }
    }
});