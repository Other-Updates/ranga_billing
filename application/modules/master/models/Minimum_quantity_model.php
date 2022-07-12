<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Minimum_quantity_model extends MY_Controller {
    private $table = 'cic_product_min_quantity';
    private $column_order = array(null,'vHeadOfficeName','vBranchName','vProductName','vProductUnitName','iMinQty'); //set column field database for datatable orderable
    private $column_search = array('vHeadOfficeName','vBranchName','vProductName','vProductUnitName','iProductMinQtyId'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

   public function get_headoffice(){
       $this->db->select('*');
       $this->db->where('eStatus','Active');
       $this->db->from('cic_master_headoffice');
       return $this->db->get()->result_array();
   }

   public function get_minimum_quantity($data){
       $this->db->insert('cic_product_min_quantity',$data);
       return $this->db->insert_id();
   }

   private function list_data() {
        $this->db->select('mq.iProductMinQtyId,br.vBranchName,pr.vProductName,pu.vProductUnitName,iMinQty');
        $this->db->join('cic_master_branch as br','br.iBranchId=mq.iBranchId','left');
        // $this->db->join('cic_master_headoffice as ho','ho.iHeadOfficeId=mq.iHeadOfficeId','left');
        $this->db->join('cic_products as pr','pr.iProductId=mq.iProductId','left');
        $this->db->join('cic_product_unit as pu','pu.iProductUnitId=mq.iProductUnitId','left');
        $this->db->from('cic_product_min_quantity as mq');
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


    public function minimum_quantity_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_minimum_quantity() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function minimum_quantity_by_id($id){
        $this->db->select('*');
        $this->db->where('iProductMinQtyId',$id);
        $this->db->from('cic_product_min_quantity');
        return $this->db->get()->row_array();
    }

    public function update_minimum_quantity($id,$data){
        $this->db->where('iProductMinQtyId',$id);
        $this->db->update('cic_product_min_quantity',$data);
    }

    public function delete_minimum_quantity($id){
        $this->db->where('iProductMinQtyId',$id);
        $this->db->delete('cic_product_min_quantity');
    }

    public function get_branch(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_branch');
        return $this->db->get()->result_array();
    }

    public function get_products(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_products');
        return $this->db->get()->result_array();
    }

    public function get_unit(){
        $this->db->select('*');
        $this->db->where('eStatus','Active');
        $this->db->from('cic_product_unit');
        return $this->db->get()->result_array();
    }

}
