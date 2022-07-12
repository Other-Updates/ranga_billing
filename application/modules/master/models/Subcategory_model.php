<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategory_model extends MY_Controller {
    private $table = 'cic_master_subcategory';
    private $column_order = array(null,'vSubcategoryName','vCategoryName','s.eStatus'); //set column field database for datatable orderable
    private $column_search = array('s.vSubcategoryName','c.vCategoryName','s.eStatus'); //set column field database for datatable searchable
    private $order = array('iSubcategoryId');
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_subcategory($data){
        $this->db->insert('cic_master_subcategory',$data);
        return $this->db->insert_id();
    }
   
    private function list_data() {       
        $this->db->select('s.iSubcategoryId,s.vSubcategoryName,s.eStatus,c.vCategoryName');
        $this->db->join('cic_master_category as c','s.iCategoryId=c.iCategoryId','left');
        $this->db->order_by('s.iSubcategoryId','desc');
        $this->db->where('s.eStatus!=','Deleted');
        $this->db->from('cic_master_subcategory as s');
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


    public function subcategory_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_subcategories() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function subcategory_by_id($id){
        $this->db->select('*');
        $this->db->where('iSubcategoryId',$id);
        $this->db->from('cic_master_subcategory');
        return $this->db->get()->row_array();
    }

    public function update_subcategory($id,$data){
        $this->db->where('iSubcategoryId',$id);
        $this->db->update('cic_master_subcategory',$data);
    }

    public function delete_subcategory($id){
        $this->db->where('iSubcategoryId',$id);
        $this->db->delete('cic_master_subcategory');
    }

    public function get_category(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_category');
        return $this->db->get()->result_array();
    }
}
