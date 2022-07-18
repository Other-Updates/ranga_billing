<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('supplier_model'); 
    }

	public function index()
	{
        $data['title'] = 'supplier';
        $this->template->write_view('content', 'supplier', $data);
        $this->template->render();
	}

    public function add_supplier(){
        $supplier = array(
            'vSupplierName' => $_POST['supplier_name'],
            'vSupplierName_Tamil' => $_POST['supplier_name_tamil'],
            'vPhoneNumber' => $_POST['phone'],
            'vEmail' => $_POST['email'],
            'vAddress' => $_POST['address'],
            'vGSTINNo' => $_POST['gstno']
        );
        $this->supplier_model->insert_supplier($supplier);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/branch'));
    }

    public function get_suppliers(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->supplier_model->supplier_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iSupplierId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iSupplierId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vSupplierName;
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->supplier_model->count_all_suppliers(),
            "recordsFiltered" => $this->supplier_model->count_all_suppliers(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_supplier(){
        $supplier_id = $_POST['id'];
        $data = $this->supplier_model->supplier_by_id($supplier_id);
        echo json_encode($data);
        exit;
    }

    public function update_supplier(){
        $id = $_POST['supplier_id'];
        $supplier = array(
            'vSupplierName' => $_POST['supplier_name'],
            'vSupplierName_Tamil' => $_POST['supplier_name_tamil'],
            'eStatus' => $_POST['status'],
            'vPhoneNumber' => $_POST['phone'],
            'vEmail' => $_POST['email'],
            'vAddress' => $_POST['address'],
            'vGSTINNo' => $_POST['gstno']
        );
        $this->supplier_model->update_supplier($id,$supplier);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/branch'));
    }

    public function delete_supplier(){
        $id = $_POST['id'];
        $supplier = array(
            'eStatus' => 'Deleted',
        );
        $this->supplier_model->update_supplier($id,$supplier);
        // $this->branch_model->delete_branch($id);
    }
}
