<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xray_report_model extends MY_Controller {
    private $table = 'cic_sales_order_details';
    private $column_order = array('so.vSalesOrderNo','cu.vCustomerName','br.vBranchName','pr.vProductName','sod.iDeliveryQTY','pru.vProductUnitName','so.dOrderedDate','so.dCreatedDate','so.eDeliveryStatus'); //set column field database for datatable orderable
    private $column_search = array('so.vSalesOrderNo','cu.vCustomerName','br.vBranchName','pr.vProductName','sod.iDeliveryQTY','pru.vProductUnitName','so.dOrderedDate','so.dCreatedDate','so.eDeliveryStatus'); //set column field database for datatable searchable 
    private $order = array('iSalesOrderId' => 'desc'); // default descending order
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    private function list_data($data = null) {       
        $from_date = $this->change_date_formate($data['from_date']);
        $to_date = $this->change_date_formate($data['to_date']);

        $this->db->select('so.iSalesOrderId,so.vSalesOrderNo,so.iBranchId,so.iSalesmanId,so.iProductId,so.iCustomerId,so.dOrderedDate,so.dCreatedDate,so.eDeliveryStatus,cu.vCustomerName,br.vBranchName,pr.vProductName,sod.iDeliveryQTY,pru.vProductUnitName');
        $this->db->join('cic_sales_order as so','so.iSalesOrderId=sod.iSalesOrderId','left');
        $this->db->join('cic_products as pr','pr.iProductId=sod.iProductId','left');
         $this->db->join('cic_product_unit as pru','pru.iProductUnitId=sod.iProductUnitId','left');
        $this->db->join('cic_customer as cu','cu.iCustomerId=so.iCustomerId');
        $this->db->join('cic_master_branch as br','br.iBranchId=so.iBranchId');
        
        if($data['salesman'] != null){
            $this->db->where_in('so.iCustomerId','(select iCustomerId from cic_customer where iSalesmanId='.$data["salesman"].')',false);
        }

        if($data['distributor'] != null)
        $this->db->where('so.iCustomerId',$data['distributor']);

        if ($data['from_date'] != "" && $data['to_date'] != "") {
            $this->db->where("so.dOrderedDate >='" . $from_date . "' AND so.dOrderedDate <='" . $to_date . "'");
        } elseif ($data['from_date'] !="" && $to_date == "") {
            $this->db->where("so.dOrderedDate >='" . $from_date . "'");
        } elseif ($data['from_date'] == "" && $data['to_date'] != "") {
            $this->db->where("so.dOrderedDate <='" . $to_date . "'");
        }

        if($data['status'] != "")
            $this->db->where('eDeliveryStatus',$data['status']);

        $this->db->from('cic_sales_order_details as sod'); 
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
        $this->db->group_by('iSalesOrderDetailsId');
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

    public function salesdetail_list($data=null){
        $this->list_data($data);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_references($data=null) {
        $this->list_data($data);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_distributor_list(){
        $this->db->select('*');
        $this->db->from('cic_customer');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }

    public function get_salesman_list(){
        $this->db->select('*');
        // $this->db->where('iUserRoleId',2);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_users');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }

}
