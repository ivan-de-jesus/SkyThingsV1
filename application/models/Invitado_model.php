<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invitado_model extends CI_Model {

    // variable (array) para almacenar la respuesta en el formato estatus, mensaje
    protected $data;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('iconst'));
        $this->load->library(array('mongolib'));

        // carga util predeterminada..
        $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error desconocido");
        $this->conn = $this->mongolib->bd;
    }

    public function login($usuario, $clave) {
        $doc = $this->conn->invitado->findOne([
            'usuario' => $usuario,
            'clave' => $clave
                ], [
            'projection' => [
                '_id' => 1,
                'primer_login' => 1
            ]
        ]);

        if ($doc) {
            $data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Acceso concedido", "identificador" => (string) $doc['_id'], "primerlogin" => $doc['primer_login']);
        } else {
            $data = array(iconst_helper::ESTATUS => iconst_helper::NA, iconst_helper::MENSAJE => "Usuario o clave erroneas");
        }

        return $data;
    }

    public function add($usuario, $clave, $id_admin) {
        $search = $this->conn->invitado->findOne([
            'usuario' => $usuario,
            'clave' => $clave
                ], [
            'projection' => [
                "_id" => 1
            ]
        ]);

        if (!$search) {
            $doc_invitado = $this->conn->invitado->insertOne([
                'usuario' => $usuario,
                'clave' => $clave,
                'primer_login' => 0,
                'adminid' => new MongoDB\BSON\ObjectId($id_admin)
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

    public function listar($id_admin) {
        $datos = array();
        $cursor = $this->conn->invitado->find(['adminid' => new MongoDB\BSON\ObjectId($id_admin)]);
        
        foreach ($cursor as $row) {
            $datos[] = array(
                'id' => (string) $row['_id'],
                'usuario' => $row['usuario'],
                'primerlogin' => ($row['primer_login'] == "0" ? "NO" : "SI")
            );
        }
        return $datos;
    }

    public function cambio_clave($usuario, $clave_antigua, $clave_nueva) {
        $actualizar = $this->conn->invitado->updateOne(['usuario' => $usuario, 'clave' => $clave_antigua], ['$set' => ['clave' => $clave_nueva, 'primer_login' => 1]]);
        $actualizarmqtt = $this->conn->mqtt_user->updateOne(['username' => $usuario, 'password' => $clave_antigua], ['$set' => ['password' => $clave_nueva]]);
        
        if ($actualizar->getModifiedCount() > 0 && $actualizarmqtt->getModifiedCount() > 0) {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Clave editada");
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error al editar, verifique sus datos");
        }
        
        return $this->data;
    }

    public function hash_clave($clave) {
        $hashed = hash('sha512', $clave);
        return $hashed;
    }

}
