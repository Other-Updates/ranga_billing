<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	public function __construct(){
		
		parent::__construct();
        $this->load->model('user_model'); 
		$this->load->library('session');
    }

	public function index(){

		$data['title'] = 'Signin Page';
		$this->template->set_master_template('../../themes/template_signin.php');
		$this->template->write_view('content', 'user', $data);
		$this->template->render();
	}

	public function user_list(){
		$data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->user_model->get_users();
		$sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iUserId.'" class="removeAttr action-icon" ><i class="fa fa-trash td-icon"></i></a>';
            $edit = '<a href="" data-id="'.$post->iUserId.'" class="addAttr action-icon" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-pencil td-icon"></i></a>';
            $row = array();
            $row[] = $sno++;   
            $row[] = $post->vName;   
            $row[] = $post->eUserType;
            $row[] = $post->iPhoneNumber;
            $row[] = $post->vAddress;
            $row[] = $post->vEmail;
            // $row[] = $post->iCommission;
			if($post->tLoginStatus == 0){
				$row[] = "<div class=\"badge badge-light-danger fw-bolder\">Logged Out</div>";
			}
			if($post->tLoginStatus == 1){
				$row[] = "<div class=\"badge badge-light-success fw-bolder\">Logged In</div>";
			}
			$row[] = $post->eStatus;
            $row[] = $edit.$delete;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user_model->count_all_users(),
            "recordsFiltered" => $this->user_model->count_all_users(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
	}

	public function forgot_password(){
		
		$data['title'] = 'forgot_password';
		$this->template->set_master_template('../../themes/template_signin.php');
		$this->template->write_view('content', 'forget_password', $data);
		$this->template->render();
   	}
	   
	public function salesman(){
		$data['title'] = 'Salesman';
		$data['role'] = $this->user_model->get_roles();
		$data['region'] = $this->user_model->get_region();
		$data['headoffice'] = $this->user_model->get_headoffice();
		$data['branch'] = $this->user_model->get_branch();
		$this->template->write_view('content', 'salesman', $data);
		$this->template->render();
	}

	public function add_user(){
		$user = array();
		$user['vName'] = $_POST['name'];
		$user['vName_Tamil'] = $_POST['add_name_tamil'];
		$user['vUserName'] = $_POST['username'];
		$user['vPassword'] = base64_encode($_POST['password']);
		$user['iPhoneNumber'] = $_POST['phone'];
		$user['vAddress'] = $_POST['address'];
		$user['vAddress_Tamil'] = $_POST['address_tamil'];
		$user['iUserRoleId'] = $_POST['type'];
		$user['iRegionId'] = $_POST['region'];
		$user['iHeadOfficeId'] = $_POST['headoffice'];
		$user['iBranchId'] = $_POST['branch'];
		$user['vEmail'] = $_POST['email'];
		// $user['iCommission'] = $_POST['commission'];
		$this->user_model->add_user($user);
		// redirect(base_url('master/user/salesman'));
		echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
	}

	public function edit_user(){
		$user_id = $_POST['id'];
		$user = $this->user_model->get_user($user_id);
        echo json_encode($user);
	}

	public function update_user(){
		$user_id = $_POST['user_id'];
		$user = array(
			'vName' => $_POST['name'],
			'vName_Tamil' => $_POST['name_tamil'],
			'iPhoneNumber' => $_POST['phone'],
			'vUserName' => $_POST['username'],
			'vPassword' => base64_encode($_POST['password']),
			'vAddress' => $_POST['address'],
			'vAddress_Tamil' => $_POST['address_tamil'],
			'iUserRoleId' => $_POST['type'],
			'iRegionId' => $_POST['region'],
			'iHeadOfficeId' => $_POST['headoffice'],
			'iBranchId' => $_POST['branch'],
			'vEmail' => $_POST['email'],
			// 'iCommission' => $_POST['commission'],
			'eStatus' => $_POST['status'],
		);
		$this->user_model->update_user($user_id,$user);
		// redirect(base_url('master/user/salesman'));
		echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
	}

	public function delete_user(){
		$user_id = $_POST['id'];
		$this->user_model->delete_user($user_id);
        redirect(base_url('master/user'));
	}

	public function edit_profile(){
		$id = $this->session->userdata('LoggedId');
		$data['profile'] = $this->user_model->get_user_profile($id);
		$this->template->write_view('content', 'edit_profile', $data);
		$this->template->render();
	}

	public function update_admin(){
		$user_id = $_POST['user_id'];
		$user = array(
			'vName' => $_POST['name'],
			'iPhoneNumber' => $_POST['phone'],
			'vAddress' => $_POST['address'],
			'vUserName' => $_POST['username'],
			'vEmail' => $_POST['email'],
		);
		$this->user_model->update_user($user_id,$user);
		redirect(base_url('dashboard'));
	}

	public function check_mail(){
		$email = $_POST['email'];
		$check = $this->user_model->check_email_exist($email);
		if($check){
			$otp = $this->user_model->generateNumericOTP('4');  
			$update['iOtpCode'] = $otp;
			$data['userid'] = $check['iUserId'];
			$this->User_model->update_user($check['iUserId'],$update);
			$this->template->set_master_template('../../themes/template_signin.php');
			$this->template->write_view('content', 'user/reset_password', $data);
			$this->template->render();
      	}else{
        	echo "<script>alert('User not found')</script>";
      	}
	}

	public function ho_based_region(){
		$region = $this->input->post('region');
		$result = $this->user_model->get_headoffice($region);
		echo json_encode($result);
		exit;
	}
	
	public function branch_based_ho(){
		$ho = $this->input->post('head_office');
		$result = $this->user_model->get_branch($ho);
		echo json_encode($result);
		exit;
	}
}
