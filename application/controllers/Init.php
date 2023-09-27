<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of init
 *
 * @author 
 */
class Init extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array("url", "iotronics"));
        $this->load->library(array("session"));
    }
    
    public function index() {
        if (is_login()) {
            redirect("dashboard");
        } else {
            redirect("login");
        }
    }

}
