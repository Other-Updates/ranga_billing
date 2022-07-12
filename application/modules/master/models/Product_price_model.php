<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_price_model extends MY_Controller {
    private $table = 'cic_product_price_list';
    private $column_order = array(null,'pd.vProductName','pu.vProductUnitName',' pr.vPacketCount','pg.vGradeName','pr.fProductPrice'); //set column field database for datatable orderable
    private $column_search = array('pd.vProductName','pu.vProductUnitName','vPacketCount','pg.vGradeName','pr.fProductPrice'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_product_price($data){
        $this->db->insert('cic_product_price_list',$data);
    }
   
    private function list_data() {       
        $this->db->select('pr.*,pd.*,pu.*,pg.*');
        $this->db->join('cic_products as pd','pd.iProductId=pr.iProductId','left');
        $this->db->join('cic_product_unit as pu','pu.iProductUnitId=pr.iProductUnitId','left');
        $this->db->join('cic_master_grade as pg','pg.iGradeId=pr.iGradeId','left');
        $this->db->from('cic_product_price_list as pr');
        $this->db->order_by('pr.iProductPriceListId','desc');
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


    public function product_price_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_product_prices() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function product_price_by_id($id){
        $this->db->select('*');
        $this->db->where('iProductPriceListId',$id);
        $this->db->from('cic_product_price_list');
        return $this->db->get()->row_array();
    }

    public function update_product_price($id,$data){
        $this->db->where('iProductPriceListId',$id);
        $this->db->update('cic_product_price_list',$data);
    }

    public function delete_product_price($id){
        $this->db->where('iProductPriceListId',$id);
        $this->db->delete('cic_product_price_list');
    }


    public function get_products(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_products');
        return $this->db->get()->result_array();
    }

    public function get_units(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_product_unit');
        return $this->db->get()->result_array();
    }

    public function get_grades(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_grade');
        return $this->db->get()->result_array();
    }

}
