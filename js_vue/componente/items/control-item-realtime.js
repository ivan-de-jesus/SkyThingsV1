Vue.component("control-item-realtime", {
	props: {
		mqttclient: Object,
		topico: Object,
	},
	data: function () {
		return {
			isnumero: true,
			datacollection: null,
			etiquetas: [],
			valores: [],
		};
	},
	watch: {
		"topico.valor": function (newVal, oldVal) {
			console.log("Nuvo", newVal);
			this.llenarDatos();
		},
	},
	mounted: function () {
		console.log("mqttclient", this.mqttclient);
		console.log("topico", this.topico);
	},
	methods: {
		llenarDatos: function () {
			this.prepareData();
			this.datacollection = {
				labels: this.etiquetas,
				datasets: [
					{
						label: this.topico.etiqueta,
						data: this.valores,
						lineTension: 0,
						fill: false,
						borderColor: "black",
						backgroundColor: "transparent",
						borderDash: [3, 1],
						pointBorderColor: "gray",
						pointBackgroundColor: "rgba(0,0,0,0.8)",
						pointRadius: 5,
						pointHoverRadius: 10,
						pointHitRadius: 30,
						pointBorderWidth: 2,
						pointStyle: "rectRounded",
					},
				],
			};
		},
		prepareData: function () {
			// inserta el ultimo valor recibido

			this.etiquetas.push(this.$getHora());
			this.valores.push(this.topico.valor);

			//Verifica que los datos sean menores o iguales a 20
			//Si es mayor, elimina el dato mas antiguo

			if (this.etiquetas.length > 20) {
				this.etiquetas.shift();
				this.valores.shift();
			}
		},
	},
	template: [
		'<div class="col-xs-12 col-sm-12 col-md-3, col-lg-4">',
		'<div class="box p-a">',
		'<div v-if="!isnumero" class="pull-left m-r">',
		'<span class="w-48 rounded primary">',
		'<i class="fa fa-question"></i>',
		"</span>",
		"</div>",
		'<div class="clear">',
		'<div class="row">',
		'<div class="col-sm-12 col-lg-12">',
		'<chart-line-realtime v-bind:chartData="datacollection"></chart-line-realtime>',
		"</div>",
		'<div v-if="!isnumero" class="col-sm-12">',
		'<h4 class="m-0 text-sm"> {{ topico.etiqueta }} </h4>',
		"</div>",
		'<div class="col-sm-12">',
		'<div class="text-muted justify-word"> {{ topico.nombre }}</div>',
		"</div>",
		"</div>",
		"</div>",
		"</div>",
		"</div>",
	].join(""),
});
