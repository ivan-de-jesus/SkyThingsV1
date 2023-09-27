
Vue.component('pie-pagina', {
    template: [
        '<div class="app-footer">',
            '<div class="p-2 text-xs">',
                '<div class="nav">',
                    '<a class="nav-link" href="" v-on:click.prevent="logout">Salir</a>',
                '</div>',
            '</div>',
        '</div>'
    ].join(''),
    data: function () {
        return {
            respuesta: []
        };
    },
    methods: {
        logout: function() {
            this.$http.post('salir').then(response => {
                this.respuesta = response.body;
                if (this.respuesta.estatus === 'SESSION_NULL') {
                    this.borrarDataStorage();
                    window.location.href = this.$base_url;
                } else {
                    this.$swal({
                        title: "",
                        text: this.respuesta.mensaje,
                        icon: "error"
                    });
                }
            }, error => {
                this.$swal({
                    title: "Error desconocido",
                    text: error.bodytext,
                    icon: "error"
                });
            });
        }, 
        borrarDataStorage: function () {
            if (this.$almacenDisponible()) {
                localStorage.removeItem('tipo_usr');
                localStorage.removeItem('usuario');
            }
        }
    }
});