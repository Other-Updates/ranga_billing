<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_report_model extends MY_Controller {
    private $table = 'sales_order_details';
    private $column_order = array('cic_sales_order.vSalesOrderNo','cu.vCustomerName','ho.vHeadOfficeName','br.vBranchName','cic_sales_order.fNetQty','cic_sales_order.CGST','cic_sales_order.SGST','cic_sales_order.fNetCostwithoutGST','cic_sales_order.fNetCost','receipt_paid','receipt_discount','sortbalance','cic_sales_order.vPayemntStatus','cic_sales_order.dOrderedDate','cic_sales_order.dCreatedDate','cic_sales_order.eDeliveryStatus'); //set column field database for datatable orderable
    private $column_search = array('cic_sales_order.vSalesOrderNo','cu.vCustomerName','ho.vHeadOfficeName','br.vBranchName','cic_sales_order.fNetQty','cic_sales_order.CGST','cic_sales_order.SGST','cic_sales_order.fNetCostwithoutGST','cic_sales_order.fNetCost','cic_sales_order.vPayemntStatus','cic_sales_order.dOrderedDate','cic_sales_order.dCreatedDate','cic_sales_order.eDeliveryStatus'); //set column field database for datatable searchable 
    private $order = array('vSalesOrderNo' => 'DESC'); // default descending order
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    private function list_data($data = null) {     
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
        $from_date = $this->change_date_formate($data['from_date']);
        $to_date = $this->change_date_formate($data['to_date']);
        $this->db->select('cic_sales_order.*,SUM(cic_receipt_bill.discount) AS receipt_discount,SUM(cic_receipt_bill.bill_amount) AS receipt_paid,SUM(cic_sales_order.fNetCost)-SUM(cic_receipt_bill.bill_amount)+SUM(cic_receipt_bill.discount) AS sortbalance,cu.vCustomerName,br.vBranchName,ho.vHeadOfficeName');
        $this->db->where('cic_sales_order.estatus', 'Active');
        // $this->db->where('cic_sales_order.eDeliveryStatus', 'Delivered');
        $this->db->join('cic_receipt_bill', 'cic_receipt_bill.receipt_id=cic_sales_order.iSalesOrderId', 'left');
        $this->db->join('cic_products as pr','pr.iProductId=cic_sales_order.iProductId','left');
        $this->db->join('cic_customer as cu','cu.iCustomerId=cic_sales_order.iCustomerId');
        $this->db->join('cic_master_headoffice as ho','ho.iHeadOfficeId=cic_sales_order.iHeadOfficeId');
        $this->db->join('cic_master_branch as br','br.iBranchId=cic_sales_order.iBranchId');
        if($this->session->userdata('UserRole') == 3){
            $this->db->where('cic_sales_order.iBranchId', $BranchId);
            $this->db->where('cic_sales_order.iCreatedBy', $logged_user);
            }
            if($this->session->userdata('UserRole') == 2){
            $this->db->where('cic_sales_order.iBranchId', $BranchId);
            }
        if($data['salesman'] != null){
            // $this->db->where_in('so.iSalesmanId',$data['salesman']);
            $this->db->where_in('cic_sales_order.iCustomerId','(select iCustomerId from cic_customer where iSalesmanId='.$data["salesman"].')',false);
        }   
        if($data['distributor'] != null)
        $this->db->where('cic_sales_order.iCustomerId',$data['distributor']);
        if (isset($data["from_date"]) && $data['from_date'] != "" && isset($data["to_date"]) && $data['to_date'] != "") {
            $this->db->where("cic_sales_order.dOrderedDate >='" . $from_date . "' AND cic_sales_order.dOrderedDate <='" . $to_date . "'");
        } elseif (isset($data["from_date"]) && $data['from_date'] !="" && isset($data["to_date"]) && $to_date == "") {
            $this->db->where("cic_sales_order.dOrderedDate >='" . $from_date . "'");
        } elseif (isset($data["from_date"]) && $data['from_date'] == "" && isset($data["to_date"]) && $data['to_date'] != "") {
            $this->db->where("cic_sales_order.dOrderedDate <='" . $to_date . "'");
        }

        if($data['payment_status'] != null){
            if($data['payment_status']=='paid')
            $this->db->where('cic_sales_order.vPayemntStatus',"SUCCESS");
            else
            $this->db->where('cic_sales_order.vPayemntStatus',"FAILED");
        }
        $this->db->group_by('cic_sales_order.iSalesOrderId');
            if($data['status'] != "")
            $this->db->where('cic_sales_order.eDeliveryStatus',$data['status']);
        else
        $this->db->where('cic_sales_order.eDeliveryStatus!=','Cancelled');
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

    //change formate to yyyy-mm-dd
    public function change_date_formate($date) {
        $d = explode('/', $date);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
    }

    public function reference_list($data=null){
        $this->list_data($data);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get('cic_sales_order')->result_array();
        // $i = 0;
        // foreach ($query as $val) {
        //     $this->db->select('SUM(discount) AS receipt_discount,SUM(bill_amount) AS receipt_paid,MAX(due_date) AS next_date,MAX(created_date) AS paid_date');
        //     $this->db->where('cic_receipt_bill.receipt_id', $val['iSalesOrderId']);
        //     $query[$i]['receipt_bill'] = $this->db->get('cic_receipt_bill')->result_array();
        //     $i++;
        // }

        return $query;
    }

    function count_all($data=null) {
        $this->db->where('cic_sales_order.estatus', 'Active');
        $this->db->join('cic_customer', 'cic_customer.iCustomerId=cic_sales_order.iCustomerId');
        $this->db->from('cic_sales_order');
        return $this->db->get()->num_rows();
    }

    function count_filtered($data=null) {
        $this->list_data($data);
        $query = $this->db->get('cic_sales_order');
        return $query->num_rows();
    }

    public function get_distributor_list($branch_id){
        $this->db->select('*');
        if(!empty($branch_id))
        $this->db->where('iBranchId', $branch_id);
        $this->db->from('cic_customer');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }

    public function get_salesman_list(){
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
        $this->db->select('*');
        // $this->db->where('iUserRoleId',2);
        if(!empty($BranchId)){
            $this->db->where('iBranchId', $BranchId);
            $this->db->where('iUserRoleId', '3');
            }
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }

    // Get GST Report
    public function get_all_sales_gst_reports($data){
        $from_date = $this->change_date_formate($data['from_date']);
        $to_date = $this->change_date_formate($data['to_date']);
        if($data['type']=="summary"){
        $this->db->select('s.*,b.vBranchName,h.vHeadOfficeName,c.vCustomerName,c.vGSTINNo as gst_no,c.vPhoneNumber,ste.iStateId,ste.vStateName');
        // $this->db->select('s.*,b.vBranchName,h.vHeadOfficeName,SUM(cr.discount) AS receipt_discount,c.vCustomerName,c.vGSTINNo as gst_no,c.vPhoneNumber,ste.vStateName');
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=s.iHeadOfficeId','left');
        // $this->db->join('cic_receipt_bill as cr', 'cr.receipt_id=s.iSalesOrderId', 'left');
        $this->db->join('cic_master_branch as b','b.iBranchId=s.iBranchId','left');
        $this->db->join('cic_customer as c','c.iCustomerId=s.iCustomerId','left');
        $this->db->join('cic_master_state as ste','ste.iStateId=c.iStateId','left');
        if (isset($data["from_date"]) && $data['from_date'] != "" && isset($data["to_date"]) && $data['to_date'] != "") {
            $this->db->where("s.dOrderedDate >='" . $from_date . "' AND s.dOrderedDate <='" . $to_date . "'");
        } elseif (isset($data["from_date"]) && $data['from_date'] !="" && isset($data["to_date"]) && $to_date == "") {
            $this->db->where("s.dOrderedDate >='" . $from_date . "'");
        } elseif (isset($data["from_date"]) && $data['from_date'] == "" && isset($data["to_date"]) && $data['to_date'] != "") {
            $this->db->where("s.dOrderedDate <='" . $to_date . "'");
        }
        if($data['salesman'] != null){
            // $this->db->where_in('so.iSalesmanId',$data['salesman']);
            $this->db->where_in('s.iCustomerId','(select iCustomerId from cic_customer where iSalesmanId='.$data["salesman"].')',false);
        }   
        if($data['distributor'] != null)
        $this->db->where('s.iCustomerId',$data['distributor']);
        if($data['payment_status'] != null){
            if($data['payment_status']=='paid')
            $this->db->where('s.vPayemntStatus',"SUCCESS");
            else
            $this->db->where('s.vPayemntStatus',"FAILED");
        }
        if($data['status'] != "")
            $this->db->where('s.eDeliveryStatus',$data['status']);
        $this->db->where('s.eDeliveryStatus!=','Cancelled');
        $this->db->where('s.eStatus','Active');
        $this->db->order_by("s.vSalesOrderNo", "desc");
        $this->db->from('cic_sales_order as s');
        $data = $this->db->get()->result_array();
        }
        else{
        $this->db->select('sod.*,s.iProductUnitId,s.iSalesOrderId,s.iDeliveryQTY,s.iDeliveryCostperQTY,s.iDeliverySubTotal,s.CGST AS cgst,s.SGST AS sgst,s.IGST AS igst,pro.vProductName,pro.vHSNNO,b.vBranchName,unit.vProductUnitName,h.vHeadOfficeName,c.vCustomerName,c.vGSTINNo as gst_no,c.vPhoneNumber,ste.iStateId,ste.vStateName');
        $this->db->join('cic_sales_order_details as s','sod.iSalesOrderId=s.iSalesOrderId','left');
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=sod.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as b','b.iBranchId=sod.iBranchId','left');
        $this->db->join('cic_customer as c','c.iCustomerId=sod.iCustomerId','left');
        $this->db->join('cic_product_unit as unit','unit.iProductUnitId=sod.iProductUnitId','left');
        $this->db->join('cic_products as pro','pro.iProductId=s.iProductId','left');
        $this->db->join('cic_master_state as ste','ste.iStateId=c.iStateId','left');
        if (isset($data["from_date"]) && $data['from_date'] != "" && isset($data["to_date"]) && $data['to_date'] != "") {
            $this->db->where("sod.dOrderedDate >='" . $from_date . "' AND sod.dOrderedDate <='" . $to_date . "'");
        } elseif (isset($data["from_date"]) && $data['from_date'] !="" && isset($data["to_date"]) && $to_date == "") {
            $this->db->where("sod.dOrderedDate >='" . $from_date . "'");
        } elseif (isset($data["from_date"]) && $data['from_date'] == "" && isset($data["to_date"]) && $data['to_date'] != "") {
            $this->db->where("sod.dOrderedDate <='" . $to_date . "'");
        }
        if($data['salesman'] != null){
            // $this->db->where_in('so.iSalesmanId',$data['salesman']);
            $this->db->where_in('sod.iCustomerId','(select iCustomerId from cic_customer where iSalesmanId='.$data["salesman"].')',false);
        }   
        if($data['distributor'] != null)
        $this->db->where('sod.iCustomerId',$data['distributor']);
        if($data['payment_status'] != null){
            if($data['payment_status']=='paid')
            $this->db->where('sod.vPayemntStatus',"SUCCESS");
            else
            $this->db->where('sod.vPayemntStatus',"FAILED");
        }
        if($data['status'] != "")
            $this->db->where('sod.eDeliveryStatus',$data['status']);
        $this->db->where('sod.eDeliveryStatus!=','Cancelled');
        $this->db->where('sod.eStatus','Active');
        $this->db->order_by("sod.vSalesOrderNo", "desc");
        $this->db->from('cic_sales_order as sod');
        $data = $this->db->get()->result_array();
        }
        return $data;
    }

}
