<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

   
}
