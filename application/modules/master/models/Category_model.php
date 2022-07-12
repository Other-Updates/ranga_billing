<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class category_model extends MY_Controller {
    private $table = 'cic_master_category';
    private $column_order = array(null,'vCategoryName','eStatus'); //set column field database for datatable orderable
    private $column_search = array('vCategoryName','eStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_category($data){
        $this->db->insert('cic_master_category',$data);
    }
   
    private function list_data() {       
        $this->db->select('iCategoryId,vCategoryName,vImage,eStatus');
        $this->db->where('eStatus!=','Deleted');
        $this->db->order_by("iCategoryId", "desc");
        $this->db->from('cic_master_category');
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


    public function category_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_categories() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function category_by_id($id){
        $this->db->select('*');
        $this->db->where('iCategoryId',$id);
        $this->db->from('cic_master_category');
        return $this->db->get()->row_array();
    }

    public function update_category($id,$data){
        $this->db->where('iCategoryId',$id);
        $this->db->update('cic_master_category',$data);
    }

    public function get_headoffice(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_headoffice');
        return $this->db->get()->result_array();
    }

    public function get_branch_by_headoffice($id){
        $this->db->select('*');
        $this->db->where_in('iHeadOfficeId',$id);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }
}
