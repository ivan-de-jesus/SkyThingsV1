<?php

/**
 * Description of Login
 *
 * @author 
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array("url", "iotronics"));
        $this->load->library(array("session"));
    }

    public function index() {
        if (!is_login()) {
            $data['title'] = "IoTronics|Login";
            $this->load->view("login", $data);
        } else {
            redirect("dashboard");
        }
    }

    public function registro() {
        if (!is_login()) {
            $data['title'] = "IoTronics|Registro";
            $this->load->view("registro", $data);
        } else {
            redirect("dashboard");
        }
    }

}
