<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

   public function get_sales_data($type)
   {
       $BranchId = $this->session->userdata('BranchId');
       $logged_user = $this->session->userdata('LoggedId');
       $this->db->select('*');
       $this->db->where('eStatus','Active');
       $this->db->where('eDeliveryStatus!=','Cancelled');
       $this->db->from('cic_sales_order');
       if($type == "DAY")
       $this->db->where("DATE_FORMAT(dCreatedDate,'%Y-%m-%d')",date('Y-m-d'));
       if($type == "MONTH")
       $this->db->where("DATE_FORMAT(dCreatedDate,'%m')",date('m'));
       if($this->session->userdata('UserRole') == 3){
        $this->db->where('iBranchId', $BranchId);
        $this->db->where('iCreatedBy', $logged_user);
        }
        if($this->session->userdata('UserRole') == 2){
        $this->db->where('iBranchId', $BranchId);
        }
       $query = $this->db->get()->result_array();
    //    echo $this->db->last_query();exit;
       return count($query);
   }

   public function get_purchase_data($type)
   {
       $this->db->select('*');
       $this->db->where('eStatus','Active');
       if($type == "DAY")
       $this->db->where("DATE_FORMAT(dCreatedDate,'%Y-%m-%d')",date('Y-m-d'));
       if($type == "MONTH")
       $this->db->where("DATE_FORMAT(dCreatedDate,'%m')",date('m'));
       $this->db->from('cic_purchase_order');
       $query = $this->db->get()->result_array();
       return count($query);
   }

   public function get_most_selling_product()
   {
    $BranchId = $this->session->userdata('BranchId');
    $logged_user = $this->session->userdata('LoggedId');
    $this->db->select("b.iProductId,c.iCategoryId,c.vProductName,c.vDescription,b.iDeliveryCostperQTY,count(b.iProductId) as sale_quantity,CONCAT('".$base_url."',c.vImages) AS product_image,SUBSTRING_INDEX(c.vImages , ',', 1) AS first_name");
    $this->db->from('cic_sales_order_details as b');
    $this->db->join('cic_master_category as d','d.iCategoryId=b.iCatagoryId','left');
    $this->db->join('cic_sales_order as sd','sd.iSalesOrderId=b.iSalesOrderId','left');
    $this->db->join('cic_products as c','c.iProductId=b.iProductId','left');
    if($this->session->userdata('UserRole') == 3){
    $this->db->where('sd.iBranchId', $BranchId);
    $this->db->where('sd.iCreatedBy', $logged_user);
    }
    if($this->session->userdata('UserRole') == 2){
    $this->db->where('sd.iBranchId', $BranchId);
    }
    $this->db->group_by('b.iProductId');
    $this->db->order_by('count(b.iProductId)','desc');
    $this->db->where('c.eStatus!=','Deleted');
    $this->db->limit(5);
    return $this->db->get()->result_array();
   }

   public function products_count()
   {
       $this->db->where('eStatus','Active');
       $this->db->from('cic_products');
       $query = $this->db->get()->result_array();
        return count($query);
   }

   public function monthly_sales()
   {
        $BranchId = $this->session->userdata('BranchId');
        $logged_user = $this->session->userdata('LoggedId');
    // SELECT SUM(`fNetQty`),SUM(`fNetCost`),DATE_FORMAT(`dOrderedDate`,'%m-%Y') as order_date,DATE_FORMAT(`dOrderedDate`,'%Y') as order_year from cic_sales_order GROUP BY DATE_FORMAT(`dOrderedDate`,'%m-%Y')
    
        $monthly_sales_amount = array();
        $monthly_sales_qty = array();
        $this->db->select("SUM(`fNetQty`) as total_qty,SUM(`fNetCost`) as total_cost,DATE_FORMAT(`dOrderedDate`,'%m-%Y') as order_date,DATE_FORMAT(`dOrderedDate`,'%Y') as order_year,DATE_FORMAT(`dOrderedDate`,'%m') as order_month");
        $this->db->group_by("DATE_FORMAT(`dOrderedDate`,'%m-%Y')");
        $this->db->where('eStatus','Active');
        $this->db->where('eDeliveryStatus!=','Cancelled');
        if($this->session->userdata('UserRole') == 3){
            $this->db->where('iBranchId', $BranchId);
            $this->db->where('iCreatedBy', $logged_user);
        }
            if($this->session->userdata('UserRole') == 2){
            $this->db->where('iBranchId', $BranchId);
        }
        $this->db->from('cic_sales_order');
        $query = $this->db->get()->result_array();
        for($i=1;$i<=12;$i++){
            if($i < 10) {
                $i = "0".$i;
            }
            $qty = 0;
            $amount = 0;
            foreach($query as $value){
                if($value['order_month'] == $i){
                    $qty = $value['total_qty'];
                    $amount = $value['total_cost'];
                    break;
                }
            } 
            $monthly_sales_amount[] = $amount;
            $monthly_sales_qty[] = $qty;
       }
       $data['amount'] = $monthly_sales_amount;
       $data['qty'] = $monthly_sales_qty;
       return $data;
   }

   
   public function downloaded_users()
   {
       $BranchId = $this->session->userdata('BranchId');
       $logged_user = $this->session->userdata('LoggedId');
       $this->db->where('tOtpVerify',1);
       $this->db->from('cic_customer');
       if($this->session->userdata('UserRole') == 3){
        $this->db->where('iBranchId', $BranchId);
        $this->db->where('iCreatedBy', $logged_user);
        }
        if($this->session->userdata('UserRole') == 2){
        $this->db->where('iBranchId', $BranchId);
        }
       $result = $this->db->get()->result_array();
       return count($result);
   }

   public function category_count()
   {
       $this->db->where('eStatus','Active');
       $this->db->from('cic_master_category');
       $result = $this->db->get()->result();
       return count($result);
   }

   public function subcategory_count()
   {
       $this->db->where('eStatus','Active');
       $this->db->from('cic_master_subcategory');
       $query = $this->db->get()->result_array();
       return count($query);
   }
}
