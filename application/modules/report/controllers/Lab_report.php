<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab_report extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url());
        }
	    parent::__construct();
        $this->load->model('lab_report_model'); 
    }

	public function index()
	{
        $data['title'] = 'Sales Detail Report';
        $data['salesman'] = $this->lab_report_model->get_salesman_list();
        $data['distributor'] = $this->lab_report_model->get_distributor_list();

        $this->template->write_view('content', 'lab_report', $data);
        $this->template->render();
	}

    public function lab_data(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        // echo "<pre>**";print_r($input_data);exit;
        $list=$this->lab_report_model->salesdetail_list($input_data);
        foreach ($list as $key=>$post) {
            $view = '<a href="'.base_url('billing/lab_fee/view_lab_fee/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
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
            "recordsTotal" => $this->lab_report_model->count_all_references($input_data),
            "recordsFiltered" => $this->lab_report_model->count_all_references($input_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }
}
