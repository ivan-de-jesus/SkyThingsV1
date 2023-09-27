<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Camara_model extends CI_Model {

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

    public function add($etiqueta, $ip_host, $puerto, $id_admin) {
        $doc = $this->conn->camara->insertOne([
            'etiqueta' => $etiqueta,
            'iphost' => $ip_host,
            'puerto' => $puerto,
            'adminid' => new MongoDB\BSON\ObjectId($id_admin),
            'invitados' => []
        ]);

        if ($doc->getInsertedCount() > 0) {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Registro correcto");
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error al registrar");
        }

        return $this->data;
    }

    public function listar($id_admin) {
        $this->data = array();
        $cursor = $this->conn->camara->find(['adminid' => new MongoDB\BSON\ObjectId($id_admin)]);
        $invitados = array();
        foreach ($cursor as $row) {
            
            foreach ($row['invitados'] as $invitado) {
                $invitados[] = (string) $invitado;
            }
            
            $this->data[] = array(
                'id' => (string) $row['_id'],
                'etiqueta' => $row['etiqueta'],
                'iphost' => $row['iphost'],
                'puerto' => $row['puerto'],
                'adminid' => (string) $row['adminid'],
                'invitados' => $invitados
            );
        }
        return $this->data;
    }
    
    public function add_invitado($id_invitado, $id_camara, $id_admin) {
        $update = $this->conn->camara->update([
            '_id' => new MongoDB\BSON\ObjectId($id_camara),
            'adminid' => new MongoDB\BSON\ObjectId($id_admin)
        ], [
            '$addToSet' => ['invitados' => new MongoDB\BSON\ObjectId($id_invitado)]
        ]);

        if ($update->getModifiedCount() > 0) {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Permiso agregado");
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error al agregar permiso");
        }

        return $this->data;
    }
    
    public function remove_invitado($id_invitado, $id_camara) {
        $update = $this->conn->camara->update([
            '_id' => new MongoDB\BSON\ObjectId($id_camara),
            'invitados' => new MongoDB\BSON\ObjectId($id_invitado)
        ], [
            '$pop' => ['invitados' => new MongoDB\BSON\ObjectId($id_invitado)]
        ]);

        if ($update->getModifiedCount() > 0) {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Permiso agregado");
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error al agregar permiso");
        }

        return $this->data;
    }

}
