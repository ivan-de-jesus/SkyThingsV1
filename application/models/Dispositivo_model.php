<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Dispositivo_model
 *
 * @author 
 */
class Dispositivo_model extends CI_Model {
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
    
    public function add($serie, $id_lugar, $id_admin) {
        $doc = $this->conn->dispositivo->insertOne([
            'serie' => $serie,
            'lugarid' => new MongoDB\BSON\ObjectId($id_lugar),
            'adminid' => new MongoDB\BSON\ObjectId($id_admin)
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
        $cursor = $this->conn->dispositivo->find(['adminid' => new MongoDB\BSON\ObjectId($id_admin)]);
        foreach ($cursor as $row) {
            $this->data[] = array(
                'id' => (string) $row['_id'],
                'serie' => $row['serie'],
                'lugar' => (string) $row['lugarid']
            );
        }
        return $this->data;
    }
}
