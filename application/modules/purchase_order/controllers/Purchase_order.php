<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
			redirect(base_url('users'));
		}
	    parent::__construct();
        $this->load->model('purchase_order_model'); 
    }

	public function index()
	{
        $data['title'] = 'Orders';
        $data['users'] = $this->purchase_order_model->get_all_user();
        $data['distributors'] = $this->purchase_order_model->get_all_distributors();
        $data['products'] = $this->purchase_order_model->get_all_products();
        $this->template->write_view('content', 'purchase_list', $data);
        $this->template->render();
	}

    public function get_purchase_order(){
        $user_role_id = $this->session->userdata('UserRole');
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->purchase_order_model->order_list();
        $sno = $input_data['start'] + 1;
        foreach ($list as $key=>$post) {
            if($user_role_id != 1){
                $check = 0;
                $edit = '<a href="#" data-id="'.$post->iPurchaseOrderId.'" data-order-no="'.$post->vPurchaseOrderNo.'" data_url="'.base_url('purchase_order/edit_purchase_order/').$post->iPurchaseOrderId.'" class="action-icon edit_purchase_order_modal fa fa-pencil td-icon" ></a>';
            }else{
                $check = 1;
                $edit = '<a href="'.base_url('purchase_order/edit_purchase_order/').$post->iPurchaseOrderId.'" data-id="'.$post->iPurchaseOrderId.'" class="action-icon edit_purchase_order" ><i class="fa fa-pencil td-icon"></i></a>';
            }
            $delete = '<a href="" data-id="'.$post->iPurchaseOrderId.'" class="action-icon removeAttr" data-check="'.$check.'"><i class="fa fa-trash td-icon"></i></a>';
            $view = '<a href="'.base_url('purchase_order/view_purchase_order/').$post->iPurchaseOrderId.'" data-id="'.$post->iPurchaseOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            $return = '';
            if($post->eDeliveryStatus == "Delivered"){
                $edit = '';
                $delete = '';
                $return = '<a href="'.base_url('purchase_order/purchase_order_return/').$post->iPurchaseOrderId.'" data-id="'.$post->iPurchaseOrderId.'" class="action-icon" ><button style="font-size: 11px;padding: 0px 8px;" class="btn btn-danger">Return</button></a>';
            }
            if($post->eDeliveryStatus == "Cancelled"){
                $edit = '';
                $delete = '';    
            }
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vPurchaseOrderNo;
            $row[] = $post->vSupplierName;
            $row[] = $post->fNetCost;
            $row[] = $post->deliverydate;
            $row[] = $post->eDeliveryStatus;
            $row[] = $post->createddate;
            $row[] = $view.$edit.$delete.$return;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->purchase_order_model->count_all_purchase(),
            "recordsFiltered" => $this->purchase_order_model->count_all_purchase(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function add_purchase(){
        $data['supplier'] = $this->purchase_order_model->get_supplier();
        $data['category'] = $this->purchase_order_model->get_category();
        $data['purchase_order_number'] = $this->purchase_order_model->get_order_number("Purchase");
        $this->template->write_view('content', 'add_purchase', $data);
        $this->template->render();
    }

    public function get_gst_values(){
        $product_id = $this->input->post('iProductId');
        $gst = $this->purchase_order_model->get_gst_by_product($product_id);
        echo json_encode($gst);
    }

    public function get_unit_price(){
        $product_id = $this->input->post('iProductId');
        $unit_id = $this->input->post('unit_id');
        $price = $this->purchase_order_model->get_price_by_unit($product_id,$unit_id);
        echo json_encode($price);
    }

    public function add_purchase_order(){
        $input = $this->input->post();
        $purchase_order_number = $input['po_number'];
        $supplier = $input['supplier']; 
        $address = $input['address'];
        $status = $input['delivery_status'];
        $ordered_date = $input['delivered_date'];
        $net_qty = $input['purchase']['net_qty'];
        $cost_withoutgst = $input['purchase']['taxable_price'];
        $cgst_price = $input['purchase']['cgst_price'];
        $sgst_price = $input['purchase']['sgst_price'];
        $igst_price = $input['purchase']['igst_price'];
        $net_cost = $input['purchase']['net_total'];
        $igst_price = $input['purchase']['igst_price'];
        $od = str_replace("/","-",$ordered_date);
        $purchase_order = array(
            'vPurchaseOrderNo' => $purchase_order_number,
            'vAddress' => $address,
            'iSupplierId' => $supplier,
            'dDeliveryDate' => date("Y-m-d",strtotime($od)),
            'eDeliveryStatus' => $status,
            'fNetQty' => $net_qty,
            'fNetCostwithoutGST' => $cost_withoutgst,
            'IGST' => $igst_price,
            'CGST' => $cgst_price,
            'SGST' => $sgst_price,
            'fNetCost' => $net_cost,
            // 'ePendingStatus' => $input['pending_status'],
            'dCreatedDate' => date('Y-m-d h:i:s'),
        );
        $purchase_order_id = $this->purchase_order_model->add_purchase_order($purchase_order);
        $this->purchase_order_model->update_order_number("Purchase");

        $category_id = $input['category'];
        $product_id = $input['product_id'];
        $unit_id = $input['unit'];
        $qty = $input['quantity'];
        $taxable_cost = $input['price'];
        $cgst = $input['cgst'];
        $sgst = $input['sgst'];
        $igst = $input['igst'];
        $cost = $input['net_value'];
        if(!empty($input['product_color_id']) && $input['product_color_id']>0){
            $color_id = $input['product_color_id'];
        }else{
            $color_id = 0;
        }
        for($i=0;$i<count($category_id);$i++){

            $purchase_order_details[] = array(
                'iPurchaseOrderId' => $purchase_order_id,
                'iCatagoryId' => $category_id[$i],
                'iProductId' => $product_id[$i],
                'iProductUnitId' => $unit_id[$i],
                'IGST' => $input['igst'][$i],
                'CGST' => $input['cgst'][$i],
                'SGST' => $input['sgst'][$i],
                'iProductColorId' => $color_id[$i],
                'iPurchaseQTY' => $qty[$i],
                'iPurchaseCostperQTY' => $taxable_cost[$i],
                'iPurchaseSubTotal' => $cost[$i],
            );
            if($status == "Delivered"){
                $result = $this->purchase_order_model->check_stock_exist($product_id[$i],$unit_id[$i],$color_id[$i]);
                    if($result){
                        $update = array(
                            'dProductQty' => $qty[$i]+$result['dProductQty'],
                        );
                        $this->purchase_order_model->update_warehouse_products($result['iWareHouseStockId'],$update);
                    }else{
                        $warehouse_stock = array(
                            'iCategoryId' => $category_id[$i],
                            'iProductId' => $product_id[$i],
                            'dProductQty' => $qty[$i],
                            'iProductUnitId' => $unit_id[$i],
                            'iProductColorId'=>$color_id[$i],
                        ); 
                        $this->purchase_order_model->insert_warehouse_products($warehouse_stock);
                    }

                $history[] = array(
                    'iPurchaseOrderId' => $purchase_order_id,
                    'ePurchaseType' => 'In',
                    'ePurchaseReferenceNo' => rand(10000,100000),
                    'iCategoryId' => $category_id[$i],
                    'iProductId' => $product_id[$i],
                    'dProductQty' => $qty[$i],
                    'iProductUnitId' => $unit_id[$i],
                    'iCreatedBy' => $this->session->userdata('LoggedId')
                );
            }
        }
        // echo"<pre>";print_r($purchase_order_details);exit;
        $this->purchase_order_model->add_purchase_details($purchase_order_details);
        if(!empty($history)){
            $this->purchase_order_model->add_purchase_history($history);
        }
        redirect(base_url('purchase_order'));
    }

    public function get_product(){
        $type = $_POST['type'];
        $cat_id = $_POST['category'];
        $product = $_POST['product'];
        $products = $this->purchase_order_model->get_product_by_category($cat_id,$product,$type);
        echo json_encode($products);
        exit;
    }

    public function edit_purchase_order($id){
        $data['purchase_order_id'] = $id;
        $data['category'] = $this->purchase_order_model->get_category();
        $data['purchase_order'] = $this->purchase_order_model->get_purchase_details_by_id($id);
        $data['supplier'] = $this->purchase_order_model->get_supplier();
        $data['unit'] = $this->purchase_order_model->get_unit();
        $this->template->write_view('content', 'edit_purchase', $data);
        $this->template->render();
    }

    public function view_purchase_order($id){
        $data['purchase_order_id'] = $id;
        $data['category'] = $this->purchase_order_model->get_category();
        $data['purchase_order'] = $this->purchase_order_model->get_purchase_details_by_id($id);
        $data['supplier'] = $this->purchase_order_model->get_supplier();
        $data['unit'] = $this->purchase_order_model->get_unit();
        $this->template->write_view('content', 'view_purchase', $data);
        $this->template->render();
    }

    public function get_sales_order_values(){
        $sales_order_id = $this->input->post('sales_order_id');
        $data['sales_order'] = $this->purchase_order_model->get_sales_details_by_id($sales_order_id);
        // echo json_encode($data);
        // exit;
    }

    public function update_purchase_order(){
        $input = $this->input->post();
        $purchase_order_id = $input['purchase_order_id'];
        $purchaseorderno = $input['purchaseorderno'];
        $address = $input['address'];
        $status = $input['delivery_status'];
        $ordered_date = $input['ordered_date'];
        $supplier = $input['supplier'];
        $taxable_price = $input['purchase']['taxable_price'];
        $cgst_price = $input['purchase']['cgst_price'];
        $sgst_price = $input['purchase']['sgst_price'];
        $igst_price = $input['purchase']['igst_price'];
        $net_total = $input['purchase']['net_total'];
        $net_qty = $input['purchase']['net_qty'];
        $od = str_replace("/","-",$ordered_date);
        $purchase_order_update_array = array(
            'vPurchaseOrderNo' => $purchaseorderno,
            'iSupplierId' => $supplier,
            'vAddress' => $address,
            'dDeliveryDate' => date("Y-m-d",strtotime($od)),
            'eDeliveryStatus' => $status,
            'fNetQty' => $net_qty,
            'fNetCostwithoutGST' => $taxable_price,
            'IGST' => $igst_price,
            'CGST' => $cgst_price,
            'SGST' => $sgst_price,
            'fNetCost' => $net_total,
            // 'ePendingStatus' => $input['pending_status'],
            'dUpdatedDate' => date('Y-m-d h:i:s'),
        );
        $this->purchase_order_model->update_purchase($purchase_order_update_array,$purchase_order_id);
        $category_id = $input['category'];
        $product_id = $input['product_id'];
        $unit = $input['unit'];
        $price = $input['price'];
        $quantity = $input['quantity'];
        $cgst = $input['cgst'];
        $sgst = $input['sgst'];
        $igst = $input['igst'];
        $net_value = $input['net_value'];
        if(!empty($input['product_color_id']) && $input['product_color_id']>0){
            $color_id = $input['product_color_id'];
        }else{
            $color_id = 0;
        }
        
        $purchase_order_detail_id = $input['iPurchaseOrderDetailsId'];
        $purchase_order_details_update_arr = array();
        $purchase_order_details_insert_arr = array();
        for($i=0;$i<count($category_id);$i++){
            if(!empty($purchase_order_detail_id[$i])){
                // $this->purchase_order_model->update_warehouse_stock($purchase_order_detail_id[$i],$product_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
                $purchase_order_details_update_arr[] = array(
                    'iPurchaseOrderDetailsId' => $purchase_order_detail_id[$i],
                    'iPurchaseOrderId' => $purchase_order_id,
                    'iProductId' => $product_id[$i],
                    'iCatagoryId' => $category_id[$i],
                    'iProductUnitId' => $unit[$i],
                    'IGST' => $igst[$i],
                    'CGST' => $cgst[$i],
                    'SGST' => $sgst[$i],
                    'iPurchaseQTY' => $quantity[$i],
                    'iPurchaseCostperQTY' => $price[$i],
                    'iPurchaseSubTotal' => $net_value[$i],
                );
            }else{
                $purchase_order_details_insert_arr[] = array(
                    'iPurchaseOrderId' => $purchase_order_id,
                    'iProductId' => $product_id[$i],
                    'iCatagoryId' => $category_id[$i],
                    'iProductUnitId' => $unit[$i],
                    'iProductColorId' => $color_id[$i],
                    'IGST' => $igst[$i],
                    'CGST' => $cgst[$i],
                    'SGST' => $sgst[$i],
                    'iPurchaseQTY' => $quantity[$i],
                    'iPurchaseCostperQTY' => $price[$i],
                    'iPurchaseSubTotal' => $net_value[$i],
                );
            }
            if($status == "Delivered"){
                $result = $this->purchase_order_model->check_stock_exist($product_id[$i],$unit[$i],$color_id[$i]);
                if($result){
                    $update = array(
                        'dProductQty' => $quantity[$i]+$result['dProductQty'],
                    );
                    $this->purchase_order_model->update_warehouse_products($result['iWareHouseStockId'],$update);
                }else{
                    $warehouse_stock = array(
                        'iCategoryId' => $category_id[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $quantity[$i],
                        'iProductUnitId' => $unit[$i],
                        'iProductColorId'=>$color_id[$i],
                    ); 
                    $this->purchase_order_model->insert_warehouse_products($warehouse_stock);
                    }
                
                $history[] = array(
                    'iPurchaseOrderId' => $purchase_order_id,
                    'ePurchaseType' => 'In',
                    'ePurchaseReferenceNo' => rand(10000,100000),
                    'iCategoryId' => $category_id[$i],
                    'iProductId' => $product_id[$i],
                    'dProductQty' => $quantity[$i],
                    'iProductUnitId' => $unit[$i],
                    'iCreatedBy' => $this->session->userdata('LoggedId')
                );
            }
        }
        if(!empty($purchase_order_details_update_arr)){
            // $this->purchase_order_model->update_purchase_details($purchase_order_details_update_arr);
        }
        if(!empty($purchase_order_details_insert_arr)){
            $this->purchase_order_model->add_purchase_details($purchase_order_details_insert_arr);
        }
        if(!empty($history)){
            $this->purchase_order_model->add_purchase_history($history);
        }
        $purchase_details_deleted_id = $input['purchase_details_deleted_id'];
        if(!empty($purchase_details_deleted_id)){
            $purchase_delete_id = explode(",",$purchase_details_deleted_id);
            foreach($purchase_delete_id as $pd){
                $this->purchase_order_model->remove_purchase_details($pd);
            }
        }
        redirect(base_url('purchase_order'));
    }

    public function get_category_by_product(){
        $product_id = $this->input->post('iProductId');
        $categoryid = $this->purchase_order_model->get_categorid($product_id);
        echo json_encode($categoryid);
        exit;
    }

    public function get_product_unit(){
        $product_id = $_POST['iProductId'];
        $unit = $this->purchase_order_model->get_unit_by_product($product_id);
        echo json_encode($unit);
    }

    public function check_product_quantity(){
        $product_id = $this->input->post('product_id');
        $branch_id = $this->input->post('branch_id');
        $quantity = $this->input->post('quantity');
        $product_unit = $this->input->post('product_unit');
        $result = $this->purchase_order_model->check_product_quantity($product_id,$branch_id,$quantity,$product_unit);
        if($result){
            echo json_encode(array('status'=>'failure','message'=>'Invalid quantity'));
        }else{
            echo json_encode(array('status'=>'success'));
        }
    }

    public function delete_purchase_order(){
        $id = $this->input->post('id');
        // $this->purchase_order_model->update_deleted_stock($id);
        $purchase_order = array(
            'eStatus' => 'Deleted',
        );
        $this->purchase_order_model->update_purchase($purchase_order,$id);
    }

    public function purchase_order_return($id){
        $data['purchase_order_id'] = $id;
        $data['category'] = $this->purchase_order_model->get_category();
        $data['purchase_order'] = $this->purchase_order_model->get_purchase_details_by_id($id);
        $data['supplier'] = $this->purchase_order_model->get_supplier();
        $data['unit'] = $this->purchase_order_model->get_unit();
        $this->template->write_view('content', 'purchase_return', $data);
        $this->template->render();
    }

    public function update_purchase_return()
    {
        $input = $this->input->post();
        $purchase_order_id = $input['purchase_order_id'];
        $purchase_order_detailsid = $input['iPurchaseOrderDetailsId'];
        $category_id = $input['category'];
        $product_id = $input['product_id'];
        $unit = $input['unit'];
        $price = $input['price'];
        $quantity = $input['quantity'];
        $total_net_quantity = $input['total_net_quantity'];
        $purchased_qty = $input['purchase_qty'];
        $cgst = $input['cgst'];
        $sgst = $input['sgst'];
        $igst = $input['igst'];
        $net_value = $input['net_value'];
        $total_amount = $input['purchase']['net_total'];
        $net_qty = $input['purchase']['net_qty'];
        if($input['product_color_id']>0){
            $color_id = $input['product_color_id'];
        }else{
            $color_id = 0;
        }
        $this->purchase_order_model->update_purchase_net_amount($total_amount,$purchase_order_id);
        $update_purchase_return = array();
        $purchase_return_no = $this->purchase_order_model->get_order_number("Purchase Return");
        $return_number = $purchase_return_no['iOrderNumber'] + 1;
        $this->purchase_order_model->update_order_number("Purchase Return");
        for($i=0;$i<count($category_id);$i++){
            $this->purchase_order_model->update_purchase_order_details($purchase_order_detailsid[$i],$product_id[$i],$category_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
            if($quantity[$i]==$purchased_qty[$i]){
                $this->purchase_order_model->remove_purchase_details($purchase_order_detailsid[$i]);
                }
            if($total_net_quantity==$net_qty){
                $this->purchase_order_model->remove_purchase_order($purchase_order_id);
            }
            $this->purchase_order_model->update_purchase_return($product_id[$i],$category_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
            $update_purchase_return[] = array(
                'iPurchaseOrderId' => $purchase_order_id,
                'vPurchaseReturnNo' => $return_number+1,
                'iPurchaseOrderDetailsId' => $purchase_order_detailsid[$i],
                'iProductId' => $product_id[$i],
                'iCatagoryId' => $category_id[$i],
                'iProductUnitId' => $unit[$i],
                'IGST' => $igst[$i],
                'CGST' => $cgst[$i],
                'SGST' => $sgst[$i],
                'iPurchaseQTY' => $quantity[$i],
                'iPurchaseCostperQTY' => $price[$i],
                'iPurchaseSubTotal' => $net_value[$i],
            );

            $history[] = array(
                'iPurchaseOrderId' => $purchase_order_id,
                'ePurchaseType' => 'Out',
                'ePurchaseReferenceNo' => rand(10000,100000),
                'iCategoryId' => $category_id[$i],
                'iProductId' => $product_id[$i],
                'dProductQty' => $quantity[$i],
                'iProductUnitId' => $unit[$i],
                'iCreatedBy' => $this->session->userdata('LoggedId')
            );
        }
        $this->purchase_order_model->add_purchase_return($update_purchase_return);
        $this->purchase_order_model->add_purchase_history($history);
        redirect(base_url('purchase_order'));
    }

    public function check_purchase_return_quantity()
    {
        $input = $this->input->post();
        $product_id = $input['product_id'];
        $quantity = $input['quantity'];
        $product_unit = $input['product_unit'];
        $purchase_order_detailsid = $input['purchase_order_detailsid'];
        $result = $this->purchase_order_model->check_return_quantity($product_id,$quantity,$product_unit,$purchase_order_detailsid);
        if($result == true){
            echo json_encode(array('status'=>'failure','message'=>'Invalid Quantity'));
        }else{
            echo json_encode(array('status'=>'success'));
        }
    }

    public function generate_otp()
    {   
        $type = $this->input->post('type');
        $order_number = $this->input->post('order_number');
        $otp['iOtpCode'] = rand(1000,9999);
        $this->purchase_order_model->get_otp($otp);
        $result_data = array(
            'iOtpCode' => $otp['iOtpCode']
        );

        // $admin_number = $this->purchase_order_model->get_admin_numbers();
        // // $numbers = $admin_number['iAdminMobileNumber'];
            
            // Authorisation details.
        $username = "smsgateway2k22@gmail.com";
        $hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";

        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "0";

        // Data for text message. This is the text message data.
        $sender = "CICSTO"; // This is who the message appears to be from.
        $numbers = "8667579048"; // A single number or a comma-seperated list of numbers
        $message = ''.$otp['iOtpCode'].' is your OTP to edit purchase order '.$order_number.', Cool in Cool Store.';
        // 612 chars or less
        // A single number or a comma-seperated list of numbers
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('https://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        // print_r($result);exit;
        curl_close($ch);

        // // Authorisation details.
        // $username = "smsgateway2k22@gmail.com";
        // $hash = "af6af7187849c32125a21b3c57dc5fd6cea5c96111d5b0bb5baf1e008bb5d9cd";

        // // Config variables. Consult http://api.textlocal.in/docs for more info.
        // $test = "0";

        // // Data for text message. This is the text message data.
        // $sender = "CICSTO"; // This is who the message appears to be from.
        // $numbers = "8667579048"; // A single number or a comma-seperated list of numbers
        // $message = ''.$otp['iOtpCode'].' is your OTP to edit purchase order '.$order_number.', Cool in Cool Store.';
        // // print_r($message);exit;
        // // 612 chars or less
        // // A single number or a comma-seperated list of numbers
        // $message = urlencode($message);
        // $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        // $ch = curl_init('http://api.textlocal.in/send/?');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $result = curl_exec($ch); // This is the result from the API
        // curl_close($ch);
        // print_r($result);exit;

        echo json_encode($result_data);
        exit;
    }

    public function get_supplier_address()
    {
        $supplier_id = $this->input->post('supplier_id');
        $data = $this->purchase_order_model->get_supplier_address($supplier_id);
        echo json_encode($data);
        exit;
    }

    public function print_purchase(){
        if($_POST){
            $id = $this->input->post('purchase_order_id');
            $data['language'] = $this->input->post('language');
        }if($_GET){
            $id = $this->input->get('purchase_order_id');
            $data['language'] = $this->input->get('language');
        }
        $data['purchase_order_id'] = $id;
        $data['category'] = $this->purchase_order_model->get_category();
        $data['purchase_order'] = $this->purchase_order_model->get_purchase_details_by_id($id);
        $data['supplier'] = $this->purchase_order_model->get_supplier();
        $data['unit'] = $this->purchase_order_model->get_unit();
        // echo "<pre>";print_r($data['purchase_order']);exit;
        $this->load->view('print_purchase',$data);
    }
    // public function get_product_unit(){
    //     $product_id = $_POST['iProductId'];
    //     $unit = $this->stock_model->get_unit_by_product_id($product_id);
    //     echo json_encode($unit);
    // }
}
