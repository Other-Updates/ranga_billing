<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Controller {
    private $table = 'cic_master_users';
    private $column_order = array(null,'us.vName','eUserType','us.iPhoneNumber','us.vAddress','us.vEmail','us.tLoginStatus'); //set column field database for datatable orderable
    private $column_search = array('us.vName','us.iPhoneNumber','us.vAddress','us.vEmail','us.eStatus'); //set column field database for datatable searchable 
    private $order = array('iUserId' => 'desc'); // default descending order
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function check_salesman_login($user,$pass){
        $this->db->select('*');
        $this->db->where('vUserName',$user);
        $this->db->where('vPassword',$pass);
        $this->db->where('iUserRoleId',1);
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }
    }

    public function get_user_profile($id){
        $this->db->select('*');
        $this->db->where('iUserId',$id);
        $this->db->from('cic_master_users');
        $query = $this->db->get()->row_array();
        return $query;
    }

    private function list_data() {       
        $this->db->select('us.*,ur.vUserRole as eUserType,us.eStatus',false);
        $this->db->where('us.iUserRoleId!=','Active');
        $this->db->from('cic_master_users as us'); 
        $this->db->order_by('us.iUserId','desc');
        $this->db->where('us.eStatus!=','Deleted');
        $this->db->join('cic_master_user_role as ur','ur.iUserRoleId=us.iUserRoleId');
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

    public function get_users() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_users() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_roles(){
        $roles = array('Salesman','Branch Manager','Admin','Superadmin');
        $this->db->where_in('vUserRole',$roles);
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_user_role');
        $this->db->order_by('vUserRole');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_region(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_regions');
        return $this->db->get()->result_array();
    }

    public function get_headoffice($region=null){
        $this->db->select('*');
        if(!empty($region))
        $this->db->where('iRegionId',$region);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_headoffice');
        return $this->db->get()->result_array();
    }

    public function get_branch($ho=null){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        if(!empty($ho))
        $this->db->where('iHeadOfficeId',$ho);
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function add_user($data){
        $this->db->insert('cic_master_users',$data);
    }

    public function get_user($id){
        $this->db->select('*');
        $this->db->where('iUserId',$id);
        $this->db->from('cic_master_users');
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function update_user($id,$data){
        $this->db->where('iUserId',$id);
        $this->db->update('cic_master_users',$data);
    }

    public function delete_user($id){
        $this->db->where('iUserId', $id);
        $this-> db->delete('cic_master_users');
    }

    public function check_email_exist(){
        $this->db->select('*');
        $this->db->where('vEmail',$email);
        $this->db->where('iUserRoleId',1);
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }else
        return false;
    }

    public function generateNumericOTP($n) {  
        $generator = "135792468"; 
        $result = ""; 
        for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        }  
        return $result; 
    }

    
}
