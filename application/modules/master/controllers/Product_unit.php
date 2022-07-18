<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_unit extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('product_unit_model'); 
    }

	public function index()
	{
        $data['title'] = 'Product_unit';
        $this->template->write_view('content', 'product_unit', $data);
        $this->template->render();
	}

    public function add_product_unit(){
        $product_unit['vProductUnitName'] = $_POST['product_unit_name'];
        $product_unit['vProductUnitName_Tamil'] = $_POST['product_unit_name_tamil'];
        $this->product_unit_model->get_product_unit($product_unit);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect('master/product_unit');
    }

    public function get_product_units(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->product_unit_model->product_unit_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iProductUnitId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iProductUnitId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vProductUnitName;   
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_unit_model->count_all_product_units(),
            "recordsFiltered" => $this->product_unit_model->count_all_product_units(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_product_unit(){
        $product_unit_id = $_POST['id'];
        $data = $this->product_unit_model->product_unit_by_id($product_unit_id);
        echo json_encode($data);
        exit;
    }

    public function update_product_unit(){
        $id = $_POST['product_unit_id'];
        $product_unit = array(
            'vProductUnitName' => $_POST['product_unit_name'],
            'vProductUnitName_Tamil' => $_POST['product_unit_name_tamil'],
            'eStatus' => $_POST['status'],
        );
        $this->product_unit_model->update_product_unit($id,$product_unit);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/product_unit'));
    }

    public function delete_product_unit(){
        $id = $_POST['id'];
        $product_unit = array(
            'eStatus' => 'Deleted',
        );
        $this->product_unit_model->update_product_unit($id,$product_unit);
    }
}
