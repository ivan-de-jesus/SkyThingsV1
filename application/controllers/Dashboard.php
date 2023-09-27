<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Dashboard
 *
 * @author 
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'iotronics'));
        $this->load->library(array('session'));
    }

    public function index() {
        if (is_login()) {
            $data['title'] = "IoTronics";
            $this->load->view("dashboard", $data);
        } else {
            redirect('login');
        }
    }

}
