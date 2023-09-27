

/*
******************************
****** PROCESOS  *************
******************************
*/



function update_values(temp1, temp2, volts){
  $("#display_temp1").html(temp1);
  $("#display_temp2").html(temp2);
  $("#display_volt").html(volts);
}



function process_msg(topic, message){
  // ej: "10,11,12"
  if (topic == "values"){
    var msg = message.toString();
    var sp = msg.split(",");
    var temp1 = sp[0];
    var temp2 = sp[1];
    var volts = sp[2];
    var status;
    update_values(temp1,temp2,volts,);
  }
}




function process_luz(){
  if ($('#input_luz').is(":checked")){
    console.log("Encendido");


    client.publish('luz', 'luz_on', (error) => {
      console.log(error || 'Mensaje enviado!!!')


    })
  }else{
    console.log("Apagado");
    client.publish('luz', 'luz_off', (error) => {
      console.log(error || 'Mensaje enviado!!!')
    })
  }
}

function process_puerta(){
  if ($('#input_puerta').is(":checked")){
    console.log("Encendido");

    client.publish('puerta', 'puerta_on', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }/*else{
    console.log("Apagado");
    client.publish('puerta', '4', (error) => {
      console.log(error || 'Mensaje enviado!!!')
    })
  }*/
}

function process_porton(){
  if ($('#input_porton').is(":checked")){
    console.log("Encendido");

    client.publish('porton', 'porton_on', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }else{
    console.log("Apagado");
    client.publish('porton', 'porton_off', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }
}

function process_foco(){
  if ($('#input_foco').is(":checked")){
    console.log("Encendido");

    client.publish('foco', 'foco_on', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }else{
    console.log("Apagado");
    client.publish('foco', 'foco_off', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }
}


function process_contacto(){
  if ($('#input_contacto').is(":checked")){
    console.log("Encendido");

    client.publish('contacto', 'contacto_on', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }else{
    console.log("Apagado");
    client.publish('contacto', 'contacto_off', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }
}

function process_interruptor6(){
  if ($('#input_interruptor6').is(":checked")){
    console.log("Encendido");

    client.publish('interruptor6', 'C', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }else{
    console.log("Apagado");
    client.publish('interruptor6', 'D', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    });
  }
}




/*
******************************
****** CONEXION  *************
******************************
*/

// connect options
const options = {
      connectTimeout: 4000,

      // Authentication
      clientId: 'prueba_14',
      username: 'web_client',
      password: '111',

      keepalive: 60,
      clean: true
};

var connected = false;

// WebSocket connect url
const WebSocket_URL = 'wss://icomtronics.tk:8094/mqtt';


const client = mqtt.connect(WebSocket_URL, options);


client.on('connect', () => {
    console.log('Mqtt conectado por WS! Exito!');

    client.subscribe('values', 'estado',{ qos: 0 }, (error) => {
      if (!error) {
        console.log('Suscripción exitosa!');
      }else{
        console.log('Suscripción fallida!');
      }
    });

    // publish(topic, payload, options/callback)
    //client.publish('fabrica', 'Hola mqx esto es un verdadero éxito', (error) => {
    //  console.log(error || 'Mensaje enviado!!!')
    //})
});

client.on('message', (topic, message) => {
  console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString());
  process_msg(topic, message);
});

client.on('reconnect', (error) => {
    console.log('Error al reconectar', error);
});

client.on('error', (error) => {
    console.log('Error de conexión:', error);
});