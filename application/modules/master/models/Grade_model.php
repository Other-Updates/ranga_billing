<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade_model extends MY_Controller {
    private $table = 'cic_master_grade';
    private $column_order = array(null,'vGradeName','eStatus'); //set column field database for datatable orderable
    private $column_search = array('vGradeName','eStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_grade($data){
        $this->db->insert('cic_master_grade',$data);
        return $this->db->insert_id();
    }
   
    private function list_data() {       
        $this->db->select('iGradeId,vGradeName,eStatus');
        $this->db->where('eStatus!=','Deleted');
        $this->db->order_by('iGradeId', "desc");
        $this->db->from('cic_master_grade');
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


    public function grade_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_grades() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function grade_by_id($id){
        $this->db->select('*');
        $this->db->where('iGradeId',$id);
        $this->db->from('cic_master_grade');
        return $this->db->get()->row_array();
    }

    public function update_grade($id,$data){
        $this->db->where('iGradeId',$id);
        $this->db->update('cic_master_grade',$data);
    }

}
