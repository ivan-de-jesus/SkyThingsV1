<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Topico_model extends CI_Model {

    // variable (array) para almacenar la respuesta en el formato estatus, mensaje
    protected $data;
    protected $conn;

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('iconst'));
        $this->load->library(array('mongolib'));

        // carga util predeterminada..
        $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error desconocido");
        $this->conn = $this->mongolib->bd;
    }

    public function add($etiqueta, $tipo, $id_admin, $id_dispositivo, $propiedades = array()) {
        //buscar informacion del dispositivo
        $disp = $this->conn->dispositivo->findOne([
                    "_id" => new MongoDB\BSON\ObjectId($id_dispositivo),
                    "adminid" => new MongoDB\BSON\ObjectId($id_admin)
                ], [
                    "projection" => [
                        "serie" => 1,
                        "lugarid" => 1
                    ]
                ]);
        
        if ($disp) {
            // usado para generar el identificador unico para el nombre del topico
            $tiempo_base = time();
            //nombre del topico compuesto por el numero de serie del dispositivo mas el hash resultado de combinar el numero de serie y el tiempo base
            $numeroSerie = $disp["serie"];
            $id_lugar = (string) $disp["lugarid"];
            $nombre = $numeroSerie . "/" . hash("md5", ($numeroSerie . $tiempo_base));

            //Verificar que no haya topicos con la etiqueta ingresada 
            $buscar = $this->conn->topico->findOne(['etiqueta' => $etiqueta], []);

            if (!$buscar) {
                $doc = $this->conn->topico->insertOne([
                    'nombre' => $nombre,
                    'etiqueta' => $etiqueta,
                    'tipo' => $tipo,
                    'propiedades' => $propiedades,
                    'adminid' => new MongoDB\BSON\ObjectId($id_admin),
                    'dispositivoid' => new MongoDB\BSON\ObjectId($id_dispositivo),
                    'lugarid' => new MongoDB\BSON\ObjectId($id_lugar),
                    'invitados' => []
                ]);

                if ($doc->getInsertedCount() > 0) {
                    $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "Registro correcto");
                } else {
                    $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Error al registrar");
                }
            } else {
                $this->data = array(iconst_helper::ESTATUS => iconst_helper::EXISTE, iconst_helper::MENSAJE => "La etiqueta [ $etiqueta ] ya existe");
            }
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "Dispositivo no localizado");
        }
        
        return $this->data;
    }

    public function listar($idusr, $tipousr) {
        $this->data = array();
        $filtro = (($tipousr == iconst_helper::USUARIO_ADMINISTRADOR) ? 'adminid' : 'invitados');

        $cursor = $this->conn->topico->find([$filtro => new MongoDB\BSON\ObjectId($idusr)]);

        foreach ($cursor as $row) {
            $this->data[] = array(
                'id' => (string) $row['_id'],
                'nombre' => $row['nombre'],
                'etiqueta' => $row['etiqueta'],
                'tipo' => $row['tipo'],
                'propiedades' => $row['propiedades'],
                'isSubscribe' => false,
                'valor' => ''
            );
        }
        return $this->data;
    }
    
    public function switch_edit_estado($id_topico, $estado) {
        
        if ($estado != "ON" && $estado != "OFF") {
            $estado = "OFF";
        }
        
        $update = $this->conn->topico->updateOne([
            '_id' => new MongoDB\BSON\ObjectId($id_topico)
            ], [
            '$set' => ['propiedades' => ['estado' => $estado]]
        ]);

        if ($update->getModifiedCount() > 0) {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::OK, iconst_helper::MENSAJE => "");
        } else {
            $this->data = array(iconst_helper::ESTATUS => iconst_helper::ERROR, iconst_helper::MENSAJE => "");
        }

        return $this->data;
    }

}
