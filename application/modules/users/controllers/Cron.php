<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller {
   public function __construct(){
	    parent::__construct();
        $this->load->model('cron_model');
    }
    //Update Stock by CSV 
    function UpdateStock(){
        $file = 'attachments/stock.csv';
        $handle = fopen($file, 'r');
        $skip_rows = 1;
        if ($file != NULL && $skip_rows > 0) {
            $skipLines = $skip_rows;
            $lineNum = 1;
            if ($skipLines > 0) {
                while (fgetcsv($handle)) {
                    if ($lineNum == $skipLines) {
                        break;
                    }
                    $lineNum++;
                }
            }
        }
        echo '<pre>';
        if ($file != NULL) {
            $count = 0;$inc_key = 0;
            $asset_name_array = array();
            $insert_batch=array();
            while ($row_data = fgetcsv($handle)) {
                $count++;
                $product_name = trim($row_data[0]);
                $product_size = trim($row_data[1]);
                $product_qty = trim($row_data[2]);
                $product_data = $this->cron_model->get_product_id($product_name);
                $product_size_data = $this->cron_model->get_product_size_id($product_size);
                if(isset($product_data) && !empty($product_data))
                {
                    if(isset($product_size_data) && !empty($product_size_data))
                    {
                        $insert_array=array();
                        $insert_array['iProductId']=$product_data[0]['iProductId'];
                        $insert_array['iProductUnitId']=$product_size_data[0]['iProductUnitId'];
                        $insert_array['iCategoryId']=$product_data[0]['iCategoryId'];
                        $insert_array['iHeadOfficeId']=4;
                        $insert_array['iBranchId']=4;
                        $insert_array['dProductQty']=$product_qty;      
                        $insert_batch[]=$insert_array;
                       echo "Product = ".$product_name.'  Size ='.$product_size.' Updated'.'<br>';
                    }
                    else
                        echo "Product Unit Not Exits = ".$product_name.' Size '.$product_size.'<br>';
                }
                else
                     echo "Product Not Exits = ".$product_name.'<br>';
            }
            //Update Stock Values
            $stock_response=$this->cron_model->update_stock($insert_batch);
        }else
            echo 'CSV File Not Exists';
                
        exit;
    }

    //Update stock from Branch to Warehours
    public function updateWarehouseStock()
    {
        $branchID = 4;
        $branchStock = $this->cron_model->get_branch_stock($branchID);
        if(isset($branchStock) && !empty($branchStock))
        {
            foreach($branchStock as $stock)
            {
                $findWarehoursProducts = $this->cron_model->getWareHouseStock($stock);
            }
        }
    }

    // Update Gst and Igst 
    public function updateGstPercentage($start)
    {
        $gstpercentage = $this->cron_model->get_gst_percentage($start);
        if(isset($gstpercentage) && !empty($gstpercentage))
        {
            foreach($gstpercentage as $gst_data)
            {
                if(!empty($gst_data['iStateId'])){
                if($gst_data['iStateId']=='2'){
                    $array = array('CGST' => '2.50', 'SGST' => '2.50', 'IGST' => '0.00');
                }
                else{
                    $array = array('CGST' => '0.00', 'SGST' => '0.00', 'IGST' => '5.00');
                }
                $update_gst_details = $this->cron_model->updateDetailsGst($array,$gst_data);

                echo "Sales_order_id = ".$gst_data['iSalesOrderId'].'  Sales_order_number ='.$gst_data['vSalesOrderNo'].' Updated'.'<br>';
            }
            }
        }
    }
    // Update Werehouse Stock

    function Update_Werehouse_Stock(){
        $file = 'attachments/warehouse_stocks.csv';
        $handle = fopen($file, 'r');
        $skip_rows = 1;
        if ($file != NULL && $skip_rows > 0) {
            $skipLines = $skip_rows;
            $lineNum = 1;
            if ($skipLines > 0) {
                while (fgetcsv($handle)) {
                    if ($lineNum == $skipLines) {
                        break;
                    }
                    $lineNum++;
                }
            }
        }
        echo '<pre>';
        if ($file != NULL) {
            $count = 0;$inc_key = 0;
            $asset_name_array = array();
            $insert_batch=array();
            while ($row_data = fgetcsv($handle)) {
                $count++;
                $product_name = trim($row_data[0]);
                $product_size = trim($row_data[1]);
                $product_qty = trim($row_data[2]);
                $product_data = $this->cron_model->get_product_id($product_name);
                $product_size_data = $this->cron_model->get_product_size_id($product_size);
                if(isset($product_data) && !empty($product_data))
                {
                    if(isset($product_size_data) && !empty($product_size_data))
                    {
                        $insert_array=array();
                        $insert_array['iProductId']=$product_data[0]['iProductId'];
                        $insert_array['iProductUnitId']=$product_size_data[0]['iProductUnitId'];
                        $insert_array['iCategoryId']=$product_data[0]['iCategoryId'];
                        $insert_array['iProductColorId']=0;
                        $insert_array['dCreatedDate']=date('Y-m-d h:i:s');
                        $insert_array['dProductQty']=$product_qty;      
                        $insert_batch[]=$insert_array;
                       echo "Product = ".$product_name.'  Size ='.$product_size.' Updated'.'<br>';
                    }
                    else
                        echo "Product Unit Not Exits = ".$product_name.' Size '.$product_size.'<br>';
                }
                else
                     echo "Product Not Exits = ".$product_name.'<br>';
            }
            //Update Werehouse Stock Values
            $stock_response=$this->cron_model->update_where_house_stock($insert_batch);
        }else
            echo 'CSV File Not Exists';
                
        exit;
    }
}