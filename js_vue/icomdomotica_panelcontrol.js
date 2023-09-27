const panel = new Vue({
	el: "#ctrl_central",
	store: store,
	data: {
		tipousuario: "administrador",
		usuario: "NA",
		sessioninit: false,
		mqttclient: null,
		topicos: [],
		topicosRealtime: [],
		topicosRange: [],
		topicosSwitch: [],
		mqttconfig: {
			isconnected: false,
			isreconecting: false,
			isconnectedmesagge: "Fuera de linea",
			isconnectedsubmesagge: "NA",
			url: null,
			options: {
				connectTimeout: 4000,
				clientId: "mqttjs_",
				username: "",
				password: "",
				keepalive: 60,
				clean: true,
				reconnectPeriod: 4000,
			},
		},
		mensaje: {
			cargando: false,
			tipo: "default",
			texto: null,
		},
		opcionesSnotify: {
			timeout: 2000,
			showProgressBar: true,
			closeOnClick: true,
			pauseOnHover: true,
		},
	},
	computed: {
		...Vuex.mapState(["credenciales"]),
		estatusConexion: function () {
			return {
				danger: !this.mqttconfig.isconnected && !this.mqttconfig.isreconecting,
				success: this.mqttconfig.isconnected && !this.mqttconfig.isreconecting,
				warn: !this.mqttconfig.isconnected && this.mqttconfig.isreconecting,
			};
		},
	},
	mounted: function () {
		this.mqttconfig.url = this.$broker_url;
		this.onLoginMqtt();
	},
	methods: {
		...Vuex.mapMutations(["credencialesUpdate"]),
		onLoginMqtt: function () {
			var tmp = [];
			this.$http.post("ingresar_mqtt").then(
				(response) => {
					tmp = response.body;
					if (tmp.estatus === "OK") {
						this.sessioninit = true;
						this.mqttconfig.options.clientId += tmp.token;
						this.mqttconfig.options.username = tmp.usuario;
						this.mqttconfig.options.password = tmp.clave;
						this.startMqtt();
					}
				},
				(error) => {
					this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
				}
			);
		},
		obtenerTopicos: function () {
			this.topicos = [];
			this.topicosRealtime = [];
			this.topicosRange = [];
			this.topicosSwitch = [];

			this.$http.get("listar_topico").then(
				(response) => {
					this.topicos = response.body;
					console.log("topicos", this.topicos);
					for (var item of this.topicos) {
						if (item.tipo == "realtime") {
							this.topicosRealtime.push(item);
						} else if (item.tipo == "range") {
							this.topicosRange.push(item);
						} else if (item.tipo == "switch") {
							this.topicosSwitch.push(item);
						}
					}
					console.log("TopicoRealtime", this.topicosRealtime);
					console.log("topicosRange", this.topicosRange);
					console.log("topicosSwitch", this.topicosSwitch);
				},
				(error) => {
					this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
				}
			);
		},
		startMqtt: function () {
			if (this.sessioninit) {
				this.obtenerTopicos();
				this.mqttclient = window.MQTT.connect(
					this.mqttconfig.url,
					this.mqttconfig.options
				);
				this.mqttconfig.isconnected = this.mqttclient.connected;
				this.mqttclient.on("connect", () => {
					this.mqttconfig.isconnected = true;
					this.mqttconfig.isreconecting = false;
					this.mqttconfig.isconnectedsubmesagge = "OK";
					this.displayEstatus();

					for (var realTime of this.topicosRealtime) {
						console.log("VariableRealtime", realTime);
						this.mqttclient.subscribe(
							realTime.nombre,
							{ qos: 0 },
							(error, granted) => {
								if (error) {
								}
								if (granted) {
								}
							}
						);
					}

					for (var switc of this.topicosSwitch) {
						this.mqttclient.subscribe(
							switc.nombre,
							{ qos: 0 },
							(error, granted) => {
								if (error) {
								}
								if (granted) {
								}
							}
						);
					}

					for (var range of this.topicosRange) {
						if (range.propiedades.addswitch) {
							this.mqttclient.subscribe(
								range.nombre,
								{ qos: 0 },
								(error, granted) => {
									if (error) {
									}
									if (granted) {
									}
								}
							);
						}
					}
				});

				this.mqttclient.on("reconnect", (error) => {
					this.mqttconfig.isconnected = false;
					this.mqttconfig.isreconecting = true;
					this.mqttconfig.isconnectedsubmesagge = "Reconectando";
					if (error) {
						this.mqttconfig.isconnectedsubmesagge = "Error al reconectar";
						this.mqttconfig.isconnected = false;
						this.mqttconfig.isreconecting = false;
					}
					this.displayEstatus();
				});

				this.mqttclient.on("error", (error) => {
					var err = error.toString();
					if (err.indexOf("Not authorized", 0)) {
						this.mqttconfig.isconnected = false;
						this.mqttconfig.isconnectedsubmesagge = "Acceso denegado";
					} else {
						this.mqttconfig.isconnected = false;
						this.mqttconfig.isconnectedsubmesagge = err;
					}
					this.displayEstatus();
					this.mqttclient.end();
				});

				this.mqttclient.on("message", (topic, message) => {
					console.log("TOPIC", topic);
					console.log("MESSAGE", message.toString());
					var i = this.topicos.findIndex((t) => t.nombre == topic);
					if (i >= 0) {
						var tmpi = -1;
						var msj = "";

						if (this.topicos[i].tipo === "switch") {
							tmpi = this.topicosSwitch.findIndex((t) => t.nombre == topic);
							this.topicosSwitch[tmpi].propiedades.estado = message.toString();
						} else if (this.topicos[i].tipo === "realtime") {
							tmpi = this.topicosRealtime.findIndex((t) => t.nombre == topic);
							msj = message.toString();
							console.log("Realtimemsj", msj);
							if (this.topicos[i].propiedades.tipodato === "num") {
								if (!isNaN(parseInt(msj, 10))) {
									this.topicosRealtime[tmpi].valor = parseInt(msj, 10);
								}
							} else {
								this.topicosRealtime[tmpi].valor = msj;
							}
						} else if (this.topicos[i].tipo === "range") {
							tmpi = this.topicosRange.findIndex((t) => t.nombre == topic);
							msj = message.toString();

							if (
								this.topicosRange[tmpi].propiedades.addswitch &&
								(msj === "ON" || msj === "OFF")
							) {
								this.topicosRange[tmpi].propiedades.estadoswitch =
									message.toString();
							}
						} else {
							console.log(message.toString());
						}
					} else {
						console.log(message.toString());
					}
				});
			}
		},
		publicar: function (topico) {
			console.log("entro aqui");
			if (this.mqttclient) {
				this.mqttclient.publish(
					topico.nombre,
					topico.estatus ? "OFF" : "ON",
					(error) => {
						if (!error) {
							topico.estatus = !topico.estatus;
						}
					}
				);
			}
		},
		verificarUsuario: function () {
			var tmp = [];
			this.$http.get("verificar_usuario").then(
				(response) => {
					tmp = response.body;
					if (tmp.estatus === "OK") {
						let cred = {
							usuario: tmp.usuario,
							isAdmin: tmp.isAdmin,
						};

						this.credencialesUpdate(cred);
					} else {
						this.$respuestaParse(response, this.mensaje, this.opcionesSnotify);
					}
				},
				(error) => {
					this.$respuestaError(error, this.mensaje, this.opcionesSnotify);
				}
			);
		},
		displayEstatus: function () {
			if (this.mqttconfig.isconnected) {
				this.mqttconfig.isconnectedmesagge = "En linea";
			} else {
				this.mqttconfig.isconnectedmesagge = "Fuera de linea";
			}
		},
	},
	created: function () {
		this.verificarUsuario();
	},
});
