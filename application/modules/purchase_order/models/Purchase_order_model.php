<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order_model extends MY_Controller {
    private $table = 'cic_sales_order_details';
    private $column_order = array(null,'o.vPurchaseOrderNo','s.vSupplierName','dDeliveryDate','o.fNetCost','o.dCreatedDate'); //set column field database for datatable orderable
    private $column_search = array('s.vSupplierName','o.vPurchaseOrderNo','dDeliveryDate','o.fNetCost','o.dCreatedDate'); //set column field database for datatable searchable 
    private $order = array('iPurchaseOrderId' => 'desc'); // default descending order
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    private function list_data() {       
        $this->db->select("o.*,DATE_FORMAT(o.dCreatedDate, '%d-%m-%Y') as deliverydate,DATE_FORMAT(o.dCreatedDate,'%d/%m/%Y') as createddate,s.vSupplierName");
        $this->db->join('cic_master_suppliers as s','o.iSupplierId=s.iSupplierId','left');
        $this->db->where('o.eStatus!=','Deleted');
        $this->db->from('cic_purchase_order as o');
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

    public function order_list(){
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    function count_all_purchase() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_user(){
        $this->db->select('*');
        $this->db->from('cic_master_users');
        $this->db->where('eStatus','Active');
        $query = $this->db->get()->result_array();
        return $query;
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

    public function add_purchase_details($data){
        $this->db->insert_batch('cic_purchase_order_details',$data);
    }
   
    public function get_headOffice(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_headoffice');
        return $this->db->get()->result_array();
    }

    public function get_branch(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function get_supplier(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_suppliers');
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

    public function get_price_by_unit($product,$unit){
        $this->db->select('*');
        $this->db->where('iProductId',$product);
        $this->db->where('iProductUnitId',$unit);
        $this->db->from('cic_product_price_list');
        return $this->db->get()->row_array();
    }

    public function get_product_by_category($cat_id,$product,$type){
        $this->db->select('p.iProductId,p.vProductName,c.iProductColorId,c.vColorName');
        $this->db->join('cic_product_color as c','p.iProductColorId!="" AND find_in_set(c.iProductColorId,p.iProductColorId)','left');
        if($type == "category")
        $this->db->where('p.iCategoryId',$cat_id);
        
        $this->db->like('p.vProductName',$product);
        $this->db->where('p.eStatus','Active');
        $this->db->from('cic_products as p');
        $this->db->limit(6);
        return $this->db->get()->result_array();
        // echo $this->db->last_query();exit;
    }

    public function update_purchase_quantity($cat_id,$product,$unit,$qty){
        $this->db->where('iCatagoryId',$cat_id);
        $this->db->where('iProductId',$product);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('dProductQty', 'dProductQty + ' . (int) $qty, FALSE);
        $this->db->update('cic_warehouse');
    }

    // public function get_sales_details_by_id($id){
    //     $this->db->select('sd.*,so.iHeadOfficeId,so.iBranchId,so.iCustomerId');
    //     $this->db->where('sd.iSalesOrderId',$id);
    //     $this->db->join('cic_sales_order as so','so.iSalesOrderId=sd.iSalesOrderId','left');
    //     return $this->db->get('cic_sales_order_details as sd')->result_array();
    // }

    public function get_purchase_details_by_id($id){
        $this->db->select('o.*,s.vSupplierName,s.vSupplierName_Tamil,s.vPhoneNumber,s.vGSTINNo as gst_no,s.vAddress,o.vAddress as purchase_address');
        $this->db->where('iPurchaseOrderId',$id);
        $this->db->join('cic_master_suppliers as s','o.iSupplierId=s.iSupplierId','left');
        $this->db->from('cic_purchase_order as o');
        $data = $this->db->get()->row_array();
        if(!empty($data)){
            foreach($data as $key=>$purchase){
                // $this->db->select('*');
                $this->db->select('po.*,c.vCategoryName,unit.vProductUnitName,pr.vHSNNO');
                $this->db->select('(select dProductQty from cic_warehouse WHERE iProductId=po.iProductId AND iProductUnitId=po.iProductUnitId AND iProductColorId=po.iProductColorId LIMIT 1) as warehouse_qty');
                $this->db->select("(CASE WHEN col.iProductColorId > 0 THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
                $this->db->join('cic_product_color as col','po.iProductColorId = col.iProductColorId AND po.iProductColorId != 0 AND po.iProductColorId != "NULL"','left');
                $this->db->where('po.iPurchaseOrderId',$id);
                $this->db->join('cic_master_category as c','c.iCategoryId=po.iCatagoryId','left');
                $this->db->join('cic_products as pr','pr.iProductId=po.iProductId','left');
                $this->db->join('cic_product_unit as unit','unit.iProductUnitId=po.iProductUnitId','left');
                // $this->db->join('cic_warehouse as w','po.iProductId=w.iProductId AND po.iProductUnitId=w.iProductUnitId AND po.iProductColorId=w.iProductUnitId','left');
                $this->db->group_by('iPurchaseOrderDetailsId');
                $purchase_order = $this->db->get('cic_purchase_order_details as po')->result_array();
                // echo $this->db->last_query();exit;
                $data['purchase_details'] = $purchase_order;
            }
            // echo"<pre>";print_r($data['purchase_details']);exit;
        }
        return $data;
    }

    public function get_unit(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        return $this->db->from('cic_product_unit')->get()->result_array();
    }

    public function update_purchase($data,$id){
        $this->db->where('iPurchaseOrderId',$id);
        $this->db->update('cic_purchase_order',$data);
    }

    public function update_purchase_details($data){
        $this->db->update_batch('cic_purchase_order_details',$data,'iPurchaseOrderDetailsId');
    }

    public function remove_purchase_details($id){

        $this->db->select('*');
        $this->db->where('iPurchaseOrderDetailsId',$id);
        $this->db->from('cic_purchase_order_details');
        $query = $this->db->get()->row_array();

        $this->db->where('iCategoryId',$query['iCatagoryId']);
        $this->db->where('iProductId',$query['iProductId']);
        $this->db->where('iProductUnitId',$query['iProductUnitId']);
        if($query['iProductColorId']>0)
        $this->db->where('iProductColorId',$query['iProductColorId']);
        $this->db->set('dProductQty','dProductQty -'.(int)$query['iPurchaseQTY'],FALSE);
        $this->db->update('cic_warehouse');

        $this->db->where('iPurchaseOrderDetailsId',$id);
        $this->db->delete('cic_purchase_order_details');
    }
    // Remove Purchase Order
    public function remove_purchase_order($id){
        $this->db->where('iPurchaseOrderId',$id);
        $this->db->delete('cic_purchase_order');
    }

    public function add_purchase_history($data){
        $this->db->insert_batch('cic_purchase_order_history',$data);
    }

    public function get_categorid($productid){
        $this->db->select('iCategoryId');
        $this->db->where('iProductId',$productid);
        $this->db->from('cic_products');
        return $this->db->get()->row_array();
    }

    public function get_unit_by_product($product_id){
        $this->db->select('un.*');
        $this->db->from('cic_products as p');
        $this->db->where('p.iProductId',$product_id);
        $this->db->join('cic_product_price_list as pr','pr.iProductId=p.iProductId','left');
        $this->db->join('cic_product_unit as un','un.iProductUnitId=pr.iProductUnitId','left');
        $this->db->group_by('un.iProductUnitId');
        $this->db->order_by('un.iProductUnitId');
        $this->db->where('pr.iProductId',$product_id);
        // $this->db->from('cic_stock as c');
        return $this->db->get()->result_array();
    }

    public function check_product_quantity($product_id,$branch_id,$qty,$unit_id){
        // $where = 'iBrandId='.$branch_id.'AND iProductId='.$product_id.
        $this->db->select('dProductQty');
        $this->db->where('iBranchId',$branch_id);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit_id);
        $result = $this->db->get('cic_stock')->row_array();
        if($result['dProductQty'] < $qty){
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

    public function add_purchase_order($data){
        $this->db->insert('cic_purchase_order',$data);
        return $this->db->insert_id();
    }

    public function check_stock_exist($product,$unit,$color_id){
        $this->db->select('iWareHouseStockId,dProductQty');
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductId',$product);
        $this->db->where('iProductUnitId',$unit);
        $query = $this->db->get('cic_warehouse');
        if($query->num_rows() >0){
            $result = $query->row_array();
            // echo "<pre>";print_r($result);exit;
            return $result;
        }else
        return false;
    }

    public function update_warehouse_products($id,$data){
        $this->db->where('iWareHouseStockId',$id);
        $this->db->update('cic_warehouse',$data);
    }

    public function insert_warehouse_products($data){
        $this->db->insert('cic_warehouse',$data);
    }

    // public function get_unit_by_product_id($product_id,$customer_id){
    //     // $this->db->select('iGradeId');
    //     // $this->db->where('iCustomerId',$customer_id);
    //     // $this->db->from('cic_customer');
    //     // $result = $this->db->get()->row_array();
    //     $this->db->select('pr.*,un.*');
    //     $this->db->join('cic_product_unit as un','un.iProductUnitId=pr.iProductUnitId','left');
    //     // $this->db->where('pr.iGradeId',$result['iGradeId']);
    //     $this->db->where('pr.iProductId',$product_id);
    //     $this->db->group_by('pr.iProductUnitId');
    //     $this->db->from('cic_product_price_list as pr');
    //     return $this->db->get()->result_array();
    // }

    public function update_warehouse_stock($purchase_order_details_id,$product_id,$unit,$quantity,$color_id){
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCatagoryId',$category_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->where('iPurchaseOrderDetailsId',$purchase_order_details_id);
        $query = $this->db->get('cic_purchase_order_details')->result_array();
        foreach ($query as $result) {
            if($result['iPurchaseQTY'] != $quantity){
                if($result['iPurchaseQTY'] > $quantity){
                    
                    $updated_quantity = $result['iPurchaseQTY'] - $quantity;
                    $this->db->set('dProductQty','dProductQty -'.(int)$updated_quantity,false);
                
                }if($result['dProductQty'] < $quantity){
                    
                    $updated_quantity = $quantity - $result['iPurchaseQTY'];
                    $this->db->set('dProductQty','dProductQty +'.(int)$updated_quantity,false);
                    
                }
                $this->db->where('iProductId',$product_id);
                $this->db->where('iProductUnitId',$unit);
                if($color_id>0)
                $this->db->where('iProductColorId',$color_id);
                $this->db->where('iCategoryId',$category_id);
                $this->db->update('cic_warehouse');
                // echo $this->db->last_query();exit;
            }
        }
    }

    public function update_purchase_return($product_id,$category_id,$unit,$quantity,$color_id)
    {
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCategoryId',$category_id);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('dProductQty','dProductQty -'.(int)$quantity,false);
        $this->db->update('cic_warehouse');
    }

    public function check_return_quantity($product_id,$quantity,$unit,$purchase_order_detailsid)
    {
        $this->db->where('iPurchaseOrderDetailsId',$purchase_order_detailsid);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->from('cic_purchase_order_details');
        $query = $this->db->get()->row_array();
        if($query['iPurchaseQTY'] < $quantity){
            return true;
        }else
        return false;
    }

    public function update_purchase_order_details($purchase_order_detailsid,$product_id,$category_id,$unit,$quantity,$color_id)
    {
        $this->db->where('iPurchaseOrderDetailsId',$purchase_order_detailsid);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCatagoryId',$category_id);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('iPurchaseQTY','iPurchaseQTY -'.(int)$quantity,false);
        $this->db->update('cic_purchase_order_details');

    }

    public function add_purchase_return($data)
    {
        $this->db->insert_batch('cic_purchase_return',$data);
    }

    public function update_deleted_stock($purchase_order_id)
    {
        $this->db->select('*');
        $this->db->where('iPurchaseOrderId',$purchase_order_id);
        $this->db->from('cic_purchase_order_details');
        $query = $this->db->get()->result_array();
        if(!empty($query)){
            foreach($query as $result){
                $this->db->set('dProductQty','dProductQty -'.(int)$result['iPurchaseQTY'],false);
                $this->db->where('iProductId',$result['iProductId']);
                if($result['iProductColorId']>0)
                $this->db->where('iProductColorId',$result['iProductColorId']);
                $this->db->where('iProductUnitId',$result['iProductUnitId']);
                $this->db->update('cic_warehouse');
            }
        }
    }

    public function get_order_number($type)
    {
        $this->db->select('iOrderNumber');
        $this->db->where('vType',$type);
        $this->db->from('cic_increment_order');
        return $this->db->get()->row_array();
    }

    public function update_order_number($type)
    {
        $this->db->where('vType',$type);
        $this->db->set('iOrderNumber','iOrderNumber+'.(int)1,false);
        $this->db->update('cic_increment_order');
    }

    public function get_otp($data)
    {
        $this->db->update('cic_general_settings',$data);
    }

    public function get_admin_numbers()
    {
        $this->db->select('*');
        $this->db->from('cic_general_settings');
        return $this->db->get()->row_array();
    }

    public function get_supplier_address($supplier)
    {
        $this->db->select('vAddress');
        $this->db->where('iSupplierId',$supplier);
        $this->db->from('cic_master_suppliers');
        return $this->db->get()->row_array();
    }

    public function update_purchase_net_amount($amount,$id)
    {
        $this->db->set('fNetCost','fNetCost-'.(int)$amount,false);
        $this->db->where('iPurchaseOrderId',$id);
        $this->db->update('cic_purchase_order');
    }
}