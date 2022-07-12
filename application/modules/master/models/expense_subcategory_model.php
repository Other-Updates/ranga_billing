<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense_subcategory_model extends CI_Model {

    private $manage_sub_category = 'manage_sub_category';
    private $manage_category = 'manage_category';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function insert_category($data) {
        if ($this->db->insert($this->manage_sub_category, $data)) {
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return FALSE;
    }

    public function get_all_subcategory_list() {
        $this->db->select($this->manage_sub_category . '.*');
        $this->db->where($this->manage_sub_category . '.status', 1);
        $query = $this->db->get($this->manage_sub_category)->result_array();
        return $query;
    }

    public function get_all_category_list() {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select($this->manage_category . '.*');
        $this->db->where($this->manage_category . '.status', 1);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where($this->manage_category . '.client_id', $client_id);
        }
        // $this->db->where($this->manage_category . '.client_id', $client_id);
        $query = $this->db->get($this->manage_category)->result_array();
        return $query;
    }

    public function get_all_list() {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.category,tab_1.*');
        $this->db->join($this->manage_category . ' AS tab_2', 'tab_2.id = tab_1.category_id', 'LEFT');
        if($this->session->userdata('UserRole') == 2){
            $this->db->where('tab_2.client_id', $client_id);
        }
        // $this->db->where('tab_2.client_id', $client_id);
        $this->db->order_by('tab_1.id', 'desc');
        $query = $this->db->get($this->manage_sub_category . ' AS tab_1');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    public function get_subcategory_by_id($id) {
        $this->db->select('tab_1.*,tab_2.category');
        $this->db->where('tab_1.id', $id);
        $this->db->join($this->manage_category . ' AS tab_2', 'tab_2.id = tab_1.category_id', 'LEFT');
        $query = $this->db->get($this->manage_sub_category . ' AS tab_1');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    public function update_subcategory($data, $id) {
        $this->db->where('id', $id);
        if ($this->db->update($this->manage_sub_category, $data)) {
            return true;
        }
        return false;
    }

    public function delete_subcategory($id) {
        $this->db->where('id', $id);
        if ($this->db->delete($this->manage_sub_category)) {
            return true;
        }
        return false;
    }

    public function add_duplicate_subcategory($data, $client_id) {
        $this->db->select('tab_1.*');
        $this->db->where('tab_1.sub_category', $data['sub_category']);
        $this->db->where('tab_1.category_id', $data['category_id']);
        // $this->db->where('tab_2.client_id', $client_id);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where('tab_2.client_id', $client_id);
        }
        $this->db->join($this->manage_category . ' AS tab_2', 'tab_2.id = tab_1.category_id', 'LEFT');
        $query = $this->db->get($this->manage_sub_category . ' AS tab_1');
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }

    public function update_duplicate_subcategory($input, $client_id) {
        $this->db->select('tab_1.*');
        $this->db->where('tab_1.category_id', $input['category_id']);
        $this->db->where('tab_1.sub_category', $input['sub_category']);
        $this->db->where('tab_1.id !=', $input['id']);
        // $this->db->where('tab_2.client_id', $client_id);\
        if($this->session->userdata('UserRole') == 2){
            $this->db->where('tab_2.client_id', $client_id);
        }
        $this->db->join($this->manage_category . ' AS tab_2', 'tab_2.id = tab_1.category_id', 'LEFT');
        $query = $this->db->get($this->manage_sub_category . ' AS tab_1');

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

}
