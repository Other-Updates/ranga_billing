<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('branch_model'); 
        // $this->modules_permission_details=$this->module_permission_check->get_permission_details(1);
    }

	public function index()
	{
    //   print_r($this->modules_permission_details);
    //    exit;
        $data['title'] = 'branch';
        $data['manager'] = $this->branch_model->get_manager();
        $data['headoffice'] = $this->branch_model->get_headoffice();
        $this->template->write_view('content', 'branch', $data);
        $this->template->render();
	}

    public function add_branch(){
        $branch = array(
            'vBranchName' => $_POST['branch_name'],
            'vBranchName_Tamil' => $_POST['branch_name_tamil'],
            'iBranchManagerId' => $_POST['manager_name'],
            'vAdhaarGst' => $_POST['aadhaar'],
            'vMobileNumber' => $_POST['mobile'],
            'vAddress' => $_POST['address'],
            'vAddress_Tamil' => $_POST['address_tamil'],
            'iHeadOfficeId' => $_POST['headoffice'],
        );
        $this->branch_model->get_branch($branch);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/branch'));
    }

    public function get_branches(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->branch_model->branch_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iBranchId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iBranchId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vHeadOfficeName;
            $row[] = $post->vBranchName;
            $row[] = $post->manager;
            $row[] = $post->vAdhaarGst;
            $row[] = $post->vMobileNumber;
            $row[] = $post->vAddress;
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->branch_model->count_all_branches(),
            "recordsFiltered" => $this->branch_model->count_all_branches(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_branch(){
        $branch_id = $_POST['id'];
        $data = $this->branch_model->branch_by_id($branch_id);
        echo json_encode($data);
        exit;
    }

    public function update_branch(){
        $id = $_POST['branch_id'];
        $branch = array(
            'vBranchName' => $_POST['branch_name'],
            'vBranchName_Tamil' => $_POST['branch_name_tamil'],
            'iBranchManagerId' => $_POST['manager_name'],
            'vAdhaarGst' => $_POST['aadhaar'],
            'vMobileNumber' => $_POST['mobile'],
            'vAddress' => $_POST['address'],
            'vAddress_Tamil' => $_POST['address_tamil'],
            'iHeadOfficeId' => $_POST['headoffice'],
            'eStatus' => $_POST['status'],
        );
        $this->branch_model->update_branch($id,$branch);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/branch'));
    }

    public function delete_branch(){
        $id = $_POST['id'];
        $branch = array(
            'eStatus' => 'Deleted',
        );
        $this->branch_model->update_branch($id,$branch);
        // $this->branch_model->delete_branch($id);
    }

    public function check_duplicate_branch(){
        $branch = $this->input->post('branch');
        $result = $this->branch_model->check_duplicate($branch);
        if($result){
            echo json_encode(array('status'=>'success','message'=>'Branch already exist'));
        }else{
            echo json_encode(array('status'=>'failure'));

        }
    }
}
