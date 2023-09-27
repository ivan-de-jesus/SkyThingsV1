
const menu = new Vue({
    el: '#ctrl_menu',
    data: {
        mqttreg: "ERROR",
        tipousuario: "invitado",
        usuario: "NA"
    }, 
    mounted: function () {
        if (this.almacenDisponible()) {
            let tipo = localStorage.getItem('tipo_usr');
            let usuario = localStorage.getItem('usuario');
            if (tipo && usuario) {
                this.tipousuario = tipo;
                this.usuario = usuario;
            } else {
                this.verificarUsuario();
            }
        } else {
            this.verificarUsuario();
        }
    },
    methods: {
        almacenDisponible: function () {
            try {
                var type = 'localStorage';
                var storage = window[type], x = '__storage_test__';
                storage.setItem(x, x);
                storage.removeItem(x);
                return true;
            } catch (e) {
                return e instanceof DOMException && (
                        e.code === 22 ||
                        e.code === 1014 ||
                        e.name === 'QuotaExceededError' ||
                        e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
                        storage.length !== 0;
            }
        },
        almacenSet: function (clave, valor) {
            if (this.almacenDisponible()) {
                localStorage.setItem(clave, valor);
            }
        },
        verificarUsuario: function () {
            var tmp = [];
            this.$http.get('verificar_usuario').then(response => {
                tmp = response.body;
                if (tmp.estatus === 'OK') {
                    this.tipousuario = tmp.tipo;
                    this.usuario = tmp.usuario;
                    this.mqttreg = tmp.mqttreg;
                    this.almacenSet('tipo_usr', this.tipousuario);
                    this.almacenSet('usuario', this.usuario);
                    this.almacenSet('mqttreg', tmp.mqttreg);
                }
            });
        }
    },
    created: function () {
        
    }
});