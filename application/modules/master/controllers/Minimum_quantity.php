<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Minimum_quantity extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('minimum_quantity_model'); 
    }

	public function index()
	{
        $data['title'] = 'Minimum_quantity';
        $data['headoffice'] = $this->minimum_quantity_model->get_headoffice();
        $data['branch'] = $this->minimum_quantity_model->get_branch();
        $data['product'] = $this->minimum_quantity_model->get_products();
        $data['unit'] = $this->minimum_quantity_model->get_unit();
        $this->template->write_view('content', 'minimum_quantity', $data);
        $this->template->render();
	}

    public function add_minimum_quantity(){
        $minimum_quantity = array(
            'iHeadOfficeId' => $_POST['headoffice'],
            'iBranchId' => $_POST['branch'],
            'iProductId' => $_POST['product'],
            'iProductUnitId' => $_POST['unit'],
            'iMinQty' => $_POST['quantity'],
        );
        $this->minimum_quantity_model->get_minimum_quantity($minimum_quantity);
        redirect(base_url('master/minimum_quantity'));
    }

    public function get_minimum_quantity(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->minimum_quantity_model->minimum_quantity_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iProductMinQtyId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iProductMinQtyId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            // $row[] = $post->vHeadOfficeName;
            $row[] = $post->vBranchName;
            $row[] = $post->vProductName;
            $row[] = $post->vProductUnitName;
            $row[] = $post->iMinQty;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->minimum_quantity_model->count_all_minimum_quantity(),
            "recordsFiltered" => $this->minimum_quantity_model->count_all_minimum_quantity(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_minimum_quantity(){
        $minimum_quantity_id = $_POST['id'];
        $data = $this->minimum_quantity_model->minimum_quantity_by_id($minimum_quantity_id);
        echo json_encode($data);
        exit;
    }

    public function update_minimum_quantity(){
        $id = $_POST['minqty_id'];
        $minimum_quantity = array(
            'iHeadOfficeId' => $_POST['headoffice'],
            'iBranchId' => $_POST['branch'],
            'iProductId' => $_POST['product'],
            'iProductUnitId' => $_POST['unit'],
            'iMinQty' => $_POST['quantity'],
        );
        $this->minimum_quantity_model->update_minimum_quantity($id,$minimum_quantity);
        redirect(base_url('master/minimum_quantity'));
    }

    public function delete_minimum_quantity(){
        $id = $_POST['id'];
        $this->minimum_quantity_model->delete_minimum_quantity($id);
    }
}
