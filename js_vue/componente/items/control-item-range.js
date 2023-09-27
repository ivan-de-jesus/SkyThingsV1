
Vue.component('control-item-range', {
    props: {
        mqttclient: Object,
        topico: Object
    },
    data: function () {
        return {
            estatus: false,
            valor: 1
        };
    },
    computed: {
        viewSwitch: function () {
            return {
                'col-sm-10 col-lg-10': this.topico.propiedades.addswitch,
                'col-sm-12 col-lg-12': !this.topico.propiedades.addswitch
            };
        }
    },
    methods: {
        publicar: function () {
            if (this.mqttclient) {
                this.mqttclient.publish(this.topico.nombre, this.valor, (error) => {
                    console.log(error || "OK");
                });
            }
        },
        publicarSwitch: function () {
            if (this.mqttclient) {
                this.mqttclient.publish(this.topico.nombre, (this.topico.propiedades.estadoswitch === "ON") ? "OFF" : "ON", (error) => {
                    if (!error) {
                        this.estatus = !(this.topico.propiedades.estadoswitch === "ON");
                    }
                });
            }
        }
    },
    template: [
        '<div class="col-xs-12 col-sm-12 col-md-3, col-lg-4">',
            '<div class="box p-a">',
                '<div class="row">',
                    '<div class="col-sm-12 col-lg-12">',
                        '<div class="row">',
                            '<div class="col-sm-8 col-lg-8">',
                                '<div class="form-group">',
                                    '<label class="form-control-label">{{ topico.etiqueta }}</label>',
                                    '<input class="form-control-range" v-on:input="publicar" type="range" min="1" max="100" step="1" v-model="valor">',
                                '</div>',
                            '</div>',
                            '<div class="col-xs-12 col-sm-4 col-lg-4">',
                                '<VueSvgGauge ',
                                'v-bind:start-angle="-110" ',
                                'v-bind:end-angle="110" ',
                                'v-bind:value="parseInt(valor, 10)" ',
                                'v-bind:separator-step="20" ',
                                'v-bind:scale-interval="10" ',
                                'v-bind:inner-radius="80"',
                                '>',
                                '<div class="inner-text inner-text--3"><span> {{ valor }} </span></div>',
                                '</VueSvgGauge>',
                            '</div>',
                        '</div>',
                    '</div>',
                    '<div v-if="this.topico.propiedades.addswitch" class="col-sm-2 col-lg-2">',
                        'Poder',
                        '<label class="ui-switch ui-switch-md info m-t-xs">',
                            '<input v-model="estatus" v-on:click="publicarSwitch" type="checkbox" >',
                            '<i></i>',
                        '</label>',
                    '</div>',
                    '<div v-bind:class="viewSwitch">',
                        '<div class="text-muted justify-word">{{ topico.nombre }}</div>',
                    '</div>',
                '</div>',
            '</div>',
        '</div>'
    ].join('')
});


