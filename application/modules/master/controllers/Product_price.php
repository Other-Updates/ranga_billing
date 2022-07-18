<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_price extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('product_price_model'); 
    }

	public function index()
	{
        $data['title'] = 'Product_price';
        $data['product'] = $this->product_price_model->get_products();
        $data['unit'] = $this->product_price_model->get_units();
        $data['grade'] = $this->product_price_model->get_grades();
        $this->template->write_view('content', 'product_price', $data);
        $this->template->render();
	}

    public function add_product_price(){
        $product_price = array(
            'iProductId' => $_POST['product'],
            'iProductUnitId' => $_POST['unit'],
            'vPacketCount' => $_POST['count'],
            'iGradeId' => $_POST['grade'],
            'fProductPrice' => $_POST['price'],
        );
        $this->product_price_model->get_product_price($product_price);
        redirect('master/product_price');
    }

    public function get_product_prices(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->product_price_model->product_price_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iProductPriceListId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iProductPriceListId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vProductName;   
            $row[] = $post->vProductUnitName;
            $row[] = $post->vPacketCount;
            $row[] = $post->vGradeName;
            $row[] = $post->fProductPrice;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_price_model->count_all_product_prices(),
            "recordsFiltered" => $this->product_price_model->count_all_product_prices(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_product_price(){
        $region_id = $_POST['id'];
        $data = $this->product_price_model->product_price_by_id($region_id);
        echo json_encode($data);
        exit;
    }

    public function update_product_price(){
        $id = $_POST['product_price_id'];
        $product_price = array(
            'iProductId' => $_POST['product'],
            'iProductUnitId' => $_POST['unit'],
            'vPacketCount' => $_POST['count'],
            'iGradeId' => $_POST['grade'],
            'fProductPrice' => $_POST['price'],
        );
        $this->product_price_model->update_product_price($id,$product_price);
        redirect(base_url('master/product_price'));
    }

    public function delete_product_price(){
        $id = $_POST['id'];
        $this->product_price_model->delete_product_price($id);
    }
}
