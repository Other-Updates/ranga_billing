<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasedetails extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url());
        }
	    parent::__construct();
        $this->load->model('purchasedetails_model'); 
    }
    //Report - Landing Function 
	public function index()
	{
        $data['title'] = 'Purchase Detail Report';
        $data['products'] = $this->purchasedetails_model->get_product_list();
        $data['units'] = $this->purchasedetails_model->get_productunit_list();
        $this->template->write_view('content', 'purchasedetails_report', $data);
        $this->template->render();
	}
    //Report - Serverside Function
    public function purchasedetail_data(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->purchasedetails_model->purchasedetail_list($input_data);
        foreach ($list as $key=>$post) {
            $view = '<a href="'.base_url('purchase_order/view_purchase_order/').$post->iPurchaseOrderId.'" data-id="'.$post->iPurchaseOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            $row = array();
            $row[] = $post->vPurchaseOrderNo;   
            $row[] = $post->vSupplierName;
            $row[] = $post->vProductName;
            $row[] = $post->iPurchaseQTY;
            $row[] = number_format((float)$post->iPurchaseCostperQTY, 2, '.', '');
            $row[] = $post->vProductUnitName;
            $row[] = date("d-m-Y",strtotime($post->dDeliveryDate));
            $row[] = date("d-m-Y",strtotime($post->dCreatedDate));
            $row[] = $post->eDeliveryStatus;
            $row[] = $view;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->purchasedetails_model->count_all_references($input_data),
            "recordsFiltered" => $this->purchasedetails_model->count_all_references($input_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }
}
