Vue.component("control-item-switch", {
	props: {
		mqttclient: Object,
		topico: Object,
	},
	data: function () {
		return {
			estatus: false,
		};
	},
	mounted: function () {},
	methods: {
		publicar: function () {
			console.log(this.mqttclient);
			if (this.mqttclient) {
				console.log(this.topico.nombre);
				this.mqttclient.publish(
					this.topico.nombre,
					this.topico.propiedades.estado === "ON" ? "OFF" : "ON",
					(error) => {
						if (!error) {
							this.estatus = !(this.topico.propiedades.estado === "ON");
						}
					}
				);
			}
		},
	},
	created: function () {
		this.estatus = this.topico.propiedades.estado === "ON";
	},
	template: [
		'<div class="col-xs-12 col-sm-12 col-md-3, col-lg-4">',
		'<div class="box p-a">',
		'<div class="row">',
		'<div class="col-sm-2">',
		'<label class="ui-switch ui-switch-md info m-t-xs">',
		'<input v-bind:id=\'"topic" + topico.id\' v-model="estatus" v-on:click=\'publicar\' type="checkbox" >',
		"<i></i>",
		"</label>",
		"</div>",
		'<div class="col-sm-10">',
		'<div class="row">',
		'<div class="col-sm-12">',
		'<label class="form-control-label">{{ topico.etiqueta }}</label>',
		"</div>",
		'<div class="col-sm-12">',
		'<div class="text-muted justify-word">{{ topico.nombre }}</div>',
		"</div>",
		"</div>",
		"</div>",
		"</div>",
		"</div>",
		"</div>",
	].join(""),
});
