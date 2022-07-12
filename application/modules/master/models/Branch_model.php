<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch_model extends MY_Controller {
    private $table = 'cic_master_branch';
    private $column_order = array(null,'vBranchName','manager','eStatus'); //set column field database for datatable orderable
    private $column_search = array('vBranchName','manager','eStatus'); //set column field database for datatable searchable 
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

   public function get_branch($data){
       $this->db->insert('cic_master_branch',$data);
       return $this->db->insert_id();
   }

   private function list_data() {       
        $this->db->select('br.iBranchId,br.vBranchName,us.vName as manager,ho.vHeadOfficeName,br.eStatus,br.vAdhaarGst,br.vMobileNumber,br.vAddress,br.vAddress_Tamil');
        $this->db->where('br.estatus!=','Deleted');
        $this->db->join('cic_master_users as us','us.iUserId=br.iBranchManagerId','left');
        $this->db->join('cic_master_headoffice as ho','ho.iHeadOfficeId=br.iHeadOfficeId','left');
        $this->db->order_by("br.iBranchId", "desc");
        $this->db->from('cic_master_branch as br');
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


    public function branch_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_branches() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function branch_by_id($id){
        $this->db->select('*');
        $this->db->where('iBranchId',$id);
        $this->db->from('cic_master_branch');
        return $this->db->get()->row_array();
    }

    public function update_branch($id,$data){
        $this->db->where('iBranchId',$id);
        $this->db->update('cic_master_branch',$data);
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
