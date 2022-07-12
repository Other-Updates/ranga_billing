<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Api_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('Asia/Kolkata');
    }
    public function check_salesman_login($user,$pass){
        $this->db->select('*');
        $this->db->where('iPhoneNumber',$user);
        $this->db->where('vPassword',$pass);
        $this->db->where('iUserRoleId',3);
        $this->db->or_where('iUserRoleId',5);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }
    }
    public function get_products(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_products');
        $query = $this->db->get();
        if($query->num_rows()){
            return $query->result_array();
        }else
        return false;
    }
    public function get_salesman(){
        $this->db->select('*');
        $this->db->where('iUserRoleId',3);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows()){
            return $query->result_array();
        }else
        return false;
    }
    public function update_user($id,$data){
        $this->db->where('iUserId',$id);
        $this->db->update('cic_master_users',$data);
    }
    public function update_login_time($data,$id=null){
        if($id != null){
            $update = array();
			$update['dLogoutTime'] = date('Y-m-d h:i:s');
            $this->update_logout_time($id,$update);
        }
        $this->db->insert('cic_salesman_attendance_track',$data);
        $id = $this->db->insert_id();
        return $id;
    }
    public function update_logout_time($id,$data){
        $this->db->where('dLoginTIme  IS NOT NULL', null, false);
        $this->db->where('dLogoutTime  IS NULL', null, false);
        $this->db->where('iUserId',$id);
        $this->db->update('cic_salesman_attendance_track',$data);
    }
    public function get_salesman_by_id($id,$lang){
        if($lang == 'English')
        $this->db->select('user.iUserId as iUserId,user.iOtpCode,user.iBranchId,user.iUserRoleId,user.vEmail,user.iPhoneNumber as iPhoneNumber,user.vAddress,user.vName as vName,user.eStatus,ur.vUserRole');
        if($lang == 'Tamil')
        $this->db->select('user.iUserId as iUserId,user.iOtpCode,user.iBranchId,user.iUserRoleId,user.vEmail,user.iPhoneNumber as iPhoneNumber,user.vAddress_Tamil as vAddress,user.vName_Tamil as vName,user.eStatus,ur.vUserRole');
        $this->db->select('"600000" As trackseconds',false);
        $this->db->where('iUserId',$id);
        $this->db->join('cic_master_user_role as ur','user.iUserRoleId=ur.iUserRoleId','left');
        $this->db->from('cic_master_users as user');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
    public function get_customer_by_id($id){
        $this->db->select('user.*,ur.vUserRole');
        $this->db->where('user.iCustomerId',$id);
        $this->db->join('cic_master_user_role as ur','user.iUserRoleId=ur.iUserRoleId','left');
        $this->db->from('cic_customer as user');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
    // Get branch headoffice
    public function get_branch_headoffice($branch){
        $this->db->select('iHeadOfficeId');
        $this->db->where('iBranchId',$branch);
        $this->db->from('cic_master_branch');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
      // Get headoffice state
      public function get_headoffice_state($headoffice){
        $this->db->select('iStateId');
        $this->db->where('iHeadOfficeId',$headoffice);
        $this->db->from('cic_master_headoffice');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
    public function get_sales($data){
        $this->db->insert_batch('cic_sales_order',$data);
        return $this->db->insert_id();
    }
    public function sales_order_by_id($id){
        $this->db->select('us.vName,pd.vProductName,ds.vName,so.*');
        $this->db->where('iSalesOrderDetailsId',$id);
        $this->db->join('cic_products as pd','pd.iProductId=so.iProductId','left');
        $this->db->join('cic_master_users as us','us.iUserId=so.iUserId','left');
        $this->db->join('cic_customer as ds','ds.iCustomerId=so.iCustomerId','left');
        $this->db->from('cic_sales_order as so');
        $query =  $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }
    }
    public function update_stock($product_id,$branchid,$unit_id,$qty,$status)
    {
        $this->db->where('iBranchId',$branchid);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit_id);
        if($status=="delete")
        $this->db->set('dProductQty','dProductQty - ' . (int) $qty, FALSE);
        else
        $this->db->set('dProductQty','dProductQty + ' . (int) $qty, FALSE);
        $this->db->update('cic_stock');
    }
    public function get_salesman_location($data){
        $this->db->insert('cic_salesman_location',$data);
    }
    public function generateNumericOTP($n) {  
        $generator = "135792468"; 
        $result = ""; 
        for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        }  
        return $result; 
    }
    public function get_custom_salesman(){
        $this->db->select('*');
        $this->db->where('iPhoneNumber',$data);
        $this->db->or_where('vEmail',$data);
        $query = $this->db->get('cic_master_users');
        if($query->num_rows() >0){
            $query->row_array();
        }else
        return false;
    }
    public function get_category(){
        $base_url = base_url().'uploads/';
        $this->db->select("iCategoryId,vCategoryName,CONCAT('".$base_url."',vImage) AS image,eStatus,dCreatedDate", FALSE);
        $this->db->from('cic_master_category');
        $this->db->where('eStatus','Active');
        return $this->db->get()->result_array();
    }
    public function add_product($data){
        $this->db->insert('cic_products',$data);
        return $this->db->insert_id();
    }
    public function product_by_id($id){
        $this->db->select('pd.*,pc.vCategoryName');
        $this->db->join('cic_master_category as pc','pc.iCategoryId=pd.iCategoryId','left');
        $this->db->where('pd.iProductId',$id);
        $this->db->from('cic_products as pd');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else false;
    }
    public function add_distributor($data){
        $this->db->insert('cic_customer',$data);
        return $this->db->insert_id();
    }
    public function distributor_by_id($id){
        $this->db->select('a.iCustomerId as iUserId,a.vPhoneNumber as iPhoneNumber,a.vCompanyName,a.vCompanyName,a.iBranchId,a.iUserRoleId,a.vCustomerName as vName,a.vEmail,a.vAddress,a.iOtpCode,b.vUserRole');
        $this->db->where('iCustomerId',$id);
        $this->db->join('cic_master_user_role as b','b.iUserRoleId=a.iUserRoleId','left');
        $this->db->from('cic_customer as a');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
    public function distributor_by_id_login($id,$lang){
        if($lang=='English')
        $this->db->select('a.iCustomerId as iUserId,a.vPhoneNumber as iPhoneNumber,a.vCompanyName,a.vCustomerName as vName,a.vAddress,a.*,b.vUserRole');
        if($lang=='Tamil')
        $this->db->select('a.iCustomerId as iUserId,a.vPhoneNumber as iPhoneNumber,a.vCompanyName_Tamil as vCompanyName,a.vCustomerName_Tamil as vName,vAddress_Tamil as vAddress,a.*,b.vUserRole');
        $this->db->select('"600000" As trackseconds',false);
        $this->db->where('iCustomerId',$id);
        $this->db->join('cic_master_user_role as b','b.iUserRoleId=a.iUserRoleId','left');
        $this->db->from('cic_customer as a');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
    // Distributor data profile
    public function distributor_by_id_login_profile($id,$lang=null){
        if($lang == 'English')
        $this->db->select('a.*,a.iCustomerId as iUserId,a.vPhoneNumber as iPhoneNumber,a.vCompanyName,a.vCustomerName as vName,b.vUserRole');
        if($lang == 'Tamil')
        $this->db->select('a.*,a.iCustomerId as iUserId,a.vPhoneNumber as iPhoneNumber,a.vCompanyName_Tamil as vCompanyName,a.vCustomerName_Tamil as vName,b.vUserRole');
        $this->db->where('iCustomerId',$id);
        $this->db->join('cic_master_user_role as b','b.iUserRoleId=a.iUserRoleId','left');
        $this->db->from('cic_customer as a');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }
    public function update_distributor($id,$data){
        $this->db->where('iCustomerId',$id);
        $this->db->update('cic_customer',$data);
    }
    // public function update_product($id,$data){
    //     $this->db->where('iProductId',$id);
    //     $this->db->update('cic_products',$data);
    // }
    public function update_sales_order($id,$data){
        $this->db->where('iSalesOrderDetailsId',$id);
        $this->db->update('sales_order_details',$data);
    }
    public function get_profile_by_id($id,$lang){
        if($lang == 'English')
        $this->db->select('iUserId,iUserRoleId,iBranchId,vName,iPhoneNumber,vAddress,vEmail');
        if($lang == 'Tamil')
        $this->db->select('iUserId,iUserRoleId,iBranchId,vName_Tamil as vName,iPhoneNumber,vAddress_Tamil as vAddress,vEmail');
        $this->db->where('iUserId',$id);
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }
    }
    public function update_profile($id,$data){
        $this->db->where('iUserId',$id);
        $this->db->update('cic_master_users',$data);
    }
    public function get_distributors($lang){
        if($lang == 'English'){
            $this->db->select('c.iCustomerId,u.vUserRole as vCustomerType,c.iGradeId,c.iSalesmanId,c.vCompanyName,c.vCustomerName,c.vPhoneNumber,c.vAddress,c.vEmail,c.eStatus,c.dCreatedDate');
        }
        if($lang == 'Tamil'){
            $this->db->select('c.iCustomerId,u.vUserRole_Tamil as vCustomerType,c.iGradeId,c.iSalesmanId,c.vCompanyName_Tamil as vCompanyName,c.vCustomerName_Tamil as vCustomerName,c.vPhoneNumber,c.vAddress_Tamil as vAddress,c.vEmail,c.eStatus,c.dCreatedDate');
        }
        $this->db->join('cic_master_user_role as u','u.iUserRoleId=c.iUserRoleId','left');
        $this->db->where('c.eStatus','Active');
        $this->db->order_by('c.iCustomerId','desc');
        $this->db->from('cic_customer as c');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }
    // public function get_stock($branch_id){
    //     // $base_url = base_url().'uploads/';
    //     // // $this->db->select('st.iProductId,GROUP_CONCAT(st.iProductUnitId ORDER BY st.iProductUnitId) As iProductUnitId,GROUP_CONCAT(st.dProductQty ORDER BY st.iProductUnitId) As dProductQty,st.iHeadOfficeId,st.iBranchId,br.vBranchName,st.iCategoryId,ct.vCategoryName,pr.vProductName');
    //     // $this->db->select("st.iProductId,GROUP_CONCAT(st.iProductUnitId ORDER BY st.iProductUnitId) As iProductUnitId,st.iHeadOfficeId,st.iBranchId,br.vBranchName,st.iCategoryId,ct.vCategoryName,pr.vProductName,CONCAT('".$base_url."',vImages) AS product_image");
    //     // $this->db->join('cic_master_branch as br','br.iBranchId=st.iBranchId','left');
    //     // $this->db->join('cic_master_category as ct','ct.iCategoryId=st.iCategoryId','left');
    //     // $this->db->join('cic_products as pr','pr.iProductId=st.iProductId','left');
    //     // $this->db->group_by('st.iProductId');
    //     // $this->db->where('st.iBranchId',$branch_id);
    //     // $this->db->from('cic_stock as st');
    //     // // $this->db->group_by('st.iCategoryId');
    //     // $query = $this->db->get();
    //     // $data =  $query->result_array();
    //     $base_url = base_url().'uploads/';
    //     if($lang == "Tamil"){
    //         $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName_Tamil as vBranchName,p.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,p.vProductName_Tamil as vProductName,CONCAT('".$base_url."',vImages) AS product_image");
    //     }
    //     if($lang == "English"){
    //         $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName,p.iCategoryId,ct.vCategoryName,p.vProductName,CONCAT('".$base_url."',vImages) AS product_image");
    //     } 
    //     $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
    //     $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
    //     $this->db->join('cic_stock as s','p.iProductId = s.iProductId','left');
    //     $this->db->join('cic_master_category as ct','p.iCategoryId=ct.iCategoryId','left');
    //     // $this->db->where('p.iCategoryId',$category_id);
    //     $this->db->where('b.iBranchId',$branch_id);
    //     $this->db->where('p.eStatus','Active');
    //     $this->db->group_by('p.iProductId');
    //     $data = $this->db->get('cic_products as p')->result_array();
    //     if(!empty($data)){
    //         foreach($data as $key=>$catagory_data){
    //             $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
    //             $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.iProductPriceListId,pl.fProductPrice,st.dProductQty');
    //             $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
    //             $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
    //             $this->db->where('pl.iProductId',$catagory_data['iProductId']);
    //             $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
    //             $this->db->group_by('pl.iProductPriceListId');
    //             $this->db->order_by('pl.iProductUnitId');
    //             $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
    //             $data[$key]['unit_details'] = $unit_details;
    //         }
    //     }
    //     return $data;
    // }
    public function get_stock($branch_id,$lang,$gradeid){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName_Tamil as vBranchName,p.iBrandId,brand.vBrandName_Tamil,p.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,p.vProductName_Tamil as vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription_Tamil as vDescription");
        }
        if($lang == "English"){
            $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName,p.iBrandId,brand.vBrandName,p.iCategoryId,ct.vCategoryName,p.vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription,p.iProductColorId");
        } 
        $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
        $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
        $this->db->join('cic_stock as s','p.iProductId = s.iProductId','left');
        $this->db->join('cic_master_category as ct','p.iCategoryId=ct.iCategoryId','left');
        $this->db->join('cic_product_min_quantity as q','p.iProductId=q.iProductId','left');
        $this->db->join('cic_product_unit as unit','q.iProductUnitId=unit.iProductUnitId','left');
        $this->db->join('cic_master_brand as brand','p.iBrandId=brand.iBrandId','left');
        // $this->db->join('cic_product_color as cl','p.iProductColorId=cl.iProductColorId','left');
        // $this->db->where('p.iCategoryId',$category_id);
        $this->db->where('b.iBranchId',$branch_id);
        $this->db->where('p.eStatus','Active');
        $this->db->group_by('p.iProductId');
        $data = $this->db->get('cic_products as p')->result_array();
        if(!empty($data)){
            foreach($data as $key=>$catagory_data){
                $color = explode(',',$catagory_data['iProductColorId']);
                $this->db->select('iProductColorId,vColorName');
                $this->db->where_in('iProductColorId',$color);
                $this->db->from('cic_product_color');
                $color_arr = $this->db->get()->result_array();
                $color_details_status = !empty($color_arr) ? "Color is available" : "No color avaialble";
                $data[$key]['color_status'] = $color_details_status;
                $data[$key]['color_details'] = $color_arr;
                
                $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
                
                // $data[$key]['product_images']=array();
                $product_images=explode(',',$catagory_data['product_img']);
                // print_r($product_images);
                $data[$key]['product_images'] = $product_images = (count($product_images)>0)?$product_images:array();
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice');
                $this->db->select('(SELECT min.iMinQty FROM cic_product_min_quantity as min WHERE min.iProductUnitId IN ("'.implode(",",$iProductUnitId).'") AND min.iBranchId = "'.$branch_id.'" AND min.iProductId = "'.$catagory_data['iProductId'].'") as iMinQty');
                $this->db->join('cic_product_unit as un','pl.iProductUnitId=un.iProductUnitId',"left");
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where('pl.iGradeId',$gradeid);
                $price_list= $this->db->get('cic_product_price_list as pl')->result_array();
                if(!empty($price_list)){
                    $product_inc = 0;
                    foreach($price_list as $price_key=>$price){
                        $price_list[$price_key]['product_image'] = "";
                        if(!empty($product_images)){
                            $product_inc = ($product_images[$product_inc]) ? $product_inc : 0;
                            if($product_images[$product_inc]){
                                $price_list[$price_key]['product_image'] = $base_url.$product_images[$product_inc];
                                if($product_inc <= $price_key)
                                    $product_inc++;
                            }
                        }
                    }
                }
                $data[$key]['price'] = $price_list;
                // echo "<pre>";print_r($data[$key]);exit;
                
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice,st.dProductQty');
                $this->db->select('(SELECT min.iMinQty FROM cic_product_min_quantity as min WHERE min.iProductUnitId IN ("'.implode(",",$iProductUnitId).'") AND min.iBranchId = "'.$branch_id.'" AND min.iProductId = "'.$catagory_data['iProductId'].'") as iMinQty');
                $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
                $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
                $this->db->where('pl.iGradeId',$gradeid);
                $this->db->group_by('pl.iProductPriceListId');
                $this->db->order_by('pl.iProductUnitId');
                $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
                $unit_details_status = !empty($unit_details) ? "Stock is available" : "Out of stock";
                $data[$key]['unit_details_status'] = $unit_details_status;
                // $data[$key]['unit_details'] = array("iProductUnitId" => 0,"vProductUnitName"=> "<--Select-->","fProductPrice"=>0,"dProductQty"=>0);
                $data[$key]['unit_details'] = $unit_details;
                // $unit_result = array();
                // $unit_result[] = array("iProductUnitId" => 0,"vProductUnitName"=> "<--Select-->","fProductPrice"=>0,"dProductQty"=>0,"iMinQty"=>0);
                // if(!empty($unit_details)){
                //     $unit_result = array_values(array_merge($unit_result,$unit_details));
                // }
                // $data[$key]['unit_details'] = $unit_result;
            }
        }
        
        return $data;
    }
    public function stock_by_categoryid($category_id,$branch_id,$lang,$gradeid){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName_Tamil as vBranchName,p.iBrandId,brand.vBrandName_Tamil as vBrandName,p.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,p.vProductName_Tamil as vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription_Tamil as vDescription");
        }
        if($lang == "English"){
            $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName,p.iBrandId,brand.vBrandName,p.iCategoryId,ct.vCategoryName,p.vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription,p.iProductColorId");
        } 
        $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
        $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
        $this->db->join('cic_stock as s','p.iProductId = s.iProductId','left');
        $this->db->join('cic_master_category as ct','p.iCategoryId=ct.iCategoryId','left');
        $this->db->join('cic_product_min_quantity as q','p.iProductId=q.iProductId','left');
        $this->db->join('cic_product_unit as unit','q.iProductUnitId=unit.iProductUnitId','left');
        $this->db->join('cic_master_brand as brand','p.iBrandId=brand.iBrandId','left');
        // $this->db->join('cic_product_color as cl','p.iProductColorId=cl.iProductColorId','left');
        $this->db->where('p.iCategoryId',$category_id);
        $this->db->where('b.iBranchId',$branch_id);
        $this->db->where('p.eStatus','Active');
        $this->db->group_by('p.iProductId');
        $data = $this->db->get('cic_products as p')->result_array();
        if(!empty($data)){
            foreach($data as $key=>$catagory_data){
                $color = explode(',',$catagory_data['iProductColorId']);
                $this->db->select('iProductColorId,vColorName');
                $this->db->where_in('iProductColorId',$color);
                $this->db->from('cic_product_color');
                $color_arr = $this->db->get()->result_array();
                $color_details_status = !empty($color_arr) ? "Color is available" : "No color avaialble";
                $data[$key]['color_status'] = $color_details_status;
                $data[$key]['color_details'] = $color_arr;
                
                $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
                $product_images=explode(',',$catagory_data['product_img']);
                $data[$key]['product_images'] = $product_images = (count($product_images)>0)?$product_images:array();
                
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice');
                $this->db->select('(SELECT min.iMinQty FROM cic_product_min_quantity as min WHERE min.iProductUnitId IN ("'.implode(",",$iProductUnitId).'") AND min.iBranchId = "'.$branch_id.'" AND min.iProductId = "'.$catagory_data['iProductId'].'") as iMinQty');
                $this->db->join('cic_product_unit as un',"pl.iProductUnitId=un.iProductUnitId AND pl.iGradeId=$gradeid","left");
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where('pl.iGradeId',$gradeid);
                $price_list= $this->db->get('cic_product_price_list as pl')->result_array();
                if(!empty($price_list)){
                    $product_inc = 0;
                    foreach($price_list as $price_key=>$price){
                        $price_list[$price_key]['product_image'] = "";
                        if(!empty($product_images)){
                            $product_inc = ($product_images[$product_inc]) ? $product_inc : 0;
                            if($product_images[$product_inc]){
                                $price_list[$price_key]['product_image'] = $base_url.$product_images[$product_inc];
                                if($product_inc <= $price_key)
                                    $product_inc++;
                            }
                        }
                    }
                }
                $data[$key]['price'] = $price_list;
                $data[$key]['product_images']=array();
                $product_images=explode(',',$catagory_data['product_img']);
                if(count($product_images)>0)
                {
                    foreach($product_images as $product_image)
                    {
                        $data[$key]['product_images'][] =  array("image"=>$base_url.$product_image);
                    }
                }
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice,st.dProductQty');
                $this->db->select('(SELECT min.iMinQty FROM cic_product_min_quantity as min WHERE min.iProductUnitId IN ("'.implode(",",$iProductUnitId).'") AND min.iBranchId = "'.$branch_id.'" AND min.iProductId = "'.$catagory_data['iProductId'].'") as iMinQty');
                $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
                $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
                $this->db->where('pl.iGradeId',$gradeid);
                $this->db->group_by('pl.iProductPriceListId');
                $this->db->order_by('pl.iProductUnitId');
                $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
                $unit_details_status = !empty($unit_details) ? "Stock is available" : "Out of stock";
                $data[$key]['unit_details_status'] = $unit_details_status;
                // $data[$key]['unit_details'] = array("iProductUnitId" => 0,"vProductUnitName"=> "<--Select-->","fProductPrice"=>0,"dProductQty"=>0);
                $data[$key]['unit_details'] = $unit_details;
                // $unit_result = array();
                // $unit_result[] = array("iProductUnitId" => 0,"vProductUnitName"=> "<--Select-->","fProductPrice"=>0,"dProductQty"=>0,"iMinQty"=>0);
                // if(!empty($unit_details)){
                //     $unit_result = array_values(array_merge($unit_result,$unit_details));
                // }
                // $data[$key]['unit_details'] = $unit_result;
            }
        }
        
        return $data;
    }
    // public function stock_by_employee_categoryid($category_id,$branch_id,$lang){
    //     $base_url = base_url().'uploads/';
    //     if($lang == "Tamil"){
    //         $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName_Tamil as vBranchName,p.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,p.vProductName_Tamil as vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription_Tamil as vDescription");
    //     }
    //     if($lang == "English"){
    //         $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName,p.iCategoryId,ct.vCategoryName,p.vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription");
    //     } 
    //     $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
    //     $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
    //     $this->db->join('cic_stock as s','p.iProductId = s.iProductId','left');
    //     $this->db->join('cic_master_category as ct','p.iCategoryId=ct.iCategoryId','left');
    //     $this->db->join('cic_product_min_quantity as q','p.iProductId=q.iProductId','left');
    //     $this->db->join('cic_product_unit as unit','q.iProductUnitId=unit.iProductUnitId','left');
    //     $this->db->where('p.iCategoryId',$category_id);
    //     $this->db->where('b.iBranchId',$branch_id);
    //     $this->db->where('p.eStatus','Active');
    //     $this->db->group_by('p.iProductId');
    //     $data = $this->db->get('cic_products as p')->result_array();
    //     if(!empty($data)){
    //         foreach($data as $key=>$catagory_data){
    //             $data[$key]['product_images']=array();
    //             $product_images=explode(',',$catagory_data['product_img']);
    //             if(count($product_images)>0)
    //             {
    //                 foreach($product_images as $product_image)
    //                 {
    //                     $data[$key]['product_images'][] =  array("image"=>$base_url.$product_image);
    //                 }
    //             }
    //             $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
    //             $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice,st.dProductQty');
    //             $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
    //             $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
    //             $this->db->where('pl.iProductId',$catagory_data['iProductId']);
    //             $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
    //             // $this->db->where('pl.iGradeId',$gradeid);
    //             $this->db->group_by('pl.iProductPriceListId');
    //             $this->db->order_by('pl.iProductUnitId');
    //             $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
    //             $unit_details_status = !empty($unit_details) ? "Stock is available" : "Out of stock";
    //             $data[$key]['unit_details_status'] = $unit_details_status;
    //             $data[$key]['unit_details'] = $unit_details;
    //         }
    //     }
        
    //     return $data;
    // }
    public function get_cities(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_regions');
        return $this->db->get()->result_array();
    }
    public function get_head_offices(){
        $this->db->select('ho.*,st.vStateName,rg.vRegionName');
        $this->db->join('cic_master_state as st','st.iStateId=ho.iStateId','left');
        $this->db->join('cic_master_regions as rg','rg.iRegionId=ho.iRegionId','left');
        $this->db->where('ho.eStatus','Active');
        $this->db->from('cic_master_headoffice as ho');
        return $this->db->get()->result_array();
    }
    public function get_branches(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }
    public function get_user_roles(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_user_role');
        return $this->db->get()->result_array();
    }
   
    public function get_today_sales($id,$lang){
        if($lang == 'English'){
            $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.iHeadOfficeId,so.iBranchId,so.iSalesmanId,so.iCustomerId,so.fNetQty,so.fNetCostwithoutGST,so.fNetCost,so.eStatus,so.eDeliveryStatus,ho.vHeadOfficeName,br.vBranchName,cus.vCustomerName,u.vUserRole as vCustomerType,so.dCreatedDate');
        }
        if($lang == 'Tamil'){
            $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.iHeadOfficeId,so.iBranchId,so.iSalesmanId,so.iCustomerId,so.fNetQty,so.fNetCostwithoutGST,so.fNetCost,so.eStatus,so.eDeliveryStatus_Tamil as eDeliveryStatus,ho.vHeadOfficeName_Tamil as vHeadOfficeName,br.vBranchName_Tamil as vBranchName,cus.vCustomerName_Tamil as vCustomerName,u.vUserRole_Tamil as vCustomerType,so.dCreatedDate');
        }
        $this->db->join('cic_master_headoffice as ho','ho.iHeadOfficeId=so.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as br','br.iBranchId=so.iBranchId','left');
        // $this->db->join('cic_master_users as us','us.iUserId=so.iSalesmanId','left');
        $this->db->join('cic_master_category as ct','ct.iCategoryId=so.iCatagoryId','left');
        $this->db->join('cic_products as pd','pd.iProductId=so.iProductId','left');
        // $this->db->join('cic_product_unit as pu','pu.iProductUnitId=so.iProductUnitId','left');
        $this->db->join('cic_customer as cus','cus.iCustomerId=so.iCustomerId','left');
        $this->db->join('cic_master_user_role as u','u.iUserRoleId=cus.iUserRoleId','left');
        $this->db->where('so.iSalesmanId',$id);
        $this->db->where('so.dCreatedDate',date('Y-m-d'));
        $this->db->order_by('so.iSalesOrderId','desc');
        $query =  $this->db->get('cic_sales_order as so');
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }
    public function get_sales_by_status($id,$status,$lang,$type){
        if($lang == 'Tamil'){
            $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.iHeadOfficeId,so.iBranchId,so.iSalesmanId,so.iCustomerId,so.fNetQty,so.fNetCostwithoutGST,so.fNetCost,so.eStatus,so.eDeliveryStatus,cus.vCustomerName_Tamil as vCustomerName,u.vUserRole_Tamil as vCustomerType,so.dCreatedDate,so.vPayemntMethod');
        }
        if($lang == 'English'){
            $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.iHeadOfficeId,so.iBranchId,so.iSalesmanId,so.iCustomerId,so.fNetQty,so.fNetCostwithoutGST,so.fNetCost,so.eStatus,so.eDeliveryStatus,cus.vCustomerName,u.vUserRole as vCustomerType,so.dCreatedDate,so.vPayemntMethod');
        }
        if(!empty($status))
        $this->db->where('so.eDeliveryStatus',$status);
        if($type=='salesman')
        $this->db->where('so.iSalesmanId',$id);
        else
        $this->db->where('so.iCustomerId',$id);
        $this->db->join('cic_customer as cus','cus.iCustomerId=so.iCustomerId','left');
        $this->db->join('cic_master_user_role as u','u.iUserRoleId=cus.iUserRoleId','left');
        $this->db->order_by('so.iSalesOrderId','desc');
        $this->db->from('cic_sales_order as so');
        return $this->db->get()->result_array();
    }
    public function update_stock_quantity($headoffice,$branch,$cat_id,$product,$unit,$qty){
        $this->db->where('iHeadOfficeId',$headoffice);
        $this->db->where('iBranchId',$branch);
        $this->db->where('iCategoryId',$cat_id);
        $this->db->where('iProductId',$product);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('dProductQty', 'dProductQty - ' . (int) $qty, FALSE);
        $this->db->update('cic_stock');
    }
    public function get_product_branch_data($id){
        $this->db->select('pb.iProductId,pr.vProductName,pb.iBranchId,br.vBranchName');
        $this->db->where('pr.iProductId',$id);
        $this->db->join('cic_products as pr','pr.iProductId=pb.iProductId','left');
        $this->db->join('cic_master_branch as br','br.iBranchId=pb.iBranchId','left');
        return $this->db->get('cic_product_branch as pb')->result_array();
    }
    public function get_all_product_branch_data(){
        $this->db->select('pb.iProductId,pr.vProductName,pb.iBranchId,br.vBranchName');
        $this->db->join('cic_products as pr','pr.iProductId=pb.iProductId','left');
        $this->db->join('cic_master_branch as br','br.iBranchId=pb.iBranchId','left');
        return $this->db->get('cic_product_branch as pb')->result_array();
    }
    public function get_sales_order_data($data){
       $this->db->insert('cic_sales_order',$data);
       return $this->db->insert_id();
    }
    public function get_sales_order_details($data){
        $this->db->insert_batch('cic_sales_order_details',$data);
    }
    public function get_sales_by_id($id){
        $this->db->select('sod.iSalesOrderDetailsId,sod.iSalesOrderId,sod.iProductId,sod.iCatagoryId,sod.iSubcatagoryId,sod.iBrandId,sod.iModelId,sod.iProductUnitId,sod.iProductColorId,sod.IGST,sod.CGST,sod.SGST,SUM(sod.iDeliverySubTotal) AS iDeliverySubTotal,SUM(sod.iDeliveryQTY) AS iDeliveryQTY,sod.iDeliveryCostperQTY,so.vSalesOrderNo,so.vPayemntStatus');
        $this->db->where('sod.iSalesOrderId',$id);
        $this->db->join('cic_sales_order as so','so.iSalesOrderId=sod.iSalesOrderId','left');
        // $this->db->from('cic_sales_order_details as sod');
        return $this->db->get('cic_sales_order_details as sod')->row_array();
    }
    public function get_category_by_branch($branchid,$lang,$customer=null){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("cuc.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,sc.vSubcategoryName_Tamil,CONCAT('".$base_url."',ct.vImage) AS image");
        }
        if($lang == "English"){
            $this->db->select("cuc.iCategoryId,ct.vCategoryName,sc.vSubcategoryName,CONCAT('".$base_url."',ct.vImage) AS image");
        }
        $this->db->join('cic_product_branch as pb','pb.iBranchId='.$branchid.'','left');
        // $this->db->where('pb.iBranchId',$branchid);
        $this->db->join('cic_master_category as ct','ct.iCategoryId=cuc.iCategoryId','left');
        $this->db->join('cic_master_subcategory as sc','sc.iCategoryId=cuc.iCategoryId','left');
    //     if(!empty($customer))
    //     {
    //     // $this->db->join('cic_customer as cus','cus.iBranchId='.$branchid.' AND cus.iCustomerId='.$customer.'','left');
    //    $this->db->join('cic_customer_category as cuc','cuc.iCategoryId=ct.iCategoryId','left');
    //     $this->db->where_in('cuc.iCustomerId','(select iCustomerId from cic_customer where iBranchId='.$branchid.' and iCustomerId='.$customer.')',false);
    //     }
        if(!empty($customer))
        $this->db->where('cuc.iCustomerId',$customer);
        $this->db->where('ct.eStatus',"Active");
        $this->db->group_by('cuc.iCategoryId');
        $this->db->from('cic_customer_category as cuc');
        $data = $this->db->get()->result_array();
        if(!empty($data)){
            foreach($data as $key=>$catagory_data){
                $color = explode(',',$catagory_data['iCategoryId']);
                $this->db->select('vSubcategoryName');
                $this->db->where_in('iCategoryId',$color);
                $this->db->from('cic_master_subcategory');
                $subcatarr = $this->db->get()->result_array();
                $array = array_column($subcatarr, 'vSubcategoryName');
                $subcategories = implode(',', $array);
                $subcategory = !empty($subcategories) ? $subcategories : "No Subcategories avaialble";
                $data[$key]['vSubcategoryName'] = $subcategory;
            }
        }
        
        return $data;
        
    }
    public function get_most_sold_category($data=""){
        $category_id = $data['category'];
        $base_url = base_url().'uploads/';
        if($data['langiage']=="Tamil"){
            $this->db->select("s.iProductId,count(s.iCatagoryId) as max_sale_quantity,c.iCategoryId,c.vCategoryName_Tamil as vCategoryName,CONCAT('".$base_url."',c.vImage) AS category_image");
        }
        if($data['langiage']=="English"){
            $this->db->select("s.iProductId,count(s.iCatagoryId) as max_sale_quantity,c.iCategoryId,c.vCategoryName,CONCAT('".$base_url."',c.vImage) AS category_image");
        }
        $this->db->join('cic_master_category as c','c.iCategoryId=s.iCatagoryId');
        $this->db->group_by('s.iCatagoryId');
        $this->db->where('eStatus!=','Deleted');
        $this->db->order_by('count(s.iCatagoryId)','desc');
        $this->db->from('cic_sales_order_details as s');
        $this->db->limit(10);
        $result =  $this->db->get()->result_array();
        
        if($data['language']=="Tamil"){
            $this->db->select("c.iProductId,c.iCategoryId,c.vProductName_Tamil as vProductName,c.vDescription_Tamil as vDescription,b.iDeliveryCostperQTY,d.vCategoryName_Tamil as vCategoryName,count(b.iProductId) as sale_quantity,CONCAT('".$base_url."',c.vImages) AS product_image");
        }
        if($data['language']=="English"){
            $this->db->select("b.iProductId,c.iCategoryId,c.vProductName,c.vDescription,b.iDeliveryCostperQTY,count(b.iProductId) as sale_quantity,CONCAT('".$base_url."',c.vImages) AS product_image,SUBSTRING_INDEX(c.vImages , ',', 1) AS first_name");
        }
        $this->db->from('cic_sales_order_details as b');
        $this->db->join('cic_master_category as d','d.iCategoryId=b.iCatagoryId','left');
        $this->db->join('cic_products as c','c.iProductId=b.iProductId','left');
        if(!empty($category_id))
        $this->db->where('c.iCategoryId',$category_id);
  
        $this->db->group_by('b.iProductId');
        $this->db->order_by('count(b.iProductId)','desc');
        $this->db->where('c.eStatus!=','Deleted');
        $result['max_sold_product_arr'] = $this->db->get()->result_array();
        return $result;
    }
    public function get_offer_products($data,$branch_id){
        $this->db->select('iCategoryId');
        $this->db->where('iCustomerId',$data['customer_id']);
        $this->db->from('cic_customer_category');
        $user_category = $this->db->get()->result_array();
        $category_arr = array();
        foreach($user_category as $uscat){
            $category_arr[] = $uscat['iCategoryId'];
        }
        $categoryid = $category_arr;
        $base_url = base_url().'uploads/';
        if($data['language'] == "Tamil"){
            $this->db->select("p.iProductId,CONCAT(p.vProductName_Tamil,' (',un.vProductUnitName_Tamil,')') as vProductName,un.iProductUnitId As iProductUnitId,d.iCategoryId,d.vCategoryName_Tamil as vCategoryName,CONCAT('".$base_url."',p.vImages) AS product_image");
        }
        if($data['language'] == "English"){
            $this->db->select("p.iProductId,l.fProductPrice,CONCAT(p.vProductName,' (',un.vProductUnitName,')') as vProductName,un.iProductUnitId As iProductUnitId,d.iCategoryId,d.vCategoryName,CONCAT('".$base_url."',p.vImages) AS product_image");
        }
        $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
        $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
        $this->db->join('cic_master_category as d','d.iCategoryId=p.iCategoryId','left');
        $this->db->where_in('d.iCategoryId',$categoryid);
        if($data['type'] == "Recent selling"){
        $this->db->join('cic_sales_order_details as s','s.iCatagoryId=p.iCategoryId','left');
        $this->db->order_by('count(s.iCatagoryId)','desc');
        $this->db->group_by('s.iCatagoryId');
        }
        $this->db->order_by('p.iProductId','desc');
        $this->db->join('cic_product_price_list as l','l.iProductId=p.iProductId','left');
        $this->db->join('cic_product_unit as un',"l.iProductUnitId = un.iProductUnitId","left");
        $this->db->join('cic_stock as st','p.iProductId = st.iProductId AND st.iProductUnitId=l.iProductUnitId AND st.iBranchId = b.iBranchId',false);
        $this->db->where('p.eStatus','Active');
        $this->db->group_by('p.iProductId');
        $this->db->or_where('b.iBranchId',$branch_id);
        $this->db->limit(10);
        $this->db->from('cic_products as p');
        $result = $this->db->get()->result_array();
        if(!empty($result)){
            foreach($result as $key=>$val){
                $str = explode(',', $val['product_image']);
                $result[$key]['single_product_image'] = $str[0];
            }
        }
        return $result;
    }
    public function insert_customer_category($data){
        $this->db->insert_batch('cic_customer_category',$data);
    }
    public function get_grades(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_grade');
        return $this->db->get()->result_array();
    }
    
    public function get_customer_userroles(){
        $roles = array('Customer');
        $this->db->select('iUserRoleId,vUserRole');
        $this->db->where('eStatus','Active');
        $this->db->where_in('vUserRole',$roles);
        $this->db->order_by('vUserRole');
        $this->db->from('cic_master_user_role');
        return $this->db->get()->result_array();
    }
    public function get_category_by_customer_id($data){
        $base_url = base_url().'uploads/';
        if($data['language'] == "Tamil"){
            $this->db->select("a.iCustomerId,a.iCategoryId,b.vCategoryName_Tamil as vCategoryName,CONCAT('".$base_url."',b.vImage) AS category_image,CONCAT('".$base_url."',b.vImage) AS image,b.eStatus,b.dCreatedDate");
        }
        if($data['language'] == "English"){
            $this->db->select("a.iCustomerId,a.iCategoryId,b.vCategoryName,CONCAT('".$base_url."',b.vImage) AS category_image,CONCAT('".$base_url."',b.vImage) AS image,b.eStatus,b.dCreatedDate");
        }
        $this->db->join('cic_master_category as b','b.iCategoryId=a.iCategoryId','left');
        $this->db->where('a.iCustomerId',$data['customer_id']);
        $this->db->from('cic_customer_category as a');
        $get_data = $this->db->get();
        if($get_data->num_rows()>1){
            $result['others'] = $get_data->result_array();
        }
        if($get_data->num_rows() == 0)
        return false;
        if($get_data->num_rows() == 1){
            
            if($data['language'] == "Tamil"){
                $this->db->select("iCategoryId,vCategoryName_Tamil as vCategoryName,CONCAT('".$base_url."',vImage) AS category_image,CONCAT('".$base_url."',vImage) AS image,eStatus,dCreatedDate");
            }
            if($data['language'] == "English"){
                $this->db->select("iCategoryId,vCategoryName,CONCAT('".$base_url."',vImage) AS category_image,CONCAT('".$base_url."',vImage) AS image,eStatus,dCreatedDate");
            }
            $this->db->where('iCategoryId!=',$result[0]['iCategoryId']);
            $this->db->from('cic_master_category');
            $result['others'] = $this->db->get()->result_array();
        }
        return $result;
    }
    public function check_customer_login($phone){
        $this->db->select('*');
        $this->db->where('vPhoneNumber',$phone);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_customer');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }
    }
    public function check_customer_exist($mobile){
        $this->db->select('*');
        $this->db->where('vPhoneNumber',$mobile);
        $this->db->where('eStatus','Active');
        $query = $this->db->get('cic_customer');
        $result = $query->row_array();
        if($query->num_rows() >0){
            $this->db->select('vUserRole');
            $this->db->where('iUserRoleId',$result['iUserRoleId']);
            $this->db->from('cic_master_user_role');
            $result['vUserRole'] = $this->db->get()->row_array();
            return $result;
        }else{
            return false;
        }
    }
    public function check_employee_exist($mobile){
        $ignore = array(1, 2);
        $this->db->select('*');
        $this->db->where('iPhoneNumber',$mobile);
        $this->db->where('eStatus','Active');
        $this->db->where_not_in('iUserRoleId', $ignore);
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        $result = $query->row_array();
        if($query->num_rows() >0){
            $this->db->select('vUserRole');
            $this->db->where('iUserRoleId',$result['iUserRoleId']);
            $this->db->from('cic_master_user_role');
            $result['vUserRole'] = $this->db->get()->row_array();
            return $result;
        }else{
            return false;
        }
    }
    public function get_customers_by_salesman_branch($salesman_id,$lang){
        $this->db->select('iBranchId');
        $this->db->where('iUserId',$salesman_id);
        $this->db->from('cic_master_users');
        $query = $this->db->get()->row_array();
        if($lang == "Tamil"){
            $this->db->select('iCustomerId,vCustomerName_Tamil as vCustomerName,iBranchId,iGradeId,vCompanyName_Tamil as vCompanyName,vPhoneNumber as iPhoneNumber,vEmail');
        }
        if($lang == "English"){
            $this->db->select('iCustomerId,vCustomerName as vCustomerName,iBranchId,iGradeId,vCompanyName,vPhoneNumber as iPhoneNumber,vEmail');
        } 
        $this->db->where('iBranchId',$query['iBranchId']);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_customer');
        return $this->db->get()->result_array();
    }
    public function products_by_category($category_id,$lang,$branch_id){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName_Tamil as vBranchName,p.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,p.iSubcatagoryId,sc.vSubcategoryName_Tamil,p.vProductName_Tamil as vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription_Tamil as vDescription");
        }
        if($lang == "English"){
            $this->db->select("p.iProductId,GROUP_CONCAT(s.iProductUnitId ORDER BY s.iProductUnitId) As iProductUnitId,s.iHeadOfficeId,s.iBranchId,br.vBranchName,p.iCategoryId,ct.vCategoryName,p.iSubcatagoryId,sc.vSubcategoryName,p.vProductName,p.vImages as product_img,CONCAT('".$base_url."',vImages) AS product_image,GROUP_CONCAT(unit.vProductUnitName SEPARATOR ',') as vProductUnitName,GROUP_CONCAT(q.iMinQty SEPARATOR ',') as iMinQty,p.vDescription");
        } 
        $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
        $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
        $this->db->join('cic_stock as s','p.iProductId = s.iProductId','left');
        $this->db->join('cic_master_category as ct','p.iCategoryId=ct.iCategoryId','left');
        $this->db->join('cic_product_min_quantity as q','p.iProductId=q.iProductId','left');
        $this->db->join('cic_product_unit as unit','q.iProductUnitId=unit.iProductUnitId','left');
        $this->db->join('cic_master_subcategory as sc','p.iSubcatagoryId=sc.iSubcategoryId','left');
        $this->db->where('p.iCategoryId',$category_id);
        $this->db->where('b.iBranchId',$branch_id);
        $this->db->where('p.eStatus','Active');
        $this->db->group_by('p.iProductId');
        $data = $this->db->get('cic_products as p')->result_array();
        if(!empty($data)){
            foreach($data as $key=>$catagory_data){
                $data[$key]['product_images']=array();
                $product_images=explode(',',$catagory_data['product_img']);
                if(count($product_images)>0)
                {
                    foreach($product_images as $product_image)
                    {
                        $data[$key]['product_images'][] =  array("image"=>$base_url.$product_image);
                    }
                }
                $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice,st.dProductQty');
                $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
                $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
                // $this->db->where('pl.iGradeId',$gradeid);
                $this->db->group_by('pl.iProductPriceListId');
                $this->db->order_by('pl.iProductUnitId');
                $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
                $unit_details_status = !empty($unit_details) ? "Stock is available" : "Out of stock";
                $data[$key]['unit_details_status'] = $unit_details_status;
                $data[$key]['unit_details'] = $unit_details;
            }
        }
        return $data;
    }
    public function get_product_details($gradeid,$branch_id,$product_id,$product_unit_id,$lang){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("p.iProductId,s.iBranchId,br.vBranchName_Tamil as vBranchName,p.iCategoryId,ct.vCategoryName_Tamil as vCategoryName,p.iSubcatagoryId,sc.vSubcategoryName_Tamil,pl.fProductPrice,CONCAT(p.vProductName_Tamil,' ',un.vProductUnitName_Tamil) AS vProductName,un.iProductUnitId,p.vImages as product_imgs,CONCAT('".$base_url."',vImages) AS product_image,IFNULL(q.iMinQty,0) as `iMinQty`,p.vDescription_Tamil as vDescription,(CASE WHEN s.dProductQty > 0 THEN s.dProductQty ELSE 0 END) As dProductQty");
        }
        if($lang == "English"){
            $this->db->select("p.iProductId,s.iBranchId,br.vBranchName,p.iCategoryId,ct.vCategoryName,p.iSubcatagoryId,sc.vSubcategoryName,pl.fProductPrice,CONCAT(p.vProductName,' ',un.vProductUnitName) AS vProductName,un.iProductUnitId,p.vImages as product_imgs,CONCAT('".$base_url."',vImages) AS product_image,IFNULL(q.iMinQty,0) as `iMinQty`,p.vDescription,p.iProductColorId,(CASE WHEN s.dProductQty > 0 THEN s.dProductQty ELSE 0 END) As dProductQty");
        } 
        $this->db->join('cic_product_branch as b','p.iProductId = b.iProductId','left');
        $this->db->join('cic_master_branch as br','b.iBranchId=br.iBranchId','left');
        $this->db->join('cic_product_price_list as pl','p.iProductId=pl.iProductId','left');
        $this->db->join('cic_product_unit as un',"pl.iProductUnitId = un.iProductUnitId","left");
        $this->db->join('cic_stock as s','p.iProductId = s.iProductId AND s.iProductUnitId=pl.iProductUnitId AND s.iBranchId = b.iBranchId',false);
        $this->db->join('cic_master_category as ct','p.iCategoryId=ct.iCategoryId','left');
        $this->db->join('cic_product_min_quantity as q','q.iProductId=pl.iProductId','left');
        $this->db->join('cic_master_subcategory as sc','p.iSubcatagoryId=sc.iSubcategoryId','left');
        $this->db->where('pl.iGradeId',$gradeid);
        $this->db->where('b.iBranchId',$branch_id);
        $this->db->where('pl.iProductUnitId',$product_unit_id);
        $this->db->where('p.iProductId',$product_id);
        $this->db->where('p.eStatus','Active');
        $this->db->group_by('pl.iProductId');
        $data = $this->db->get('cic_products as p')->result_array();
        
        if(!empty($data)){
            foreach($data as $key=>$catagory_data){
                $color = explode(',',$catagory_data['iProductColorId']);
                $this->db->select('iProductColorId,vColorName');
                $this->db->where_in('iProductColorId',$color);
                $this->db->from('cic_product_color');
                $color_arr = $this->db->get()->result_array();
                $color_details_status = !empty($color_arr) ? "Color is available" : "No color avaialble";
                $data[$key]['color_status'] = $color_details_status;
                $data[$key]['color_details'] = $color_arr;
                
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice');
                $this->db->join('cic_product_unit as un','pl.iProductUnitId=un.iProductUnitId',"left");
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where('pl.iGradeId',$gradeid);
                $price_list= $this->db->get('cic_product_price_list as pl')->result_array();
                // $data[$key]['price'] = $price_list;
                $data[$key]['product_images']=array();
                $product_images=explode(',',$catagory_data['product_imgs']);
                if(count($product_images)>0)
                {
                    foreach($product_images as $product_image)
                    {
                        $data[$key]['product_images'][] =  $base_url.$product_image;
                    }
                }
                $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
                $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice,st.dProductQty');
                $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
                $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
                $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
                $this->db->where('pl.iGradeId',$gradeid);
                $this->db->group_by('pl.iProductPriceListId');
                $this->db->order_by('pl.iProductUnitId');
                $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
                if($catagory_data['dProductQty']!=0 && $catagory_data['dProductQty']>=$catagory_data['iMinQty']){
                    $unit_details_status =  "Stock is available";
                }
                else{
                $unit_details_status = "Out of stock";
                }
                // $unit_details_status = ($catagory_data['dProductQty']!=0 || $catagory_data['dProductQty']>$catagory_data['iMinQty']) ? "Stock is available" : "Out of stock";
                $data[$key]['unit_details_status'] = $unit_details_status;
                // $data[$key]['unit_details'] = $unit_details;
            }
        }
        return $data;
    }
    public function get_subcategory_by_category($customerid,$branch_id,$categoryid=null,$lang,$status=null){
        $this->db->select('iCategoryId');
        $this->db->where('iCustomerId',$customerid);
        $this->db->from('cic_customer_category');
        $user_category = $this->db->get()->result_array();
        $category_arr = array();
        foreach($user_category as $uscat){
            $category_arr[] = $uscat['iCategoryId'];
        }
        $cat_id = $category_arr;
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("s.iSubcategoryId,s.iCategoryId,s.vSubcategoryName_Tamil as vSubcategoryName,c.vCategoryName_Tamil as vCategoryName,CONCAT('".$base_url."',s.vImage) as subcategory_image");
        }
        if($lang == "English"){
            $this->db->select("s.iSubcategoryId,s.iCategoryId,s.vSubcategoryName,c.vCategoryName,CONCAT('".$base_url."',s.vImage) as subcategory_image");
        }
        $this->db->join('cic_master_category as c','s.iCategoryId=c.iCategoryId','left');
        if(!empty($status)){
            $this->db->join('cic_sales_order_details as t','t.iSubcatagoryId=s.iSubcategoryId','left');
            if(!empty($customerid))
            $this->db->where_in('s.iCategoryId',$cat_id);
            $this->db->order_by('count(t.iSubcatagoryId)','desc');
            $this->db->group_by('t.iSubcatagoryId');
            $this->db->limit(9);
            }
        if(!empty($categoryid))    
        $this->db->where('s.iCategoryId',$categoryid);
        $this->db->where('s.eStatus','Active');
        $this->db->group_by('s.iSubcategoryId');
        $result = $this->db->get('cic_master_subcategory as s')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$subcategory){
                if($lang == "Tamil"){
                    $this->db->select("p.iProductId,p.vProductName_Tamil as vProductName,p.vDescription_Tamil as vDescription,p.iCategoryId,c.vCategoryName_Tamil as vCategoryName,p.iBrandId,brand.vBrandName_Tamil as vBrandName,CONCAT('".$base_url."',p.vImages) AS product_image");
                }
                if($lang == "English"){
                    $this->db->select("p.*,c.vCategoryName,brand.vBrandName,CONCAT('".$base_url."',p.vImages) AS product_image");
                }
                $this->db->join('cic_product_branch as b','p.iProductId=b.iProductId','left');
                $this->db->join('cic_master_category as c','p.iCategoryId=c.iCategoryId','left');
                $this->db->join('cic_master_brand as brand','p.iBrandId=brand.iBrandId','left');
                $this->db->where('p.iSubcatagoryId',$subcategory['iSubcategoryId']);
                $this->db->where('b.iBranchId',$branch_id);
                $this->db->where('p.eStatus','Active');
                $product_details = $this->db->get('cic_products as p')->result_array();
                $product_status = !empty($product_details) ? "Product is available" : "No Product available";
                $result[$key]['product_status'] = $product_status;
                $result[$key]['products'] = $product_details;
            }
        }
        return $result;
    }
    public function get_product_by_subcategory($subcategoryid,$branch_id,$lang){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("p.iProductId,p.vProductName_Tamil,p.vDescription_Tamil,CONCAT('".$base_url."',p.vImages) AS product_image");
        }
        if($lang == "English"){
            $this->db->select("p.*,CONCAT('".$base_url."',p.vImages) AS product_image");
        }
        $this->db->join('cic_product_branch as b','p.iProductId=b.iProductId','left');
        $this->db->where('p.iSubcatagoryId',$subcategoryid);
        $this->db->where('b.iBranchId',$branch_id);
        $this->db->where('eStatus','Active');
        return  $this->db->get('cic_products as p')->result_array();
    }
    
    public function get_current_version($data)
    {
        $this->db->select('*');
        $this->db->where('vPackageName',$data['package_name']);
        $this->db->from('cic_app_version');
        return $this->db->get()->row_array();
    }
    
    public function get_offer_details($branch_id)
    {
        $base_url = base_url().'uploads/';
        $this->db->select("vOfferName,iOfferTypeId,vOfferBadge,CONCAT('".$base_url."',vImage) as offer_banner");
        // $this->db->select("*,CONCAT('".$base_url."',vImage) as offer_banner");
        $this->db->where('find_in_set("'.$branch_id.'", iBranchId)');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_offers');
        $result = $this->db->get()->result_array();
        if(!empty($result)){
            foreach($result as $key=>$val){
                if($lang == "Tamil"){
                    $this->db->select("p.iProductId,p.vProductName_Tamil as vProductName,p.vDescription_Tamil as vDescription,p.iCategoryId,c.vCategoryName_Tamil as vCategoryName,p.iBrandId,brand.vBrandName_Tamil as vBrandName,CONCAT('".$base_url."',p.vImages) AS product_image");
                }
                if($lang == "English"){
                    $this->db->select("p.iProductId,p.vProductName,c.vCategoryName,brand.vBrandName,p.vDescription,p.iCategoryId,c.vCategoryName,brand.vBrandName,CONCAT('".$base_url."',p.vImages) AS product_image");
                }
                $this->db->join('cic_master_category as c','p.iCategoryId=c.iCategoryId','left');
                $this->db->join('cic_master_brand as brand','p.iBrandId=brand.iBrandId','left');
                $this->db->where('p.iProductId',$val['iOfferTypeId']);
                $this->db->where('p.eStatus','Active');
                $this->db->limit(10);
                $product_details = $this->db->get('cic_products as p')->result_array();
                $product_status = !empty($product_details) ? "Product is available" : "No Product available";
                $result[$key]['product_status'] = $product_status;
                $result[$key]['products'] = $product_details;
            }
        }
        return $result;
    }
    public function update_sales_payment_status($id,$data)
    {
        $this->db->where('iSalesOrderId',$id);
        $this->db->update('cic_sales_order',$data);
    }
    public function get_subcategory_by_category_v1($branch_id,$user_id,$lang){
        $this->db->select('iCategoryId');
        $this->db->where('iCustomerId',$user_id);
        $this->db->from('cic_customer_category');
        $user_category = $this->db->get()->result_array();
        $category_arr = array();
        foreach($user_category as $uscat){
            $category_arr[] = $uscat['iCategoryId'];
        }
        $categoryid = $category_arr;
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
            $this->db->select("s.iSubcategoryId,s.iCategoryId,s.vSubcategoryName_Tamil as vSubcategoryName,c.vCategoryName_Tamil as vCategoryName,CONCAT('".$base_url."',s.vImage) as subcategory_image");
        }
        if($lang == "English"){
            $this->db->select("s.iSubcategoryId,s.iCategoryId,s.vSubcategoryName,c.vCategoryName,CONCAT('".$base_url."',s.vImage) as subcategory_image");
        }
        $this->db->join('cic_master_category as c','s.iCategoryId=c.iCategoryId','left');
        $this->db->where_in('s.iCategoryId',$categoryid);
        $this->db->where('s.eStatus','Active');
        $this->db->group_by('s.iSubcategoryId');
        $result = $this->db->get('cic_master_subcategory as s')->result_array();
        if(!empty($result)){
            foreach($result as $key=>$subcategory){
                if($lang == "Tamil"){
                    $this->db->select("p.iProductId,p.vProductName_Tamil as vProductName,p.vDescription_Tamil as vDescription,p.iCategoryId,c.vCategoryName_Tamil as vCategoryName,p.iBrandId,brand.vBrandName_Tamil as vBrandName,CONCAT('".$base_url."',p.vImages) AS product_image");
                }
                if($lang == "English"){
                    $this->db->select("p.iProductId,p.vProductName,c.vCategoryName,brand.vBrandName,p.vDescription,p.iCategoryId,c.vCategoryName,brand.vBrandName,CONCAT('".$base_url."',p.vImages) AS product_image");
                }
                $this->db->join('cic_product_branch as b','p.iProductId=b.iProductId','left');
                $this->db->join('cic_master_category as c','p.iCategoryId=c.iCategoryId','left');
                $this->db->join('cic_master_brand as brand','p.iBrandId=brand.iBrandId','left');
                $this->db->where('p.iSubcatagoryId',$subcategory['iSubcategoryId']);
                $this->db->where('b.iBranchId',$branch_id);
                $this->db->where('p.eStatus','Active');
                $this->db->limit(10);
                $product_details = $this->db->get('cic_products as p')->result_array();
                $product_status = !empty($product_details) ? "Product is available" : "No Product available";
                $result[$key]['product_status'] = $product_status;
                $result[$key]['products'] = $product_details;
            }
        }
        return $result;
    }
    public function get_order_number()
    {
        $this->db->select('iOrderNumber');
        $this->db->where('vType','Sales');
        $this->db->from('cic_increment_order');
        return $this->db->get()->row_array();
    }
    public function update_order_number()
    {
        $this->db->where('vType','Sales');
        $this->db->set('iOrderNumber','iOrderNumber+'.(int)1,false);
        $this->db->update('cic_increment_order');
    }
    public function get_subcategory_categories($subid)
    {
        $this->db->select('iCategoryId');
        $this->db->where('iCategoryId',$subid);
        $this->db->from('cic_master_subcategory');
        return $this->db->get()->result_array();
    }
    public function stock_by_categoryid_v1($category_id,$branch_id,$lang,$gradeid,$type,$sorting){
        $base_url = base_url().'uploads/';
        if($lang == "Tamil"){
           $this->db->select("p.iProductId,CONCAT(p.vProductName_Tamil,' (',un.vProductUnitName_Tamil,')') AS vProductName,pl.fProductPrice,un.vProductUnitName,p.vDescription_Tamil as vDescription,p.iProductColorId,p.iBrandId,brand.vBrandName_Tamil as vBrandName,p.iCategoryId,p.iSubcatagoryId,ct.vCategoryName_Tamil as vCategoryName,br.vBranchName_Tamil as vBranchName,pl.iProductUnitId As iProductUnitId,s.iHeadOfficeId,s.iProductUnitId As iProductUnitId,CONCAT('".$base_url."',SUBSTRING_INDEX(p.vImages, ',', 1)) AS product_image,IFNULL(q.iMinQty,0) as `iMinQty`,(CASE WHEN s.dProductQty > 0 THEN s.dProductQty ELSE 0 END) As dProductQty");
        }
        if($lang == "English"){
          $this->db->select("p.iProductId,CONCAT(p.vProductName,' (',un.vProductUnitName,')') AS vProductName,pl.fProductPrice,p.vDescription,p.iProductColorId,p.iBrandId,brand.vBrandName,p.iCategoryId,p.iSubcatagoryId,ct.vCategoryName,br.vBranchName,pl.iProductUnitId As iProductUnitId,s.iHeadOfficeId,CONCAT('".$base_url."',SUBSTRING_INDEX(p.vImages, ',', 1)) AS product_image,IFNULL(q.iMinQty,0) as `iMinQty`,(CASE WHEN s.dProductQty > 0 THEN s.dProductQty ELSE 0 END) As dProductQty");
        } 
        $this->db->join('cic_product_branch as b','b.iProductId = p.iProductId');
        $this->db->join('cic_master_branch as br','br.iBranchId=b.iBranchId','left');
        $this->db->join('cic_master_brand as brand','brand.iBrandId=p.iBrandId','left');
        $this->db->join('cic_master_category as ct','ct.iCategoryId=p.iCategoryId','left');
        $this->db->join('cic_product_price_list as pl','p.iProductId=pl.iProductId');
        $this->db->join('cic_product_unit as un',"pl.iProductUnitId = un.iProductUnitId","left");
        $this->db->join('cic_stock as s','s.iProductId = p.iProductId AND s.iProductUnitId = un.iProductUnitId AND s.iBranchId = b.iBranchId',false);
        $this->db->join('cic_product_min_quantity as q','p.iProductId=q.iProductId','left');
        $this->db->where('b.iBranchId',$branch_id);
        $this->db->where('pl.iGradeId',$gradeid);

        if($sorting=="popularity"){
            $this->db->join('cic_sales_order_details as sd','sd.iProductId=p.iProductId');
            if($type=="category")
            $this->db->order_by('count(sd.iCatagoryId)','desc');
            else
            $this->db->order_by('count(sd.iSubcatagoryId)','desc');
        }
        elseif($sorting=="asc" || $sorting=="desc")
        {
            $price = (isset($sorting) && $sorting == 'desc') ? 'desc' : 'asc';
            $this->db->order_by('pl.fProductPrice',$price);
        }
        elseif($sorting=="atoz" || $sorting=="ztoa")
        {
            $product_name = (isset($sorting) && $sorting == 'atoz') ? 'asc' : 'desc';
            $this->db->order_by('p.vProductName',$product_name);
        }
        else
        $this->db->order_by('pl.iProductUnitId');
        if($type=="category")
            // $this->db->where_in('p.iCategoryId',$category_id);
            $this->db->where('p.iCategoryId',$category_id);
        else
            $this->db->where('p.iSubcatagoryId',$category_id);
        $this->db->where('p.eStatus','Active');
        $this->db->group_by('pl.iProductPriceListId');
        $data = $this->db->get('cic_products as p')->result_array();
        if(!empty($data)){
            foreach($data as $key=>$catagory_data){
                // $iProductUnitId = explode(',',$catagory_data['iProductUnitId']);
                // $this->db->select('pl.iProductUnitId,un.vProductUnitName,pl.fProductPrice,st.dProductQty');
                // $this->db->select('(SELECT min.iMinQty FROM cic_product_min_quantity as min WHERE min.iProductUnitId IN ("'.implode(",",$iProductUnitId).'") AND min.iBranchId = "'.$branch_id.'" AND min.iProductId = "'.$catagory_data['iProductId'].'") as iMinQty');
                // $this->db->join('cic_product_unit as un','un.iProductUnitId=pl.iProductUnitId');
                // $this->db->join('cic_stock as st','st.iProductUnitId=pl.iProductUnitId AND st.iProductUnitId=pl.iProductUnitId',false);
                // $this->db->join('cic_product_min_quantity as pmq','pl.iProductUnitId=pmq.iProductUnitId AND pmq.iBranchId = "'.$branch_id.'" AND pmq.iProductId = "'.$catagory_data['iProductId'].'"','left');
                // $this->db->where('pl.iProductId',$catagory_data['iProductId']);
                // $this->db->where_in('pl.iProductUnitId',$iProductUnitId);
                // $this->db->where('pl.iGradeId',$gradeid);
                // $this->db->group_by('pl.iProductPriceListId');
                // $this->db->order_by('pl.iProductUnitId');
                $unit_details = $this->db->get('cic_product_price_list as pl')->result_array();
                if($catagory_data['dProductQty']!=0 && $catagory_data['dProductQty']>=$catagory_data['iMinQty']){
                    $unit_details_status =  "Stock is available";
                }
                else{
                $unit_details_status = "Out of stock";
                }
                
                $data[$key]['unit_details_status'] = $unit_details_status;
            }
        }
        
        return $data;
    }
    public function get_headoffice_and_region($branch){
        $this->db->select('h.iHeadOfficeId,h.iRegionId');
        $this->db->from('cic_master_branch as b');
        $this->db->where('b.ibranchId',$branch);
        $this->db->join('cic_master_headoffice as h','b.iHeadOfficeId=h.iHeadOfficeId','left');
        return $this->db->get()->row_array();
    }
    // Get headoffice state
    // public function get_headoffice_state($headoffice){
    // $this->db->select('iStateId');
    // $this->db->where('iHeadOfficeId',$headoffice);
    // $this->db->from('cic_master_headoffice');
    // $query = $this->db->get();
    // if($query->num_rows() >0){
    //     return $query->row_array();
    // }else
    // return false;
    // }
    // AddUser Account
    public function add_user_document($data){
        $this->db->insert('cic_user_document_details',$data);
        return $this->db->insert_id();
    }
    // GetUser Account
    public function userdocument_by_id($id){
        $this->db->select('a.*');
        $this->db->where('iCustomerId',$id);
        $this->db->from('cic_user_document_details as a');
        $query = $this->db->get();
        if($query->num_rows() >0)
        return $query->row_array();
        else
        return false;
    }
    // Update User Account
    public function update_document_account($id,$data){
        $this->db->where('iCustomerId',$id);
        $this->db->update('cic_user_document_details',$data);
    }
    // Get Search products
    public function get_search_products($customer,$branch,$grade,$lang){
        $base_url = base_url().'uploads/';
        $this->db->select('iCategoryId');
        $this->db->where('iCustomerId',$customer);
        $this->db->from('cic_customer_category');
        $user_category = $this->db->get()->result_array();
        $category_arr = array();
        foreach($user_category as $uscat){
            $category_arr[] = $uscat['iCategoryId'];
        }
        $cat_id = $category_arr;
        if($lang == "Tamil"){
            $this->db->select("p.iProductId,pl.iProductPriceListId,CONCAT(p.vProductName_Tamil,' (',un.vProductUnitName_Tamil,')') AS vProductName,sub.vSubcategoryName_Tamil AS vSubcategoryName,un.vProductUnitName,p.iCategoryId,p.iSubcatagoryId,c.vCategoryName_Tamil as vCategoryName,br.vBranchName_Tamil as vBranchName,pl.iProductUnitId As iProductUnitId,s.iHeadOfficeId,s.iProductUnitId As iProductUnitId,CONCAT('".$base_url."',SUBSTRING_INDEX(p.vImages, ',', 1)) AS product_image");
         }
         if($lang == "English"){
           $this->db->select("p.iProductId,pl.iProductPriceListId,CONCAT(p.vProductName,' (',un.vProductUnitName,')') AS vProductName,sub.vSubcategoryName,p.iCategoryId,p.iSubcatagoryId,c.vCategoryName,pl.iProductUnitId As iProductUnitId,CONCAT('".$base_url."',SUBSTRING_INDEX(p.vImages, ',', 1)) AS product_image");
         } 
    $this->db->join('cic_master_category as c','p.iCategoryId=c.iCategoryId','left');
    $this->db->join('cic_master_subcategory as sub','p.iSubcatagoryId=sub.iSubcategoryId','left');
    $this->db->join('cic_master_brand as brand','p.iBrandId=brand.iBrandId','left');
    $this->db->join('cic_product_branch as pb','pb.iProductId=p.iProductId','left');
    $this->db->join('cic_master_branch as br','pb.iBranchId=br.iBranchId','left');
    $this->db->join('cic_product_price_list as pl','p.iProductId=pl.iProductId',false);
    $this->db->join('cic_product_unit as un',"pl.iProductUnitId = un.iProductUnitId","left");
    $this->db->join('cic_stock as s','s.iProductId = p.iProductId AND s.iProductUnitId = un.iProductUnitId AND s.iBranchId = pb.iBranchId',false);
    $this->db->where_in('p.iCategoryId',$cat_id);
    $this->db->where('pb.iBranchId',$branch);
    $this->db->where('pl.iGradeId',$grade);
    $this->db->where('p.eStatus','Active');
    $this->db->group_by('pl.iProductPriceListId');
    $this->db->order_by('pl.iProductUnitId');
    
    $product_details = $this->db->get('cic_products as p')->result_array();
    return $product_details;
}
// Get salesorder prodcut details
public function get_salesorder_details($id,$lang){
    if($lang == 'Tamil'){
        $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.dOrderedDate,so.vPayemntMethod,so.vAddress,so.eDeliveryStatus,so.iCustomerId,so.fNetQty,so.dCreatedDate,so.fNetCostwithoutGST,so.fNetCost,so.fAdditionalCharge,so.eStatus,cus.vCustomerName_Tamil as vCustomerName,u.vUserRole_Tamil as vCustomerType');
    }
    if($lang == 'English'){
        $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.dOrderedDate,so.vPayemntMethod,so.vAddress,so.eDeliveryStatus,so.iCustomerId,so.fNetQty,so.dCreatedDate,so.fNetCostwithoutGST,so.fNetCost,so.fAdditionalCharge,so.eStatus,cus.vCustomerName,u.vUserRole as vCustomerType');
    }
    $this->db->join('cic_customer as cus','cus.iCustomerId=so.iCustomerId','left');
    $this->db->join('cic_master_user_role as u','u.iUserRoleId=cus.iUserRoleId','left');
    $this->db->where('so.iSalesOrderId',$id);
    $this->db->where('so.eStatus','Active');
    $this->db->order_by('so.iSalesOrderId','desc');
    $this->db->from('cic_sales_order as so');
    $data = $this->db->get()->result_array();
    return $data;
}

public function get_salesorder_product_details($id,$lang){
    $base_url = base_url().'uploads/';
    if(!empty($id)){
            if($lang == "Tamil"){
                $this->db->select("sd.iDeliveryQTY,sd.iDeliveryCostperQTY,sd.iDeliverySubTotal,p.iProductId,CONCAT(p.vProductName_Tamil,' (',un.vProductUnitName_Tamil,')') AS vProductName,p.vDescription_Tamil as vDescription,p.iCategoryId,p.iBrandId,CONCAT('".$base_url."',SUBSTRING_INDEX(p.vImages, ',', 1)) AS product_image,un.iProductUnitId");
            }
            if($lang == "English"){
                $this->db->select("sd.iDeliveryQTY,sd.iDeliveryCostperQTY,sd.iDeliverySubTotal,p.iProductId,CONCAT(p.vProductName,' (',un.vProductUnitName,')') AS vProductName,p.vDescription as vDescription,p.iCategoryId,p.iBrandId,CONCAT('".$base_url."',SUBSTRING_INDEX(p.vImages, ',', 1)) AS product_image,un.iProductUnitId");
            }
            $this->db->join('cic_sales_order_details as sd','sd.iProductId=p.iProductId');
            $this->db->join('cic_product_unit as un',"sd.iProductUnitId = un.iProductUnitId","right");
            $this->db->where('sd.iSalesOrderId',$id);
            $this->db->where('p.eStatus','Active');
            $product_details = $this->db->get('cic_products as p')->result_array();
    }
    return $product_details;
}
public function cancel_salesorder($id,$action){
    if(!empty($id)){
            $cancel = array('eDeliveryStatus' => 'Cancelled', 'eDeliveryStatus_Tamil' => 'ரத்து செய்யப்பட்டது');
            $this->db->where('iSalesOrderId', $id);
            if ($this->db->update('cic_sales_order', $cancel)) {
                return TRUE;
            }
    }
    return FALSE;
}
// Update Reciept increament
public function update_increment($id, $type) {
    $this->db->where('cic_increment_order.vType', $type);
    if ($this->db->update('cic_increment_order', $id)) {
        return true;
    }
    return false;
}
// Insert Reciept Bill
public function insert_reciept_bill($data){
    $this->db->insert('cic_receipt_bill',$data);
    return $this->db->insert_id();
}
// Gst Amount Updation 
public function update_sales_order_gst_amount($data,$id){
    $this->db->where('iSalesOrderId',$id);
    $this->db->update('cic_sales_order', $data);
}
// Get salesorder id by salesno
public function get_sales_order_id_by_no($id){
    $this->db->select('iSalesOrderId');
    $this->db->where('vSalesOrderNo',$id);
    return $this->db->get('cic_sales_order')->row_array();
}
}