<?php

/**
 * Description of iotronics_helper
 *
 * @author 
 */

function is_login() {
    $CI = &get_instance();
    $user = $CI->session->userdata('logged');
    return (isset($user)) ? $user : false;
}

function item_get($item) {
    $CI = &get_instance();
    $tmp = $CI->session->userdata($item);
    return (isset($tmp)) ? $tmp : NULL;
}