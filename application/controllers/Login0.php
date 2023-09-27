<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Login0
 *
 * @author 
 */
class Login0 extends CI_Controller {

    //almacena los datos enviados desde las peticiones ajax
    private $request;

    /*
     * datos de sesion a almacenar
     *  --> logged (valores de true y false)
     *  --> idusr (valores enteros)
     *  --> nombreusr (valores alfanumericos)
     *  --> tipousr (valores administrador, invitado)
     *      ---> mas los que sean necesarios
     */

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('iotronics', 'iconst'));
        $this->load->library(array('session'));

        $this->request = json_decode(file_get_contents('php://input'));
    }

    public function ingresar() {
        $retorno = array();
        if (!is_login()) {
            if ($this->request->tipo == "administrador") {
                $this->load->model("admin_model");
                $secure_pass = hash("sha256", $this->request->clave);
                $retorno = $this->admin_model->login($this->request->usuario, $secure_pass);
                if ($retorno[iconst_helper::ESTATUS] == iconst_helper::OK) {
                    $user = array(
                        "logged" => true,
                        "idusr" => $retorno["identificador"],
                        "nombreusr" => $this->request->usuario,
                        "clavemqtt" => $this->request->clave,
                        "tipousr" => "administrador",
                        "isAdmin" => true);
                    $this->session->set_userdata($user);
                }
            } else if ($this->request->tipo == "invitado") {
                $this->load->model("invitado_model");
                $secure_pass = hash("sha256", $this->request->clave);
                $retorno = $this->invitado_model->login($this->request->usuario, $secure_pass);
                if ($retorno[iconst_helper::ESTATUS] == iconst_helper::OK && $retorno['primerlogin'] == "1") {
                    $user = array(
                        "logged" => true,
                        "idusr" => $retorno["identificador"],
                        "nombreusr" => $this->request->usuario,
                        "clavemqtt" => $this->request->clave,
                        "tipousr" => "invitado",
                        "isAdmin" => false);
                    $this->session->set_userdata($user);
                }
            }
        }
        echo json_encode($retorno);
    }

    public function ingresar_mqtt() {
        $retorno = array();
        if (is_login() && !item_get("token") && !item_get("mqttusuario") && !item_get("mqttclave")) {
            $items_delete = array('clavemqtt');
            $retorno = array(iconst_helper::ESTATUS => iconst_helper::OK, 'usuario' => item_get("nombreusr"), 'clave' => item_get("clavemqtt"), "token" => rand(1, 9999));
            $user = array(
                "token" => $retorno['token'],
                "mqttusuario" => $retorno['usuario'],
                "mqttclave" => $retorno['clave']);
            $this->session->set_userdata($user);
            $this->session->unset_userdata($items_delete);
        } else if (is_login() && item_get("token") && item_get("mqttusuario") && item_get("mqttclave")) {
            $retorno = array(iconst_helper::ESTATUS => iconst_helper::OK, 'usuario' => item_get("mqttusuario"), 'clave' => item_get("mqttclave"), "token" => item_get("token"));
        }
        echo json_encode($retorno);
    }

    public function verificar_usuario() {
        if (is_login()) {
            
            $usr = "NA";
            
            if (item_get("nombreusr")) {
                $tmpusr = explode("@", item_get("nombreusr"));
                $usr = $tmpusr[0];
            }
            
            echo json_encode(array(iconst_helper::ESTATUS => iconst_helper::OK, 
                'isAdmin' => item_get("isAdmin"), 
                'usuario' => $usr
                ));
        }
    }

    public function salir() {
        $items_delete = array('logged', 'idusr', 'tipousr', 'token', 'mqttusuario', 'mqttclave', 'isAdmin');
        $this->session->unset_userdata($items_delete);
        if (is_login()) {
            $retorno = array(iconst_helper::ESTATUS => iconst_helper::SESSION_INIT, iconst_helper::MENSAJE => 'Error al cerrar sesión');
        } else {
            $retorno = array(iconst_helper::ESTATUS => iconst_helper::SESSION_NULL, iconst_helper::MENSAJE => 'Sesión no iniciada');
        }
        $this->session->sess_destroy();
        echo json_encode($retorno);
    }

    public function add_admin() {
        $retorno = array();
        if (!is_login()) {
            $usuario = $this->request->usuario;
            $clave = $this->request->clave;
            $claveconf = $this->request->claveconf;

            if (strcmp($clave, $claveconf) == 0) {
                $this->load->model("admin_model");
                $secure_pass = hash("sha256", $clave);
                $retorno = $this->admin_model->add($usuario, $secure_pass);
            } else {
                $retorno = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Las claves no son iguales");
            }
        }
        echo json_encode($retorno);
    }

    public function add_invitado() {
        $retorno = array();
        if (!is_login()) {
            $usuario = $this->request->usuario;
            $clave = $this->request->clave;
            $claveconf = $this->request->claveconf;

            if (strcmp($clave, $claveconf) == 0) {
                $this->load->model("invitado_model");
                $secure_pass = hash("sha256", $clave);
                $retorno = $this->invitado_model->add($usuario, $secure_pass);
            } else {
                $retorno = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Las claves no son iguales");
            }
        }
        echo json_encode($retorno);
    }

    public function add_mqtt() {
        $retorno = array();
        if (is_login()) {
            $usuario = $this->request->usuario;
            $clave = $this->request->clave;
            $claveconf = $this->request->claveconf;

            if (strcmp($clave, $claveconf) == 0) {
                $this->load->model("mqtt_model");
                $retorno = $this->mqtt_model->add($usuario, $clave, item_get("idusr"));
            } else {
                $retorno = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Las claves no son iguales");
            }
        }
        echo json_encode($retorno);
    }

    public function cambio_clave_invitado() {
        $retorno = array();
        if (!is_login()) {
            $usuario = $this->request->usuario;
            $claveactual = $this->request->actual;
            $clavenueva = $this->request->nueva;
            $clavenuevaconf = $this->request->nuevaconf;

            if (strcmp($clavenueva, $clavenuevaconf) == 0) {
                $secure_old_pass = hash("sha256", $claveactual);
                $secure_new_pass = hash("sha256", $clavenueva);
                $this->load->model("invitado_model");
                $retorno = $this->invitado_model->cambio_clave($usuario, $secure_old_pass, $secure_new_pass);
            } else {
                $retorno = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Las claves no son iguales");
            }
        }
        echo json_encode($retorno);
    }
    
    private function is_usuario_mqtt_registrado() {
        if (is_login()) {
            $this->load->model("mqtt_model");
            $retorno = $this->mqtt_model->hay_registro(item_get("idusr"));
            if ($retorno[iconst_helper::ESTATUS] == iconst_helper::OK) {
                return 'SI';
            } else if ($retorno[iconst_helper::ESTATUS] == iconst_helper::NA) {
                return 'NO';
            } else {
                return 'ERROR';
            }
        }
    }

}
