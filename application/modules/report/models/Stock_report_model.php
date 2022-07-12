<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_report_model extends MY_Controller {
    private $table = 'cic_sales_order_details';
    private $column_order_warehouse = array(null,'vCategoryName','vSubcategoryName','vProductName','vProductUnitName','dProductQty','iPurchaseCostperQTY'); //set column field database for datatable orderable
    private $column_search_warehouse = array('vCategoryName','vSubcategoryName','vProductName','vProductUnitName','dProductQty','iPurchaseCostperQTY'); //set column field database for datatable searchable 
    private $order = array('iStockId' => 'desc'); // default descending order

    private $column_order = array(null,'vHeadOfficeName','vBranchName','vCategoryName','vSubcategoryName','vProductName','vProductUnitName','dProductQty','fProductPrice'); //set column field database for datatable orderable
    private $column_search = array('vHeadOfficeName','vBranchName','vCategoryName','vSubcategoryName','vProductName','vProductUnitName','dProductQty','fProductPrice'); //set column field database for datatable searchable 
    private $warehouse = array('iWareHouseStockId' => 'desc'); // default descending order
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

    private function list_data($data = null) { 
        $BranchId = $this->session->userdata('BranchId');
        $this->db->select('cs.iStockId,pl.fProductPrice,ho.vHeadOfficeName,br.vBranchName,ct.vCategoryName,sub.vSubcategoryName,pu.vProductUnitName,cs.dProductQty');
        // $this->db->select("(CASE WHEN (col.iProductColorId > 0 AND col.iProductColorId NOT NULL) THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
        $this->db->select("(CASE WHEN (col.iProductColorId > 0 AND col.iProductColorId IS NOT NULL) THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
        $this->db->join('cic_product_color as col','cs.iProductColorId = col.iProductColorId AND cs.iProductColorId != 0 AND cs.iProductColorId != "NULL"','left');
        $this->db->join('cic_product_price_list as pl','cs.iProductId = pl.iProductId AND cs.iProductUnitId = pl.iProductUnitId','left');
        if($data['branch'] != null)
        $this->db->where('cs.iBranchId',$data['branch']);
        if($data['category']!= null)
        $this->db->where('cs.iCategoryId',$data['category']);
        $this->db->join('cic_master_headoffice as ho','ho.iHeadOfficeId=cs.iHeadOfficeId','left');
        $this->db->join('cic_master_branch as br','cs.iBranchId=br.iBranchId','left');
        $this->db->join('cic_master_category as ct','ct.iCategoryId=cs.iCategoryId','left');
        $this->db->join('cic_products as pr','pr.iProductId=cs.iProductId','left');
        if(!empty($data['subcategory']))
        $this->db->where('pr.iSubcatagoryId',$data['subcategory']);
        $this->db->join('cic_product_unit as pu','pu.iProductUnitId=cs.iProductUnitId','left');
        $this->db->join('cic_master_subcategory as sub','pr.iSubcatagoryId=sub.iSubcategoryId','left');
        if(!empty($this->session->userdata('BranchId')))
        $this->db->where('cs.iBranchId', $BranchId);
        $this->db->where('pl.iGradeId', '6'); //B Grade Product Price
        $this->db->group_by(['cs.iProductId', 'cs.iCategoryId', 'cs.iProductUnitId', 'cs.iProductColorId']);
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


    public function stock_branch($data=null) {
        $this->list_data($data);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_stock($data=null) {
        $this->list_data($data);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_branch(){
        $BranchId = $this->session->userdata('BranchId');
        $this->db->select('*');
        if(!empty($BranchId))
        $this->db->where('iBranchId', $BranchId);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function get_subcategory()
    {
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_subcategory');
        return $this->db->get()->result_array();
    }

    private function list_data_warehouse($data = null) {
        $this->db->select('w.iWareHouseStockId,MAX(po.iPurchaseCostperQTY) AS iPurchaseCostperQTY,ct.vCategoryName,sub.vSubcategoryName,pu.vProductUnitName,w.dProductQty');
        $this->db->select("(CASE WHEN col.iProductColorId > 0 THEN CONCAT(pr.vProductName,' (',col.vColorName,')')ELSE pr.vProductName END) as vProductName",false);
        $this->db->join('cic_product_color as col','w.iProductColorId = col.iProductColorId AND w.iProductColorId != 0 AND w.iProductColorId != "NULL"','left');
        $this->db->join('cic_purchase_order_details as po','w.iProductId = po.iProductId AND w.iCategoryId = po.iCatagoryId AND w.iProductUnitId = po.iProductUnitId AND w.iProductColorId = po.iProductColorId','left');
        if($data['category']!= null)
        $this->db->where('w.iCategoryId',$data['category']);
        $this->db->join('cic_master_category as ct','ct.iCategoryId=w.iCategoryId','left');
        $this->db->join('cic_products as pr','pr.iProductId=w.iProductId','left');
        if(!empty($data['subcategory']))
        $this->db->where('pr.iSubcatagoryId',$data['subcategory']);
        $this->db->join('cic_product_unit as pu','pu.iProductUnitId=w.iProductUnitId','left');
        $this->db->join('cic_master_subcategory as sub','pr.iSubcatagoryId=sub.iSubcategoryId','left');
        $this->db->group_by(['w.iProductId', 'w.iCategoryId', 'w.iProductUnitId', 'w.iProductColorId']);
        $this->db->from('cic_warehouse as w');
        $i = 0; 
        foreach ($this->column_search_warehouse as $item) 
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
                if(count($this->column_search_warehouse) - 1 == $i) //last loop
                    $this->db->group_end(); 
            }
            $i++;
        }       
        if(isset($_POST['order'])) { 
            $this->db->order_by($this->column_order_warehouse[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->warehouse)) {
            $order = $this->warehouse;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function warehouse_stock($data=null) {
        $this->list_data_warehouse($data);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_warestock($data=null) {
        $this->list_data_warehouse($data);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
