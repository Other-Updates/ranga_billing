<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_model extends CI_Model
{

    private $expense = 'expense';
    private $branches = 'cic_master_branch';
    private $manage_sub_category = 'manage_sub_category';
    private $manage_category = 'manage_category';
    private $balance_sheet = 'balance_sheet';
    private $vendor = 'vendor';
    private $erp_pr = 'erp_pr';
    private $erp_invoice = 'erp_invoice';
    private $customer = 'customer';
    var $joinTable1 = 'erp_manage_firms tab_2';
    var $joinTable2 = 'manage_sub_category tab_3';
    var $primaryTable = 'expense tab_1';
    var $selectColumn = 'tab_1.*,tab_3.sub_category,tab_4.category';
    var $column_order = array(null, 'tab_1.type',  'tab_4.category', 'tab_3.sub_category', 'tab_1.mode',  'tab_1.amount', 'tab_1.created_at', null);
    var $column_search = array('tab_1.id', 'tab_1.mode', 'tab_4.category', 'tab_3.sub_category', 'tab_1.type', 'tab_1.amount');
    var $order = array('tab_1.id' => 'desc ');

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_expense($data)
    {
        $query = $this->db->insert($this->expense, $data);
        if ($query){
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return FALSE;
    }

    public function insert_balance_sheet($data)
    {
        if ($this->db->insert($this->balance_sheet, $data)) {
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return FALSE;
    }

    public function get_all_firms()
    {
        $client_id = $this->session->userdata('LoggedId');
        $firms = $this->get_all_firms_by_user_id($client_id);
        $frim_id = array();
        foreach ($firms as $value) {
            $frim_id[] = $value['firm_id'];
        }
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->where('client_id', $client_id);
        $this->db->where_in('erp_manage_firms.firm_id', $frim_id);
        $query = $this->db->get('erp_manage_firms')->result_array();
        return $query;
    }

    public function update_company_amt($data, $firm_id)
    {
        $client_id = $this->session->userdata('LoggedId');
        $data['company_amount'] = $data;
        $this->db->where('firm_id', $firm_id);
        $this->db->where('client_id', $client_id);
        if ($this->db->update($this->erp_manage_firms, array('company_amount' => $data))) {
            return true;
        }
        return false;
    }

    public function get_all_expenses()
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_1.*,tab_3.sub_category,tab_4.category');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        // $this->db->where_in('tab_1.firm_id', $frim_id);
        // $this->db->where('tab_1.client_id', $client_id);
        $this->db->order_by('tab_1.id', 'desc');
        $query = $this->db->get($this->expense . ' AS tab_1');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    public function get_all_balance()
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.prefix,tab_1.*,tab_3.sub_category,tab_4.category');
        $this->db->join($this->erp_manage_firms . ' AS tab_2', 'tab_2.firm_id = tab_1.firm_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        $this->db->order_by('tab_1.id', 'desc');
        $this->db->where('tab_1.client_id', $client_id);

        $query = $this->db->get($this->balance_sheet . ' AS tab_1');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    public function getSubCategory($data)
    {
        $this->db->select('id,sub_category');
        $this->db->where('category_id', $data);
        $query = $this->db->get('manage_sub_category')->result_array();
        return $query;
    }

    public function getCompanyAmt($data)
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('firm_id,firm_name,company_amount,opening_balance');
        $this->db->where('client_id', $client_id);
        $this->db->where('firm_id', $data);
        $query = $this->db->get('erp_manage_firms')->result_array();
        return $query;
    }

    public function edit_expenses($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->expense);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    public function update_expenses($input, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update($this->expense, $input)) {
            return true;
        }
        return false;
    }

    public function update_balance_sheet($input, $id)
    {
        $this->db->where('id', $id);
        if ($this->db->update($this->balance_sheet, $input)) {
            return true;
        }
        return false;
    }

    public function get_subcategory($key)
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.*,tab_1.comments');
        $this->db->join($this->manage_sub_category . ' AS tab_2', 'tab_1.id = tab_2.category_id', 'Join');
        $this->db->where('tab_1.client_id', $client_id);
        $this->db->where('tab_1.comments', $key);
        $query = $this->db->get($this->manage_category . ' AS tab_1')->result_array();

        return $query;
    }

    public function get_cat_subcat_details($key)
    {

        $this->db->select('tab_1.*');
        $this->db->where('tab_1.comments', $key);
        $query = $this->db->get($this->manage_category . ' AS tab_1')->result_array();

        $result = '';
        $data['comments'] = $key;
        if ($key == 1) {
            $data['category'] = 'Sales';
            $data['comments'] = '1';
            $data1['sub_category'] = 'Sales Receipt';
        } elseif ($key == 2) {
            $data['category'] = 'Purchase';
            $data['comments'] = '2';
            $data1['sub_category'] = 'Purchase Receipt';
        }

        if (!empty($query)) {
            $result['category_id'] = $query[0]['id'];
            $this->db->select('tab_2.*');
            $this->db->where('tab_2.category_id', $query[0]['id']);
            $query1 = $this->db->get($this->manage_sub_category . ' AS tab_2')->result_array();
            if (!empty($query1)) {
                $result['sub_category_id'] = $query1[0]['id'];
            } else {
                $data1['category_id'] = $result['category_id'];
                $result['sub_category_id'] = $this->insert_sub_category($data1);
            }
        } else {
            $query = $this->db->insert($this->manage_category, $data);
            $data1['category_id'] = $this->db->insert_id();
            $result['category_id'] = $data1['category_id'];
            $result['sub_category_id'] = $this->insert_sub_category($data1);
        }
        return $result;
    }

    public function insert_sub_category($data)
    {

        $this->db->insert($this->manage_sub_category, $data);
        return $this->db->insert_id();
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->set('status', '0');
        $query = $this->db->update($this->expense);
        if($query) {
            return true;
        }
        return false;
    }
    public function get_all_firms_by_user_id($user_id)
    {
        $this->db->select('erp_manage_firms.firm_id, firm_name, prefix');
        $this->db->join('erp_user_firms', 'erp_user_firms.firm_id = erp_manage_firms.firm_id');
        $this->db->where('erp_user_firms.user_id', $user_id);
        $query = $this->db->get('erp_manage_firms')->result_array();
        return $query;
    }

    public function change_date_formate($date)
    {
        $d = explode('/', $date);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
    }

    public function get_datatables($search_data)
    {
        $BranchId = $this->session->userdata('BranchId');
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_1.*,tab_3.sub_category,tab_4.category,tab_2.vBranchName');
        $this->db->join($this->branches . ' AS tab_2', 'tab_2.iBranchId = tab_1.branch_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        // $this->db->where_in('tab_1.firm_id', $frim_id);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where_in('tab_1.branch_id', $BranchId);
        }
        $this->db->where('tab_1.status', '1');
        if (isset($search_data) && !empty($search_data)) {
            if (!empty($search_data['from_date']))
                $search_data['from_date'] = $this->change_date_formate($search_data['from_date']);
            if (!empty($search_data['to_date']))
                $search_data['to_date'] = $this->change_date_formate($search_data['to_date']);
            if ($search_data['from_date'] == '1970-01-01')
                $search_data['from_date'] = '';
            if ($search_data['to_date'] == '1970-01-01')
                $search_data['to_date'] = '';
            if (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "' AND DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] == "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            }
            if (!empty($search_data['mode']) && $search_data['mode'] != 'Select') {

                $this->db->where('tab_1.mode', $search_data['mode']);
            }
            if (!empty($search_data['branch_id']) && $search_data['branch_id'] != 'Select') {

                $this->db->where('tab_1.branch_id', $search_data['branch_id']);
            }
            if (!empty($search_data['cat_id']) && $search_data['cat_id'] != 'Select') {

                $this->db->where('tab_1.cat_id', $search_data['cat_id']);
            }
            if (!empty($search_data['sub_cat_id']) && $search_data['sub_cat_id'] != 'Select') {

                $this->db->where('tab_1.sub_cat_id', $search_data['sub_cat_id']);
            }
        }

        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($search_data['search']['value']) { // if datatable send POST for search
                if($this->session->userdata('UserRole') == 2){
                    $this->db->where_in('tab_1.branch_id', $BranchId);
                }
                $this->db->where('tab_1.status', '1');
                if ($i === 0) { // first loop
                    $this->db->like($item, $search_data['search']['value']);
                } else {
                    $this->db->or_like($item, $search_data['search']['value']);
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // if ($_POST['length'] != -1)
        //     $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get($this->expense . ' AS tab_1');
        return $query->result();
    }

    public function count_all()
    {
        $client_id = $this->session->userdata('LoggedId');
        // $this->db->where('client_id', $client_id);
        $this->db->from($this->primaryTable);
        return $this->db->count_all_results();
    }

    public function count_filtered($search_data)
    {
        $BranchId = $this->session->userdata('BranchId');
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_1.*,tab_3.sub_category,tab_4.category,tab_2.vBranchName');
        $this->db->join($this->branches . ' AS tab_2', 'tab_2.iBranchId = tab_1.branch_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        // $this->db->where_in('tab_1.firm_id', $frim_id);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where_in('tab_1.branch_id', $BranchId);
        }
        $this->db->where('tab_1.status', '1');
        if (isset($search_data) && !empty($search_data)) {
            if (!empty($search_data['from_date']))
                $search_data['from_date'] = $this->change_date_formate($search_data['from_date']);
            if (!empty($search_data['to_date']))
                $search_data['to_date'] = $this->change_date_formate($search_data['to_date']);
            if ($search_data['from_date'] == '1970-01-01')
                $search_data['from_date'] = '';
            if ($search_data['to_date'] == '1970-01-01')
                $search_data['to_date'] = '';
            if (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "' AND DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] == "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            }
            if (!empty($search_data['mode']) && $search_data['mode'] != 'Select') {

                $this->db->where('tab_1.mode', $search_data['mode']);
            }
            if (!empty($search_data['branch']) && $search_data['branch'] != 'Select') {

                $this->db->where('tab_1.branch_id', $search_data['branch_id']);
            }
            if (!empty($search_data['cat_id']) && $search_data['cat_id'] != 'Select') {

                $this->db->where('tab_1.cat_id', $search_data['cat_id']);
            }
            if (!empty($search_data['sub_cat_id']) && $search_data['sub_cat_id'] != 'Select') {

                $this->db->where('tab_1.sub_cat_id', $search_data['sub_cat_id']);
            }
        }

        $i = 0;
        foreach ($this->column_search as $item) { // loop column
            if ($search_data['search']['value']) { // if datatable send POST for search
                if($this->session->userdata('UserRole') == 2){
                    $this->db->where_in('tab_1.branch_id', $BranchId);
                }
                $this->db->where('tab_1.status', '1');
                if ($i === 0) { // first loop
                    $this->db->like($item, $search_data['search']['value']);
                } else {
                    $this->db->or_like($item, $search_data['search']['value']);
                }
            }
            $i++;
        }
        $query = $this->db->get($this->expense . ' AS tab_1');

        return $query->num_rows();
    }

    public function get_balance_datatables($search_data)
    {
        $client_id = $this->session->userdata('LoggedId');
        $firms = $this->get_all_firms_by_user_id($client_id);
        $frim_id = array();
        foreach ($firms as $value) {
            $frim_id[] = $value['firm_id'];
        }
        $this->db->select('tab_2.prefix,tab_2.opening_balance,tab_1.*,tab_3.sub_category,tab_4.category,tab_4.comments,tab_5.inv_id,tab_6.po_no,tab_7.store_name,tab_8.store_name as cust_name');
        $this->db->join($this->erp_manage_firms . ' AS tab_2', 'tab_2.firm_id = tab_1.firm_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        $this->db->join($this->erp_invoice . ' AS tab_5', 'tab_5.id = tab_1.inv_id', 'LEFT');
        $this->db->join($this->erp_pr . ' AS tab_6', 'tab_6.id = tab_1.pr_id', 'LEFT');
        $this->db->join($this->vendor . ' AS tab_7', 'tab_7.id = tab_6.supplier', 'LEFT');
        $this->db->join($this->customer . ' AS tab_8', 'tab_8.id = tab_5.customer', 'LEFT');
        $this->db->where_in('tab_1.firm_id', $frim_id);
        $this->db->where('tab_1.client_id', $client_id);
        if (isset($search_data) && !empty($search_data)) {
            if (!empty($search_data['from_date']))
                $search_data['from_date'] = date('Y-m-d', strtotime($search_data['from_date']));
            if (!empty($search_data['to_date']))
                $search_data['to_date'] = date('Y-m-d', strtotime($search_data['to_date']));
            if ($search_data['from_date'] == '1970-01-01')
                $search_data['from_date'] = '';
            if ($search_data['to_date'] == '1970-01-01')
                $search_data['to_date'] = '';
            if (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "' AND DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] == "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            }
            if (!empty($search_data['firm_id']) && $search_data['firm_id'] != 'Select') {

                $this->db->where('tab_1.firm_id', $search_data['firm_id']);
            }
        }


        $column_order = array(null, 'tab_4.comments', 'tab_1.mode', 'tab_1.created_at',  'tab_1.amount', 'tab_1.amount', 'tab_1.balance',);
        $column_search = array('tab_1.id', 'tab_4.comments', 'tab_1.mode',  'tab_1.type', 'tab_1.mode', 'tab_2.opening_balance', 'tab_1.created_at', 'tab_1.amount', 'tab_1.amount', 'tab_1.balance',);
        $order = array('tab_1.id' => 'ASC');
        $i = 0;
        foreach ($column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $like = "" . $item . " LIKE '%" . $_POST['search']['value'] . "%'";
                    //$this->db->like($item, $_POST['search']['value']);
                } else {
                    //$query = $this->db->or_like($item, $_POST['search']['value']);
                    $like .= " OR " . $item . " LIKE '%" . $_POST['search']['value'] . "%'" . "";
                }
            }
            $i++;
        }
        if ($like) {
            $where = "(" . $like . " )";
            $this->db->where($where);
        }
        if (isset($_POST['order']) && $column_order[$_POST['order']['0']['column']] != null) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // if ($_POST['length'] != -1)
        //     $this->db->limit($_POST['length'], $_POST['start']);


        $query = $this->db->get($this->balance_sheet . ' AS tab_1');

        return $query->result();
    }

    public function count_all_balance()
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->where('client_id', $client_id);
        $this->db->from($this->balance_sheet);
        return $this->db->count_all_results();
    }

    public function count_filtered_balance($search_data)
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.prefix,tab_2.opening_balance,tab_1.*,tab_3.sub_category,tab_4.category,tab_4.comments,tab_5.inv_id,tab_6.po_no,tab_7.store_name,tab_8.store_name as cust_name');
        $this->db->join($this->erp_manage_firms . ' AS tab_2', 'tab_2.firm_id = tab_1.firm_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        $this->db->join($this->erp_invoice . ' AS tab_5', 'tab_5.id = tab_1.inv_id', 'LEFT');
        $this->db->join($this->erp_pr . ' AS tab_6', 'tab_6.id = tab_1.pr_id', 'LEFT');
        $this->db->join($this->vendor . ' AS tab_7', 'tab_7.id = tab_6.supplier', 'LEFT');
        $this->db->join($this->customer . ' AS tab_8', 'tab_8.id = tab_5.customer', 'LEFT');
        $this->db->where('tab_1.client_id', $client_id);
        if (isset($search_data) && !empty($search_data)) {
            if (!empty($search_data['from_date']))
                $search_data['from_date'] = date('Y-m-d', strtotime($search_data['from_date']));
            if (!empty($search_data['to_date']))
                $search_data['to_date'] = date('Y-m-d', strtotime($search_data['to_date']));
            if ($search_data['from_date'] == '1970-01-01')
                $search_data['from_date'] = '';
            if ($search_data['to_date'] == '1970-01-01')
                $search_data['to_date'] = '';
            if (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "' AND DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] == "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            }
            if (!empty($search_data['firm_id']) && $search_data['firm_id'] != 'Select') {

                $this->db->where('tab_1.firm_id', $search_data['firm_id']);
            }
        }

        $column_search = array('tab_1.id', 'tab_2.prefix', 'tab_1.type', 'tab_4.category', 'tab_3.sub_category', 'tab_1.mode', 'tab_2.company_amount', 'tab_1.amount', 'tab_1.amount', 'tab_1.balance', 'tab_1.created_at');

        $i = 0;
        foreach ($column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $like = "" . $item . " LIKE '%" . $_POST['search']['value'] . "%'";
                    //$this->db->like($item, $_POST['search']['value']);
                } else {
                    //$query = $this->db->or_like($item, $_POST['search']['value']);
                    $like .= " OR " . $item . " LIKE '%" . $_POST['search']['value'] . "%'" . "";
                }
            }
            $i++;
        }
        if ($like) {
            $where = "(" . $like . " )";
            $this->db->where($where);
        }
        if (isset($_POST['order']) && $column_order[$_POST['order']['0']['column']] != null) { // here order processing
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        $query = $this->db->get($this->balance_sheet . ' AS tab_1');

        return $query->num_rows();
    }

    public function get_expenses_datas($search_data)
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.prefix,tab_1.*,tab_3.sub_category,tab_4.category');
        $this->db->join($this->erp_manage_firms . ' AS tab_2', 'tab_2.firm_id = tab_1.firm_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        $this->db->order_by('tab_1.id', 'desc');
        if (isset($search_data) && !empty($search_data)) {
            if (!empty($search_data['from_date']))
                $search_data['from_date'] = date('Y-m-d', strtotime($search_data['from_date']));
            if (!empty($search_data['to_date']))
                $search_data['to_date'] = date('Y-m-d', strtotime($search_data['to_date']));
            if ($search_data['from_date'] == '1970-01-01')
                $search_data['from_date'] = '';
            if ($search_data['to_date'] == '1970-01-01')
                $search_data['to_date'] = '';
            if (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "' AND DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] == "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            }
            if (!empty($search_data['firm_id']) && $search_data['firm_id'] != 'Select') {

                $this->db->where('tab_1.firm_id', $search_data['firm_id']);
            }
            if (!empty($search_data['cat_id']) && $search_data['cat_id'] != 'Select') {

                $this->db->where('tab_1.cat_id', $search_data['cat_id']);
            }
            if (!empty($search_data['sub_cat_id']) && $search_data['sub_cat_id'] != 'Select') {

                $this->db->where('tab_1.sub_cat_id', $search_data['sub_cat_id']);
            }
        }
        $this->db->where('tab_1.client_id', $client_id);
        $query = $this->db->get($this->expense . ' AS tab_1');
        return $query->result_array();
    }

    public function get_balance_datas($search_data)
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.prefix,tab_2.opening_balance,tab_1.*,tab_3.sub_category,tab_4.category,tab_4.comments,tab_5.inv_id,tab_6.po_no,tab_7.store_name,tab_8.store_name as cust_name');
        $this->db->join($this->erp_manage_firms . ' AS tab_2', 'tab_2.firm_id = tab_1.firm_id', 'LEFT');
        $this->db->join($this->manage_sub_category . ' AS tab_3', 'tab_3.id = tab_1.sub_cat_id', 'LEFT');
        $this->db->join($this->manage_category . ' AS tab_4', 'tab_4.id = tab_1.cat_id', 'LEFT');
        $this->db->join($this->erp_invoice . ' AS tab_5', 'tab_5.id = tab_1.inv_id', 'LEFT');
        $this->db->join($this->erp_pr . ' AS tab_6', 'tab_6.id = tab_1.pr_id', 'LEFT');
        $this->db->join($this->vendor . ' AS tab_7', 'tab_7.id = tab_6.supplier', 'LEFT');
        $this->db->join($this->customer . ' AS tab_8', 'tab_8.id = tab_5.customer', 'LEFT');
        $this->db->order_by('tab_1.id', 'desc');
        $this->db->where('tab_1.client_id', $client_id);
        if (isset($search_data) && !empty($search_data)) {
            if (!empty($search_data['from_date']))
                $search_data['from_date'] = date('Y-m-d', strtotime($search_data['from_date']));
            if (!empty($search_data['to_date']))
                $search_data['to_date'] = date('Y-m-d', strtotime($search_data['to_date']));
            if ($search_data['from_date'] == '1970-01-01')
                $search_data['from_date'] = '';
            if ($search_data['to_date'] == '1970-01-01')
                $search_data['to_date'] = '';
            if (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "' AND DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $search_data["to_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] != "" && isset($search_data["to_date"]) && $search_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') >='" . $search_data["from_date"] . "'");
            } elseif (isset($search_data["from_date"]) && $search_data["from_date"] == "" && isset($search_data["to_date"]) && $search_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(tab_1.created_at,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            }
            if (!empty($search_data['firm_id']) && $search_data['firm_id'] != 'Select') {

                $this->db->where('tab_1.firm_id', $search_data['firm_id']);
            }
        }

        $query = $this->db->get($this->balance_sheet . ' AS tab_1');
        return $query->result_array();
    }

    function get_balance_datas_by_inv_id($inv_id)
    {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('SUM(amount) AS amount,firm_id');
        $this->db->where('inv_id', $inv_id);
        $this->db->where('client_id', $client_id);
        $query = $this->db->get('balance_sheet')->result_array();
        return $query;
    }

    function delete_balance_data_by_inv($inv_id)
    {
        $this->db->where('inv_id', $inv_id);
        $this->db->delete($this->balance_sheet);
    }
    //Get all sub & categories
    public function get_all_subcategory_list() {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select('tab_2.category,tab_1.*');
        $this->db->join($this->manage_category . ' AS tab_2', 'tab_2.id = tab_1.category_id', 'LEFT');
        if($this->session->userdata('UserRole') == 2){
            $this->db->where('tab_2.client_id', $client_id);
        }
        $query = $this->db->get($this->manage_sub_category . ' AS tab_1')->result_array();
        return $query;
    }

    public function get_all_category_list() {
        $client_id = $this->session->userdata('LoggedId');
        $this->db->select($this->manage_category . '.*');
        $this->db->where($this->manage_category . '.status', 1);
        if($this->session->userdata('UserRole') == 2){
            $this->db->where($this->manage_category . '.client_id', $client_id);
        }
        $query = $this->db->get($this->manage_category)->result_array();
        return $query;
    }
}
