<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_page_offers_model extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_category(){
        $this->db->select('*');
        $this->db->where('eStatus',"Active");
        $this->db->from('cic_master_category');
        return $this->db->get()->result_array();
    }
   
    public function insert_dashboard_data($data){
        $this->db->insert('cic_app_dashboard',$data);
    }

    public function get_branch(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }
    
    public function get_type_based_data($type)
    {
        if($type == "product"){
            $this->db->select('iProductId as id,vProductName as name');
            $this->db->where('eStatus','Active');
            $this->db->from('cic_products');
            return $this->db->get()->result_array();
        }
        if($type == "category"){
            $this->db->select('iCategoryId as id,vCategoryName as name');
            $this->db->where('eStatus','Active');
            $this->db->from('cic_master_category');
            return $this->db->get()->result_array();
        }
        if($type == "subcategory"){
            $this->db->select('iSubcategoryId as id,vSubcategoryName as name');
            $this->db->where('eStatus','Active');
            $this->db->from('cic_master_subcategory');
            return $this->db->get()->result_array();
        }
    }

    public function insert_offers($data){
        $this->db->insert_batch('cic_offers',$data);
    }

    public function get_offer_list()
    {
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_offers');
        return $this->db->get()->result_array();
    }

    public function all_products()
    {
        $this->db->select('iProductId,vProductName');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_products');
        return $this->db->get()->result_array();
    }

    public function all_categories()
    {
        $this->db->select('iCategoryId,vCategoryName');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_category');
        return $this->db->get()->result_array();
    }

    public function all_sub_categories()
    {
        $this->db->select('iSubcategoryId,vSubcategoryName');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_subcategory');
        return $this->db->get()->result_array();
    }

    public function remove_offer_details($id)
    {
        $this->db->where('iOfferId',$id);
        $this->db->delete('cic_offers');
    }

    public function update_offers($data){
        $this->db->update_batch('cic_offers',$data,'iOfferId');
        // echo "<pre>";echo $this->db->last_query();exit;
    }
}
