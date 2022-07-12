<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {
   public function __construct(){
	    parent::__construct();
        $this->load->model('users_model');
    }
    
    public function index(){

		$data['title'] = 'Signin Page';
		$this->template->set_master_template('../../themes/template_signin.php');
		$this->template->write_view('content', 'users', $data);
		$this->template->render();
	}

    public function login(){
		$username = $_POST['username'];
		$password = base64_encode($_POST['password']);
		$login = $this->users_model->check_salesman_login($username,$password);
		if($login){
			$this->session->set_userdata('LoggedId',$login['iUserId']);
			$this->session->set_userdata('UserRole',$login['iUserRoleId']);
			if($this->session->userdata('UserRole') == 2){
				$branch_id = $this->users_model->get_branch_id($login['iUserId']);
				$getBranchId = [];
				foreach($branch_id as $id) {
				$getBranchId[] = $id['iBranchId'];
				}
				$branch_id_data = implode(",",$getBranchId);
				// $this->session->set_userdata('BranchId',$branch_id_data);
				$this->session->set_userdata('BranchId',$login['iBranchId']);
				$this->session->set_userdata('HeadOfficeId',$login['iHeadOfficeId']);
				redirect(base_url('dashboard'));
			}
			if($this->session->userdata('UserRole') == 3){
				$this->session->set_userdata('HeadOfficeId',$login['iHeadOfficeId']);
			    $this->session->set_userdata('BranchId',$login['iBranchId']);
				redirect(base_url('dashboard'));
			}
			redirect(base_url('dashboard'));
		}else{
			$this->session->set_flashdata('error', 'Invalid username or password');
            redirect('/users');
		}
	}

	public function logout(){
		$this->session->unset_userdata('LoggedId');
		$this->session->sess_destroy();
		redirect(base_url());
	}
}