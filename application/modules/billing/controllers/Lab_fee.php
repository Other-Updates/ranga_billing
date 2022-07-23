<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lab_fee extends MY_Controller {
    
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('lab_fee_model'); 
        $this->load->model('order/order_model'); 
    }

	public function index()
	{
        $data['title'] = 'pharmacy_bill';
        $this->template->write_view('content', 'lab_fees', $data);
        $this->template->render();
	}

    public function add_lab_fee(){
        $head_office_id = $this->session->userdata('HeadOfficeId');
        $branch_id = $this->session->userdata('BranchId');
        $data['headoffice'] = $this->order_model->get_headOffice($head_office_id);
        $data['branches'] = $this->order_model->get_branch($branch_id);
        $data['customer'] = $this->order_model->get_customer($branch_id);
        $data['salesman'] = $this->order_model->get_salesman($branch_id);
        $data['category'] = $this->order_model->get_category();
        $data['sales_order_number'] = $this->order_model->get_order_number();
        $data['user'] = $this->order_model->get_all_user();
        $data['grade'] = $this->order_model->get_grade();
        $data['region'] = $this->order_model->get_regions();
        $data['roles'] = $this->order_model->get_roles();
        $data['state'] = $this->order_model->get_states();
        $this->template->write_view('content', 'add_lab_fees', $data);
        $this->template->render();
    }

    public function edit_lab_fee($id){
        $data['sales_order_id'] = $id;
        $head_office_id = $this->session->userdata('HeadOfficeId');
        $branch_id = $this->session->userdata('BranchId');
        $data['headoffice'] = $this->order_model->get_headOffice($head_office_id);
        $data['salesman'] = $this->order_model->get_salesman($branch_id);
        $data['branches'] = $this->order_model->get_branch($branch_id);
        $data['category'] = $this->order_model->get_category();
        $data['sales_order'] = $this->order_model->get_sales_details_by_id($id);
        $data['customer'] = $this->order_model->get_customer();
        $data['unit'] = $this->order_model->get_unit();
        $this->template->write_view('content', 'edit_lab_fees', $data);
        $this->template->render();
    }

    public function view_lab_fee($id){
        $data['sales_order_id'] = $id;
        $data['headoffice'] = $this->order_model->get_headoffice();
        $data['category'] = $this->order_model->get_category();
        $data['sales_order'] = $this->order_model->get_sales_details_by_id($id);
        $data['customer'] = $this->order_model->get_customer();
        $data['unit'] = $this->order_model->get_unit();
        $this->template->write_view('content', 'view_lab_fees', $data);
        $this->template->render();
    }

    public function get_lab_bills(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->order_model->order_list();
        // echo"<pre>";print_r($list);exit;
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            // $order_date_timestamp = strtotime($post->dOrderedDate);
            // $converted_ordered_date = date('d-m-Y ', $order_date_timestamp);   
            // $created_date_timestamp = strtotime($post->salecreateddate);
            // $converted_created_date = date('d-m-Y ', $order_date_timestamp);   
            $delete = '<a href="" data-id="'.$post->iSalesOrderId.'" class="action-icon removeAttr " ><i class="fa fa-trash td-icon"></i></a>';
            $edit = '<a href="'.base_url('billing/lab_fee/edit_lab_fee/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><i class="fa fa-pencil td-icon tm2"></i></a>';
            $view = '<a href="'.base_url('billing/lab_fee/view_lab_fee/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            $return = "";
            if($post->eDeliveryStatus == "Delivered"){
                $edit = "";
                $delete = "";
                $return = '<a href="'.base_url('billing/lab_fee/consult_fee_return/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><button style="font-size: 11px;padding: 0px 8px;" class="btn btn-return">Return</button></a></a>';
            }if($post->eDeliveryStatus == "Cancelled"){
                $edit = "";
                $delete = "";
            }
            $row = array();
            $row[] = $sno++;
            // $row[] = $post->vProductName;   
            // $row[] = $post->vName;
            $row[] = $post->vSalesOrderNo;
            $row[] = $post->vCustomerName;
            $row[] = $post->fNetQty;
            $row[] = $post->fNetCost;
            $row[] = $post->ordereddate;
            $row[] = $post->eDeliveryStatus;
            $row[] = $post->salecreateddate;
            $row[] = $view.$edit.$delete;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->order_model->count_all_sales(),
            "recordsFiltered" => $this->order_model->count_all_sales(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_supplier(){
        $supplier_id = $_POST['id'];
        $data = $this->lab_fee_model->supplier_by_id($supplier_id);
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
        $this->lab_fee_model->update_supplier($id,$supplier);
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
        $this->lab_fee_model->update_supplier($id,$supplier);
        // $this->branch_model->delete_branch($id);
    }
}
