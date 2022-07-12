<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $cic_products = 'cic_products';
        $cic_product_unit = 'cic_product_unit';
        $cic_stock = 'cic_stock';
        $cic_warehouse = 'cic_warehouse';
        $this->load->database();

    }
    
    //Product Id - Get Function
    function get_product_id($product_name){
        $this->db->select('iProductId,iCategoryId');
        $this->db->where('TRIM(vProductName)',$product_name);
        $this->db->where('eStatus','Active');
        $query = $this->db->get('cic_products');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }
    
    //Product Size Id - Get Function
    function get_product_size_id($product_size){
        $this->db->select('iProductUnitId');
        $this->db->where('TRIM(vProductUnitName)',$product_size);
        $this->db->where('eStatus','Active');
        $query = $this->db->get('cic_product_unit');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
    }
    
    //Update final Stock
    function update_stock($insertData)
    {
        if ($this->db->insert_batch('cic_stock', $insertData)) {
            return TRUE;
        }
        return FALSE;
    }

   //Get Branch Stock By ID
   function get_branch_stock($branchID)
   {
        $this->db->select('*');
        $this->db->where('dProductQty >',0);
        $this->db->where('iBranchId',$branchID);
        $query = $this->db->get('cic_stock');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return NULL;
   }

    //Update final Warehours Stock
    function update_warehoursstock($insertData)
    {
        if ($this->db->insert_batch('cic_warehouse', $insertData)) {
            return TRUE;
        }
        return FALSE;
    }

    function getWareHouseStock($stock)
    {
        $insertArray=$updateArray=$currentStock=array();
        $this->db->select('dProductQty');
        $this->db->where('iCategoryId',$stock['iCategoryId']);
        $this->db->where('iProductId',$stock['iProductId']);
        $this->db->where('iProductUnitId',$stock['iProductUnitId']);
        $this->db->where('iProductColorId',$stock['iProductColorId']);
        $query = $this->db->get('cic_warehouse');
        if ($query->num_rows() > 0) {
            $currentStock = $query->result_array();
            $updateArray['dProductQty']=$currentStock[0]['dProductQty']+$stock['dProductQty'];
            $updateArray['dUpdatedDate']=date('Y-m-d H:i');
            $this->db->where('iCategoryId',$stock['iCategoryId']);
            $this->db->where('iProductId',$stock['iProductId']);
            $this->db->where('iProductUnitId',$stock['iProductUnitId']);
            $this->db->where('iProductColorId',$stock['iProductColorId']);
            if ($this->db->update('cic_warehouse', $updateArray)) {
                echo $stock['iProductId'].' == '.$stock['iProductUnitId'].' Updated QTY'.$updateArray['dProductQty'].'<br>';
            }
        }else{
            $insertArray['iCategoryId']=$stock['iCategoryId'];
            $insertArray['iProductId']=$stock['iProductId'];
            $insertArray['iProductUnitId']=$stock['iProductUnitId'];
            $insertArray['iProductColorId']=$stock['iProductColorId'];
            $insertArray['dProductQty']=$stock['dProductQty'];
            $insertArray['dCreatedDate']=date('Y-m-d H:i');
            if ($this->db->insert('cic_warehouse', $insertArray)) {
                echo $stock['iProductId'].' == '.$stock['iProductUnitId'].' Inserted QTY'.$insertArray['dProductQty'].'<br>';
            }
        }

    }

    // Get Sales Order
    function get_gst_percentage($start)
   {
        $this->db->select('s.iSalesOrderId,s.vSalesOrderNo,sd.CGST,sd.SGST,sd.IGST,s.CGST AS cgst_amt,s.SGST AS sgst_amt,s.IGST AS igst_amt,s.fNetCost,s.vSalesOrderNo,h.vHeadOfficeName,ste.iStateId');
        $this->db->join('cic_sales_order_details as sd','sd.iSalesOrderId=s.iSalesOrderId','left');
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=s.iHeadOfficeId','left');
        $this->db->join('cic_customer as c','c.iCustomerId=s.iCustomerId','left');
        $this->db->join('cic_master_state as ste','ste.iStateId=c.iStateId','left');
        // $this->db->where(array('sd.CGST' => '5.00', 'sd.SGST' => '5.00'));
        $this->db->limit(200, $start);
        $query = $this->db->get('cic_sales_order as s');
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        }
        return NULL;
   }
    // Update Gst Percentage
    function updateDetailsGst($data,$gst_data)
    {
        $this->db->where('iSalesOrderId',$gst_data['iSalesOrderId']);
        $this->db->set($data);
        $update = $this->db->update('cic_sales_order_details');
        if ($update){
            $cgst_percent = $data['CGST'] / 100 * $gst_data['fNetCost'];
            $sgst_percent = $data['SGST'] / 100 * $gst_data['fNetCost'];
            $igst_percent = $data['IGST'] / 100 * $gst_data['fNetCost'];
            $query = $this->UpdateGstPrice($cgst_percent,$sgst_percent,$igst_percent,$gst_data);
        return TRUE;
        }
        return FALSE;
    }

    // Update Gst Price Sales Order
    function UpdateGstPrice($cgst,$sgst,$igst,$data)
    {
        if($data['iStateId']=='2'){
        $gst_amount = $cgst + $sgst;
        $taxable_amt = $data['fNetCost'] - $gst_amount;
        $gst_data = array('fNetCostwithoutGST'=>$taxable_amt, 'CGST' => $gst_amount/2, 'SGST' => $gst_amount/2, 'IGST' => '0.00');
        }
        else{
        $gst_amount = $igst;
        $taxable_amt = $data['fNetCost'] - $gst_amount;
        $gst_data = array('fNetCostwithoutGST'=>$taxable_amt, 'CGST' => '0.00', 'SGST' => '0.00', 'IGST' => $gst_amount);
        }
        $this->db->where('iSalesOrderId',$data['iSalesOrderId']);
        $this->db->set($gst_data);
        $update = $this->db->update('cic_sales_order');
        if ($update) {
            return TRUE;
        }
        return FALSE;
    }

    //Update Werehouse Stock
    function update_where_house_stock($insertData)
    {
        if ($this->db->insert_batch('cic_warehouse', $insertData)) {
            return TRUE;
        }
        return FALSE;
    }

}
