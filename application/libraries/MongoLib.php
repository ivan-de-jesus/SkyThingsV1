<?php

class MongoLib {

    private $url;
    private $cliente;
    public $bd;

    public function __construct() {
        $ci = & get_instance();
        $ci->load->config('mongoci');

        $server = $ci->config->item('mongo_server');
        $port = $ci->config->item('mongo_port');
        $username = $ci->config->item('mongo_username');
        $password = $ci->config->item('mongo_password');

        if ($ci->config->item('mongo_auth') === FALSE) {
            $this->url = "mongodb://$server:$port";
        } else {
            $this->url = "mongodb://$username:$password@$server:$port";
        }
        
        $this->cliente = new MongoDB\Client($this->url);
        $this->bd = $this->cliente->ieiot;
    }

}
