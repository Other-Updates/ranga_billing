<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function check_salesman_login($user,$pass){
        // $where = "(vUserName='$user' OR iPhoneNumber='$user') AND (vPassword='replace($pass, '=', ' ')')";
        $this->db->select('*');
        $this->db->where("(vUserName = '$user' OR iPhoneNumber = '$user')");
        $this->db->where('vPassword', $pass);
        $this->db->where_in('iUserRoleId',array(1,2,3));
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->row_array();
        }
    }

    public function get_branch_id($id){
        $this->db->select('iBranchId');
        $this->db->where('iBranchManagerId',$id);
        $this->db->where('eStatus','Active');
        return $this->db->get('cic_master_branch')->result_array();
    }
   
}
