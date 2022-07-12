<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_receipt_model extends CI_Model {

    private $table_name2 = 'cic_receipt_bill';
    private $cic_cih = 'cic_cash_in_hand_details';
    private $cic_sales_order = 'cic_sales_order';
    private $cic_customer = 'cic_customer';
    private $cic_increment_order = 'cic_increment_order';
    private $user_firms = 'erp_user_firms';
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

        $this->db->order_by('cic_sales_order.vSalesOrderNo', 'desc');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $query = $this->db->get('cic_sales_order')->result_array();

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
        // echo $this->db->last_query();exit;
        $i = 0;
        foreach ($query as $val) {
//            $this->db->select('SUM(return_amount) as return_amt');
//            $this->db->where('sales_return_details.inv_id', $val['id']);
//            $query[$i]['return_amount'] = $this->db->get('sales_return_details')->result_array();


            $this->db->select('SUM(discount) AS receipt_discount,SUM(bill_amount) AS receipt_paid,MAX(due_date) AS next_date,MAX(created_date) AS paid_date,SUM(expense) AS exp_amt');
            $this->db->where('cic_receipt_bill.receipt_id', $val['iSalesOrderId']);
            $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
            $i++;
        }

        return $query;
    }
    //change formate to yyyy-mm-dd
    public function change_date_formate($date) {
        $d = explode('/', $date);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
    }
    function _get_datatables_query($serch_data = array()) {
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
        $this->db->select('cic_sales_order.*,cic_receipt_bill.discount,cic_receipt_bill.bill_amount,cic_receipt_bill.due_date,cic_receipt_bill.created_date');
        $this->db->select('cic_customer.vCustomerName,cic_customer.vPhoneNumber');

        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->where('cic_sales_order.eDeliveryStatus', 'Delivered');
        $this->db->join('cic_receipt_bill', 'cic_receipt_bill.receipt_id=cic_sales_order.iSalesOrderId', 'left');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        // if(!empty($payment_method)){
        // // $this->db->select('cic_cash_in_hand_details.iSalesOrderId AS CashSalesId');
        // $this->db->where('cic_sales_order.vPayemntMethod', $payment_method);
        // $this->db->where('cic_sales_order.vPayemntStatus', 'FAILED');
        // $this->db->join('cic_cash_in_hand_details', 'cic_cash_in_hand_details.iSalesmanId=cic_sales_order.iSalesmanId', 'left');
        // // $this->db->where_in('cic_sales_order.iSalesOrderId','cic_cash_in_hand_details.iSalesOrderId');
        // $this->db->where_not_in('cic_sales_order.iSalesOrderId','select iSalesOrderId from cic_cash_in_hand_details where iSalesmanId=cic_sales_order.iSalesmanId',false);
        // // $this->db->where_not_in('cic_sales_order.iSalesOrderId','cic_cash_in_hand_details.iSalesOrderId',false);
        // if($serch_data['salesman'] != null){
        //         $this->db->where('cic_cash_in_hand_details.iSalesmanId',$serch_data['salesman']);
        //             // $this->db->where_not_in('cic_sales_order.iSalesOrderId','select iSalesOrderId from cic_cash_in_hand_details where iSalesmanId='.$serch_data['salesman'].'',false);
        //         }
        //         if($serch_data['branch'] != null){
        //             $this->db->where('cic_cash_in_hand_details.iBranchId',$serch_data['branch']);
        //         }
        //     }
        if($this->session->userdata('UserRole') == 3){
            $this->db->where('cic_sales_order.iBranchId', $BranchId);
            $this->db->where('cic_sales_order.iCreatedBy', $logged_user);
            }
            if($this->session->userdata('UserRole') == 2){
            $this->db->where('cic_sales_order.iBranchId', $BranchId);
            }
            if (isset($serch_data) && !empty($serch_data)) {
                // if($serch_data['salesman'] != null){
                //     $this->db->where('cic_sales_order.iSalesmanId',$serch_data['salesman']);
                //     // $this->db->where_not_in('cic_sales_order.iSalesOrderId','select iSalesOrderId from cic_cash_in_hand_details where iSalesmanId='.$serch_data['salesman'].'',false);
                // }
                // if($serch_data['branch'] != null){
                //     $this->db->where('cic_sales_order.iBranchId',$serch_data['branch']);
                // }
            $from_date = $this->change_date_formate($serch_data['from_date']);
            $to_date = $this->change_date_formate($serch_data['to_date']);
                if ($serch_data['from_date'] != "" && $serch_data['to_date'] != "") {
                    $this->db->where("cic_receipt_bill.created_date >='" . $from_date . "' AND cic_receipt_bill.created_date <='" . $to_date . "'");
                } elseif ($serch_data['from_date'] !="" && $to_date == "") {
                    $this->db->where("cic_receipt_bill.created_date >='" . $from_date . "'");
                } elseif ($serch_data['from_date'] == "" && $serch_data['to_date'] != "") {
                    $this->db->where("cic_receipt_bill.created_date <='" . $to_date . "'");
                }
            }    
            $this->db->group_by('cic_sales_order.iSalesOrderId');
        $column_order = array(null, 'cic_sales_order.vSalesOrderNo', 'cic_customer.vCustomerName', 'cic_sales_order.fNetCost', 'cic_receipt_bill.bill_amount', 'cic_receipt_bill.discount', 'cic_sales_order.balance', 'cic_sales_order.dCreatedDate', 'cic_receipt_bill.created_date', 'cic_receipt_bill.due_date', 'cic_sales_order.vPayemntStatus', null);
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

    function count_filtered($search_data) {
        $this->_get_datatables_query($search_data);
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
        $this->db->order_by('cic_sales_order.vSalesOrderNo', 'desc');
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
        $this->db->select('cic_sales_order.vSalesOrderNo,cic_sales_order.vSalesOrderNo,cic_sales_order.iCustomerId,cic_sales_order.fNetCost');
        $this->db->select('cic_customer.vCustomerName');
        $this->db->order_by('cic_sales_order.vSalesOrderNo', 'desc');
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

        $this->db->order_by('cic_sales_order.vSalesOrderNo', 'desc');
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
        $this->db->order_by('cic_sales_order.vSalesOrderNo', 'desc');
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
        $this->db->where_in('iSalesOrderId', $data);
        if ($this->db->update('cic_sales_order', array('receipt_status' => 1))) {

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

        $this->db->select('cic_customer.vCustomerName, cic_customer.vEmail, cic_customer.vPhoneNumber,cic_customer.vAddress,SUM(rb.discount) as total_disc');
        $this->db->join('cic_receipt_bill as rb','cic_sales_order.iSalesOrderId=rb.receipt_id');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId = cic_sales_order.iCustomerId');

        $query = $this->db->get('cic_sales_order')->result_array();
        $k = 0;
        foreach ($query as $val) {
            $this->db->where('cic_sales_order_details.iSalesOrderId', $id);
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

        $this->db->select('SUM(discount) as sum_discount');
        $this->db->where('receipt_id',$id);
        $this->db->where('id <=', $rec_id);
        $query['sum_discount_amount'] = $this->db->get('cic_receipt_bill')->row_array();
        // echo $this->db->last_query();exit;
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
        $result = $this->db->get('erp_user_firms')->result_array();
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
        $this->db->select('bill_amount');
        $this->db->where('id',$id);
        $query = $this->db->get('cic_receipt_bill')->row_array();
        
        if($query['bill_amount'] != $bill_amount){
            $this->db->select('receipt_id');
            $this->db->where('id',$id);
            $result = $this->db->get('cic_receipt_bill')->result_array();
            foreach($result as $data){
                $this->db->where('receipt_id',$data['receipt_id']);
                // $this->db->where('id !=',$id);
                $this->db->where('id >',$id);
                if($query['bill_amount'] < $bill_amount){
                    $val = $bill_amount - $query['bill_amount'];
                    $this->db->set('total_paid_amt','total_paid_amt +'.(int)$val,false);
                }
                if($query['bill_amount'] > $bill_amount){
                    $val = $query['bill_amount'] - $bill_amount;
                    $this->db->set('total_paid_amt','total_paid_amt -'.(int)$val,false);
                }
                $this->db->update('cic_receipt_bill');
            }
        }



        $insert['bill_amount'] = $bill_amount;
        $insert['total_paid_amt'] = $bill_amount;

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

    public function get_receipt_details($receipt_id)
    {
        $this->db->select('total_paid_amt');
        $this->db->where('receipt_id',$receipt_id);
        $this->db->order_by('id','desc');
        $query = $this->db->get('cic_receipt_bill')->row_array();
        return $query['total_paid_amt'];
    }

    public function get_discount($id)
    {
        $this->db->select('SUM(discount) AS receipt_discount,SUM(bill_amount) as total_bill_amt,SUM(expense) as receipt_expense');
        $this->db->where('receipt_id', $id);
        return $this->db->get('cic_receipt_bill')->row_array();
    }

    // Insert Cashin hand Info
    public function insert_cash_in_hand_details($data) {
        if ($this->db->insert($this->cic_cih, $data)) {
            $insert_id = $this->db->insert_id();

            return $insert_id;
        }
        return false;
    }

    // Cash Handover View
    function cash_hand_over_list($search_data) {
        $this->cash_hand_over_list_query($search_data);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get('cic_cash_in_hand_details AS ch')->result_array();
        // echo $this->db->last_query();exit;
        return $query;
    }
    // Cash Handover View Query
    function cash_hand_over_list_query($serch_data = array()) {
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
        $this->db->select('ch.iReceiptId,ch.iSalesmanId,SUM(ch.fNetCost) AS tot_cost,SUM(ch.fAdditionalCharge) AS tot_add,SUM(ch.iTotalPaidAmount) AS tot_paid,ch.tRemarks,ch.dPaidDate,user.vName,br.vBranchName');
        $this->db->join('cic_master_branch AS br', 'br.iBranchId=ch.iBranchId', 'left');
        $this->db->join('cic_master_users AS user', 'user.iUserId=ch.iSalesmanId');
        // $this->db->where('so.estatus', 'Active');
        if($this->session->userdata('UserRole') == 3){
            $this->db->where('br.iBranchId', $BranchId);
            $this->db->where('user.iUserId', $logged_user);
            }
            if($this->session->userdata('UserRole') == 2){
            $this->db->where('br.iBranchId', $BranchId);
            }
            if (isset($serch_data) && !empty($serch_data)) {
            if($serch_data['salesman'] != null){
                $this->db->where('ch.iSalesmanId',$serch_data['salesman']);
            }
            if($serch_data['branch'] != null){
                $this->db->where('ch.iBranchId',$serch_data['branch']);
            }
            $from_date = $this->change_date_formate($serch_data['from_date']);
            $to_date = $this->change_date_formate($serch_data['to_date']);
                if ($serch_data['from_date'] != "" && $serch_data['to_date'] != "") {
                    $this->db->where("ch.dPaidDate >='" . $from_date . "' AND ch.dPaidDate <='" . $to_date . "'");
                } elseif ($serch_data['from_date'] !="" && $to_date == "") {
                    $this->db->where("ch.dPaidDate >='" . $from_date . "'");
                } elseif ($serch_data['from_date'] == "" && $serch_data['to_date'] != "") {
                    $this->db->where("ch.dPaidDate <='" . $to_date . "'");
                }
            }    
        $this->db->group_by('ch.iSalesmanId');
        $column_order = array(null, 'br.vBranchName', 'user.vName', 'ch.fNetCost', 'ch.fAdditionalCharge', 'ch.iTotalPaidAmount', 'ch.tRemarks', 'ch.dPaidDate', null);
        $column_search = array('br.vBranchName', 'user.vName', 'ch.fNetCost');
        $order = array('ch.iCashHandId' => 'DESC');
        $i = 0;
        foreach ($column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $like = "" . $item . " LIKE '%" . $_POST['search']['value'] . "%'";
                } else {
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

    function cash_hand_over_count_all() {
        $this->db->join('cic_master_users AS user', 'user.iUserId=ch.iSalesmanId');
        $this->db->from('cic_cash_in_hand_details AS ch');
        return $this->db->get()->num_rows();
    }

    function cash_hand_over_count_filtered($search_data) {
        $this->cash_hand_over_list_query($search_data);
        $query = $this->db->get('cic_cash_in_hand_details AS ch');
        return $query->num_rows();
    }
    // Get Cash Hand info By ID
    public function get_cash_hand_info_id($id,$form,$to){
        $from_date = $this->change_date_formate($form);
        $to_date = $this->change_date_formate($to);
        $this->db->select('so.vSalesOrderNo,rp.id,rp.bill_amount,rp.expense,rp.receipt_no,rp.receipt_id,ch.iCashHandId,ch.iReceiptId,ch.iSalesmanId,ch.fNetCost,ch.fAdditionalCharge,ch.iTotalPaidAmount,ch.tRemarks,br.vBranchName,ch.dPaidDate,user.vName,cus.vCustomerName');
        $this->db->where('ch.iSalesmanId',$id);
        if ($form != "" && $to != "") {
            $this->db->where("ch.dPaidDate >='" . $from_date . "' AND ch.dPaidDate <='" . $to_date . "'");
        } elseif ($form !="" && $to == "") {
            $this->db->where("ch.dPaidDate >='" . $from_date . "'");
        } elseif ($form == "" && $to != "") {
            $this->db->where("ch.dPaidDate <='" . $to_date . "'");
        }
        $this->db->join('cic_master_branch AS br', 'br.iBranchId=ch.iBranchId', 'left');
        $this->db->join('cic_master_users AS user', 'user.iUserId=ch.iSalesmanId');
        $this->db->join('cic_receipt_bill AS rp','find_in_set(rp.id,ch.iReceiptId)<> 0','left',false);
        $this->db->join('cic_sales_order AS so', 'so.iSalesOrderId=rp.receipt_id', 'left');
        $this->db->join('cic_customer AS cus', 'cus.iCustomerId=so.iCustomerId', 'left');
        // $this->db->group_by('so.vSalesOrderNo');
        $this->db->order_by('ch.iCashHandId','desc');
        $this->db->from('cic_cash_in_hand_details AS ch');
        $query['cash_details'] = $this->db->get()->result_array();
        return $query;
    }
    // Get Receipt cash
    function get_datatables_receipt_cash($search_data) {
        $this->_get_datatables_query_receipt_cash($search_data);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get('cic_receipt_bill')->result_array();
        return $query;
    }

    // Get Reciept Cash Query
    function _get_datatables_query_receipt_cash($serch_data = array()) {
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
        $this->db->select('cic_receipt_bill.id,cic_receipt_bill.receipt_id,cic_receipt_bill.expense,cic_receipt_bill.receipt_no,cic_receipt_bill.discount AS receipt_discount,cic_receipt_bill.bill_amount AS receipt_paid,cic_receipt_bill.created_date AS paid_date,cic_sales_order.vSalesOrderNo,cic_sales_order.iSalesOrderId,cic_sales_order.fNetCost,cic_sales_order.fAdditionalCharge,cic_sales_order.iSalesmanId,cic_sales_order.vPayemntStatus');
        $this->db->select('cic_customer.vCustomerName,cic_customer.vPhoneNumber');
        $this->db->join('cic_sales_order', 'cic_sales_order.iSalesOrderId=cic_receipt_bill.receipt_id', 'left');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $this->db->where('cic_sales_order.eDeliveryStatus', 'Delivered');
        $this->db->where('cic_receipt_bill.status', 1);
        if($this->session->userdata('UserRole') == 3){
            $this->db->where('cic_sales_order.iBranchId', $BranchId);
            $this->db->where('cic_sales_order.iSalesmanId', $logged_user);
            }
            if($this->session->userdata('UserRole') == 2){
            $this->db->where('cic_sales_order.iBranchId', $BranchId);
            }
            if (isset($serch_data) && !empty($serch_data)) {
                if($serch_data['salesman'] != null){
                    $this->db->where('cic_sales_order.iSalesmanId',$serch_data['salesman']);
                    }
                    if($serch_data['branch'] != null){
                        $this->db->where('cic_sales_order.iBranchId',$serch_data['branch']);
                    }
            $from_date = $this->change_date_formate($serch_data['from_date']);
            $to_date = $this->change_date_formate($serch_data['to_date']);
                if ($serch_data['from_date'] != "" && $serch_data['to_date'] != "") {
                    $this->db->where("cic_receipt_bill.created_date >='" . $from_date . "' AND cic_receipt_bill.created_date <='" . $to_date . "'");
                } elseif ($serch_data['from_date'] !="" && $to_date == "") {
                    $this->db->where("cic_receipt_bill.created_date >='" . $from_date . "'");
                } elseif ($serch_data['from_date'] == "" && $serch_data['to_date'] != "") {
                    $this->db->where("cic_receipt_bill.created_date <='" . $to_date . "'");
                }
            }    
        $column_order = array(null, 'cic_receipt_bill.receipt_no', 'cic_customer.vCustomerName', 'cic_sales_order.fNetCost', 'receipt_paid', 'cic_receipt_bill.expense', 'cic_receipt_bill .created_date', 'cic_sales_order.vPayemntStatus');
        $column_search = array('cic_receipt_bill.receipt_no', 'cic_customer.vCustomerName', 'cic_sales_order.vPayemntStatus');
        $order = array('cic_receipt_bill.receipt_id' => 'DESC');
        $i = 0;
        foreach ($column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $like = "" . $item . " LIKE '%" . $_POST['search']['value'] . "%'";
                } else {
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
    function count_all_reciept_cash() {
        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->join('cic_sales_order', 'cic_sales_order.iSalesOrderId=cic_receipt_bill.receipt_id');
        $this->db->from('cic_receipt_bill');
        return $this->db->get()->num_rows();
    }

    function count_filtered_reciept_cash($search_data) {
        $this->_get_datatables_query_receipt_cash($search_data);
        $query = $this->db->get('cic_receipt_bill');
        return $query->num_rows();
    }

    // Update Receipt Status
    public function update_receipt_status($id) {
        $this->db->where_in('id',$id,false);
        $this->db->set('status',0);
        $query = $this->db->update('cic_receipt_bill');
        if($query){
            return true;
        }
        return false;
    }

}
