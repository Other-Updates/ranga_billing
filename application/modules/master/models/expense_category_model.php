<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense_category_model extends CI_Model {

    private $manage_category = 'manage_category';
    private $manage_sub_category = 'manage_sub_category';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function insert_category($data) {
        if ($this->db->insert($this->manage_category, $data)) {
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }

        return FALSE;
    }

    function get_all_category_list() {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select($this->manage_category . '.*');
        $this->db->where($this->manage_category . '.status', 1);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where($this->manage_category . '.client_id', $client_id);
        }
        // $this->db->where($this->manage_category . '.client_id', $client_id);
        $this->db->order_by('manage_category.id', 'desc');
        $query = $this->db->get($this->manage_category)->result_array();
        return $query;
    }

    public function get_category_by_id($id) {
        $this->db->select($this->manage_category . '.*');
        $this->db->where($this->manage_category . '.id', $id);
        $query = $this->db->get($this->manage_category)->result_array();
        return $query;
    }

    public function update_category($data, $id) {
        $this->db->where('id', $id);
        if ($this->db->update($this->manage_category, $data)) {
            return true;
        }
        return false;
    }

    public function delete_category($id) {
        $this->db->where('category_id', $id);
        $this->db->delete($this->manage_sub_category);
        $this->db->where('id', $id);
        if ($this->db->delete($this->manage_category)) {
            return true;
        }
        return false;
    }

    public function add_duplicate_category($data, $client_id) {

        $this->db->select('*');
        $this->db->where('category', $data['category']);
        $this->db->where('status', 1);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where($this->manage_category . '.client_id', $client_id);
        }
        $query = $this->db->get($this->manage_category);
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }

    public function update_duplicate_category($cat_name, $id, $comments, $client_id) {
        $this->db->select('*');
        $this->db->where('category', $cat_name);
        $this->db->where('comments', $comments);
        $this->db->where('id !=', $id);
        $this->db->where('status', 1);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where($this->manage_category . '.client_id', $client_id);
        }
        $query = $this->db->get($this->manage_category)->result_array();

        return $query;
    }

}
