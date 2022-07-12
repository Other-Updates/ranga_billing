<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HeadOffice_model extends MY_Controller {
    private $table = 'cic_master_headoffice';
    private $column_order = array('ho.vHeadOfficeName','ho.eStatus'); //set column field database for datatable orderable
    private $column_search = array('ho.vHeadOfficeName','ho.eStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_headoffice($data){
        $this->db->insert('cic_master_headoffice',$data);
        return $this->db->insert_id();
    }
   
    private function list_data() {       
        $this->db->select('ho.iHeadOfficeId,ho.vHeadOfficeName,ho.eStatus,st.vStateName as state,rg.vRegionName as region');
        $this->db->join('cic_master_state as st','st.iStateId=ho.iStateId','left');
        $this->db->join('cic_master_regions as rg','rg.iRegionId=ho.iRegionId','left');
        $this->db->order_by('ho.iHeadOfficeId', 'desc');
        $this->db->where('ho.eStatus!=','Deleted');
        $this->db->from('cic_master_headoffice as ho');
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


    public function headoffice_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_headoffices() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function headoffice_by_id($id){
        $this->db->select('*');
        $this->db->where('iHeadOfficeId',$id);
        $this->db->from('cic_master_headoffice');
        return $this->db->get()->row_array();
    }

    public function update_headoffice($id,$data){
        $this->db->where('iheadofficeId',$id);
        $this->db->update('cic_master_headoffice',$data);
    }

    public function delete_headoffice($id){
        $this->db->where('iheadofficeId',$id);
        $this->db->delete('cic_master_headoffice');
    }

    public function get_state(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_state');
        return $this->db->get()->result_array();
    }

    public function get_region(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_regions');
        return $this->db->get()->result_array();
    }
}
