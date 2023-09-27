<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Mqtt_model
 *
 * @author 
 */
class Mqtt_model extends CI_Model {

    // variable (array) para almacenar la respuesta en el formato estatus, mensaje
    protected $data;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('iconst'));

        // carga util predeterminada..
        $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error desconocido");
    }

    public function login($usuario, $clave) {
        $this->db->trans_start();
        $this->db->query('call login_mqtt(?,?,@respuesta)', array($usuario, $clave));
        $response = $this->db->query('select @respuesta as respuesta');
        $this->db->trans_complete();
        $tmp = $response->row();

        if ($tmp) {
            switch ($tmp->respuesta) {
                case iconst_helper::EXISTE:
                    $data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Acceso concedido", "token" => rand(1, 9999));
                    break;
                case iconst_helper::NA:
                    $data = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Usuario o clave erroneas");
                    break;
                default:
                    break;
            }
        } else {
            $er = $this->db->error();
            $data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => $er->code . ":" . $er->message);
        }

        return $data;
    }

    public function get($idusr, $tipo) {
        $response = $this->db->query('call mqtt_get(?,?)', array($idusr, $tipo));
        $tmp = $response->row();

        if ($tmp) {
            $data = array(iconst_helper::ESTATUS => iconst_helper::OK, 'usuario' => $tmp->username, 'clave' => $tmp->password, "token" => rand(1, 9999));
        } else {
            $er = $this->db->error();
            $data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => $er->code . ":" . $er->message);
        }

        return $data;
    }

    public function add($usuario, $clave, $idadmin) {
        $this->db->trans_start();
        $this->db->query('call mqtt_add(?,?,?,@respuesta)', array($usuario, $clave, $idadmin));
        $response = $this->db->query('select @respuesta as respuesta');
        $this->db->trans_complete();
        $tmp = $response->row();

        if ($tmp) {
            switch ($tmp->respuesta) {
                case iconst_helper::OK:
                    $data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Registro correcto");
                    break;
                case iconst_helper::EXISTE:
                    $data = array(iconst_helper::ESTATUS => iconst_helper::EXISTE, iconst_helper::MENSAJE => "El usuario ingresado ya existe");
                    break;
                default:
                    break;
            }
        } else {
            $er = $this->db->error();
            $data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => $er->code . ":" . $er->message);
        }

        return $data;
    }

    public function hay_registro($idadmin) {
        $response = $this->db->query('call admin_tiene_usrmqtt(?)', array($idadmin));
        $tmp = $response->row();

        if ($tmp) {
            if ($tmp->mqtt_user_id) {
                $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Tiene usuario mqtt registrado");
            } else {
                $this->data = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Registre un usuario mqtt");
            }
        } else {
            $er = $this->db->error();
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => $er->code . ":" . $er->message);
        }

        return $this->data;
    }

}
