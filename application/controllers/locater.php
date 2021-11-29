<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locater extends CI_Controller {

    public function addLocation() {
        $this->load->view("newLocater");
    }

}