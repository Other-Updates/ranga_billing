<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends MY_Controller {
    private $table = 'cic_sales_order_details';
    private $column_order = array(null,'so.vSalesOrderNo','cu.vCustomerName','so.fNetQty','so.fNetCost','so.dOrderedDate','so.eDeliveryStatus','so.dCreatedDate'); //set column field database for datatable orderable
    private $column_search = array('so.vSalesOrderNo','cu.vCustomerName','so.fNetQty','so.fNetCost','so.dOrderedDate','so.eDeliveryStatus','so.dCreatedDate'); //set column field database for datatable searchable 
    private $order = array('vSalesOrderNo' => 'desc'); // default descending order
    private $sales_return_order = array(null,'qty','dCreatedDate');
    private $sales_return_search = array(null,'qty','dCreatedDate');
    private $sales_return = array('iSalesReturnId'=>'desc');
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    private function list_data() {       
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
        $this->db->select('so.*,cu.*,pr.*,DATE_FORMAT(so.dCreatedDate, "%d-%m-%Y") as salecreateddate,DATE_FORMAT(so.dOrderedDate, "%d-%m-%Y") as ordereddate');
        $this->db->join('cic_products as pr','pr.iProductId=so.iProductId','left');
        $this->db->join('cic_customer as cu','cu.iCustomerId=so.iCustomerId','left');
        if($this->session->userdata('UserRole') == 3){
        $this->db->where('so.iBranchId', $BranchId);
        $this->db->where('so.iCreatedBy', $logged_user);
        }
        if($this->session->userdata('UserRole') == 2){
        $this->db->where('so.iBranchId', $BranchId);
        }
        $this->db->where('so.eStatus!=','Deleted');
        $this->db->where('so.eDeliveryStatus!=','Cancelled');
        // $this->db->group_by('so.vSalesOrderNo');
        $this->db->from('cic_sales_order as so');
        $i = 0; 
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {               
                if($i===0) // first loop
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }       
        if(isset($_POST['order'])) { 
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function get_sales_return_list() {
        // $this->db->select('sr.*,cu.*,pr.*,DATE_FORMAT(so.dCreatedDate, "%d-%m-%Y") as salecreateddate,DATE_FORMAT(so.dOrderedDate, "%d-%m-%Y") as ordereddate');
        // $this->db->join('cic_products as pr','pr.iProductId=so.iProductId','left');
        // $this->db->join('cic_customer as cu','cu.iCustomerId=so.iCustomerId','left');
        // $this->db->group_by('so.vSalesOrderNo');
        $this->db->select('iSalesOrderId,count(iDeliveryQTY) as qty,dCreatedDate');
        $this->db->group_by('iSalesOrderId');
        $this->db->from('cic_sales_return');
        $i = 0; 
        foreach ($this->sales_return_search as $item) 
        {
            if($_POST['search']['value']) 
            {               
                if($i===0) // first loop
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->sales_return_search) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }       
        if(isset($_POST['order'])) { 
            $this->db->order_by($this->sales_return_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function order_list(){
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    function count_all_sales() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_user(){
        $BranchId = $this->session->userdata('BranchId'); 
        $this->db->select('*');
        if(!empty($BranchId)){
        $this->db->where('iBranchId',$BranchId);
        $this->db->where('iUserRoleId','3');
        }
        $this->db->from('cic_master_users');
        $this->db->where('eStatus','Active');
        $query = $this->db->get()->result_array();
        return $query;
    }
    // get Grade
    public function get_grade(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_grade');
        return $this->db->get()->result_array();
    }
    // get Regions
    public function get_regions(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_regions');
        return $this->db->get()->result_array();
    }
    // Get Roles
    public function get_roles(){
        $roles = array('Distributor','Customer','Retailer');
        $this->db->where_in('vUserRole',$roles);
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_user_role');
        $this->db->order_by('vUserRole');
        $query = $this->db->get()->result_array();
        return $query;
    }
    // Get States
    public function get_states()
    {
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_state');
        return $this->db->get()->result_array();
    }

    public function get_all_distributors(){
        $this->db->select('*');
        $this->db->from('cic_customer');
        $this->db->where('eStatus','Active');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_all_products(){
        $this->db->select('*');
        $this->db->from('cic_products');
        $this->db->where('eStatus','Active');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_sales_order($data){
        $this->db->insert('cic_sales_order',$data);
        return $this->db->insert_id();
    }

    public function get_sales_details($data){
        $this->db->insert_batch('cic_sales_order_details',$data);
    }
   
    public function get_headOffice($head_id){
        $this->db->select('*');
        if(!empty($head_id))
        $this->db->where('iHeadOfficeId',$head_id);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_headoffice');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_branch($branch_id){
        $this->db->select('*');
        if(!empty($this->session->userdata('BranchId')))
        $this->db->where('iBranchId', $branch_id);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function get_customer($branch_id){
        $this->db->select('*');
        if(!empty($branch_id))
        $this->db->where('iBranchId',$branch_id);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_customer');
        return $this->db->get()->result_array();
    }

    public function get_salesman($branch_id){
        $this->db->select('*');
        if(!empty($branch_id))
        $this->db->where('iBranchId',$branch_id);
        $this->db->where('eStatus','Active');
        $this->db->where('iUserRoleId','3');
        $this->db->from('cic_master_users');
        return $this->db->get()->result_array();
    }

    public function get_category(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_category');
        return $this->db->get()->result_array();
    }

    public function get_gst_by_product($id){
        $this->db->select('IGST,CGST,SGST');
        $this->db->where('iProductId',$id);
        $this->db->from('cic_products');
        return $this->db->get()->row_array();
    }

    public function get_price_by_unit($product,$unit,$color_id,$branch_id,$grade){
        $this->db->select('pl.*,st.dProductQty');
        $this->db->join('cic_stock as st','pl.iProductId=st.iProductId and st.iBranchId='.$branch_id,'left');
        $this->db->where('pl.iProductId',$product);
        $this->db->where('pl.iProductUnitId',$unit);
        $this->db->where('pl.iGradeId',$grade);
        $this->db->where('st.iProductUnitId',$unit);
        if($color_id>0)
        $this->db->where('st.iProductColorId',$color_id);
        //$this->db->where('',$branch_id);
        $this->db->from('cic_product_price_list as pl');
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row_array();
        }else
        return false;
    }

    public function get_product_by_category($headoffice,$branch,$cat_id,$product,$type,$customer_id){
        $this->db->select('iCategoryId');
        $this->db->where('iCustomerId',$customer_id);
        $this->db->from('cic_customer_category');
        $result = $this->db->get()->result_array();
        $report_arr = array();
        for($i=0;$i<count($result);$i++) {
            array_push($report_arr,$result[$i]['iCategoryId']);
        }
        // $this->db->select('st.*,pr.vProductName');
        // if($type == "category")
        // $this->db->where('st.iCategoryId',$cat_id);
        // if(!empty($result))
        // $this->db->where_in('st.iCategoryId',$report_arr);
        // $this->db->like('pr.vProductName',$product);
        // $this->db->join('cic_products as pr','pr.iProductId=st.iProductId','left');
        // $this->db->where('pr.eStatus','Active');
        // $this->db->where('st.iBranchId',$branch);
        // $this->db->where('st.iHeadOfficeId',$headoffice);
        // $this->db->group_by('st.iProductId');
        // $this->db->from('cic_stock as st');
        // return $this->db->get()->result_array();

        // $this->db->select('pr.iCategoryId,pr.iProductId,pr.vProductName,c.iProductColorId,c.vColorName');
        // $this->db->join('cic_stock as st','pr.iProductId=st.iProductId','left');
        // $this->db->join('cic_product_color as c','pr.iProductColorId!="" AND find_in_set(c.iProductColorId,pr.iProductColorId)','left');
        // if($type == "category")
        // $this->db->where('pr.iCategoryId',$cat_id);
        // if(!empty($result))
        // $this->db->where_in('pr.iCategoryId',$report_arr);
        // $this->db->like('pr.vProductName',$product);
        // $this->db->where('pr.eStatus','Active');
        // $this->db->where('st.iBranchId',$branch);
        // $this->db->group_by('st.iProductId');
        // $this->db->limit(6);
        // $this->db->from('cic_products as pr');
        // return $this->db->get()->result_array();
        // echo $this->db->last_query();exit;

        $this->db->select('p.vProductName,b.iProductId,p.iCategoryId,c.iProductColorId,c.vColorName');
        $this->db->join('cic_products as p','p.iProductId=b.iProductId','left');
        $this->db->join('cic_product_color as c','p.iProductColorId!="" AND find_in_set(c.iProductColorId,p.iProductColorId)','left');
        $this->db->where('b.iBranchId',$branch);
        if(!empty($report_arr))
        $this->db->where_in('p.iCategoryId',$report_arr);
        if($type == "category")
        $this->db->where('p.iCategoryId',$cat_id);
        $this->db->where('p.eStatus','Active');
        $this->db->like('p.vProductName',$product);
        return $this->db->get('cic_product_branch as b')->result_array();

    }

    public function update_stock_quantity($headoffice,$branch,$cat_id,$product,$unit,$qty,$color_id){
        $this->db->where('iHeadOfficeId',$headoffice);
        $this->db->where('iBranchId',$branch);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iCategoryId',$cat_id);
        $this->db->where('iProductId',$product);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('dProductQty', 'dProductQty - ' . (int) $qty, FALSE);
        $this->db->update('cic_stock');
    }

    // public function get_sales_details_by_id($id){
    //     $this->db->select('sd.*,so.iHeadOfficeId,so.iBranchId,so.iCustomerId');
    //     $this->db->where('sd.iSalesOrderId',$id);
    //     $this->db->join('cic_sales_order as so','so.iSalesOrderId=sd.iSalesOrderId','left');
    //     return $this->db->get('cic_sales_order_details as sd')->result_array();
    // }

    public function get_sales_details_by_id($id){
        $this->db->select('s.*,b.vBranchName,cb.receipt_id,h.vHeadOfficeName,c.vCustomerName,user.vName,c.vGSTINNo as gst_no,c.vPhoneNumber,ste.vStateName,ste.iStateId,');
        $this->db->where('iSalesOrderId',$id);
        $this->db->join('cic_master_users as user','user.iUserId=s.iSalesmanId','left');
        $this->db->join('cic_receipt_bill as cb','cb.receipt_id=s.iSalesOrderId','left');
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=s.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as b','b.iBranchId=s.iBranchId','left');
        $this->db->join('cic_customer as c','c.iCustomerId=s.iCustomerId','left');
        $this->db->join('cic_master_state as ste','ste.iStateId=c.iStateId','left');
        $this->db->from('cic_sales_order as s');
        $data = $this->db->get()->result_array();
        if(!empty($data)){
            foreach($data as $key=>$sales){
                $this->db->select('sod.*,pr.vProductName_Tamil,pr.vHSNNO,c.vCategoryName,unit.vProductUnitName');
                $this->db->select('(select dProductQty from cic_stock WHERE iBranchId='.$sales['iBranchId'].' AND iProductId=sod.iProductId AND iProductUnitId=sod.iProductUnitId AND iProductColorId=sod.iProductColorId GROUP BY sod.iSalesOrderDetailsId) as product_qty',false);
                $this->db->select("(CASE WHEN col.iProductColorId > 0 THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
                $this->db->join('cic_product_color as col','sod.iProductColorId = col.iProductColorId AND sod.iProductColorId != 0 AND sod.iProductColorId != "NULL"','left');
                $this->db->join('cic_master_category as c','c.iCategoryId=sod.iCatagoryId','left');
                $this->db->join('cic_products as pr','pr.iProductId=sod.iProductId','left');
                $this->db->join('cic_product_unit as unit','unit.iProductUnitId=sod.iProductUnitId','left');
                $this->db->join('cic_stock as st','sod.iProductColorId=st.iProductColorId','left');
                $this->db->where('sod.iSalesOrderId',$id);
                $this->db->from('cic_sales_order_details as sod');
                $this->db->group_by('iSalesOrderDetailsId');
                $sale_order = $this->db->get()->result_array();
                $data[$key]['sales_details'] = $sale_order;
            }
        }
        return $data;
    }

    public function get_unit(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        return $this->db->from('cic_product_unit')->get()->result_array();
    }

    public function update_sales($data,$id){
        $this->db->where('iSalesOrderId',$id);
        $this->db->update('cic_sales_order',$data);
    }

    public function update_sales_details($data){
        $this->db->update_batch('cic_sales_order_details',$data,'iSalesOrderDetailsId');
    }

    public function remove_sales_details($id){
        $this->db->where('iSalesOrderDetailsId',$id);
        $this->db->delete('cic_sales_order_details');
    }
    // Remove sales order
    public function remove_sales_order($id){
        $this->db->where('iSalesOrderId',$id);
        $this->db->delete('cic_sales_order');
    }
    public function update_sales_status($data,$id){
        $this->db->where('iSalesOrderId',$id);
        $this->db->update('cic_sales_order',$data);
    }

    public function get_branch_products_history($data){
        $this->db->insert_batch('cic_stock_history',$data);
    }

    public function get_categorid($productid){
        $this->db->select('iCategoryId');
        $this->db->where('iProductId',$productid);
        $this->db->from('cic_products');
        return $this->db->get()->row_array();
    }

    public function get_unit_by_customer($product_id,$customer_id){
        // $this->db->select('iGradeId');
        // $this->db->where('iCustomerId',$customer_id);
        // $this->db->from('cic_customer');
        // $result = $this->db->get()->row_array();
        // $this->db->select('c.*,un.vProductUnitName');
        // $this->db->join('cic_product_price_list as pr','pr.iProductUnitId=c.iProductUnitId','left');
        // $this->db->join('cic_product_unit as un','un.iProductUnitId=pr.iProductUnitId','left');
        // $this->db->where('pr.iGradeId',$result['iGradeId']);
        // $this->db->where('c.iProductId',$product_id);
        // $this->db->group_by('pr.iProductUnitId');
        // $this->db->from('cic_stock as c');
        // return $this->db->get()->result_array();

        $this->db->select('pr.*,un.*');
        $this->db->join('cic_product_unit as un','un.iProductUnitId=pr.iProductUnitId','left');
        // $this->db->where('pr.iGradeId',$result['iGradeId']);
        $this->db->where('pr.iProductId',$product_id);
        $this->db->group_by('pr.iProductUnitId');
        $this->db->from('cic_product_price_list as pr');
        return $this->db->get()->result_array();
    }

    public function check_product_quantity($product_id,$branch_id,$qty,$unit_id,$color_id,$oldqty = NULL){
        // $where = 'iBrandId='.$branch_id.'AND iProductId='.$product_id.
        $this->db->select('dProductQty');
        $this->db->where('iBranchId',$branch_id);
        if($color_id > 0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit_id);
        $result = $this->db->get('cic_stock')->row_array();
        if(($result['dProductQty']+$oldqty) < $qty){
            return true;
        }
    }

    public function edit_sale_order($id){
        $this->db->select('b.vBranchName,h.vHeadOfficeName,c.vCustomerName');
        $this->db->where('iSalesOrderId',$id);
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=s.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as b','b.iBranchId=s.iBranchId','left');
        $this->db->join('cic_customer as c','c.iCustomerId=s.iCustomerId','left');
        $this->db->from('cic_sales_order as s');
        return $this->db->get()->row_array();
    }

    public function check_return_quantity($product_id,$quantity,$unit,$sales_order_detailsid)
    {
        $this->db->where('iSalesOrderDetailsId',$sales_order_detailsid);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->from('cic_sales_order_details');
        $query = $this->db->get()->row_array();
        if($query['iDeliveryQTY'] < $quantity){
            return true;
        }else
        return false;
    }

    public function update_sales_return($product_id,$category_id,$unit,$quantity,$branch,$sales_order_id,$color_id,$status=null)
    {
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCategoryId',$category_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->where('iBranchId',$branch);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->set('dProductQty','dProductQty +'.(int)$quantity,false);
        $this->db->update('cic_stock');
    }

    public function update_sales_order($quantity,$sales_order_id)
    {
        $this->db->set('fNetQty','fNetQty -'.(int)$quantity,false);
        $this->db->where('iSalesOrderId',$sales_order_id);
        $this->db->update('cic_sales_order');
    }

    public function update_sales_order_details($sales_order_detailsid,$product_id,$category_id,$unit,$quantity,$color_id)
    {
        $this->db->where('iSalesOrderDetailsId',$sales_order_detailsid);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCatagoryId',$category_id); //Category id changed
        $this->db->where('iProductUnitId',$unit);
        // if($color_id>0)
        // $this->db->where('iProductColorId',$color_id);
        $this->db->set('iDeliveryQTY','iDeliveryQTY -'.(int)$quantity,false);
        $this->db->update('cic_sales_order_details');
    }

    public function add_sales_return($data)
    {
        $this->db->insert_batch('cic_sales_return',$data);
    }

    public function update_deleted_stock($sale_order_id)
    {
        $this->db->select('iBranchId');
        $this->db->where('iSalesOrderId',$sale_order_id);
        $this->db->from('cic_sales_order');
        $data = $this->db->get()->row_array();
        
        $this->db->select('*');
        $this->db->where('iSalesOrderId',$sale_order_id);
        $this->db->from('cic_sales_order_details');
        $sale_order = $this->db->get()->result_array();
        if(!empty($sale_order)){
            foreach ($sale_order as $result) {
                $this->db->set('dProductQty','dProductQty +'.(int)$result['iDeliveryQTY'],false);
                $this->db->where('iBranchId',$data['iBranchId']);
                $this->db->where('iProductId',$result['iProductId']);
                $this->db->where('iProductUnitId',$result['iProductUnitId']);
                $this->db->update('cic_stock');
                // echo $this->db->last_query();exit;
            }
        }
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

    public function update_branch_stock($sales_order_detail_id,$product_id,$category_id,$unit,$quantity,$branch_id,$color_id){
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCatagoryId',$category_id);
        $this->db->where('iProductUnitId',$unit);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iSalesOrderDetailsId',$sales_order_detail_id);
        $query = $this->db->get('cic_sales_order_details')->result_array();
        
        foreach ($query as $result) {
            if($result['iDeliveryQTY'] != $quantity){
                if($result['iDeliveryQTY'] > $quantity){
                    $updated_quantity = $result['iDeliveryQTY'] - $quantity;
                    $this->db->set('dProductQty','dProductQty +'.(int)$updated_quantity,false);
                }if($result['iDeliveryQTY'] < $quantity){
                    $updated_quantity = $quantity - $result['iDeliveryQTY'];
                    $this->db->set('dProductQty','dProductQty -'.(int)$updated_quantity,false);
                }
                $this->db->where('iProductId',$product_id);
                $this->db->where('iProductUnitId',$unit);
                if($result['iProductColorId']>0)
                $this->db->where('iProductColorId',$result['iProductColorId']);
                $this->db->where('iCategoryId',$category_id);
                $this->db->where('iBranchId',$branch_id);
                $this->db->update('cic_stock');
            }
        }
    }
    
    public function get_address($customer)
    {
        $this->db->select('vAddress,iSalesmanId');
        $this->db->where('iCustomerId',$customer);
        $this->db->from('cic_customer');
        return $this->db->get()->row_array();
    }

    public function get_customer_by_id($id){
        $this->db->select('*');
        $this->db->where('iCustomerId',$id);
        $this->db->from('cic_customer');
        return $this->db->get()->row_array();
    }

    public function update_sales_net_amount($amount,$id)
    {
        $this->db->set('fNetCost','fNetCost-'.(int)$amount,false);
        $this->db->where('iSalesOrderId',$id);
        $this->db->update('cic_sales_order');
    }
     public function get_sales_order_last_id($orderno){
        $this->db->select('cic_sales_order.vSalesOrderNo');
        $this->db->where('cic_sales_order.vSalesOrderNo',$orderno);
        return $this->db->get('cic_sales_order')->row_array();
    }
    // Get receipt no
    function get_last_id($type) {
        $this->db->select('code');
        $this->db->where('vType', $type);
        $query = $this->db->get('cic_increment_order')->row_array();
        return $query;
    }
    // Update sales payment status
    public function update_payment_status($data,$id)
    {
        $this->db->set($data);
        $this->db->where('iSalesOrderId',$id);
        $this->db->update('cic_sales_order');
    }
}
