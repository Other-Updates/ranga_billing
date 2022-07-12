<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('distributor_model'); 
    }

	public function index(){

        $data['title'] = 'distributor';
        $data['user'] = $this->distributor_model->get_all_users();
        $data['categories'] = $this->distributor_model->get_categories();
        $data['grade'] = $this->distributor_model->get_grade();
        $data['region'] = $this->distributor_model->get_regions();
        $data['roles'] = $this->distributor_model->get_roles();
        $data['branch'] = $this->distributor_model->get_branch();
        $data['state'] = $this->distributor_model->get_states();
        $this->template->write_view('content', 'distributor', $data);
        $this->template->render();
	}

    public function get_distributor(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->distributor_model->distributor_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iCustomerId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="" data-id="'.$post->iCustomerId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vCustomerName;   
            $row[] = $post->vUserRole;
            $row[] = $post->vGradeName;
            $row[] = $post->vCompanyName;
            $row[] = $post->vPhoneNumber;
            // $row[] = $post->vAddress;
            // $row[] = $post->vEmail;
            // $row[] = $post->vReferenceDistributor;
            // $row[] = $post->salesman_name;
            // $row[] = $post->iCommission;
            $row[] = $post->eStatus;   
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->distributor_model->count_all_distributors(),
            "recordsFiltered" => $this->distributor_model->count_all_distributors(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function add_distributor(){
        $logged_user = $this->session->userdata('LoggedId');
        $category = $_POST['category'];
        $location = $this->distributor_model->get_headoffice_and_region($_POST['branch']);
		$distributor = array();
		$distributor['vCustomerName'] = $_POST['name'];
		$distributor['vCustomerName_Tamil'] = $_POST['name_tamil'];
		$distributor['iUserRoleId'] = $_POST['type'];
        $distributor['iHeadOfficeId'] = $location['iHeadOfficeId'];
        $distributor['iRegionId'] = $location['iRegionId'];
		$distributor['vPhoneNumber'] = $_POST['phone'];
		$distributor['iBranchId'] = $_POST['branch'];
		$distributor['vAddress'] = $_POST['address'];
		$distributor['vAddress_Tamil'] = $_POST['address_tamil'];
		$distributor['vEmail'] = $_POST['email'];
		$distributor['vCompanyName'] = $_POST['company_name'];
		$distributor['vCompanyName_Tamil'] = $_POST['company_name_tamil'];
		$distributor['iSalesmanId'] = $_POST['salesman_id'];
		$distributor['iGradeId'] = $_POST['grade'];
        $distributor['vGSTINNo'] = $_POST['gstin'];
        $distributor['iStateId'] = $_POST['state'];
		$distributor['dCreatedDate'] = date('Y-m-d h:i:s');
        $distributor['iCreatedBy'] = $logged_user;
		// $distributor['iCommission'] = $_POST['commission'];
		$customer_id = $this->distributor_model->add_distributor($distributor);
        for($i=0;$i<count($category);$i++){
            $customer_category[] = array(
                'iCustomerId' => $customer_id,
                'iCategoryId' => $category[$i],
            );
        }
        $this->distributor_model->add_customer_category($customer_category);
		echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/distributor'));
	}

    public function update_distributor(){
        // print_r($_POST);exit;
        $logged_user = $this->session->userdata('LoggedId');
        $category_check_edit = $_POST['category_check_edit'];
        $location = $this->distributor_model->get_headoffice_and_region($_POST['branch']);
        $distributor['iHeadOfficeId'] = $location['iHeadOfficeId'];
        $distributor['iRegionId'] = $location['iRegionId'];
        $distributor_id = $_POST['distributorid'];
        $distributor['vCustomerName'] = $_POST['name'];
        $distributor['vCustomerName_Tamil'] = $_POST['name_tamil'];
		$distributor['iUserRoleId'] = $_POST['type'];
        // if($_POST['type'] == 'Customer'){
        //     $distributor['vCustomerType_Tamil'] = 'வாடிக்கையாளர்';
        // }
        // if($_POST['type'] == 'Retailer'){
        //     $distributor['vCustomerType_Tamil'] = 'சில்லறை விற்பனையாளர்';
        // }
        // if($_POST['type'] == 'Distributor'){
        //     $distributor['vCustomerType_Tamil'] = 'விநியோகஸ்தர்';

        // }
		$distributor['vPhoneNumber'] = $_POST['phone'];
		$distributor['vAddress'] = $_POST['address'];
		$distributor['vAddress_Tamil'] = $_POST['address_tamil'];
		$distributor['vEmail'] = $_POST['email'];
		$distributor['vCompanyName'] = $_POST['company_name'];
		$distributor['vCompanyName_Tamil'] = $_POST['company_name_tamil'];
		$distributor['iSalesmanId'] = $_POST['salesman_id'];
		$distributor['iGradeId'] = $_POST['grade'];
		$distributor['iBranchId'] = $_POST['branch'];
        $distributor['eStatus'] = $_POST['status'];
        $distributor['vGSTINNo'] = $_POST['gstin'];
        $distributor['iStateId'] = $_POST['state'];
        $distributor['iUpdatedBy'] = $logged_user;
        $this->distributor_model->update_distributor($distributor_id,$distributor);

        $this->distributor_model->delete_customer_categories($distributor_id);

        if(!empty($category_check_edit)){
            for($i=0;$i<count($category_check_edit);$i++){
                $customer_category[] = array(
                    'iCustomerId' => $distributor_id,
                    'iCategoryId' => $category_check_edit[$i],
                );
            }
            $this->distributor_model->add_customer_category($customer_category);
        }
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/distributor'));
    }

    public function edit_distributor(){
        $distributor_id = $_POST['id'];
        $distributor = $this->distributor_model->get_distributor_by_id($distributor_id);
        echo json_encode($distributor);
    }

    public function delete_distributor(){
        $distributor_id = $_POST['id'];
        $update = array(
            'dUpdatedDate' => date('Y-md-d h:i:s'),
            'eStatus' => 'Deleted',
        );
        $this->distributor_model->update_distributor($distributor_id,$update);
        redirect(base_url('master/user'));
    }
}
