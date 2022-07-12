<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Region_model extends MY_Controller {
    private $table = 'cic_master_regions';
    private $column_order = array(null,'vRegionName','eStatus'); //set column field database for datatable orderable
    private $column_search = array('vRegionName','eStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_region($data){
        $this->db->insert('cic_master_regions',$data);
    }
   
    private function list_data() {       
        $this->db->select('iRegionId,vRegionName,IF(eStatus=1,"Active","Inactive") as tStatus');
        $this->db->order_by('iRegionId','desc');
        $this->db->where('eStatus!=','Deleted');
        $this->db->from('cic_master_regions');
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


    public function region_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_regions() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function region_by_id($id){
        $this->db->select('*');
        $this->db->where('iRegionId',$id);
        $this->db->from('cic_master_regions');
        return $this->db->get()->row_array();
    }

    public function update_region($id,$data){
        $this->db->where('iRegionId',$id);
        $this->db->update('cic_master_regions',$data);
    }

    public function delete_region($id){
        $this->db->where('iRegionId',$id);
        $this->db->delete('cic_master_regions');
    }
}
