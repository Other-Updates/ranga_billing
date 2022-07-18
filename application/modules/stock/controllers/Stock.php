<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Controller {
   public function __construct(){
    //    print_r($this->session->userdata('BranchId'));exit;
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url('users'));
        }
	    parent::__construct();
        $this->load->model('stock_model');
    }

	public function add_stock()
	{
        $data['title'] = 'stock_report';
        $data['headoffice'] = $this->stock_model->get_headoffice();
        $data['category'] = $this->stock_model->get_category();
        $data['unit'] = $this->stock_model->get_unit();
        $data['branch'] = $this->stock_model->get_branch_by_headoffice();
        $data['delivery_order_number'] = $this->stock_model->get_order_number();
        // echo "<pre>";print_r($data);exit;
        $this->template->write_view('content', 'stock', $data);
        $this->template->render();
	}

    public function get_category(){
        $category = $this->stock_model->get_category();
        echo json_encode($category);
        exit;
    }

    public function add_branch_products(){
        $category = $_POST['category'];
        $headoffice = $_POST['headoffice'];
        $product = $_POST['product'];
        $product_id = $_POST['product_id'];
        $branch = $_POST['branch'];
        $shipping_adress = $_POST['shipping_adress'];
        $unit = $_POST['unit'];
        $quantity = $_POST['quantity'];
        $priceper = $_POST['price'];
        $netper = $_POST['net_value'];
        $total_net_quantity = $_POST['total_net_quantity'];
        $cgst = $_POST['deleivery']['cgst_price'];
        $sgst = $_POST['deleivery']['sgst_price'];
        $igst = $_POST['deleivery']['igst_price'];
        $netcost = $_POST['deleivery']['net_total'];
        $delivery_order_number = $_POST['delivery_order_number'];
        $delivery_order_date = str_replace("/","-",$this->input->post('delivery_order_date'));
        $delivery_status = $this->input->post('delivery_status');
        if(!empty($_POST['product_color_id']) && $_POST['product_color_id']>0){
            $color_id = $_POST['product_color_id'];
        }else{
            $color_id = 0;
        }
            $delivery_order = array(
                'vDeliveryOrderNo' => $delivery_order_number,
                'iHeadOfficeId' => $headoffice,
                'iBranchId' => $branch,
                'vShippingAddress' => $shipping_adress,
                'fNetQty' => $total_net_quantity,
                'IGST' => $igst,
                'CGST' => $cgst,
                'SGST' => $sgst,
                'fNetCost' => $netcost,
                'eDeliveryStatus' => $delivery_status,
                'dDeliveryDate' => date("Y-m-d",strtotime($delivery_order_date)),
                'dCreatedDate' => date('Y-m-d h:i:s'),
            );
            $delivery_order_id = $this->stock_model->insert_delivery_order($delivery_order);
            $this->stock_model->update_order_number();
            for($i=0;$i<count($category);$i++) {
                $delivery_order_details[] = array(
                    'iDeliveryOrderId' => $delivery_order_id,
                    'iProductId' => $product_id[$i],
                    'iCatagoryId' => $category[$i],
                    'iProductUnitId' => $unit[$i],
                    'iDeliveryQTY' => $quantity[$i],
                    'iDeliveryCostperQTY' => $priceper[$i],
                    'iDeliverySubTotal' => $netper[$i],
                    'iProductColorId' => $color_id[$i],
                );
                if($delivery_status == "Completed"){
                    $this->stock_model->update_warehouse($category[$i],$product_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);

                    $result = $this->stock_model->check_stock_exist($headoffice,$branch,$category[$i],$product_id[$i],$unit[$i],$color_id[$i]);
                    if($result){
                        $update = array(
                            // 'iStockId'=>$result['iStockId'],
                            'dProductQty' => $quantity[$i]+$result['dProductQty'],
                        );
                        $this->stock_model->update_branch_products($result['iStockId'],$update);
                    }else{
                        $insert = array(
                            'iHeadOfficeId' => $headoffice,    
                            'iBranchId' => $branch,
                            'iCategoryId' => $category[$i],
                            'iProductId' => $product_id[$i],
                            'dProductQty' => $quantity[$i],
                            'iProductUnitId' => $unit[$i],
                            'iProductColorId' => $color_id[$i],
                        );
                    $this->stock_model->get_branch_products($insert);
                    }
                    $history[] = array(
                        'eStockType' => 'In',
                        'eStockReferenceNo' => rand(10000,100000),
                        'iHaedOfficeId' => $headoffice,
                        'iBranchId' => $branch,
                        'iCategoryId' => $category[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $quantity[$i],
                        'iProductUnitId' => $unit[$i],
                        'iProductColorId' => $color_id[$i]
                    );
                    // $this->stock_model->get_product_details();
                    $purchase_history[] = array(
                        // 'iPurchaseOrderId' => $purchase_order_id,
                        'ePurchaseType' => 'Out',
                        'ePurchaseReferenceNo' => rand(10000,100000),
                        'iCategoryId' => $category[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $quantity[$i],
                        'iProductUnitId' => $unit[$i],
                        'iCreatedBy' => $this->session->userdata('LoggedId')
                    );
                }
            }
            
        // }
        // if(!empty($update)){
        //     $this->stock_model->update_branch_products($update);
        // }
        // if(!empty($insert)){
        //     $this->stock_model->get_branch_products($insert);
        // }
        $this->stock_model->insert_delivery_order_details($delivery_order_details);
        if(!empty($history)){
            $this->stock_model->get_branch_products_history($history);
        }
        if(!empty($purchase_history)){
            $this->stock_model->add_purchase_history($purchase_history);
        }

        redirect(base_url('stock'));
    }

    public function get_product(){
        $cat_id = $_POST['category'];
        $product = $_POST['product'];
        $branch = $_POST['branch'];
        $type = $_POST['type'];
        $products = $this->stock_model->get_product_by_category($cat_id,$product,$branch,$type);
        // echo "<pre>";print_r($products);exit;
        echo json_encode($products);
        exit;
    }
    
    public function get_branch(){
        $headoffice_id = $_POST['headoffice'];
        $data = $this->stock_model->get_branch_by_headoffice($headoffice_id);
        //   echo "<pre>";print_r($data);exit;
        echo json_encode($data);
        exit;
    }
    public function get_deliver(){
        $delivery_order_id = $_POST['iDeliveryOrderId'];
        $data = $this->stock_model->get_branch_by_delivery_order_id($delivery_order_id);
        //   echo "<pre>";print_r($data);exit;
        // echo json_encode($data);
        // exit;
    }

    public function update_delivery_details(){
        $input = $this->input->post();
        $delivery_order_id = $input['delivery_order_id'];
        $headoffice = $input['headoffice'];
        $product_id = $input['product_id'];
        $branch = $input['branch'];
        $shipping_adress = $input['shipping_adress'];
        $priceper = $_POST['price'];
        $netper = $_POST['net_value'];
        $total_net_quantity = $_POST['total_net_quantity'];
        $cgst = $_POST['deleivery']['cgst_price'];
        $sgst = $_POST['deleivery']['sgst_price'];
        $igst = $_POST['deleivery']['igst_price'];
        $netcost = $_POST['deleivery']['net_total'];
        $delivery_order_number = $input['delivery_order_number'];
        $delivery_status = $this->input->post('delivery_status');
        $delivery_order_date = str_replace("/","-",$this->input->post('delivery_order_date'));
        $delivery_order_update_array = array(
            'iHeadOfficeId' => $headoffice,
            'iBranchId' => $branch,
            'vShippingAddress' => $shipping_adress,
            'vDeliveryOrderNo' => $delivery_order_number,
            'fNetQty' => $total_net_quantity,
            'IGST' => $igst,
            'CGST' => $cgst,
            'SGST' => $sgst,
            'fNetCost' => $netcost,
            'eDeliveryStatus' => $delivery_status,
            'dDeliveryDate' => date("Y-m-d",strtotime($delivery_order_date))

        );
        $this->stock_model->update_delivery_order($delivery_order_update_array,$delivery_order_id);

            
            $category = $input['category'];
            $product = $input['product'];
            $product_id = $input['product_id'];
            if(!empty($input['product_color_id']) && $input['product_color_id']>0){
                $color_id = $input['product_color_id'];
            }else{
                $color_id = 0;
            }

            $unit = $input['unit'];
            $quantity = $input['quantity'];
            $delivery_order_detail_id = $input['delivery_details_id'];
            $deliver_order_details_update_arr = array();
            $deliver_order_details_insert_arr = array();
            for($i=0;$i<count($category);$i++) {
                if(!empty($delivery_order_detail_id[$i])){
                    // $this->stock_model->update_delivery_stock($delivery_order_detail_id[$i],$product_id[$i],$category[$i],$unit[$i],$quantity[$i],$branch,$color_id[$i]);
                    $deliver_order_details_update_arr[] = array(
                        'iDeliveryOrderDetailsId' => $delivery_order_detail_id[$i],
                        'iDeliveryOrderId' => $delivery_order_id,
                        'iProductId' => $product_id[$i],
                        'iCatagoryId' => $category[$i],
                        'iProductUnitId' => $unit[$i],
                        'iDeliveryQTY' => $quantity[$i],
                        'iDeliveryCostperQTY' => $priceper[$i],
                        'iDeliverySubTotal' => $netper[$i],
                        'iProductColorId' =>$color_id[$i],
                    );
                }else{
                    $deliver_order_details_insert_arr[] = array(
                        'iDeliveryOrderId' => $delivery_order_id,
                        'iProductId' => $product_id[$i],
                        'iCatagoryId' => $category[$i],
                        'iProductUnitId' => $unit[$i],
                        'iDeliveryQTY' => $quantity[$i],
                        'iProductColorId'=> $color_id[$i],
                    );
                }
                if($delivery_status == "Completed"){
                    $this->stock_model->update_warehouse($category[$i],$product_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
                    $result = $this->stock_model->check_stock_exist($headoffice,$branch,$category[$i],$product_id[$i],$unit[$i],$color_id[$i]);
                    if($result){
                        $update = array(
                            // 'iStockId'=>$result['iStockId'],
                            'dProductQty' => $quantity[$i]+$result['dProductQty'],
                        );
                        $this->stock_model->update_branch_products($result['iStockId'],$update);
                    }else{
                        $insert = array(
                            'iHeadOfficeId' => $headoffice,    
                            'iBranchId' => $branch,
                            'iCategoryId' => $category[$i],
                            'iProductId' => $product_id[$i],
                            'dProductQty' => $quantity[$i],
                            'iProductUnitId' => $unit[$i],
                            'iProductColorId' => $color_id[$i],
                        );
                        $this->stock_model->get_branch_products($insert);

                    }
                    $history[] = array(
                        'eStockType' => 'In',
                        'eStockReferenceNo' => rand(10000,100000),
                        'iHaedOfficeId' => $headoffice,
                        'iBranchId' => $branch,
                        'iCategoryId' => $category[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $quantity[$i],
                        'iProductUnitId' => $unit[$i],
                        'iProductColorId' => $color_id[$i],
                        'iCreatedBy' => $this->session->userdata('LoggedId')
                    );
                    $purchase_history[] = array(
                        // 'iPurchaseOrderId' => $purchase_order_id,
                        'ePurchaseType' => 'Out',
                        'ePurchaseReferenceNo' => rand(10000,100000),
                        'iCategoryId' => $category[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $quantity[$i],
                        'iProductUnitId' => $unit[$i],
                        'iCreatedBy' => $this->session->userdata('LoggedId')
                    );
                }
            }
        // if(!empty($update)){
        //     $this->stock_model->update_branch_products($update);
        // }
        // if(!empty($insert)){
        //     $this->stock_model->get_branch_products($insert);
        // }
        if(!empty($deliver_order_details_update_arr)){
            $this->stock_model->update_deliver_details($deliver_order_details_update_arr);
        }
        if(!empty($deliver_order_details_insert_arr)){
            $this->stock_model->get_deliver_details($deliver_order_details_insert_arr);
        }
        if(!empty($history)){
            $this->stock_model->get_branch_products_history($history);
        }
        if(!empty($purchase_history)){
            $this->stock_model->add_purchase_history($purchase_history);
        }

        if(!empty($input['deliver_order_delete_id'])){
            $deliver_details_delete_id = $input['deliver_order_delete_id'];
            $deliver_delete_id = explode(",",$deliver_details_delete_id);
            foreach($deliver_delete_id as $sd){
                $this->stock_model->delete_stock($sd);
                $this->stock_model->remove_deliver_details($sd);
            }
        }
        redirect(base_url('stock'));
    }

    public function index(){
        $this->template->write_view('content', 'stock_list', $data);
        $this->template->render();
    }


    public function get_branch_stock(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $branch_id = $this->session->userdata('BranchId');
        $list=$this->stock_model->stock_branch($input_data,$branch_id);
        $sno = $input_data['start'] + 1;
        foreach ($list as $key=>$post) {
            if($this->session->userdata('UserRole') == 1){
            $delete = '<a href="" data-id="'.$post->iDeliveryOrderId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="'.base_url('stock/edit_stock/'.$post->iDeliveryOrderId).'" data-id="'.$post->iDeliveryOrderId.'" class="action-icon addAttr"><i class="fa fa-edit fs-5"></i></a>';
            }
            $view = "";
            $return = "";
            if($post->eDeliveryStatus == "Completed"){
                if($this->session->userdata('UserRole') == 1){
                $edit = "";
                $delete = "";
                $return = '<a href="'.base_url('stock/delivery_order_return/').$post->iDeliveryOrderId.'" data-id="'.$post->iDeliveryOrderId.'" class="action-icon" ><button style="font-size: 11px;padding: 0px 8px;" class="btn btn-danger">Return</button></a></a>';
                $view = '<a href="'.base_url('stock/view_stock/').$post->iDeliveryOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            }
            else{
                $edit = "";
                $delete = "";
                $return = "";
                $view = '<a href="'.base_url('stock/view_stock/').$post->iDeliveryOrderId.'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            }
            }
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vDeliveryOrderNo;
            $row[] = $post->vBranchName;
            $row[] = $post->fNetQty;
            $row[] = $post->eDeliveryStatus;
            $row[] = $edit.$delete.$view.$return;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stock_model->count_all_stock(),
            "recordsFiltered" => $this->stock_model->count_all_stock(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function get_product_unit(){
        $product_id = $_POST['iProductId'];
        $unit = $this->stock_model->get_unit_by_product_id($product_id);
        echo json_encode($unit);
    }

    public function edit_stock($id)
	{
        $data['delivery_order_id'] = $id;
        $data['headoffice'] = $this->stock_model->get_headoffice();
        $data['category'] = $this->stock_model->get_category(); 
        $data['delivery_details'] = $this->stock_model->get_branch_by_delivery_order_id($id);
        // echo"<pre>";print_r($data['delivery_details']);exit;
        $data['unit'] = $this->stock_model->get_unit();
        $this->template->write_view('content', 'edit_stock', $data);
        $this->template->render();
    }
    public function view_stock($id)
	{
        $data['delivery_order_id'] = $id;
        $data['headoffice'] = $this->stock_model->get_headoffice();
        $data['category'] = $this->stock_model->get_category(); 
        $data['delivery_details'] = $this->stock_model->get_branch_by_delivery_order_id($id);
        // echo"<pre>";print_r($data['delivery_details']);exit;
        $data['unit'] = $this->stock_model->get_unit();
        $this->template->write_view('content', 'view_stock', $data);
        $this->template->render();
    }

    public function delete_delivery_order(){
        $delivery_order_id = $this->input->post('id');
        // $this->stock_model->update_deleted_stock($delivery_order_id);
        $add_branch_products = array(
            'eStatus' => 'Deleted',
        );
        $this->stock_model->update_delivery_order($add_branch_products,$delivery_order_id);
    }

    public function check_product_quantity(){
        $product_id = $this->input->post('product_id');
        $qty = $this->input->post('quantity');
        $unit = $this->input->post('product_unit');
        $product_color = $this->input->post('product_color');
        $result = $this->stock_model->check_warehouse_qty($product_id,$qty,$unit,$product_color);
        if($result){
            echo json_encode(array('status'=>'failure','message'=>'Out of Stock'));
        }else{
            echo json_encode(array('status'=>'success'));
        }
    }

    public function get_product_quantity()
    {
        $product_id = $this->input->post('product_id');
        $unit_id = $this->input->post('unit_id');
        $color_id = $this->input->post('color_id');
        $result = $this->stock_model->get_product_qty($product_id,$unit_id,$color_id);
        echo json_encode($result);
        exit;
    }

    // Print Delievery
    public function print_delievery(){
        if($_POST){
            $id = $this->input->post('delivery_order_id');
            $data['language'] = $this->input->post('language');
        }if($_GET){
            $id = $this->input->get('delivery_order_id');
            $data['language'] = $this->input->get('language');
        }
        $data['delivery_order_id'] = $id;
        $data['headoffice'] = $this->stock_model->get_headoffice();
        $data['category'] = $this->stock_model->get_category();
        $data['deleivery_order'] = $this->stock_model->get_deleivery_details_by_id($id);
        
        $data['unit'] = $this->stock_model->get_unit();
        $this->load->view('print_deleivery',$data);
    }
    // Delivery Return Data
    public function delivery_order_return($id)
	{
        $data['delivery_order_id'] = $id;
        $data['headoffice'] = $this->stock_model->get_headoffice();
        $data['category'] = $this->stock_model->get_category(); 
        $type='return';
        $data['delivery_details'] = $this->stock_model->get_branch_by_delivery_order_id($id,$type);
        // echo"<pre>";print_r($data['delivery_details']);exit;
        $data['unit'] = $this->stock_model->get_unit();
        $this->template->write_view('content', 'delivery_order_return', $data);
        $this->template->render();
    }
    // Return Delievery Order
    public function return_delivery_order(){
        $input = $this->input->post();
        $delivery_order_id = $input['delivery_order_id'];
        $headoffice = $input['headoffice'];
        $product_id = $input['product_id'];
        $branch = $input['branch'];
        $shipping_adress = $input['shipping_adress'];
        $priceper = $_POST['price'];
        $netper = $_POST['net_value'];
        $total_net_quantity = $_POST['total_net_quantity'];
        $cgst = $_POST['deleivery']['cgst_price'];
        $sgst = $_POST['deleivery']['sgst_price'];
        $igst = $_POST['deleivery']['igst_price'];
        $netcost = $_POST['deleivery']['net_total'];
        $net_qty = $_POST['deleivery']['net_qty'];
        $delivery_order_number = $input['delivery_order_number'];
        $delivery_status = $this->input->post('delivery_status');
        $delivery_order_date = str_replace("/","-",$this->input->post('delivery_order_date'));
        $delivery_order_update_array = array(
            'iHeadOfficeId' => $headoffice,
            'iBranchId' => $branch,
            'vShippingAddress' => $shipping_adress,
            'vDeliveryOrderNo' => $delivery_order_number,
            'fNetQty' => $total_net_quantity,
            'IGST' => $igst,
            'CGST' => $cgst,
            'SGST' => $sgst,
            'fNetCost' => $netcost,
            'eDeliveryStatus' => $delivery_status,
            'dDeliveryDate' => date("Y-m-d",strtotime($delivery_order_date))

        );
        $this->stock_model->update_delivery_order($delivery_order_update_array,$delivery_order_id);
            $category = $input['category'];
            $product = $input['product'];
            $product_id = $input['product_id'];
            if(!empty($input['product_color_id']) && $input['product_color_id']>0){
                $color_id = $input['product_color_id'];
            }else{
                $color_id = 0;
            }
            $unit = $input['unit'];
            $quantity = $input['quantity'];
            $delivered_qty = $input['delivered_qty'];
            $delivery_order_detail_id = $input['delivery_details_id'];
            $deliver_order_details_update_arr = array();
            for($i=0;$i<count($category);$i++) {
                if(!empty($delivery_order_detail_id[$i])){
                    $return_qty = $delivered_qty[$i] - $quantity[$i];
                    $deliver_order_details_update_arr[] = array(
                        'iDeliveryOrderDetailsId' => $delivery_order_detail_id[$i],
                        'iDeliveryOrderId' => $delivery_order_id,
                        'iProductId' => $product_id[$i],
                        'iCatagoryId' => $category[$i],
                        'iProductUnitId' => $unit[$i],
                        'iDeliveryQTY' => $return_qty,
                        'iDeliveryCostperQTY' => $priceper[$i],
                        'iDeliverySubTotal' => $netper[$i],
                        'iProductColorId' =>$color_id[$i],
                    );
                }
                if($quantity[$i]==$delivered_qty[$i]){
                $this->stock_model->remove_deliver_details($delivery_order_detail_id[$i]);
                }
                if($total_net_quantity==$net_qty){
                $this->stock_model->remove_deliver_order($delivery_order_id);
                }
                $this->stock_model->return_warehouse($category[$i],$product_id[$i],$unit[$i],$quantity[$i],$color_id[$i]);
                $result = $this->stock_model->check_stock_exist($headoffice,$branch,$category[$i],$product_id[$i],$unit[$i],$color_id[$i]);
                if($result){
                    // if($result['dProductQty']==$quantity[$i]){
                    // $this->stock_model->delete_stock($result['iStockId']);
                    // }
                    // else{
                    $update = array(
                        // 'iStockId'=>$result['iStockId'],
                        'dProductQty' => $result['dProductQty'] - $quantity[$i],
                    );
                    $this->stock_model->update_branch_products($result['iStockId'],$update);
                }
                    $history[] = array(
                        'eStockType' => 'In',
                        'eStockReferenceNo' => rand(10000,100000),
                        'iHaedOfficeId' => $headoffice,
                        'iBranchId' => $branch,
                        'iCategoryId' => $category[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $return_qty,
                        'iProductUnitId' => $unit[$i],
                        'iProductColorId' => $color_id[$i],
                        'iCreatedBy' => $this->session->userdata('LoggedId')
                    );
                    $purchase_history[] = array(
                        // 'iPurchaseOrderId' => $purchase_order_id,
                        'ePurchaseType' => 'Out',
                        'ePurchaseReferenceNo' => rand(10000,100000),
                        'iCategoryId' => $category[$i],
                        'iProductId' => $product_id[$i],
                        'dProductQty' => $return_qty,
                        'iProductUnitId' => $unit[$i],
                        'iCreatedBy' => $this->session->userdata('LoggedId')
                    );
            }
        if(!empty($deliver_order_details_update_arr)){
            $this->stock_model->update_deliver_details($deliver_order_details_update_arr);
        }
        if(!empty($history)){
            $this->stock_model->get_branch_products_history($history);
        }
        if(!empty($purchase_history)){
            $this->stock_model->add_purchase_history($purchase_history);
        }
        redirect(base_url('stock'));
    }
}