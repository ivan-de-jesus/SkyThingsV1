<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Lugar_model
 *
 * @author 
 */
class Lugar_model extends CI_Model {

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

    public function add($descripcion, $id_admin) {
        $doc = $this->conn->lugar->insertOne([
            'descripcion' => $descripcion,
            'adminid' => new MongoDB\BSON\ObjectId($id_admin)
        ]);

        if ($doc->getInsertedCount() > 0) {
            $data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Registro correcto");
        } else {
            $data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error al registrar");
        }

        return $data;
    }

    public function listar($id_admin) {
        $datos = array();
        $cursor = $this->conn->lugar->find(['adminid' => new MongoDB\BSON\ObjectId($id_admin)]);
        foreach ($cursor as $row) {
            $datos[] = array(
                'id' => (string) $row['_id'],
                'descripcion' => $row['descripcion'],
                'adminid' => (string) $row['adminid']
            );
        }
        return $datos;
    }

}
