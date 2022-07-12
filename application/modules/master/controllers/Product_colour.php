<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_colour extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('product_colour_model'); 
    }

	public function index()
	{
        $data['title'] = 'Product_colour';
        $this->template->write_view('content', 'product_colour', $data);
        $this->template->render();
	}

    public function add_product_color(){
        $product_color['vColorName'] = $_POST['product_color_name'];
        // $product_unit['vProductUnitName_Tamil'] = $_POST['product_unit_name_tamil'];
        $this->product_colour_model->get_product_color($product_color);
        // echo"<pre>"; print_r($product_color); exit;
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect('master/product_unit');
    }

    public function get_product_colours(){
        // echo 1 ;exit;
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->product_colour_model->product_color_list();
        $sno = $input_data['start'] + 1;
    //  echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iProductColorId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="" data-id="'.$post->iProductColorId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vColorName;   
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_colour_model->count_all_product_units(),
            "recordsFiltered" => $this->product_colour_model->count_all_product_units(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_product_colour(){
        $product_colour_id = $_POST['id'];
        $data = $this->product_colour_model->product_color_by_id($product_colour_id);
        echo json_encode($data);
        exit;
    }

    public function update_product_colour(){
        $id = $_POST['product_color_id'];
        $product_color = array(
            'vColorName' => $_POST['product_color_name'],
            // 'vProductUnitName_Tamil' => $_POST['product_unit_name_tamil'],
            'eStatus' => $_POST['status'],
        );
        $this->product_colour_model->update_product_color($id,$product_color);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/product_unit'));
    }

    public function delete_product_color(){
        // echo 1; exit;
        $id = $_POST['id'];
        $product_color = array(
            'eStatus' => 'Deleted',
        );
        $this->product_colour_model->update_product_color($id,$product_color);
    }
}
