<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xray_report extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url());
        }
	    parent::__construct();
        $this->load->model('xray_report_model'); 
    }

	public function index()
	{
        $data['title'] = 'Sales Detail Report';
        $data['salesman'] = $this->xray_report_model->get_salesman_list();
        $data['distributor'] = $this->xray_report_model->get_distributor_list();

        $this->template->write_view('content', 'xray_report', $data);
        $this->template->render();
	}

    public function xray_data(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        // echo "<pre>**";print_r($input_data);exit;
        $list=$this->xray_report_model->salesdetail_list($input_data);
        foreach ($list as $key=>$post) {
            $view = '<a href="'.base_url('billing/xray_fee/view_xray_fee/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            $row = array();
            $row[] = $post->vSalesOrderNo;   
            $row[] = $post->vCustomerName;
            $row[] = $post->vBranchName;
            $row[] = $post->vProductName;
            $row[] = $post->iDeliveryQTY;
            $row[] = $post->vProductUnitName;
            $row[] = date("d-m-Y",strtotime($post->dOrderedDate));
            $row[] = date("d-m-Y",strtotime($post->dCreatedDate));
            $row[] = $post->eDeliveryStatus;
            $row[] = $view;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->xray_report_model->count_all_references($input_data),
            "recordsFiltered" => $this->xray_report_model->count_all_references($input_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }
}
