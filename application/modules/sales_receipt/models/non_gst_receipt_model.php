<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Non_gst_receipt_model extends CI_Model {

    private $table_name1 = 'receipt';
    private $table_name2 = 'cic_receipt_bill';
    private $cic_increment_order = 'cic_increment_order';
    private $cic_customer_amount = 'cic_customer_amount';
    private $cic_sales_order = 'cic_sales_order';
    private $cic_customer = 'cic_customer';

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function check_so_no($po) {
        $this->db->select('receipt_no');
        $this->db->where('receipt_no', $po);
        $query = $this->db->get('receipt')->result_array();
        return $query;
    }

    public function insert_receipt($data) {
        if ($this->db->insert($this->table_name1, $data)) {
            $insert_id = $this->db->insert_id();

            return $insert_id;
        }
        return false;
    }

    public function insert_agent_amount($data) {
        if ($this->db->insert($this->cic_customer_amount, $data)) {
            $insert_id = $this->db->insert_id();

            return $insert_id;
        }
        return false;
    }

    public function insert_receipt_bill($data) {
        if ($this->db->insert($this->table_name2, $data)) {
            $insert_id = $this->db->insert_id();

            return $insert_id;
        }
        return false;
    }

    public function update_increment($id, $type) {
        $this->db->where($this->cic_increment_order . '.vType', $type);
        if ($this->db->update($this->cic_increment_order, $id)) {
            return true;
        }
        return false;
    }

    public function get_all_amount($serch_data = NULL) {
        $this->db->select('cic_customer_amount.*,agent.name,cic_receipt_bill.receipt_no,cic_quotation.job_id');
        $this->db->where($this->cic_customer_amount . '.recevier', 1);
        $this->db->join('agent', 'agent.id=cic_customer_amount.recevier_id', 'left');
        $this->db->join('cic_receipt_bill', 'cic_receipt_bill.id=cic_customer_amount.receipt_id', 'left');
        $this->db->join('cic_quotation', 'cic_quotation.id=cic_customer_amount.receipt_id', 'left');
        $query = $this->db->get('cic_customer_amount')->result_array();
        return $query;
    }

    public function get_all_receipt($serch_data = NULL) {
        if (isset($serch_data) && !empty($serch_data)) {
            if (!empty($serch_data['from_date']))
                $serch_data['from_date'] = date('Y-m-d', strtotime($serch_data['from_date']));
            if (!empty($serch_data['to_date']))
                $serch_data['to_date'] = date('Y-m-d', strtotime($serch_data['to_date']));
            if ($serch_data['from_date'] == '1970-01-01')
                $serch_data['from_date'] = '';
            if ($serch_data['to_date'] == '1970-01-01')
                $serch_data['to_date'] = '';


            if (!empty($serch_data['inv_id']) && $serch_data['inv_id'] != 'Select') {

                $this->db->where($this->cic_sales_order . '.vSalesOrderNo', $serch_data['inv_id']);
            }
            if (!empty($serch_data['customer']) && $serch_data['customer'] != 'Select') {
                $this->db->where($this->cic_customer . '.id', $serch_data['customer']);
            }

            if (isset($serch_data["from_date"]) && $serch_data["from_date"] != "" && isset($serch_data["to_date"]) && $serch_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') >='" . $serch_data["from_date"] . "' AND DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            } elseif (isset($serch_data["from_date"]) && $serch_data["from_date"] != "" && isset($serch_data["to_date"]) && $serch_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') >='" . $serch_data["from_date"] . "'");
            } elseif (isset($serch_data["from_date"]) && $serch_data["from_date"] == "" && isset($serch_data["to_date"]) && $serch_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            }
        }



        $this->db->select('cic_sales_order.*');
        $this->db->select('cic_customer.vCustomerName,cic_customer.vPhoneNumber');

        $this->db->order_by('cic_sales_order.iSalesOrderId', 'desc');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');


        $query = $this->db->get('cic_sales_order')->result_array();
        //echo "<pre>";
        //print_r($query);
        //exit;
        //echo $this->db->last_query();
        //exit;

        $i = 0;
        foreach ($query as $val) {
            $this->db->select('SUM(discount) AS receipt_discount,SUM(bill_amount) AS receipt_paid,MAX(due_date) AS next_date,MAX(created_date) AS paid_date');
            $this->db->where('cic_receipt_bill.receipt_id', $val['id']);
            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }

        return $query;
    }

    function get_datatables($search_data) {
        $this->_get_datatables_query($search_data);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get('cic_sales_order')->result_array();
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('SUM(discount) AS receipt_discount,SUM(bill_amount) AS receipt_paid,MAX(due_date) AS next_date,MAX(created_date) AS paid_date');
            $this->db->where('cic_receipt_bill.receipt_id', $val['iSalesOrderId']);
            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }

        return $query;
    }

    function _get_datatables_query($search_data = array()) {

        $this->db->select('cic_sales_order.*');
        $this->db->select('cic_customer.vCustomerName,cic_customer.vPhoneNumber');

        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->where('cic_sales_order.inv_type', '2');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $column_order = array(null, 'cic_sales_order.vSalesOrderNo', 'cic_customer.vCustomerName', 'cic_sales_order.fNetCost', null, null, null, null, null, null, 'cic_sales_order.vPayemntStatus', null);
        $column_search = array('cic_sales_order.vSalesOrderNo', 'cic_customer.vCustomerName', 'cic_sales_order.fNetCost', 'cic_sales_order.vPayemntStatus');
        $order = array('cic_sales_order.iSalesOrderId' => 'DESC');
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
    }

    function count_all() {
        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $this->db->from('cic_sales_order');
        return $this->db->get()->num_rows();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get('cic_sales_order');
        return $query->num_rows();
    }

    public function get_all_cashpay_receipt($serch_data = NULL) {
        if (isset($serch_data) && !empty($serch_data)) {
            if (!empty($serch_data['from_date']))
                $serch_data['from_date'] = date('Y-m-d', strtotime($serch_data['from_date']));
            if (!empty($serch_data['to_date']))
                $serch_data['to_date'] = date('Y-m-d', strtotime($serch_data['to_date']));
            if ($serch_data['from_date'] == '1970-01-01')
                $serch_data['from_date'] = '';
            if ($serch_data['to_date'] == '1970-01-01')
                $serch_data['to_date'] = '';


            if (!empty($serch_data['inv_id']) && $serch_data['inv_id'] != 'Select') {

                $this->db->where($this->cic_sales_order . '.vSalesOrderNo', $serch_data['inv_id']);
            }
            if (!empty($serch_data['customer']) && $serch_data['customer'] != 'Select') {
                $this->db->where($this->customer . '.id', $serch_data['customer']);
            }

            if (isset($serch_data["from_date"]) && $serch_data["from_date"] != "" && isset($serch_data["to_date"]) && $serch_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') >='" . $serch_data["from_date"] . "' AND DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            } elseif (isset($serch_data["from_date"]) && $serch_data["from_date"] != "" && isset($serch_data["to_date"]) && $serch_data["to_date"] == "") {

                $this->db->where("DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') >='" . $serch_data["from_date"] . "'");
            } elseif (isset($serch_data["from_date"]) && $serch_data["from_date"] == "" && isset($serch_data["to_date"]) && $serch_data["to_date"] != "") {

                $this->db->where("DATE_FORMAT(" . $this->cic_sales_order . ".dCreatedDate,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            }
        }
        $this->db->select('cic_sales_order.*');
        $this->db->select('cic_customer.vCustomerName,cic_customer.vPhoneNumber');
        $firms = $this->user_auth->get_user_firms();
        $frim_id = array();
        foreach ($firms as $value) {
            $frim_id[] = $value['firm_id'];
        }
        $this->db->where_in('cic_sales_order.contract_customer', 0);
        $this->db->where_in('cic_sales_order.firm_id', $frim_id);
        $this->db->order_by('cic_sales_order.iSalesOrderId', 'desc');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $query = $this->db->get('cic_sales_order')->result_array();
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('SUM(discount) AS receipt_discount,SUM(bill_amount) AS receipt_paid,MAX(due_date) AS next_date');
            $this->db->where('cic_receipt_bill.receipt_id', $val['id']);
            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }
        return $query;
    }

    public function get_sales_list($values = array()) {
        $this->db->select('cic_sales_order.iSalesOrderId,cic_sales_order.inv_id,cic_sales_order.iCustomerId,cic_sales_order.firm_id,cic_sales_order.fNetCost');
        $this->db->select('cic_customer.vCustomerName');
        $this->db->order_by('cic_sales_order.iSalesOrderId', 'desc');
        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $query = $this->db->get('cic_sales_order')->result_array();
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('SUM(cic_receipt_bill.discount) AS receipt_discount,SUM(cic_receipt_bill.bill_amount) AS receipt_paid,cic_receipt_bill.due_date');
            $this->db->where('cic_receipt_bill.receipt_id', $val['id']);
            if ($values['from'] != '') {
                $this->db->where('cic_receipt_bill.due_date >=', $values['from']);
            }
            if ($values['to'] != '') {
                $this->db->where('cic_receipt_bill.due_date <=', $values['to']);
            }

            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }
        return $query;
    }

    public function get_sales_list_by_user_id($id, $values = array()) {
        $firms = $this->get_firm_by_user($id);

        $this->db->select('cic_sales_order.*');
        $this->db->select('cic_customer.vCustomerName');

        $this->db->order_by('cic_sales_order.iSalesOrderId', 'desc');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId = cic_sales_order.iCustomerId');
        $query = $this->db->get('cic_sales_order')->result_array();
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('SUM(discount) AS receipt_discount, SUM(bill_amount) AS receipt_paid, due_date');
            $this->db->where('cic_receipt_bill.receipt_id', $val['id']);
            if ($values['from'] != '') {
                $this->db->where('cic_receipt_bill.due_date >=', $values['from']);
            }
            if ($values['to'] != '') {
                $this->db->where('cic_receipt_bill.due_date <=', $values['to']);
            }
            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }
        return $query;
    }

    public function get_receipt_by_user_id($id, $serch_data = NULL) {
        $this->db->select('cic_sales_order.*');
        $this->db->select('cic_customer.vCustomerName');
        $this->db->where('cic_sales_order.firm_id', $values['firm']);
        $this->db->order_by('cic_sales_order.iSalesOrderId', 'desc');
        $this->db->group_by('cic_sales_order.iCustomerId');
        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId = cic_sales_order.iCustomerId');
        $query = $this->db->get('cic_sales_order')->result_array();
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('SUM(discount) AS receipt_discount, SUM(bill_amount) AS receipt_paid, MAX(due_date) AS next_date');
            $this->db->where('cic_receipt_bill.receipt_id', $val['id']);
            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }
        return $query;
    }

    public function get_all_receipt_cash($serch_data = NULL) {
        if (isset($serch_data) && !empty($serch_data)) {
            if (!empty($serch_data['from_date']))
                $serch_data['from_date'] = date('Y-m-d', strtotime($serch_data['from_date']));
            if (!empty($serch_data['to_date']))
                $serch_data['to_date'] = date('Y-m-d', strtotime($serch_data['to_date']));
            if ($serch_data['from_date'] == '1970-01-01')
                $serch_data['from_date'] = '';
            if ($serch_data['to_date'] == '1970-01-01')
                $serch_data['to_date'] = '';
            if (!empty($serch_data['cah_option']) && $serch_data['cah_option'] != 'Select') {
                $this->db->where('cic_customer_amount.recevier', $serch_data['cah_option']);
            }
            if (!empty($serch_data['agent']) && $serch_data['agent'] != 'Select') {
                $this->db->where('agent.id', $serch_data['agent']);
            }
            if (isset($serch_data["from_date"]) && $serch_data["from_date"] != "" && isset($serch_data["to_date"]) && $serch_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(cic_customer_amount.created_date,'%Y-%m-%d') >='" . $serch_data["from_date"] . "' AND DATE_FORMAT(cic_customer_amount.created_date,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            } elseif (isset($serch_data["from_date"]) && $serch_data["from_date"] != "" && isset($serch_data["to_date"]) && $serch_data["to_date"] == "") {
                $this->db->where("DATE_FORMAT(cic_customer_amount.created_date,'%Y-%m-%d') >='" . $serch_data["from_date"] . "'");
            } elseif (isset($serch_data["from_date"]) && $serch_data["from_date"] == "" && isset($serch_data["to_date"]) && $serch_data["to_date"] != "") {
                $this->db->where("DATE_FORMAT(cic_customer_amount.created_date,'%Y-%m-%d') <= '" . $serch_data["to_date"] . "'");
            }
        }
        $this->db->select('cic_customer_amount.*, agent.name');
        $this->db->join('agent', 'agent.id = cic_customer_amount.recevier_id', 'left');
        $query = $this->db->get('cic_customer_amount')->result_array();
        return $query;
    }

    public function get_receipt_by_id($id) {
        $this->db->select('cic_sales_order.*');
        $this->db->where('cic_sales_order.iSalesOrderId', $id);
        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->select('cic_customer.vCustomerName, cic_customer.vPhoneNumber');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId = cic_sales_order.iCustomerId');
        $query = $this->db->get('cic_sales_order')->result_array();
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('*');
            $this->db->where('cic_receipt_bill.receipt_id', $val['iSalesOrderId']);
            $query[$i]['receipt_history'] = $this->db->get('cic_receipt_bill')->result_array();
//            $this->db->select('SUM(return_amount) as return_amt');
//            $this->db->where('sales_return_details.inv_id', $val['id']);
//            $return_details = $this->db->get('sales_return_details')->result_array();

            $query[$i]['return_amount'] = $return_details[0]['return_amt'];

            $query[$i]['payable_amt'] = $val['fNetCost'] - $return_details[0]['return_amt'];
            $j = 0;
            foreach ($query[$i]['receipt_history'] as $rep) {
//                if ($rep['recevier'] != 'company') {
//                    $this->db->select('name');
//                    $this->db->where('id', $rep['recevier_id']);
//                    $recevier = $this->db->get('agent')->result_array();
//                    $query[$i]['receipt_history'][$j]['recevier'] = $recevier[0]['name'];
//                }
                $j++;
            }
            $i++;
        }
        return $query;
    }

    public function get_receipt_by_id_for_agent($data) {//echo "<pre>";
        $this->db->select('receipt.*');
        $this->db->where_in('receipt.id', $data);
        $this->db->select('cic_customer.vCustomerName, selling_percent');
        $this->db->select('agent.name as agent_name');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId = ' . $this->table_name1 . '.customer_id');
        $this->db->join('agent', 'agent.id = ' . $this->table_name1 . '.agent_id');
        $query = $this->db->get('receipt')->result_array();
        //echo "<pre>";print_r($query);

        $i = 0;
        foreach ($query as $val) {
            $this->db->select('*');
            $this->db->where('cic_receipt_bill.receipt_id', $val['id']);
            $query[$i]['receipt_history'] = $this->db->get('cic_receipt_bill')->result_array();

            $arr = explode('-', $val['inv_list']);

            $this->db->select('invoice.inv_no, invoice.id, inv_date, org_value, total_value');
            $this->db->where('customer.id', $val['customer_id']);
            $this->db->where_in('invoice.id', $arr);
            $this->db->join('package', 'package.id = invoice.package_id');
            $this->db->join('customer', 'customer.id = package.customer');
            $query[$i]['inv_details'] = $this->db->get('invoice')->result_array();



            $i++;
        }

        return $query;
    }

    public function update_invoice($data, $id) {
        $this->db->where('iSalesOrderId', $id);
        if ($this->db->update('cic_sales_order', $data)) {

            return true;
        }
        return false;
    }

    public function get_inv_details($id) {
        $this->db->select('cic_sales_order.*');
        $this->db->where('cic_sales_order.iSalesOrderId', $id);
        $query = $this->db->get('cic_sales_order');
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }

    public function update_invoice_status($data) {
        $this->db->where_in('id', $data);
        if ($this->db->update('invoice', array('receipt_status' => 1))) {

            return true;
        }
        return false;
    }

    public function update_receipt_id($no) {
        $this->db->where('vType', 'rp_code');
        if ($this->db->update('cic_increment_order', array('value' => $no))) {

            return true;
        }
        return false;
    }

    public function get_all_rp_no($data) {
        $this->db->select('receipt_no');
        $this->db->like('receipt_no', $data['q']);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($this->table_name1)->result_array();
        return $query;
    }

    function checking_payment_checkno($input) {

        $this->db->select('*');
        $this->db->where('dd_no', $input);
        $query = $this->db->get('cic_receipt_bill');

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }

    function update_checking_payment_checkno($input) {

        $this->db->select('*');
        $this->db->where('dd_no', $input);
        $query = $this->db->get('cic_receipt_bill');

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }

    public function get_receipt_download_by_id($id, $rec_id) {//echo "<pre>";
        $this->db->where('cic_sales_order.iSalesOrderId', $id);
        $this->db->select('cic_sales_order.*');

        $this->db->select('cic_customer.vCustomerName, cic_customer.vEmail, cic_customer.vPhoneNumber,cic_customer.vAddress');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId = cic_sales_order.iCustomerId');

        $query = $this->db->get('cic_sales_order')->result_array();
        $k = 0;
        foreach ($query as $val) {
            $this->db->where('cic_sales_order_details.iSalesOrderDetailsId', $id);
            $this->db->join('cic_products', 'cic_products.iProductId = cic_sales_order_details.iProductId');
            $this->db->select('cic_sales_order_details.*, cic_products.vProductName');
            $query[$k]['po_details'] = $this->db->get('cic_sales_order_details')->result_array();
            $k++;
        }
        $i = 0;
        foreach ($query as $val) {
            $this->db->select('*');
            $this->db->where('cic_receipt_bill.id', $rec_id);
            $query[$i]['receipt_history'] = $this->db->get('cic_receipt_bill')->result_array();

            $j = 0;
//            foreach ($query[$i]['receipt_history'] as $rep) {
//                if ($rep['recevier'] != 'company') {
//                    $this->db->select('name');
//                    $this->db->where('id', $rep['recevier_id']);
//                    $recevier = $this->db->get('agent')->result_array();
//                    $query[$i]['receipt_history'][$j]['recevier'] = $recevier[0]['name'];
//                }
//                $j++;
//            }
            $i++;
        }
        return $query;
    }

    public function get_company() {
        $this->db->select('*');
        $query = $this->db->get('customer')->result_array();
        return $query;
    }

    public function get_firm_by_user($id) {
        $this->db->select('firm_id');
        $this->db->where($this->user_firms . '.user_id', $id);
        $result = $this->db->get('cic_user_firms')->result_array();
        return $result;
    }

    function parse_number($number, $dec_point = null) {
        if (empty($dec_point)) {
            $locale = localeconv();
            $dec_point = $locale['decimal_point'];
        }
        return floatval(str_replace($dec_point, '.', preg_replace('/[^\d' . preg_quote($dec_point) . ']/', '', $number)));
    }

    public function update_receipt_entry($insert, $id) {

        $bill_amount = str_replace(",", "", $insert['bill_amount']);

        $insert['bill_amount'] = $bill_amount;

        $this->db->where($this->table_name2 . '.id', $id);
        if ($this->db->update($this->table_name2, $insert)) {

            return true;
        }


        return false;
    }

    function get_customer_by_id($id) {
        $this->db->select('tab_1.*');
        $this->db->where('tab_1.iCustomerId', $id);
        $query = $this->db->get($this->cic_customer . ' AS tab_1');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }

    function get_last_id($type) {
        $this->db->select('*');
        $this->db->where('vType', $type);
        $query = $this->db->get('cic_increment_order')->result_array();
        return $query;
    }

}
