<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Admin_model
 *
 * @author
 */
class Admin_model extends CI_Model {

    // variable (array) para almacenar la respuesta en el formato estatus, mensaje
    protected $data;
    private $conn;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('iconst'));
        $this->load->library(array('mongolib'));

        // carga util predeterminada..
        $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error desconocido");
        $this->conn = $this->mongolib->bd;
    }

    public function login($usuario, $clave) {
        $doc = $this->conn->admin->findOne([
            'usuario' => $usuario,
            'clave' => $clave
                ], [
            'projection' => [
                "_id" => 1
            ]
        ]);

        if ($doc) {
            $data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Acceso concedido", "identificador" => (string) $doc["_id"]);
        } else {
            $data = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Usuario o clave erroneas");
        }

        return $data;
    }

    public function add($usuario, $clave) {
        $search = $this->conn->admin->findOne([
            'usuario' => $usuario,
            'clave' => $clave
                ], [
            'projection' => [
                "_id" => 1
            ]
        ]);

        if (!$search) {
            $doc_admin = $this->conn->admin->insertOne([
                'usuario' => $usuario,
                'clave' => $clave
            ]);

            $doc_mqtt = $this->conn->mqtt_user->insertOne([
                'username' => $usuario,
                'password' => $clave,
                'is_superuser' => false,
                'salt' => ''
            ]);
            
            $data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Registro correcto");
        } else {
            $data = array(iconst_helper::ESTATUS => iconst_helper::EXISTE, iconst_helper::MENSAJE => "El usuario ingresado ya existe");
        }

        return $data;
    }

    public function hash_clave($clave) {
        $hashed = hash('sha256', $clave);
        return $hashed;
    }

}
