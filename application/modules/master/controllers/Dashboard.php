<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('dashboard_model'); 
    }

	public function index()
	{
        $data['title'] = 'Dashboard';
        $this->template->write_view('content', 'dashboard', $data);
        $this->template->render();
	}
}
