<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_role_model extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_roles($data){
        $this->db->insert('cic_master_user_role',$data);
    }

    public function get_user_roles(){
        $this->db->select('*');
        $this->db->where('eStatus!=','Deleted');
        $this->db->order_by('iUserRoleId','desc');
        $this->db->from('cic_master_user_role');
        $query = $this->db->get()->result_array();
        return $query;
    }
   
    public function edit_roles($id){
        $this->db->select('*');
        $this->db->where('iUserRoleId',$id);
        $this->db->from('cic_master_user_role');
        return $this->db->get()->row_array();
    }

    public function update_roles($id,$data){
        $this->db->where('iUserRoleId',$id);
        $this->db->update('cic_master_user_role',$data);
    }


    public function get_user_permissions_by_role($user_role_id) {
        $this->db->select('*');
        $this->db->where('iUserRoleId', $user_role_id);
        $query = $this->db->get('cic_user_role_permissions');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    function get_all_user_sections_with_modules() {
        $this->db->select('*');
        $query = $this->db->get('cic_master_user_modules');
        $modules = $query->result_array();
        $user_section_arr = array();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $sections = $this->get_user_sections_by_module_id($module['iUserModuleId']);
                $user_section_arr[$module['iUserModuleId']] = $module;
                $user_section_arr[$module['iUserModuleId']]['sections'] = $sections;
            }
        }
        return $user_section_arr;
    }

    function get_user_sections_by_module_id($id) {
        $this->db->select('tab_1.*');
        $this->db->join('cic_master_user_modules AS tab_2', 'tab_2.iUserModuleId = tab_1.iUserModuleId', 'LEFT');
        $this->db->where('tab_1.iUserModuleId', $id);
        $this->db->where('tab_1.eStatus', 'Active');
        $query = $this->db->get('cic_master_user_sections AS tab_1');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    public function insert_user_permission($data){
        $this->db->insert('cic_user_role_permissions',$data);
    }

    function update_user_role($data, $id) {
        $this->db->where('iUserRoleId', $id);
        if ($this->db->update('cic_master_user_role', $data)) {
            return TRUE;
        }
        return FALSE;
    }

    function delete_user_permission_by_role($role) {
        $this->db->where('iUserRoleId', $role);
        if ($this->db->delete('cic_user_role_permissions')) {
            return TRUE;
        }
        return FALSE;
    }

    // public function delete_roles($id){
    //     $this->db->where('iUserRoleId',$id);
    //     $this->db->delete('cic_master_user_role');
    // }
}
