<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Permiso_model
 *
 * @author 
 */
class Permiso_model extends CI_Model {

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
    
    public function permisos_topico($lugar_id, $invitado_id, $admin_id) {
        $this->data = array();
        
        $cursor = $this->conn->topico->find([
                "adminid" => new MongoDB\BSON\ObjectId($admin_id), 
                "lugarid" => new MongoDB\BSON\ObjectId($lugar_id)
            ]);
        
        foreach ($cursor as $row) {
            
            $permit = $this->busqueda_invitado($row['invitados'], $invitado_id);
            
            $this->data[] = array(
                'id' => (string) $row['_id'],
                'etiqueta' => $row['etiqueta'],
                'nombre' => $row['nombre'],
                'tipo' => $row['tipo'],
                'permitido' => ($permit ? 1 : 0)
            );
        }
        return $this->data;
    }
    
    public function get_topico($invitado_id, $topico_id) {
        $this->data = array();
        $doc = $this->conn->topico->findOne(['_id' => new MongoDB\BSON\ObjectId($topico_id)], []);
        
        if ($doc) {
            
            $permit = $this->busqueda_invitado($doc['invitados'], $invitado_id);
            
            $this->data = array(
                'id' => (string) $doc['_id'],
                'etiqueta' => $doc['etiqueta'],
                'nombre' => $doc['nombre'],
                'permitido' => ($permit ? 1 : 0)
            );
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Control no localizado");
        }
        
        return $this->data;
    }
    
    public function add_invitado($id_invitado, $id_topico, $id_admin) {
        $update = $this->conn->topico->updateOne([
                '_id' => new MongoDB\BSON\ObjectId($id_topico),
                'adminid' => new MongoDB\BSON\ObjectId($id_admin)
            ], [
                '$addToSet' => ['invitados' => new MongoDB\BSON\ObjectId($id_invitado)]
            ]);

        $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "");
        
        return $this->data;
    }

    public function remove_invitado($id_invitado, $id_topico, $id_admin) {
        $update = $this->conn->topico->updateOne([
                '_id' => new MongoDB\BSON\ObjectId($id_topico),
                'adminid' => new MongoDB\BSON\ObjectId($id_admin)
            ], [
                '$pull' => ['invitados' => new MongoDB\BSON\ObjectId($id_invitado)]
            ]);

        $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "");
        
        return $this->data;
    }
    
    private function busqueda_invitado($array, $id_invitado) {
        $match = false;
        
        foreach ($array as $key) {
            if ($key == $id_invitado) {
                $match = true;
                break;
            }
        }
        
        return $match;
    }

}
