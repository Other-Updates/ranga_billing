<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('brand_model'); 
    }

	public function index()
	{
        $data['title'] = 'Brand';
        $this->template->write_view('content', 'brand', $data);
        $this->template->render();
	}

    public function add_brand(){
        $brand['vBrandName'] = $_POST['brand_name'];
        $brand['vBrandName_Tamil'] = $_POST['brand_name_tamil'];
        $this->brand_model->get_brand($brand);
        // redirect('master/brand');
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function get_brands(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->brand_model->brand_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iBrandId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="" data-id="'.$post->iBrandId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vBrandName;   
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->brand_model->count_all_brands(),
            "recordsFiltered" => $this->brand_model->count_all_brands(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_brand(){
        $brand_id = $_POST['id'];
        $data = $this->brand_model->brand_by_id($brand_id);
        echo json_encode($data);
        exit;
    }

    public function update_brand(){
        $id = $_POST['brand_id'];
        $brand = array(
            'vBrandName' => $_POST['brand_name'],
            'vBrandName_Tamil' => $_POST['brand_name_tamil'],
            'eStatus' => $_POST['status'],
        );
        $this->brand_model->update_brand($id,$brand);
        // redirect(base_url('master/brand'));
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function delete_brand(){
        $id = $_POST['id'];
        $brand = array(
            'eStatus' => "Deleted",
        );
        $this->brand_model->update_brand($id,$brand);
    }
}
