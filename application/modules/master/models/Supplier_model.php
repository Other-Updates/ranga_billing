<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends MY_Controller {
    private $table = 'cic_master_branch';
    private $column_order = array(null,'vSupplierName','eStatus'); //set column field database for datatable orderable
    private $column_search = array('vSupplierName','eStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

   public function get_manager(){
       $this->db->select('*');
       $this->db->where('iUserRoleId',2);
       $this->db->where('eStatus','Active');
       $this->db->from('cic_master_users');
       return $this->db->get()->result_array();
   }

   public function get_headoffice(){
       $this->db->select('*');
       $this->db->where('eStatus','Active');
       $this->db->from('cic_master_headoffice');
       return $this->db->get()->result_array();
   }

   public function insert_supplier($data){
       $this->db->insert('cic_master_suppliers',$data);
       return $this->db->insert_id();
   }

   private function list_data() {       
        $this->db->select('*');
        $this->db->where('eStatus!=','Deleted');
        $this->db->order_by("iSupplierId", "desc");
        $this->db->from('cic_master_suppliers');
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


    public function supplier_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_suppliers() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function supplier_by_id($id){
        $this->db->select('*');
        $this->db->where('iSupplierId',$id);
        $this->db->from('cic_master_suppliers');
        return $this->db->get()->row_array();
    }

    public function update_supplier($id,$data){
        $this->db->where('iSupplierId',$id);
        $this->db->update('cic_master_suppliers',$data);
    }

    public function delete_branch($id){
        $this->db->where('iBranchId',$id);
        $this->db->delete('cic_master_branch');
    }

    public function check_duplicate($branch){
        $this->db->select('*');
        $this->db->where('vBranchName',$branch);
        $this->db->from('cic_master_branch');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }
}
