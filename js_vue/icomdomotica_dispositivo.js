
const dispositivo = new Vue({
    el: '#ctrl_dispositivo',
    store: store,
    data: {
        tipousuario: "administrador",
        usuario: "NA",
        dispositivo: {
            lugarid: 0,
            serie: ''
        },
        lugares: [],
        dispositivos: [],
        msj: [],
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
            return !(this.dispositivo.lugarid !== 0 && this.dispositivo.serie.length > 0);
        }
    },
    methods: {
        ...Vuex.mapMutations(['credencialesUpdate']),
        onSubmit: function () {
            this.$http.post('nuevo_dispositivo', this.dispositivo).then(response => {
                this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
                this.obtenerDispositivos();
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
            this.limpiar();
        },
        obtenerDispositivos: function () {
            this.$http.get('listar_dispositivo').then(response => {
                this.dispositivos = response.body;
            }, error => {
                this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
            });
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
        },
        limpiar: function () {
            this.dispositivo.lugarid = 0;
            this.dispositivo.serie = '';
        }
    },
    created: function() {
        this.verificarUsuario();
        this.obtenerLugares();
        this.obtenerDispositivos();
    }
});