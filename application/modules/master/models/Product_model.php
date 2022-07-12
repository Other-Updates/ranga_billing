<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends MY_Controller {
    private $table = 'cic_products';
    private $column_order = array(null,'pd.vProductName','pc.vCategoryName','pd.vHSNNO','pd.eStatus'); //set column field database for datatable orderable
    private $column_search = array('vProductName','vCategoryName'); //set column field database for datatable searchable 
    private $order = array('iProductId' => 'desc'); // default descending order

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //product list
    private function list_data() {
        $this->db->select('pd.*,pc.vCategoryName,pd.eStatus');
        $this->db->where('pd.eStatus!=','Deleted');
        $this->db->from('cic_products as pd');
        $this->db->order_by('pd.iProductId', 'desc');
        $this->db->join('cic_master_category as pc','pc.iCategoryId=pd.iCategoryId','left');
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

    public function product_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    function count_all_products() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_products($data){
        $this->db->insert('cic_products',$data);
        return $this->db->insert_id();
    }

    public function get_product_price($data){
        $this->db->insert_batch('cic_product_price_list',$data);
    }

    public function update_price_list($data){
        $this->db->update_batch('cic_product_price_list',$data,'iProductPriceListId');
    }
    public function get_min_qty($data){
        $this->db->insert_batch('cic_product_min_quantity',$data);
    }

    public function update_min_qty($data){
        $this->db->update_batch('cic_product_min_quantity',$data,'iProductMinQtyId');
    }

    public function get_product_by_id($data){
        $this->db->select('pd.eStatus as status,pc.*,pd.*');
        $this->db->where('iProductId',$data);
        $this->db->join('cic_master_category as pc','pc.iCategoryId=pd.iCategoryId','left');
        $this->db->from('cic_products as pd');
        $query = $this->db->get()->row_array();
        return $query;
    }
    
    public function update_product($data,$id){
        $this->db->where('iProductId',$id);
        $this->db->update('cic_products',$data);
        
    }

    public function delete_product($id){
        $this->db->where('iProductId', $id);
        $this-> db->delete('cic_products');
    }

    public function get_product_category(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_category');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_product_subcategory(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_subcategory');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_product_model(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_model');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_product_brand(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_brand');
        $query = $this->db->get()->result_array();
        return $query;
    }    
    
    public function get_product_unit(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->order_by('vProductUnitName');
        $this->db->from('cic_product_unit');
        return $this->db->get()->result_array();
    }

    public function get_product_grade(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->order_by('iGradeId','desc');
        $this->db->from('cic_master_grade');
        return $this->db->get()->result_array();
    }

    public function get_branch(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->order_by('iBranchId','desc');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function get_product_branch($data){
        $this->db->insert_batch('cic_product_branch',$data);
    }

    public function insert_product_branch($data){
        $this->db->insert('cic_product_branch',$data);
    }

    public function get_product_branch_by_product($id){
        $this->db->select('*');
        $this->db->where('iProductId',$id);
        return $this->db->get('cic_product_branch')->result_array();
    }

    public function get_product_price_by_product($id){
        $this->db->select('*');
        $this->db->where('iProductId',$id);
        return $this->db->get('cic_product_price_list')->result_array();
    }

    public function get_product_minqty_product($id){
        $this->db->select('*');
        $this->db->where('iProductId',$id);
        return $this->db->get('cic_product_min_quantity')->result_array();
    }

    public function remove_price($id){
        $this->db->where('iProductPriceListId',$id);
        $this->db->delete('cic_product_price_list');
    }

    public function remove_minqty($id){
        $this->db->where('iProductMinQtyId',$id);
        $this->db->delete('cic_product_min_quantity');
    }

    public function remove_branch_product($id){
        $this->db->where('iProductId',$id);
        $this->db->delete('cic_product_branch');
    }

    public function get_category_by_name($category){
        $this->db->select('iCategoryId');
        $this->db->where('LOWER(vCategoryName)',strtolower($category));
        $this->db->from('cic_master_category');
        return $this->db->get()->row_array();
    }

    public function get_subcategory_by_name($subcategory,$category_id){
        // echo $subcategory; echo $category_id;exit;
        $this->db->select('iSubcategoryId');
        $this->db->where('LOWER(vSubcategoryName)',strtolower($subcategory));
        $this->db->where('iCategoryId',$category_id);
        $this->db->from('cic_master_subcategory');
        return $this->db->get()->row_array();
    }

    public function get_brand_by_name($brand){
        $this->db->select('iBrandId');
        $this->db->where('LOWER(vBrandName)',strtolower($brand));
        $this->db->from('cic_master_brand');
        return $this->db->get()->row_array();
    }

    public function get_model_by_name($model){
        $this->db->select('iModelId');
        $this->db->where('LOWER(vModelName)',strtolower($model));
        $this->db->from('cic_master_model');
        return $this->db->get()->row_array();
    }

    public function get_head_office_by_name($headoffice){
        $this->db->select('iHeadOfficeId');
        $this->db->where('LOWER(vHeadOfficeName)',strtolower($headoffice));
        $this->db->from('cic_master_headoffice');
        return $this->db->get()->row_array();
    }

    public function get_branch_by_name($branch,$headofficeid){
        $this->db->select('iBranchId');
        $this->db->where('LOWER(vBranchName)',strtolower($branch));
        $this->db->where('iHeadOfficeId',$headofficeid);
        $this->db->from('cic_master_branch');
        return $this->db->get()->row_array();
    }

    public function get_unit_by_name($unit){
        $this->db->select('iProductUnitId');
        $this->db->where('LOWER(vProductUnitName)',strtolower($unit));
        $this->db->from('cic_product_unit');
        return $this->db->get()->row_array();
    }

    public function is_product_exist($product,$category,$subcategory,$brand,$model){
        $this->db->select('iProductId');
        $this->db->where('LOWER(vProductName)',strtolower($product));
        $this->db->where('iCategoryId',$category);
        $this->db->where('iSubcatagoryId',$subcategory);
        $this->db->where('iBrandId',$brand);
        $this->db->where('iModelId',$model);
        $this->db->where('eStatus!=','Deleted');
        $query = $this->db->get('cic_products');
        if($query->num_rows() >0){
            $result = $query->row_array();
            return $result['iProductId'];
        }
    }
    
    public function get_grade_by_name($grade_name){
        $this->db->select('iGradeId');
        $this->db->where('LOWER(vGradeName)',strtolower($grade_name));
        return $this->db->get('cic_master_grade')->row_array();
    }

    public function check_min_qty_exist($branch_id,$product_id,$unit_id){
        $this->db->select('iProductMinQtyId');
        $this->db->where('iBranchId',$branch_id);
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit_id);
        $query = $this->db->get('cic_product_min_quantity');
        if($query->num_rows() >0){
            $result = $query->row_array();
            return $result['iProductMinQtyId'];
        }
    }

    public function check_exist_price_list($product_id,$unit_id,$gradeid){
        $this->db->select('iProductPriceListId');
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit_id);
        $this->db->where('iGradeId',$gradeid);
        $query = $this->db->get('cic_product_price_list');
        if($query->num_rows() >0){
            $result = $query->row_array();
            return $result['iProductPriceListId'];
        }
    }

    public function get_category_gst($category_id){
        $this->db->select('IGST,CGST,SGST');
        $this->db->where('iCategoryId',$category_id);
        $this->db->from('cic_master_category');
        return $this->db->get()->row_array();
    }

    public function subcategory_by_category($categoryid)
    {
        $this->db->select('*');
        $this->db->where('iCategoryId',$categoryid);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_subcategory');
        return $this->db->get()->result_array();
    }
}
