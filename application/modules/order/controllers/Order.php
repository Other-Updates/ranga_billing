<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
			redirect(base_url('users'));
		}
	    parent::__construct();
        $this->load->model('order_model'); 
        $this->load->model('sales_receipt/sales_receipt_model');
    }

	public function index()
	{
        $data['title'] = 'Orders';
        $data['users'] = $this->order_model->get_all_user();
        $data['distributors'] = $this->order_model->get_all_distributors();
        $data['products'] = $this->order_model->get_all_products();
        $this->template->write_view('content', 'order', $data);
        $this->template->render();
	}

    public function get_sales_order(){
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
            $delete = '<a href="" data-id="'.$post->iSalesOrderId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="'.base_url('order/edit_sales_order/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><i class="icofont icofont-ui-edit"></i></a>';
            $view = '<a href="'.base_url('order/view_sales_order/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><i class="fa fa-eye"></i></a>';
            $return = "";
            if($post->eDeliveryStatus == "Delivered"){
                $edit = "";
                $delete = "";
                $return = '<a href="'.base_url('order/sale_order_return/').$post->iSalesOrderId.'" data-id="'.$post->iSalesOrderId.'" class="action-icon" ><button style="font-size: 11px;padding: 0px 8px;" class="btn btn-success">Return</button></a></a>';
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
            $row[] = $view.$edit.$delete.$return;
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

    // public function get_order(){
    //     $order = array(
    //         'iUserId' => $_POST['salesman'],
    //         'iProductId' => $_POST['product'],
    //         'iDistributorId' => $_POST['distributor'],
    //         'iSalesOrderNumber' => $_POST['sales_order_number'],
    //         'iSalesAmount' => $_POST['sales_amount'],
    //         'dSalesOrderDate' => $_POST['sales_order_date'],
    //     );
    //     $this->order_model->get_sales_order($order);
    //     redirect(base_url('order'));
    // }

    // public function edit_sales_order(){
    //     $order_id = $_POST['id'];
    //     $edit = $this->order_model->edit_sales($order_id);
    //     echo json_encode($edit);
    // }
    
    // public function update_sales_order(){
    //     $sales_order_id = $_POST['sales_order_id'];
    //     $order = array(
    //         'iUserId' => $_POST['salesman'],
    //         'iProductId' => $_POST['product'],
    //         'iDistributorId' => $_POST['distributor'],
    //         'iSalesOrderNumber' => $_POST['sales_order_number'],
    //         'iSalesAmount' => $_POST['sales_amount'],
    //         'dSalesOrderDate' => $_POST['sales_order_date'],
    //     );
    //     $this->order_model->update_sales($sales_order_id,$order);
    //     redirect(base_url('order'));
    // }

    public function add_sales(){
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
        $this->template->write_view('content', 'add_sales', $data);
        $this->template->render();
    }

    public function get_gst_values(){
        $product_id = $this->input->post('iProductId');
        $gst = $this->order_model->get_gst_by_product($product_id);
        echo json_encode($gst);
    }

    public function get_unit_price(){
        $product_id = $this->input->post('iProductId');
        $unit_id = $this->input->post('unit_id');
        $color_id = $this->input->post('color_id');
        $branch_id = $this->input->post('branch');
        $grade = $this->input->post('grade');
        $price = $this->order_model->get_price_by_unit($product_id,$unit_id,$color_id,$branch_id,$grade);
        echo json_encode($price);
    }

    public function add_sales_order(){
        $input = $this->input->post();
        $headoffice_id = $input['headoffice'];
        $branch_id = $input['branch'];
        $customer_id = $input['customer'];
        $address = $input['adress'];
        $shipping_address = $input['shipping_adress'];
        $status = $input['delivery_status'];
        $payment_status = $input['payment_status'];
        $ordered_date = $input['ordered_date'];
        $check_sales_order_id = $this->order_model->get_sales_order_last_id($input['so_number']);
        if(!empty($check_sales_order_id)){
        $values = explode("-",$check_sales_order_id['vSalesOrderNo']);
        $values = $values[1];
        $lastno = $values + 1;
        $sale_order_number = date('Y')."-".$lastno;
        }
        else
        $sale_order_number = $input['so_number'];
        $net_qty = $input['sales']['net_qty'];
        $cost_withoutgst = $input['sales']['taxable_price'];
        $cgst_price = $input['sales']['cgst_price'];
        $sgst_price = $input['sales']['sgst_price'];
        $igst_price = $input['sales']['igst_price'];
        $net_cost = $input['sales']['net_total'];
        $additional_charge = $input['sales']['additional_charge'];
        if($this->session->userdata('UserRole') == 3)
        $salesman_id = $this->session->userdata('LoggedId');
        else
        $salesman_id = $input['salesman'];
        $od = str_replace("/","-",$ordered_date);
        $sales_order = array(
            'vSalesOrderNo' => $sale_order_number,
            'iHeadOfficeId' => $headoffice_id,
            'iBranchId' => $branch_id,
            'iCustomerId' => $customer_id,
            'iSalesmanId' => $salesman_id,
            'vAddress' => $address,
            'vShippingAddress' => $shipping_address,
            'dOrderedDate' => date("Y-m-d",strtotime($od)),
            'eDeliveryStatus' => $status,
            'iCreatedBy' => $salesman_id,
            'fNetQty' => $net_qty,
            'fNetCostwithoutGST' => $cost_withoutgst,
            'IGST' => $igst_price,
            'CGST' => $cgst_price,
            'SGST' => $sgst_price,
            'fAdditionalCharge' => $additional_charge,
            'fNetCost' => $net_cost,
            'vPayemntStatus' => "FAILED",
            'dCreatedDate' => date('Y-m-d h:i:s'),
        );
        // echo"<pre>";print_r($sales_order);
        // exit;
        $sales_order_id = $this->order_model->get_sales_order($sales_order);
        if($status=="Delivered" && $payment_status=="Completed"){
            $status_payment = "SUCCESS";
            $status = "Delivered";
            $total_paid_amt = $net_cost;
            $balance = '0.00';
            $update_sales_order = array(
                'eDeliveryStatus' => $status,
                'vPayemntStatus' => $status_payment,
                'paid_amount' => $total_paid_amt,
                'balance'=> $balance
            );
            $this->order_model->update_payment_status($update_sales_order,$sales_order_id);
            $receipt_no= $this->order_model->get_last_id('Gst Code');
            $receipt_bill = array(
                'receipt_id' => $sales_order_id,
                'receipt_no' => $receipt_no['code'],
                'bill_amount' => $net_cost,
                'total_paid_amt' => $net_cost,
                'terms' => 1,
                'created_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d'),
                'remarks' => 'Completed'
            );
            $insert_id = $this->sales_receipt_model->insert_receipt_bill($receipt_bill);
            $insert_id++;
            $inc['vType'] = 'Gst Code';
            $inc['code'] = 'RECQ000' . $insert_id;
            $this->sales_receipt_model->update_increment($inc, $inc['vType']);
        }
        $this->order_model->update_order_number();
        $customer_data = $this->order_model->get_customer_by_id($customer_id);
        // Authorisation details.
        $username = "smsgateway2k22@gmail.com";
        $hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";

        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "0";

        // Data for text message. This is the text message data.
        $sender = "CICSTO"; // This is who the message appears to be from.
        $numbers = $customer_data['vPhoneNumber']; // A single number or a comma-seperated list of numbers
        if($status == "Not shipped"){
            $message = "Dear Customer,Your Order ".$sale_order_number." has been placed successfully, Thank you for shopping at CoolinCool Store.";
        }
        if($status == "Delivered"){
            $message = "Dear Customer, Your Order ".$sale_order_number." has been delivered successfully, For any help, please contact us at 9655007712, Cool in Cool Store";
        }
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);

        $category_id = $input['category'];
        $product_id = $input['product_id'];
        $unit_id = $input['unit'];
        $qty = $input['quantity'];
        $taxable_cost = $input['price'];
        $cgst = $input['cgst'];
        $sgst = $input['sgst'];
        $igst = $input['igst'];
        $cost = $input['net_value'];
        if($input['product_color_id']>0){
            $color_id = $input['product_color_id'];
        }else{
            $color_id = 0;
        }

        for($i=0;$i<count($category_id);$i++){
            $sales_details[] = array(
                'iSalesOrderId' => $sales_order_id,
                'iCatagoryId' => $category_id[$i],
                'iProductId' => $product_id[$i],
                'iProductUnitId' => $unit_id[$i],
                'IGST' => ($input['igst'][$i]!=0)?$input['igst'][$i]:0,
                'CGST' => ($input['cgst'][$i]!=0)?$input['cgst'][$i]:0,
                'SGST' => ($input['sgst'][$i]!=0)?$input['sgst'][$i]:0,
                'iDeliveryQTY' => $qty[$i],
                'iDeliveryCostperQTY' => $taxable_cost[$i],
                'iDeliverySubTotal' => $cost[$i],
                'iProductColorId' => $color_id[$i],
            );
            if($status == "Delivered"){
                $result = $this->order_model->update_stock_quantity($headoffice_id,$branch_id,$category_id[$i],$product_id[$i],$unit_id[$i],$qty[$i],$color_id[$i]);
                $history[] = array(
                    'eStockType' => 'Out',
                    'eStockReferenceNo' => rand(10000,100000),
                    'iHaedOfficeId' => $headoffice_id,
                    'iBranchId' => $branch_id,
                    'iCategoryId' => $category_id[$i],
                    'iProductId' => $product_id[$i],
                    'dProductQty' => $qty[$i],
                    'iProductUnitId' => $unit_id[$i],
                    'iProductColorId' => $color_id[$i],
                );
            }
        }
        $this->order_model->get_sales_details($sales_details);
        if(!empty($history)){
            $this->order_model->get_branch_products_history($history);
        }
        // redirect(base_url('order'));
        if($status == "Delivered")
            redirect(base_url("order/print_sales?sales_order_id=".$sales_order_id."&language=".$input['language']));
        else
            redirect(base_url("order"));
    }

    public function get_product(){
        $type = $_POST['type'];
        $cat_id = $_POST['category'];
        $product = $_POST['product'];
        $headoffice_id = $_POST['headoffice'];
        $branch_id = $_POST['branch'];
        $customer_id = $_POST['customer_id'];
        $products = $this->order_model->get_product_by_category($headoffice_id,$branch_id,$cat_id,$product,$type,$customer_id);
        echo json_encode($products);
        exit;
    }

    public function edit_sales_order($id){
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
        $this->template->write_view('content', 'edit_sales', $data);
        $this->template->render();
    }

    public function view_sales_order($id){
        $data['sales_order_id'] = $id;
        $data['headoffice'] = $this->order_model->get_headoffice();
        $data['category'] = $this->order_model->get_category();
        $data['sales_order'] = $this->order_model->get_sales_details_by_id($id);
        $data['customer'] = $this->order_model->get_customer();
        $data['unit'] = $this->order_model->get_unit();
        $this->template->write_view('content', 'view_sales', $data);
        $this->template->render();
    }

    public function get_sales_order_values(){
        $sales_order_id = $this->input->post('sales_order_id');
        $data['sales_order'] = $this->order_model->get_sales_details_by_id($sales_order_id);
        // echo json_encode($data);
        // exit;
    }

    public function update_sales_order(){
        $input = $this->input->post();
        // echo "<pre>";print_r($input);exit;
        $sales_order_id = $input['sales_order_id'];
        $salesorderno = $input['salesorderno'];
        $headoffice = $input['headoffice'];
        $address = $input['adress'];
        $shipping_address = $input['shipping_adress'];
        $status = $input['delivery_status'];
        $payment_status = $input['payment_status'];
        $ordered_date = $input['ordered_date'];
        $branch = $input['branch'];
        $customer = $input['customer'];
        $taxable_price = $input['sales']['taxable_price'];
        $cgst_price = $input['sales']['cgst_price'];
        $sgst_price = $input['sales']['sgst_price'];
        $igst_price = $input['sales']['igst_price'];
        $net_total = $input['sales']['net_total'];
        $net_qty = $input['sales']['net_qty'];
        $additional_charge = $input['sales']['additional_charge'];
        $od = str_replace("/","-",$ordered_date);
        if($this->session->userdata('UserRole') == 3)
        $salesman_id = $this->session->userdata('LoggedId');
        else
        $salesman_id = $input['salesman'];
        $sales_order_update_array = array(
            'vSalesOrderNo' => $salesorderno,
            'iHeadOfficeId' => $headoffice,
            'iBranchId' => $branch,
            'iCustomerId' => $customer,
            'iSalesmanId' => $salesman_id,
            'vAddress' => $address,
            'vShippingAddress' => $shipping_address,
            'dOrderedDate' => date("Y-m-d",strtotime($od)),
            'eDeliveryStatus' => $status,
            'iUpdatedBy' => $salesman_id,
            'fNetQty' => $net_qty,
            'fNetCostwithoutGST' => $taxable_price,
            'IGST' => $igst_price,
            'CGST' => $cgst_price,
            'SGST' => $sgst_price,
            'fAdditionalCharge' => $additional_charge,
            'fNetCost' => $net_total,
        );
        $this->order_model->update_sales($sales_order_update_array,$sales_order_id);
        if($status=="Delivered" && $payment_status=="Completed"){
            $status_payment = "SUCCESS";
            $status = "Delivered";
            $total_paid_amt = $net_total;
            $balance = '0.00';
            $update_sales_order = array(
                'eDeliveryStatus' => $status,
                'vPayemntStatus' => $status_payment,
                'paid_amount' => $total_paid_amt,
                'balance'=> $balance
            );
            $this->order_model->update_payment_status($update_sales_order,$sales_order_id);
            $receipt_no= $this->order_model->get_last_id('Gst Code');
            $receipt_bill = array(
                'receipt_id' => $sales_order_id,
                'receipt_no' => $receipt_no['code'],
                'bill_amount' => $net_total,
                'terms' => 1,
                'total_paid_amt' => $net_total,
                'created_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d'),
                'remarks' => 'Completed'
            );
            $insert_id = $this->sales_receipt_model->insert_receipt_bill($receipt_bill);
            $insert_id++;
            $inc['vType'] = 'Gst Code';
            $inc['code'] = 'RECQ000' . $insert_id;
            $this->sales_receipt_model->update_increment($inc, $inc['vType']);
        }
        $customer_data = $this->order_model->get_customer_by_id($customer);
        // Authorisation details.
        $username = "smsgateway2k22@gmail.com";
        $hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";

        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "0";

        // Data for text message. This is the text message data.
        $sender = "CICSTO"; // This is who the message appears to be from.
        $numbers = $customer_data['vPhoneNumber']; // A single number or a comma-seperated list of numbers
        if($status == "Cancelled"){
            $message = "Dear Customer, Your Order ".$salesorderno." has been canceled, For any help, please contact us at 9655007712. Cool in Cool Store";
        }
        if($status == "Delivered"){
            $message = "Dear Customer, Your Order ".$salesorderno." has been delivered successfully, For any help, please contact us at 9655007712, Cool in Cool Store";
        }
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);

        $sales_details_delete_id = $input['sales_details_deleted_id'];
        $sales_delete_id = explode(",",$sales_details_delete_id);
        foreach($sales_delete_id as $sd){
            $this->order_model->remove_sales_details($sd);
        }
        $category_id = $input['category'];
        $product_id = $input['product_id'];
        $unit = $input['unit'];
        $price = $input['price'];
        $quantity = $input['quantity'];
        $cgst = $input['cgst'];
        $sgst = $input['sgst'];
        $igst = $input['igst'];
        $color_id = $input['product_color_id'];
        $net_value = $input['net_value'];
        $sales_order_detail_id = $input['sales_order_detail_id'];
        $sales_order_details_update_arr = array();
        $sales_order_details_insert_arr = array();
        for($i=0;$i<count($category_id);$i++){
            if(!empty($sales_order_detail_id[$i])){
                // echo 1;exit;
                // $this->order_model->update_branch_stock($sales_order_detail_id[$i],$product_id[$i],$category_id[$i],$unit[$i],$quantity[$i],$branch,$color_id[$i]);
                $sales_order_details_update_arr[] = array(
                    'iSalesOrderDetailsId' => $sales_order_detail_id[$i],
                    'iSalesOrderId' => $sales_order_id,
                    'iProductId' => $product_id[$i],
                    'iCatagoryId' => $category_id[$i],
                    'iProductUnitId' => $unit[$i],
                    'iProductColorId'=> $color_id[$i],
                    'IGST' => ($input['igst'][$i]!=0)?$input['igst'][$i]:0,
                    'CGST' => ($input['cgst'][$i]!=0)?$input['cgst'][$i]:0,
                    'SGST' => ($input['sgst'][$i]!=0)?$input['sgst'][$i]:0,
                    'iDeliveryQTY' => $quantity[$i],
                    'iDeliveryCostperQTY' => $price[$i],
                    'iDeliverySubTotal' => $net_value[$i],
                );
            }else{
                $sales_order_details_insert_arr[] = array(
                    'iSalesOrderId' => $sales_order_id,
                    'iProductId' => $product_id[$i],
                    'iCatagoryId' => $category_id[$i],
                    'iProductUnitId' => $unit[$i],
                    'iProductColorId'=> $color_id[$i],
                    'IGST' => ($input['igst'][$i]!=0)?$input['igst'][$i]:0,
                    'CGST' => ($input['cgst'][$i]!=0)?$input['cgst'][$i]:0,
                    'SGST' => ($input['sgst'][$i]!=0)?$input['sgst'][$i]:0,
                    'iDeliveryQTY' => $quantity[$i],
                    'iDeliveryCostperQTY' => $price[$i],
                    'iDeliverySubTotal' => $net_value[$i],
                );
            }
            if($status == "Delivered"){
                // echo 1;exit;
                $this->order_model->update_stock_quantity($headoffice,$branch,$category_id[$i],$product_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
                $history[] = array(
                    'eStockType' => 'Out',
                    'eStockReferenceNo' => rand(10000,100000),
                    'iHaedOfficeId' => $headoffice,
                    'iBranchId' => $branch,
                    'iCategoryId' => $category_id[$i],
                    'iProductId' => $product_id[$i],
                    'dProductQty' => $quantity[$i],
                    'iProductUnitId' => $unit[$i],
                    'iProductColorId'=> $color_id[$i],
                );
            }
        }
        if(!empty($sales_order_details_update_arr)){
            $this->order_model->update_sales_details($sales_order_details_update_arr);
        }
        if(!empty($sales_order_details_insert_arr)){
            $this->order_model->get_sales_details($sales_order_details_insert_arr);
        }
        if(!empty($history)){
            $this->order_model->get_branch_products_history($history);
        }
        if($status == "Delivered")
        redirect(base_url("order/print_sales?sales_order_id=".$sales_order_id."&language=".$input['language']));
        else
        redirect(base_url("order"));
    }

    public function delete_sale_order(){
        $sale_order_id = $this->input->post('id');
        // $this->order_model->update_deleted_stock($sale_order_id);
        if(!empty($this->session->userdata('LoggedId'))){
            $salesman_id = $this->session->userdata('LoggedId');
        }
        $sale_order = array(
            'eStatus' => 'Deleted',
            'iUpdatedBy' => $salesman_id,
        );
        $this->order_model->update_sales_status($sale_order,$sale_order_id);
    }

    public function get_category_by_product(){
        $product_id = $this->input->post('iProductId');
        $categoryid = $this->order_model->get_categorid($product_id);
        echo json_encode($categoryid);
        exit;
    }

    public function get_product_unit_by_customer(){
        $product_id = $_POST['iProductId'];
        $customer_id = $_POST['customer_id'];
        $unit = $this->order_model->get_unit_by_customer($product_id,$customer_id);
        echo json_encode($unit);
    }

    public function check_product_quantity(){
        $product_id = $this->input->post('product_id');
        $branch_id = $this->input->post('branch_id');
        $quantity = $this->input->post('quantity');
        $product_unit = $this->input->post('product_unit');
        $color_id = $this->input->post('color_id');
        $result = $this->order_model->check_product_quantity($product_id,$branch_id,$quantity,$product_unit,$color_id);
        if($result){
            echo json_encode(array('status'=>'failure','message'=>'Out of Stock'));
        }else{
            echo json_encode(array('status'=>'success'));
        }
    }

    public function print_sales(){
        if($_POST){
            $id = $this->input->post('sales_order_id');
            $data['language'] = $this->input->post('language');
        }if($_GET){
            $id = $this->input->get('sales_order_id');
            $data['language'] = $this->input->get('language');
        }
        $data['sales_order_id'] = $id;
        $data['headoffice'] = $this->order_model->get_headoffice();
        $data['category'] = $this->order_model->get_category();
        $data['sales_order'] = $this->order_model->get_sales_details_by_id($id);
        $data['unit'] = $this->order_model->get_unit();
        $this->load->view('print_sales',$data);
    }

    public function sale_order_return($id){
        $data['sales_order_id'] = $id;
        $data['headoffice'] = $this->order_model->get_headoffice();
        $data['category'] = $this->order_model->get_category();
        $data['sales_order'] = $this->order_model->get_sales_details_by_id($id);
        $data['customer'] = $this->order_model->get_customer();
        $data['unit'] = $this->order_model->get_unit();
        $this->template->write_view('content', 'sales_return', $data);
        $this->template->render();
    }
    public function check_sale_return_quantity()
    {
        $input = $this->input->post();
        $product_id = $input['product_id'];
        $quantity = $input['quantity'];
        $product_unit = $input['product_unit'];
        $sale_order_detailsid = $input['sale_order_detailsid'];
        $result = $this->order_model->check_return_quantity($product_id,$quantity,$product_unit,$sale_order_detailsid);
        if($result == true){
            echo json_encode(array('status'=>'failure','message'=>'Invalid Quantity'));
        }else{
            echo json_encode(array('status'=>'success'));
        }
    }

    public function return_sales_order()
    {
        $input = $this->input->post();
        $sales_order_id = $input['sales_order_id'];
        $sales_order_detailsid = $input['sales_order_detail_id'];
        $branch = $input['branch_id'];
        $category_id = $input['category'];
        $product_id = $input['product_id'];
        $unit = $input['unit'];
        $net_qty = $input['sales']['net_qty'];
        $price = $input['price'];
        $quantity = $input['quantity'];
        $total_net_quantity = $input['total_net_quantity'];
        $saled_qty = $input['sales_qty'];
        $cgst = $input['cgst'];
        $sgst = $input['sgst'];
        $igst = $input['igst'];
        $net_value = $input['net_value'];
        $total_amount = $input['sales']['net_total'];
        if($input['product_color_id']>0){
            $color_id = $input['product_color_id'];
        }else{
            $color_id = 0;
        }
        $update_sales_return = array();
        $this->order_model->update_sales_net_amount($total_amount,$sales_order_id);
        for($i=0;$i<count($category_id);$i++){
            $this->order_model->update_sales_order($quantity[$i],$sales_order_id);
            $this->order_model->update_sales_order_details($sales_order_detailsid[$i],$product_id[$i],$category_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
            if($quantity[$i]==$saled_qty[$i]){
                $this->order_model->remove_sales_details($sales_order_detailsid[$i]);
                }
            if($total_net_quantity==$net_qty){
                $this->order_model->remove_sales_order($sales_order_id);
            }
            $this->order_model->update_sales_return($product_id[$i],$category_id[$i],$unit[$i],$quantity[$i],$branch,$sales_order_id,$color_id[$i]);
            $update_sales_return[] = array(
                'iSalesOrderId' => $sales_order_id,
                'iSalesOrderDetailsId' => $sales_order_detailsid[$i],
                'iProductId' => $product_id[$i],
                'iCatagoryId' => $category_id[$i],
                'iProductUnitId' => $unit[$i],
                'IGST' => ($input['igst'][$i]!=0)?$input['igst'][$i]:0,
                'CGST' => ($input['cgst'][$i]!=0)?$input['cgst'][$i]:0,
                'SGST' => ($input['sgst'][$i]!=0)?$input['sgst'][$i]:0,
                'iDeliveryQTY' => $quantity[$i],
                'iDeliveryCostperQTY' => $price[$i],
                'iDeliverySubTotal' => $net_value[$i],
                'dCreatedDate' => date("Y-m-d h:i:s")
            );
        }
        $this->order_model->add_sales_return($update_sales_return);
        redirect(base_url('order'));
    }

    public function get_address_by_customer()
    {
        $customer_id = $this->input->post('customer_id');
        $result = $this->order_model->get_address($customer_id);
        echo json_encode($result);
        exit;

    }
    public function get_salesman(){
        $branch_id = $_POST['branch'];
        $data = $this->order_model->get_salesman($branch_id);
        //   echo "<pre>";print_r($data);exit;
        echo json_encode($data);
        exit;
    }
    public function get_customer_by_branch(){
        $branch_id = $_POST['branch'];
        $data = $this->order_model->get_customer($branch_id);
        //   echo "<pre>";print_r($data);exit;
        echo json_encode($data);
        exit;
    }

    // public function view_sales_return()
    // {
        
    // }
}