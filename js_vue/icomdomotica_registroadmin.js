
const registro_admin = new Vue({
    el: '#ctrl_registro',
    data: {
        admin: {
            usuario: "",
            clave: "",
            claveconf: ""
        },
        respuesta: []
    },
    computed: {
        isFormEmpty: function () {
            return !(this.admin.usuario.length > 0 && this.admin.clave.length > 0 && this.admin.claveconf.length > 0);
        }
    },
    methods: {
        onSubmit: function () {
            this.$http.post('../nuevo_administrador', this.admin).then(response => {
                this.respuesta = response.body;
                if (this.respuesta.estatus === 'OK') {
                    window.location.href = this.$base_url;
                } else {
                    this.$swal({
                        title: "",
                        text: this.respuesta.mensaje,
                        icon: "error"
                    });
                }
                this.admin.usuario = "";
                this.admin.clave = "";
                this.admin.claveconf = "";
            }, error => {
                this.$swal({
                    title: "Error desconocido",
                    text: error.body,
                    icon: "error"
                });
            });
        }
    }
});