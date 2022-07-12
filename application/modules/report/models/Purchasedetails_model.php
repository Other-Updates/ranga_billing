<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchasedetails_model extends MY_Controller {
    private $table = 'cic_purchase_order_details';
    private $column_order = array('po.vPurchaseOrderNo','sup.vSupplierName','pr.vProductName','pod.iPurchaseQTY','pod.iPurchaseCostperQTY','pru.vProductUnitName','po.dDeliveryDate','po.dCreatedDate','po.eDeliveryStatus'); //set column field database for datatable orderable
    private $column_search = array('po.vPurchaseOrderNo','sup.vSupplierName','pr.vProductName','pod.iPurchaseQTY','pod.iPurchaseCostperQTY','pru.vProductUnitName','po.dDeliveryDate','po.dCreatedDate','po.eDeliveryStatus'); //set column field database for datatable searchable 
    private $order = array('iPurchaseOrderId' => 'desc'); // default descending order
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //Datatable Details
    private function list_data($data = null) {       
        $from_date = $this->change_date_formate($data['from_date']);
        $to_date = $this->change_date_formate($data['to_date']);
        $this->db->select('po.iPurchaseOrderId,po.vPurchaseOrderNo,pod.iProductId,po.dDeliveryDate,po.dCreatedDate,po.eDeliveryStatus,sup.vSupplierName,pr.vProductName,pod.iPurchaseQTY,pod.iPurchaseCostperQTY,pru.vProductUnitName');
        $this->db->join('cic_purchase_order as po','po.iPurchaseOrderId=pod.iPurchaseOrderId','left');
        $this->db->join('cic_products as pr','pr.iProductId=pod.iProductId','left');
        $this->db->join('cic_product_unit as pru','pru.iProductUnitId=pod.iProductUnitId','left');
        $this->db->join('cic_master_suppliers as sup','sup.iSupplierId=po.iSupplierId');
        
        if($data['product'] != null)
            $this->db->where('pod.iProductId',$data['product']);

        if($data['productunit'] != null)
          $this->db->where('pod.iProductUnitId',$data['productunit']);

        if ($data['from_date'] != "" && $data['to_date'] != "") {
            $this->db->where("po.dDeliveryDate >='" . $from_date . "' AND po.dDeliveryDate <='" . $to_date . "'");
        } elseif ($data['from_date'] !="" && $to_date == "") {
            $this->db->where("po.dDeliveryDate >='" . $from_date . "'");
        } elseif ($data['from_date'] == "" && $data['to_date'] != "") {
            $this->db->where("po.dDeliveryDate <='" . $to_date . "'");
        }

        if($data['status'] != "")
            $this->db->where('eDeliveryStatus',$data['status']);

        $this->db->from('cic_purchase_order_details as pod'); 
        $i = 0; 
        foreach ($this->column_search as $item) 
        {
            $search_val = trim($_POST['search']['value']);
            if($search_val) 
            {               
                if($i===0) // first loop
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $search_val);
                } else 
                    $this->db->or_like($item, $search_val);
                    
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }       
        $this->db->group_by('iPurchaseOrderDetailsId');
        if(isset($_POST['order'])) 
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        elseif(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    //change formate to yyyy-mm-dd
    public function change_date_formate($date) {
        $d = explode('/', $date);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
    }
    //Get Product List
    public function purchasedetail_list($data=null){
        $this->list_data($data);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    //Get Datatable Count
    public function count_all_references($data=null) {
        $this->list_data($data);
        $query = $this->db->get();
        return $query->num_rows();
    }
    //Get product List 
    public function get_product_list(){
        $this->db->select('iProductId,vProductName');
        $this->db->from('cic_products');
        $this->db->where('eStatus','Active');
        $this->db->order_by('vProductName','asc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }
    //Get product Unit 
    public function get_productunit_list(){
        $this->db->select('iProductUnitId,vProductUnitName');
        $this->db->from('cic_product_unit');
        $this->db->where('eStatus','Active');
        $this->db->order_by('CAST(vProductUnitName AS unsigned)','asc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            return $query->result_array();
        }else
        return false;
    }

}
