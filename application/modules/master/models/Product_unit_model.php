<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_unit_model extends MY_Controller {
    private $table = 'cic_product_unit';
    private $column_order = array(null,'vProductUnitName','eStatus'); //set column field database for datatable orderable
    private $column_search = array('vProductUnitName','eStatus'); //set column field database for datatable searchable 
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_product_unit($data){
        $this->db->insert('cic_product_unit',$data);
        return $this->db->insert_id();
    }
   
    private function list_data() {       
        $this->db->select('iProductUnitId,vProductUnitName,eStatus');
        $this->db->where('eStatus!=','Deleted');
        $this->db->order_by('iProductUnitId','desc');
        $this->db->from('cic_product_unit');
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


    public function product_unit_list() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_product_units() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function product_unit_by_id($id){
        $this->db->select('*');
        $this->db->where('iProductUnitId',$id);
        $this->db->from('cic_product_unit');
        return $this->db->get()->row_array();
    }

    public function update_product_unit($id,$data){
        $this->db->where('iProductUnitId',$id);
        $this->db->update('cic_product_unit',$data);
    }

}
