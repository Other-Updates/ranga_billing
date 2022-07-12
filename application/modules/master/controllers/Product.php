<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('product_model'); 
        $this->load->model('category_model'); 
        $this->load->model('subcategory_model'); 
        $this->load->model('model_model'); 
        $this->load->model('brand_model');
        $this->load->model('branch_model');
        $this->load->model('headoffice_model');
        $this->load->model('product_unit_model');
        $this->load->model('grade_model');
        $this->load->model('product_price_model');
        $this->load->model('product_colour_model');
        $this->load->model('minimum_quantity_model');
    }

	public function index()
	{
        $data['title'] = 'Product';
        $this->template->write_view('content', 'product', $data);
        $this->template->render();
	}

    public function add_product(){
        $color_check = $this->input->post('color_check');
        $color_check= implode(",",$color_check);
        // echo"<pre>"; print_r($color_check); exit;
        $product_name = $this->seo_friendly_url($_POST['product_name']);
        $config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $product_name.'-'.rand(10000,10000000);
        $this->load->library('upload');
        $files = $_FILES;
        $count = count($_FILES['product_image']['name']);
        $file_name = array();
        for($i=0; $i<$count; $i++){           
            $_FILES['product_image']['name']= $files['product_image']['name'][$i];
            $_FILES['product_image']['type']= $files['product_image']['type'][$i];
            $_FILES['product_image']['tmp_name']= $files['product_image']['tmp_name'][$i];
            $_FILES['product_image']['error']= $files['product_image']['error'][$i];
            $_FILES['product_image']['size']= $files['product_image']['size'][$i];    
    
            $this->upload->initialize($config);
            $this->upload->do_upload('product_image');
            $upload_data = $this->upload->data();
            $file_name[] = $upload_data['file_name'];
        }
            $image = implode(",",$file_name);
            // for($c=0;$c<count($color_check);$c++){
                // $color_product_arr[] = array(
                    // 'iProductColorId'=> $color_check[$c],
                    // 'iCategoryId'=>$_POST['product_category'],
                // );
                // $color_check = $this->input->post('color_check');
                // echo"<pre>"; print_r($color_check); exit;
            // }
        $product = array(
            'vProductName' => $_POST['product_name'],
            'vProductName_Tamil' => $_POST['product_name_tamil'],
            'vDescription' => $_POST['description'],
            'vDescription_Tamil' => $_POST['description_tamil'],
            'iCategoryId' => $_POST['product_category'],
            'iSubcatagoryId' => $_POST['subcategory'],
            'iBrandId' => $_POST['brand_name'],
            'iProductColorId' => $color_check,
            'iModelId' => $_POST['model_name'],
            'IGST' => $_POST['igst'],
            'CGST' => $_POST['cgst'],
            'SGST' => $_POST['sgst'],
            'vHSNNO' => $_POST['hsn_no'],
            'vImages' => $image,
            'dCreatedDate' => date('Y-m-d'),
        );
        // print_r($product);exit;
        $product_id = $this->product_model->get_products($product);
         //echo"<pre>"; print_r($product_id); exit;

        $branch_check = $this->input->post('branch_check');
        // echo"<pre>"; print_r($branch_check); exit;
        if(!empty($branch_check)){
            for($h=0;$h<count($branch_check);$h++){
                $branch_product_arr[] = array(
                    'iProductId'=> $product_id,
                    'iBranchId'=> $branch_check[$h],
                    'iCategoryId'=>$_POST['product_category'],
                );
            }
            $this->product_model->get_product_branch($branch_product_arr);
        }
        
       // echo"<pre>"; print_r($branch_product_arr); exit;
        $price=array();
        $unit = $this->input->post('unit');
        $amount = $this->input->post('price');
        $grade = $this->input->post('grade');
        $pack = $this->input->post('pack');
        if(count($unit)>0)
        {
            for($i=0;$i<count($unit);$i++){
                $price[] = array(
                    'iProductId' => $product_id,
                    'iProductUnitId' => $unit[$i],
                    'iGradeId' => $grade[$i],
                    'vPacketCount' => $pack[$i],
                    'fProductPrice' => $amount[$i],
                );
            }
            $this->product_model->get_product_price($price);
        }
        $branch = $this->input->post('branch');
        $unit_minqty = $this->input->post('unit_minqty');
        $minimum_quantity = $this->input->post('minimum_quantity');
        if(count($min_qty)>0)
        {
        for($j=0;$j<count($branch);$j++){
            $min_qty[] = array(
                'iBranchId' => $branch[$j],
                'iProductId' => $product_id,
                'iProductUnitId' => $unit_minqty[$j],
                'iMinQty' => $minimum_quantity[$j],
            );
        }
        $this->product_model->get_min_qty($min_qty);
    }
        redirect(base_url('master/product'));
    }

    public function get_products(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->product_model->product_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $img = explode(',',$post->vImages);
            $delete = '<a href="" data-id="'.$post->iProductId.'" class="removeAttr action-icon" ><i class="icofont icofont-ui-delete"></i></a>';
            // $edit = '<a href="" data-id="'.$post->iProductId.'" class="addAttr action-icon" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="icofont icofont-ui-edit"></i></a>';
            $edit = '<a href="'.base_url().'master/product/edit_product/'.$post->iProductId.'"  class="action-icon"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;   
            // if(!empty($post->image)){
                if(file_exists(FCPATH."uploads/".$img[0]) && $post->vImages != null){
            $row[] = '<img src="'.base_url().'uploads/'.$img[0].'" class="img-thumbnail" width="50" height="50" />';
            }else{
                $row[] = '<img src="'.base_url().'uploads/logo/logo.png" class="img-thumbnail" width="50" height="50" />';
            }
            $row[] = $post->vProductName;               
            $row[] = $post->vCategoryName;
            $row[] = $post->vHSNNO;
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_model->count_all_products(),
            "recordsFiltered" => $this->product_model->count_all_products(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }
    
    // public function edit_product(){
    //     $product_id = $_POST['id'];
    //     $product = $this->product_model->get_product_by_id($product_id);
    //     echo json_encode($product);
    // }

    public function edit_product($product_id){
        $data['subcategory'] = $this->product_model->get_product_subcategory();
        $data['model'] = $this->product_model->get_product_model();
        $data['brand'] = $this->product_model->get_product_brand();
        $data['category'] = $this->product_model->get_product_category();
        $data['unit'] = $this->product_model->get_product_unit();
        $data['color'] = $this->product_colour_model->list_color();
        $data['grade'] = $this->product_model->get_product_grade();
        $data['branch'] = $this->product_model->get_branch();
        $data['product'] = $this->product_model->get_product_by_id($product_id);
        $data['product_branch'] = $this->product_model->get_product_branch_by_product($product_id);
        $data['price'] = $this->product_model->get_product_price_by_product($product_id);
        $data['min_qty'] = $this->product_model->get_product_minqty_product($product_id);
        $this->template->write_view('content', 'edit_product', $data);
        $this->template->render();
    }

    public function update_product(){
        $color_check = $this->input->post('color_check');
        foreach($color_check as $color){
            $color_array[]= $color['color_check'];
        }
        $color_check= implode(",",$color_array);
        // print_r($color_check); exit;
        // $color_check = $this->input->post('color_check');
        $product_name = $this->seo_friendly_url($_POST['product_name']);
        $product_id = $_POST['product_id'];
        // echo "<pre>";print_r(($_FILES));exit;
        $config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $product_name.'-'.rand(10000,10000000);
        
        $this->load->library('upload');
        $files = $_FILES;
        $count = count($_FILES['product_image']['name']);
        $file_name = array();
        $product = array(
            'vProductName' => $_POST['product_name'],
            'vProductName_Tamil' => $_POST['product_name_tamil'],
            'vDescription' => $_POST['description'],
            'vDescription_Tamil' => $_POST['description_tamil'],
            'iCategoryId' => $_POST['product_category'],
            'iSubcatagoryId' => $_POST['subcategory'],
            'iBrandId' => $_POST['brand_name'],
            'iProductColorId' => $color_check,
            'iModelId' => $_POST['model_name'],
            'vHSNNO' => $_POST['hsn_no'],
            'IGST' => $_POST['igst'],
            'CGST' => $_POST['cgst'],
            'SGST' => $_POST['sgst'],
            'eStatus' => $_POST['status'],
        );
        // echo"<pre>"; print_r($product); exit;
        if($count >0){
            for($i=0; $i<$count; $i++){           
                $_FILES['product_image']['name']= $files['product_image']['name'][$i];
                $_FILES['product_image']['type']= $files['product_image']['type'][$i];
                $_FILES['product_image']['tmp_name']= $files['product_image']['tmp_name'][$i];
                $_FILES['product_image']['error']= $files['product_image']['error'][$i];
                $_FILES['product_image']['size']= $files['product_image']['size'][$i];    
                
                $this->upload->initialize($config);
                $this->upload->do_upload('product_image');
                $upload_data = $this->upload->data();
                $file_name[] = $upload_data['file_name'];
            }
            $product['vImages']  = $image = implode(",",$file_name);
        }
        $this->product_model->update_product($product,$product_id);
        
        $this->product_model->remove_branch_product($product_id);
        
        $product_branch = $this->input->post('branch_check');
        // echo"<pre>"; print_r($product_branch); exit;
        if(!empty($product_branch)){
            
            $product_branch_array = array();
            for($s=0;$s<count($product_branch);$s++){
                $product_branch_array[] = array(
                    'iProductId'=> $product_id,
                    'iBranchId' => $product_branch[$s],
                    'iCategoryId' => $_POST['product_category'],
                );
            }
            $this->product_model->get_product_branch($product_branch_array);
        }
        // echo"<pre>"; print_r($product_branch); exit;

        $delete_price = $this->input->post('product_unit_deletedid');
        $del_price_id = explode(",",$delete_price);

        foreach($del_price_id as $del_price){
            $this->product_model->remove_price($del_price);
        }

        $minqty_deletedid = $this->input->post('minqty_deletedid');
        $del_minqty_id = explode(",",$minqty_deletedid);

        foreach($del_minqty_id as $del_minqty){
            $this->product_model->remove_minqty($del_minqty);
        }

        $unit = $this->input->post('unit');
        $price = $this->input->post('price');
        $pack = $this->input->post('pack');
        $grade = $this->input->post('grade');
        $price_list_id = $this->input->post('price_list_id');
        // print_r($pack);
        $update_price = array();
        $insert_price = array();
        for($i=0;$i<count($unit);$i++){
            if(!empty($price_list_id[$i])){
                $update_price[] = array(
                    'iProductPriceListId'=>$price_list_id[$i],
                    'iProductId' => $product_id,
                    'iProductUnitId' => $unit[$i],
                    'vPacketCount' => $pack[$i],
                    'iGradeId' => $grade[$i],
                    'fProductPrice' => $price[$i],
                );
            }else{
                $insert_price[] = array(
                    'iProductId' => $product_id,
                    'iProductUnitId' => $unit[$i],
                    'vPacketCount' => $pack[$i],
                    'iGradeId' => $grade[$i],
                    'fProductPrice' => $price[$i],
                );
            }
           
        }

        if(!empty($update_price)){
            $this->product_model->update_price_list($update_price);
        }
        if(!empty($insert_price)){
            $this->product_model->get_product_price($insert_price);
        }

        $branch = $this->input->post('branch');
        $unit_minqty = $this->input->post('unit_minqty');
        $minimum_quantity = $this->input->post('minimum_quantity');
        $min_qty_id = $this->input->post('min_qty_id');

        $update_minqty = array();
        $insert_minqty = array();
        for($j=0;$j<count($branch);$j++){
            if(!empty($min_qty_id[$j])){
                $update_minqty[] = array(
                    'iProductMinQtyId'=>$min_qty_id[$j],
                    'iProductId' => $product_id,
                    'iProductUnitId' => $unit_minqty[$j],
                    'iBranchId' => $branch[$j],
                    'iMinQty' => $minimum_quantity[$j],
                );
            }else{
                $insert_minqty[] = array(
                    'iProductId' => $product_id,
                    'iProductUnitId' => $unit_minqty[$j],
                    'iBranchId' => $branch[$j],
                    'iMinQty' => $minimum_quantity[$j],
                );
            }
        }
        if(!empty($update_minqty)){
            $this->product_model->update_min_qty($update_minqty);
        }
        if(!empty($insert_minqty)){
            $this->product_model->get_min_qty($insert_minqty);
        }

        redirect(base_url('master/product'));
    }

    public function delete_product(){
        $product_id = $_POST['id'];
        $product_arr = array(
            'eStatus' => "Deleted",
        );
        $this->product_model->update_product($product_arr,$product_id);
        redirect(base_url('master/product'));
    }

    public function insert_product(){
        // echo 1; exit;
        $data['title'] = 'Add Product';
        $data['category'] = $this->product_model->get_product_category();
        $data['subcategory'] = $this->product_model->get_product_subcategory();
        $data['model'] = $this->product_model->get_product_model();
        $data['brand'] = $this->product_model->get_product_brand();
        $data['color'] = $this->product_colour_model->list_color();
        $data['unit'] = $this->product_model->get_product_unit();
        $data['grade'] = $this->product_model->get_product_grade();
        $data['branch'] = $this->product_model->get_branch();
        //   echo "<pre>";print_r(($data['color']));exit;
        $this->template->write_view('content', 'add_product', $data);
        $this->template->render();
    }

    function import_products() {
        if ($this->input->post()) {
            $skip_rows = $this->input->post('skip_rows');
            $is_success = 0;
            
            if (!empty($_FILES['product_data'])) {

                $config['upload_path'] = './attachments/csv/';

                $config['allowed_types'] = 'csv';

                $this->load->library('upload', $config);

                $random_hash = substr(str_shuffle(time()), 0, 3) . strrev(mt_rand(100000, 999999));
                
                $extension = pathinfo($_FILES['product_data']['name'], PATHINFO_EXTENSION);

                $new_file_name = 'product_' . $random_hash . '.' . $extension;

                $_FILES['product_data'] = array(
                    'name' => $new_file_name,
                    'type' => $_FILES['product_data']['type'],
                    'tmp_name' => $_FILES['product_data']['tmp_name'],
                    'error' => $_FILES['product_data']['error'],
                    'size' => $_FILES['product_data']['size']
                );

               // $config['file_name'] = $new_file_name;

                $this->upload->initialize($config);
                


                $this->upload->do_upload('product_data');

                $upload_data = $this->upload->data();

                $file_name = $upload_data['file_name'];

                $file = 'attachments/csv/' . $file_name;
                // $file = base_url() . 'attachements/csv/sample_product.xlsx';
                $handle = fopen($file, 'r');

                $lineNum = 1;
                if ($file != NULL && $skip_rows > 0 || $lineNum == 1) {

                    $skipLines = $skip_rows;


                    if ($skipLines > 0 || $lineNum == 1) {

                        while (fgetcsv($handle)) {

                            if ($lineNum == $skipLines || $lineNum == 1) {

                                break;
                            }

                            $lineNum++;
                        }
                    }
                }

                $count = 1;
                if ($file != NULL) {
                    while ($row_data = fgetcsv($handle)) {
                        
                        $product_name = $row_data[0];
                        $category = trim($row_data[1]);
                        $status = 'Active';
                        $subcategory = $row_data[2];
                        $brand = $row_data[3];
                        $model = $row_data[4];
                        $igst = $row_data[5];
                        $cgst = $row_data[6];
                        $sgst = $row_data[7];
                        $hsnno = $row_data[8];
                        $description = $row_data[9];
                        $unit = $row_data[10];
                        $ss_rate = $row_data[11];
                        $d_rate = $row_data[12];
                        $r_rate = $row_data[13];
                        $mrp = $row_data[14];
                        $headoffice = $row_data[15];
                        $branchname = $row_data[16];
                        $min_qty = $row_data[17];
                        // echo $min_qty;exit;
                        //get category
                        $category_details = $this->product_model->get_category_by_name($category);
                        $category_id = ($category_details!="")?$category_details['iCategoryId']:0;
                        if(empty($category_details)){
                            $category_arr = array(
                                'vCategoryName' => $category,
                                'iCreatedBy' => $this->session->userdata('LoggedId'),
                                'dCreatedDate' => date('Y-m-d h:i:s'),
                            );
                            $category_id = $this->category_model->get_category($category_arr);
                        }
                        if(!empty($subcategory)){
                            $subcategory_details = $this->product_model->get_subcategory_by_name($subcategory,$category_id);
                            $subcategory_id = ($subcategory_details!="")?$subcategory_details['iSubcategoryId']:0;
                            if(empty($subcategory_details)){
                                $subcategory_arr = array(
                                    'iCategoryId' => $category_id,
                                    'vSubcategoryName' => $subcategory,
                                    'iCreatedBy' => $this->session->userdata('LoggedId'),
                                    'dCreatedDate' => date('Y-m-d h:i:s'),
                                );
                                $subcategory_id = $this->subcategory_model->get_subcategory($subcategory_arr);
                            }
                        }
                        if(!empty($brand)){
                            $brand_details = $this->product_model->get_brand_by_name($brand);
                            $brand_id = ($brand_details!="")?$brand_details['iBrandId']:0;
                            if(empty($brand_details)){
                                $brand_arr = array(
                                    'vBrandName' => $brand,
                                    'iCreatedBy' => $this->session->userdata('LoggedId'),
                                    'dCreatedDate' => date('Y-m-d h:i:s'),
                                );
                                $brand_id = $this->brand_model->get_brand($brand_arr);
                            }
                        }
                        if(!empty($model)){
                            $model_details = $this->product_model->get_model_by_name($model);
                            $model_id = ($model_details!="")?$model_details['iModelId']:0;
                            if(empty($model_details)){
                                $model_arr = array(
                                    'vModelName' => $model,
                                    'vModelName_Tamil' => null,
                                    'iCreatedBy' => $this->session->userdata('LoggedId'),
                                    'dCreatedDate' => date('Y-m-d h:i:s'),
                                );
                                $model_id = $this->model_model->get_model($model_arr);
                            }
                        }

                        //get head office details
                        if(!empty($headoffice)){
                            $headoffice_details = $this->product_model->get_head_office_by_name($headoffice);
                            $headoffice_id = ($headoffice_details!="")?$headoffice_details['iHeadOfficeId']:0;
                            // print_r($headoffice_details);exit;
                            if(empty($headoffice_details)){
                                $head_office_arr = array(
                                    'vHeadOfficeName' => $headoffice,
                                    'vHeadOfficeName_Tamil' => null,
                                    'iCreatedBy' => $this->session->userdata('LoggedId'),
                                    'dCreatedDate' => date('Y-m-d h:i:s'),
                                );
                                $headoffice_id = $this->headoffice_model->get_headoffice($head_office_arr);
                            }
                        }
                        if(!empty($branchname)){

                            //get branch details
                            $branch_details = $this->product_model->get_branch_by_name($branchname,$headoffice_id);
                            $branch_id = ($branch_details!="")?$branch_details['iBranchId']:0;
                            // print_r($branch_details);exit;
                            if(empty($branch_details)){
                                $branch_arr = array(
                                    'vBranchName' => $branchname,
                                    'iHeadOfficeId' => $headoffice_id,
                                    'vBranchName_Tamil' => null,
                                    'iCreatedBy' => $this->session->userdata('LoggedId'),
                                    'dCreatedDate' => date('Y-m-d h:i:s'),
                                );
                                $branch_id = $this->branch_model->get_branch($branch_arr);
                            }
                        }                        

                        if(!empty($unit)){

                            //get unit details
                            $unit_details = $this->product_model->get_unit_by_name($unit);
                            $unit_id = ($unit_details!="")?$unit_details['iProductUnitId']:0;
                            if(empty($unit_details)){
                                $unit_arr = array(
                                    'vProductUnitName' => $unit,
                                    'vProductUnitName_Tamil' => null,
                                );
                                $unit_id = $this->product_unit_model->get_product_unit($unit_arr);
                            }
                        }

                        $product_id = $this->product_model->is_product_exist($product_name, $category_id,$subcategory_id,$brand_id,$model_id);
                        // print_r($product_id);exit;
                        $product_data = array();
                        $product_data['vProductName'] = $product_name;
                        $product_data['vProductName_Tamil'] = null;
                        $product_data['iCategoryId'] = $category_id;
                        $product_data['iBrandId'] = $brand_id;
                        $product_data['iSubcatagoryId'] = $subcategory_id;
                        $product_data['iModelId'] = $model_id;
                        $product_data['IGST'] = $igst;
                        $product_data['SGST'] = $sgst;
                        $product_data['CGST'] = $cgst;
                        $product_data['vHSNNO'] = $hsnno;
                        $product_data['vDescription'] = $description;
                        $product_data['vDescription_Tamil'] = null;
                        // $product_data['cash_con_price'] = $cash_con_price;
                        // $product_data['credit_con_price'] = $credit_con_price;
                        // $product_data['vip_price'] = $vip_price;
                        // $product_data['vvip_price'] = $vvip_price;
                        // $product_data['h1_price'] = $h1_price;
                        // $product_data['h2_price'] = $h2_price;
                        // $product_data['unit'] = $unit;
                        if(empty($product_id)){
                            $product_data['dCreatedDate'] = date('Y-m-d h:i:s');
                            $product_id = $this->product_model->get_products($product_data);
                        }else {
                            $product_data['dCreatedDate'] = date('Y-m-d h:i:s');
                            $this->product_model->update_product($product_data, $product_id);

                            // $this->stock_details($product_data, $pro_id);
                        }

                        //inser product under branch
                        if(!empty($branch_id)){
                            // echo 1;exit;
                            $product_branch_array = array(
                                'iProductId'=> $product_id,
                                'iBranchId' => $branch_id,
                                'iCategoryId' => $category_id,
                            );
                            $this->product_model->insert_product_branch($product_branch_array);
                        }

                        if(!empty($mrp)){

                                //get mrp rate unit details
                                $mrp_grade = 'MRP';
                            $mrp_grade_details = $this->product_model->get_grade_by_name($mrp_grade);
                            $mrp_gradeid = ($mrp_grade_details!="")?$mrp_grade_details['iGradeId']:0;
                            if(empty($mrp_grade_details)){
                                $mrp_rate_arr = array(
                                    'vGradeName' => $mrp_grade,
                                );
                                $mrp_gradeid = $this->grade_model->get_grade($mrp_rate_arr);
                            }
                            $mrp_grade_price_id = $this->product_model->check_exist_price_list($product_id,$unit_id,$mrp_gradeid);
                            $price_list_mrp = array(
                                'iProductId' => $product_id,
                                'iProductUnitId' => $unit_id,
                                'iGradeId' => $mrp_gradeid,
                                'vPacketCount' => 1,
                                'fProductPrice' => $mrp
                            );
                            if(empty($mrp_grade_price_id)){
                                $this->product_price_model->get_product_price($price_list_mrp);
                            }else{
                                $this->product_price_model->update_product_price($mrp_grade_price_id,$price_list_mrp);
                            }
                        }


                        if(!empty($ss_rate)){

                                //get ss rate unit details
                                $ss_grade = 'SS Rate';
                                $ss_grade_details = $this->product_model->get_grade_by_name($ss_grade);
                                $ss_gradeid = ($ss_grade_details!="")?$ss_grade_details['iGradeId']:0;
                                if(empty($ss_grade_details)){
                                $ss_rate_arr = array(
                                    'vGradeName' => $ss_grade,
                                );
                                $ss_gradeid = $this->grade_model->get_grade($ss_rate_arr);
                            }
                            $ss_grade_price_id = $this->product_model->check_exist_price_list($product_id,$unit_id,$ss_gradeid);
                            $price_list1 = array(
                                'iProductId' => $product_id,
                                'iProductUnitId' => $unit_id,
                                'iGradeId' => $ss_gradeid,
                                'vPacketCount' => 1,
                                'fProductPrice' => $ss_rate
                            );
                            if(empty($ss_grade_price_id)){
                                $this->product_price_model->get_product_price($price_list1);
                            }else{
                                $this->product_price_model->update_product_price($ss_grade_price_id,$price_list1);
                            }
                        }
                        
                        if(!empty($d_rate)){

                            // get d rate unit details 
                            $d_grade = 'D Rate';
                            $d_grade_details = $this->product_model->get_grade_by_name($d_grade);
                            $d_gradeid = ($d_grade_details!="")?$d_grade_details['iGradeId']:0;
                            if(empty($d_grade_details)){
                                $d_rate_arr = array(
                                    'vGradeName' => $d_grade,
                                );
                                $d_gradeid = $this->grade_model->get_grade($d_rate_arr);
                            }
                            $d_grade_price_id = $this->product_model->check_exist_price_list($product_id,$unit_id,$d_gradeid);
                            $price_list2 = array(
                                'iProductId' => $product_id,
                                'iProductUnitId' => $unit_id,
                                'iGradeId' => $d_gradeid,
                                'vPacketCount' => 1,
                                'fProductPrice' => $d_rate
                            );
                            if(empty($d_grade_price_id)){
                                $this->product_price_model->get_product_price($price_list2);
                            }else{
                                $this->product_price_model->update_product_price($d_grade_price_id,$price_list2);
                            }
                        }

                        if(!empty($r_rate)){

                                // get r rate unit details 
                                $r_grade = 'R Rate';
                                $r_grade_details = $this->product_model->get_grade_by_name($r_grade);
                                $r_gradeid = ($r_grade_details!="")?$r_grade_details['iGradeId']:0;
                                if(empty($r_grade_details)){
                                    $r_rate_arr = array(
                                        'vGradeName' => $r_grade,
                                    );
                                    $r_gradeid = $this->grade_model->get_grade($r_rate_arr);
                                }
                            $r_grade_price_id = $this->product_model->check_exist_price_list($product_id,$unit_id,$r_gradeid);
                            
                            
                            $price_list3 = array(
                                'iProductId' => $product_id,
                                'iProductUnitId' => $unit_id,
                                'iGradeId' => $r_gradeid,
                                'vPacketCount' => 1,
                                'fProductPrice' => $r_rate
                            );
                            if(empty($r_grade_price_id)){
                                $this->product_price_model->get_product_price($price_list3);
                            }else{
                                $this->product_price_model->update_product_price($r_grade_price_id,$price_list3);
                            }
                        }

                        if($min_qty != ""){
                            $min_qty_id = $this->product_model->check_min_qty_exist($branch_id,$product_id,$unit_id);
                            $min_qty_array = array(
                                'iBranchId' => $branch_id,
                                'iProductId' => $product_id,
                                'iProductUnitId' => $unit_id,
                                'iMinQty' => $min_qty,
                            );
                            if(empty($min_qty_id)){
                                $this->minimum_quantity_model->get_minimum_quantity($min_qty_array);
                            }else{
                                $this->minimum_quantity_model->update_minimum_quantity($min_qty_id,$min_qty_array);
                            }
                        }
                           
                    }
                }
            }
            redirect($this->config->item('base_url') . 'master/product');
        }
    }

    public function get_category_gst_values(){
        $category_id = $this->input->post('cat_id');
        $gst = $this->product_model->get_category_gst($category_id);
        echo json_encode($gst);
    }

    public function subcategory_by_category()
    {
        $category_id = $this->input->post('category');
        $result = $this->product_model->subcategory_by_category($category_id);
        echo json_encode($result);
        exit;
    }
    //Removed spcl Characters 
    function seo_friendly_url($string)
    {
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
        return strtolower(trim($string, '-'));
    }
}
