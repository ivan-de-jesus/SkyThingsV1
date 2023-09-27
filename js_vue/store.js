
const store = new Vuex.Store({
    state: {
        refrescarTopicos: false,
        refrescarCamaras: false,
        credenciales: {
            usuario: "NA",
            isAdmin: false
        }
    },
    mutations: {
        refrescarTopicosUpdate: function (state) {
            state.refrescarTopicos = !state.refrescarTopicos;
        },
        refrescarCamarasUpdate: function (state) {
            state.refrescarCamaras = !state.refrescarCamaras;
        },
        credencialesUpdate: function (state, dat) {
            state.credenciales = dat;
        }
    }
});