<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor_model extends MY_Controller {
    private $table = 'cic_customer';
    private $column_order = array(null,'di.vCustomerName','u.vUserRole','gr.vGradeName','di.vCompanyName','di.vPhoneNumber','di.vAddress','di.vEmail','us.Vname','di.eStatus'); //set column field database for datatable orderable
    private $column_search = array('di.vCustomerName','u.vUserRole','di.vCompanyName','di.vPhoneNumber','di.vAddress','di.vEmail','us.Vname','di.eStatus'); //set column field database for datatable searchable 
    private $order = array('iCustomerId' => 'desc'); // default descending order
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    private function list_data() {    
        $BranchId = $this->session->userdata('BranchId'); 
        $logged_user = $this->session->userdata('LoggedId');    
        $this->db->select('di.*,u.vUserRole,us.Vname as salesman_name,gr.vGradeName,di.eStatus');
        $this->db->join('cic_master_user_role as u','u.iUserRoleId=di.iUserRoleId','left');
        $this->db->join('cic_master_users as us','us.iUserId=di.iSalesmanId','left');
        $this->db->join('cic_master_grade as gr','gr.iGradeId=di.iGradeId','left');
        if(!empty($BranchId))
        $this->db->where('di.iBranchId',$BranchId);
        $this->db->order_by("di.iCustomerId", "desc");
        $this->db->where('di.eStatus!=','Deleted');
        $this->db->from('cic_customer as di');
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


    public function distributor_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_distributors() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function add_distributor($data){
        $this->db->insert('cic_customer',$data);
        return $this->db->insert_id();
    }

    public function update_distributor($id,$data){
        $this->db->where('iCustomerId',$id);
        $this->db->update('cic_customer',$data);
    }

    public function get_distributor_by_id($id){
        $this->db->select('*');
        $this->db->where('iCustomerId',$id);
        $this->db->from('cic_customer');
        $query = $this->db->get()->row_array();

        $this->db->select('iCategoryId');
        $this->db->where('iCustomerId',$id);
        $this->db->from('cic_customer_category');
        $query['category_arr'] = $this->db->get()->result_array();

        return $query;
    }

    public function delete_distributor($id){
        $this->db->where('iCustomerId', $id);
        $this-> db->delete('cic_customer');
    }

    public function get_all_users(){
        $BranchId = $this->session->userdata('BranchId'); 
        $this->db->select('*');
        if(!empty($BranchId)){
        $this->db->where('iBranchId',$BranchId);
        $this->db->where('iUserRoleId','3');
        }
        $this->db->from('cic_master_users');
        if(!empty($BranchId)){
        $this->db->where('iBranchId',$BranchId);
        $this->db->where('iUserRoleId','3');
        }
        $this->db->where('eStatus','Active');
        return $this->db->get()->result_array();
    }

    public function get_grade(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_grade');
        return $this->db->get()->result_array();
    }

    public function get_categories(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_category');
        return $this->db->get()->result_array();
    }

    public function add_customer_category($data){
        $this->db->insert_batch('cic_customer_category',$data);
    }

    public function delete_customer_categories($id){
        $this->db->where('iCustomerId',$id);
        $this->db->delete('cic_customer_category');
    }

    public function get_regions(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_regions');
        return $this->db->get()->result_array();
    }

    public function get_roles(){
        $roles = array('Distributor','Customer','Retailer');
        $this->db->where_in('vUserRole',$roles);
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_user_role');
        $this->db->order_by('vUserRole');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_branch(){
        $BranchId = $this->session->userdata('BranchId'); 
        $this->db->select('*');
        if(!empty($BranchId))
        $this->db->where('iBranchId',$BranchId);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function get_headoffice_and_region($branch){
        $this->db->select('h.iHeadOfficeId,h.iRegionId');
        $this->db->from('cic_master_branch as b');
        $this->db->where('b.ibranchId',$branch);
        $this->db->join('cic_master_headoffice as h','b.iHeadOfficeId=h.iHeadOfficeId','left');
        return $this->db->get()->row_array();
    }

    public function get_states()
    {
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_state');
        return $this->db->get()->result_array();
    }
}
