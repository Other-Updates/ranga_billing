<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends MY_Controller {
    private $column_order = array('vDeliveryOrderNo','vBranchName','fNetQty','eDeliveryStatus'); //set column field database for datatable orderable
    private $column_search = array('vDeliveryOrderNo','vBranchName','fNetQty','eDeliveryStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function get_category(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_category');
        return $this->db->get()->result_array();
    }
    public function get_unit(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        return $this->db->from('cic_product_unit')->get()->result_array();
    }

    public function get_product_by_category($cat_id,$product,$branch,$type){
       /* $this->db->select('p.*');
        $this->db->join('cic_product_branch as pb1','pb1.iProductId=p.iProductId AND pb1.iBranchId IN ('.$branch.')','join');
        $this->db->join('cic_product_branch as pb2','pb2.iProductId=p.iProductId AND pb2.iBranchId IN ('.$branch.') AND pb1.iProductBranchId != pb2.iProductBranchId','join');
        $this->db->where('p.eStatus','Active');
        $this->db->where('p.iCategoryId',$cat_id);
        $this->db->like('p.vProductName',$product);
        $this->db->from('cic_products as p');
        $this->db->group_by('p.iProductId');
        return $this->db->get()->result_array();
        // $this->db->select('select iProductId from ');
       // echo $this->db->last_query();exit; */

        //    $this->db->select('p.iProductId,p.vProductName');
        
        $this->db->select('p.vProductName,b.iProductId,c.iProductColorId,c.vColorName,p.icategoryId as prod_category');
        $this->db->join('cic_products as p','p.iProductId=b.iProductId','left');
        $this->db->join('cic_product_color as c','p.iProductColorId!="" AND find_in_set(c.iProductColorId,p.iProductColorId)','left');
        $this->db->where('b.iBranchId',$branch);
        if($type == "category")
        $this->db->where('b.iCategoryId',$cat_id);
        $this->db->like('p.vProductName',$product);
        $this->db->where('p.eStatus!=','Deleted');
        return $this->db->get('cic_product_branch as b')->result_array();
        
    //    $this->db->select('(SELECT GROUP_CONCAT(DISTINCT iProductId) FROM cic_product_branch WHERE iBranchId = b.iBranchId) As product_ids');
    //    $this->db->where_in('b.iBranchId',$branch);
    //    $this->db->group_by('b.iBranchId'); 
    //    $product_details = $this->db->get('cic_product_branch as b')->result_array();
    //    if(!empty($product_details)){
    //     echo '<pre>';print_r($product_details);
    //        $product_ids = array_map(function($product_details){
    //                 return $product_details['product_ids'];
    //        },$product_details);
    //        echo '<pre>';print_r($product_ids);exit;
    //    }
    }

    public function get_headoffice(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_headoffice');
        return $this->db->get()->result_array();
    }
   
    public function get_branch_by_headoffice($id){
        $this->db->select('*');
        $this->db->where('iHeadOfficeId',$id);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }
    public function get_branch_by_delivery_order_id($id,$type=null){
        // print_r($id);exit;
        $this->db->select('s.*,b.vBranchName,h.vHeadOfficeName,h.iStateId');
        $this->db->where('iDeliveryOrderId',$id);
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=s.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as b','b.iBranchId=s.iBranchId','left');
        // $this->db->join('cic_customer as c','c.iCustomerId=s.iCustomerId','left');
        $this->db->from('cic_delivery_order as s');
        $data = $this->db->get()->result_array();
        if(!empty($data)){
            foreach($data as $key=>$sales){     
                $this->db->select('sod.*,c.vCategoryName,unit.vProductUnitName,sod.iProductColorId,unit.vProductUnitName',FALSE);
                $this->db->select("(CASE WHEN col.iProductColorId > 0 THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
                if(!empty($type))
                $this->db->select('(select dProductQty from cic_stock WHERE iBranchId='.$sales['iBranchId'].' AND iProductId=sod.iProductId AND iProductUnitId=sod.iProductUnitId AND iProductColorId=sod.iProductColorId GROUP BY sod.iDeliveryOrderDetailsId) as product_qty',false);
                else
                $this->db->select('(select dProductQty from cic_warehouse WHERE iProductId=sod.iProductId AND iProductUnitId=sod.iProductUnitId AND iProductColorId=sod.iProductColorId GROUP BY sod.iDeliveryOrderDetailsId) as product_qty',false); //Get Limit delivery product
                $this->db->join('cic_product_color as col','sod.iProductColorId = col.iProductColorId AND sod.iProductColorId != 0 AND sod.iProductColorId != "NULL"','left');
                $this->db->where('sod.iDeliveryOrderId',$id);
                $this->db->join('cic_master_category as c','c.iCategoryId=sod.iCatagoryId','left');
                $this->db->join('cic_products as pr','pr.iProductId=sod.iProductId','left');
                $this->db->join('cic_product_unit as unit','unit.iProductUnitId=sod.iProductUnitId','left');
                // $this->db->join('cic_warehouse as st','sod.iProductColorId=st.iProductColorId','left');
                $this->db->from('cic_delivery_order_details as sod');
                $this->db->group_by('iDeliveryOrderDetailsId');
                $delivery_order = $this->db->get()->result_array();
                //echo $this->db->last_query();exit;
                $data[$key]['delivery_details'] = $delivery_order;
            }
        }
        return $data;
    }

    public function get_branch_products($data){
        $this->db->insert('cic_stock',$data);
    }

    public function update_branch_products($id,$data){
         $this->db->where('iStockId',$id);
        // $this->db->set('dProductQty', 'dProductQty + ' . (int) $data[0]['dProductQty'], FALSE);
        $this->db->update('cic_stock',$data);
    }
    public function update_deliver_details($data){
        // echo"<pre>";print_r($data);exit;
        $this->db->update_batch('cic_delivery_order_details',$data,'iDeliveryOrderDetailsId');
        // echo $this->db->last_query();exit;
    }
    public function get_deliver_details($data){
        $this->db->insert_batch('cic_delivery_order_details',$data);
    }
    public function add_purchase_history($data){
        $this->db->insert_batch('cic_purchase_order_history',$data);
    }
    public function update_delivery_order($data,$id){
        $this->db->where('iDeliveryOrderId',$id);
        $this->db->update('cic_delivery_order',$data);
    }
    public function remove_deliver_details($id){
        $this->db->where('iDeliveryOrderDetailsId',$id);
        $this->db->delete('cic_delivery_order_details');
    }
    public function remove_deliver_order($id){
        $this->db->where('iDeliveryOrderId',$id);
        $this->db->delete('cic_delivery_order');
    }
    public function get_branch_products_history($data){
        $this->db->insert_batch('cic_stock_history',$data);
    }

    public function insert_delivery_order($data){
        $this->db->insert('cic_delivery_order',$data);
        return $this->db->insert_id();
    }

    public function insert_delivery_order_details($data){
        $this->db->insert_batch('cic_delivery_order_details',$data);
    }

    private function list_data() {      
        $BranchId = $this->session->userdata('BranchId');
        $this->db->select('cs.iStockId,ho.vHeadOfficeName,br.vBranchName,ct.vCategoryName,pr.vProductName,pu.vProductUnitName,cs.dProductQty');
        if(!empty($this->session->userdata('BranchId')))
        $this->db->where('cs.iBranchId IN('.$BranchId.')',NULL,false);
        $this->db->join('cic_master_headoffice as ho','ho.iHeadOfficeId=cs.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as br','cs.iBranchId=br.iBranchId','left');
        $this->db->join('cic_master_category as ct','ct.iCategoryId=cs.iCategoryId','left');
        $this->db->join('cic_products as pr','pr.iProductId=cs.iProductId','left');
        $this->db->join('cic_product_unit as pu','pu.iProductUnitId=cs.iProductUnitId','left');
        $this->db->order_by('cs.iStockId','desc');
        $this->db->from('cic_stock as cs');
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
    public function update_sales_status($data,$id){
        $this->db->where('iDeliveryOrderId',$id);
        $this->db->update('cic_delivery_order',$data);
    }


    public function stock_branch($data,$branch) {
        $BranchId = $this->session->userdata('BranchId');
        $this->db->select('do.iDeliveryOrderId,do.vDeliveryOrderNo,br.vBranchName,do.fNetQty,do.eDeliveryStatus');
        $this->db->join('cic_master_branch as br','do.iBranchId=br.iBranchId','left');
        $this->db->where('do.eStatus!=','Deleted');
        if(!empty($this->session->userdata('BranchId')))
        $this->db->where('do.iBranchId', $BranchId);
        $this->db->order_by('iDeliveryOrderId','desc');
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->from('cic_delivery_order as do');
        

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

        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_stock() {
        // $this->list_data();
        $BranchId = $this->session->userdata('BranchId');
        if(!empty($this->session->userdata('BranchId')))
        $this->db->where('iBranchId IN('.$BranchId.')',NULL,false);
        $this->db->where('eStatus!=','Deleted');
        $this->db->from('cic_delivery_order');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_unit_by_product_id($product_id,$customer_id){
        // $this->db->select('iGradeId');
        // $this->db->where('iCustomerId',$customer_id);
        // $this->db->from('cic_customer');
        // $result = $this->db->get()->row_array();
        // $this->db->select('pr.*,un.*');
        // $this->db->join('cic_product_unit as un','un.iProductUnitId=pr.iProductUnitId','left');
        // // $this->db->where('pr.iGradeId',$result['iGradeId']);
        // $this->db->where('pr.iProductId',$product_id);
        // $this->db->from('cic_warehouse as pr');
        // return $this->db->get()->result_array();

        $this->db->select('pr.*,un.*');
        $this->db->join('cic_product_unit as un','un.iProductUnitId=pr.iProductUnitId','left');
        // $this->db->where('pr.iGradeId',$result['iGradeId']);
        $this->db->where('pr.iProductId',$product_id);
        $this->db->group_by('pr.iProductUnitId');
        $this->db->from('cic_product_price_list as pr');
        return $this->db->get()->result_array();
    }

    public function check_stock_exist($headoffice,$branch,$category,$product,$unit,$color_id){
        $this->db->select('iStockId,dProductQty');
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iHeadOfficeId',$headoffice);
        $this->db->where('iBranchId',$branch);
        $this->db->where('iCategoryId',$category);
        $this->db->where('iProductId',$product);
        $this->db->where('iProductUnitId',$unit);
        $query = $this->db->get('cic_stock');
        if($query->num_rows() >0){
            $result = $query->row_array();
            // echo "<pre>";print_r($result);exit;
            return $result;
        }else
        return false;
    }
    
    public function update_warehouse($category,$product,$unit,$qty,$color_id){
        $this->db->where('iCategoryId',$category);
        $this->db->where('iProductId',$product);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('dProductQty', 'dProductQty - ' . (int) $qty, FALSE);
        $this->db->update('cic_warehouse');
    }
    // 
    public function return_warehouse($category,$product,$unit,$qty,$color_id){
        $this->db->where('iCategoryId',$category);
        $this->db->where('iProductId',$product);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->set('dProductQty', 'dProductQty + ' . (int) $qty, FALSE);
        $this->db->update('cic_warehouse');
    }

    public function check_product_quantity($product_id,$qty,$unit_id){
        // $where = 'iBrandId='.$branch_id.'AND iProductId='.$product_id.
        $this->db->select('dProductQty');
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit_id);
        $result = $this->db->get('cic_warehouse')->row_array();
        if($result['dProductQty'] < $qty){
            return true;
        }
    }

    public function get_order_number()
    {
        $this->db->select('iOrderNumber');
        $this->db->where('vType','Delivery');
        $this->db->from('cic_increment_order');
        return $this->db->get()->row_array();
    }

    public function update_order_number()
    {
        $this->db->where('vType','Delivery');
        $this->db->set('iOrderNumber','iOrderNumber+'.(int)1,false);
        $this->db->update('cic_increment_order');
    }

    public function update_delivery_stock($delivery_order_details_id,$product_id,$category_id,$unit,$quantity,$branchid,$color_id){
        $this->db->where('iProductId',$product_id);
        $this->db->where('iCatagoryId',$category_id);
        if($color_id>0)
        $this->db->where('iProductColorId',$color_id);
        $this->db->where('iProductUnitId',$unit);
        $this->db->where('iDeliveryOrderDetailsId',$delivery_order_details_id);
        $result = $this->db->get('cic_delivery_order_details')->row_array();
        if($result['iDeliveryQTY'] != $quantity){

                if($result['iDeliveryQTY'] > $quantity){
                    
                $updated_quantity = $result['iDeliveryQTY'] - $quantity;
                $this->db->set('dProductQty','dProductQty -'.(int)$updated_quantity,false);
                
            }if($result['iDeliveryQTY'] < $quantity){
                
                $updated_quantity = $quantity - $result['iDeliveryQTY'];
                $this->db->set('dProductQty','dProductQty +'.(int)$updated_quantity,false);
                
            }
            $this->db->where('iBranchId',$branchid);
            $this->db->where('iCategoryId',$category_id);
            if($result['iProductColorId']>0)
            $this->db->where('iProductColorId',$result['iProductColorId']);
            $this->db->where('iProductId',$product_id);
            $this->db->where('iProductUnitId',$unit);
            $this->db->update('cic_stock');
            
            if($result['iDeliveryQTY'] > $quantity){

                $updated_quantity_warhouse = $result['iDeliveryQTY'] - $quantity;
                $this->db->set('dProductQty','dProductQty +'.(int)$updated_quantity_warhouse,false);
                
            }if($result['iDeliveryQTY'] < $quantity){
                
                $updated_quantity_warhouse = $quantity - $result['iDeliveryQTY'];
                $this->db->set('dProductQty','dProductQty -'.(int)$updated_quantity,false);
                
            }
            $this->db->where('iCategoryId',$category_id);
            if($result['iProductColorId']>0)
            $this->db->where('iProductColorId',$result['iProductColorId']);
            $this->db->where('iProductId',$product_id);
            $this->db->where('iProductUnitId',$unit);
            $this->db->update('cic_warehouse');
        }
    }

    public function update_deleted_stock($delivery_order_id)
    {
        $this->db->select('iBranchId');
        $this->db->where('iDeliveryOrderId',$delivery_order_id);
        $this->db->from('cic_delivery_order');
        $data = $this->db->get()->row_array();
        
        $this->db->select('*');
        $this->db->where('iDeliveryOrderId',$delivery_order_id);
        $this->db->from('cic_delivery_order_details');
        $delivery_order = $this->db->get()->result_array();
        if(!empty($delivery_order)){
            foreach ($delivery_order as $result) {
                $this->db->set('dProductQty','dProductQty -'.(int)$result['iDeliveryQTY'],false);
                if($result['iProductColorId']>0)
                $this->db->where('iProductColorId',$result['iProductColorId']);
                $this->db->where('iBranchId',$data['iBranchId']);
                $this->db->where('iProductId',$result['iProductId']);
                $this->db->where('iProductUnitId',$result['iProductUnitId']);
                $this->db->update('cic_stock');
                // echo $this->db->last_query();exit;
                $this->db->set('dProductQty','dProductQty +'.(int)$result['iDeliveryQTY']);
                if($result['iProductColorId']>0)
                $this->db->where('iProductColorId',$result['iProductColorId']);
                $this->db->where('iProductId',$result['iProductId']);
                $this->db->where('iProductUnitId',$result['iProductUnitId']);
                $this->db->update('cic_warehouse');
            }
        }
    }

    public function delete_stock($delivery_order_details_id)
    {
        $this->db->select('*');
        $this->db->where('iDeliveryOrderDetailsId',$delivery_order_details_id);
        $this->db->from('cic_delivery_order_details');
        $delivery_order_details = $this->db->get()->result_array();
        $this->db->select('iBranchId');
        $this->db->where('iDeliveryOrderId',$delivery_order_details[0]['iDeliveryOrderId']);
        $this->db->from('cic_delivery_order');
        $query = $this->db->get()->row_array();
        foreach($delivery_order_details as $result){

            $this->db->where('iBranchId',$query['iBranchId']);
            $this->db->set('dProductQty','dProductQty -'.(int)$result['iDeliveryQTY'],false);
            $this->db->where('iProductId',$result['iProductId']);
            $this->db->where('iProductUnitId',$result['iProductUnitId']);
            $this->db->update('cic_stock');

            $this->db->set('dProductQty','dProductQty +'.(int)$result['iDeliveryQTY']);
            if($result['iProductColorId']>0)
            $this->db->where('iProductColorId',$result['iProductColorId']);
            $this->db->where('iProductId',$result['iProductId']);
            $this->db->where('iProductUnitId',$result['iProductUnitId']);
            $this->db->update('cic_warehouse');
        }
    }

    public function check_warehouse_qty($product_id,$qty,$unit,$product_color)
    {
        $this->db->select('dProductQty');
        $this->db->where('iProductId',$product_id);
        $this->db->where('iProductUnitId',$unit);
        if($product_color>0)
        $this->db->where('iProductColorId',$product_color);
        $result = $this->db->get('cic_warehouse')->row_array();
        if($result['dProductQty'] < $qty){
            return true;
        }
    }

    public function get_product_qty($product,$unit,$color)
    {
        $this->db->select('pl.*,cw.dProductQty');
        $this->db->join('cic_product_price_list as pl','pl.iProductId=cw.iProductId and pl.iProductUnitId=cw.iProductUnitId','left');
        $this->db->where('cw.iProductId',$product);
        $this->db->where('cw.iProductUnitId',$unit);
        $this->db->where('pl.iGradeId',6); // Default Grade B for all delivery Order
        if($color>0)
        $this->db->where('cw.iProductColorId',$color);
        $this->db->from('cic_warehouse as cw');
        $query = $this->db->get();
        if($query->num_rows()>0){
            return $query->row_array();
        }else
        return false;
    }

    // Deleivery Bill Details
    public function get_deleivery_details_by_id($id){
        $this->db->select('s.*,b.vBranchName,b.vAdhaarGst,b.vMobileNumber,b.vAddress,b.vAddress_Tamil,h.vHeadOfficeName,st.vStateName,st.iStateId');
        $this->db->where('iDeliveryOrderId',$id);
        $this->db->join('cic_master_headoffice as h','h.iHeadOfficeId=s.iHeadOfficeId','left');
        $this->db->join('cic_master_state as st','st.iStateId=h.iStateId','left');
        $this->db->join('cic_master_branch as b','b.iBranchId=s.iBranchId','left');
        $this->db->from('cic_delivery_order as s');
        $data = $this->db->get()->result_array();
        if(!empty($data)){
            foreach($data as $key=>$sales){
                $this->db->select('sod.*,pr.vProductName_Tamil,pr.vHSNNO,c.vCategoryName,unit.vProductUnitName');
                $this->db->select('(select dProductQty from cic_stock WHERE iBranchId='.$sales['iBranchId'].' AND iProductId=sod.iProductId AND iProductUnitId=sod.iProductUnitId AND iProductColorId=sod.iProductColorId GROUP BY sod.iDeliveryOrderDetailsId) as product_qty',false);
                $this->db->select("(CASE WHEN col.iProductColorId > 0 THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
                $this->db->join('cic_product_color as col','sod.iProductColorId = col.iProductColorId AND sod.iProductColorId != 0 AND sod.iProductColorId != "NULL"','left');
                $this->db->join('cic_master_category as c','c.iCategoryId=sod.iCatagoryId','left');
                $this->db->join('cic_products as pr','pr.iProductId=sod.iProductId','left');
                $this->db->join('cic_product_unit as unit','unit.iProductUnitId=sod.iProductUnitId','left');
                $this->db->join('cic_stock as st','sod.iProductColorId=st.iProductColorId','left');
                $this->db->where('sod.iDeliveryOrderId',$id);
                $this->db->from('cic_delivery_order_details as sod');
                $this->db->group_by('iDeliveryOrderDetailsId');
                $deleivery_order = $this->db->get()->result_array();
                $data[$key]['deleivery_details'] = $deleivery_order;
            }
        }
        return $data;
    }
}
