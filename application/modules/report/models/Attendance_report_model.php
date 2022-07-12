<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_report_model extends MY_Controller {
    private $table = 'cic_salesman_attendance_track';
    private $column_order = array(null,'us.vName','sat.dLoginTIme','sat.dLogoutTime'); //set column field database for datatable orderable
    private $column_search = array('vName','dLoginTIme','dLogoutTime'); //set column field database for datatable searchable 
    private $order = array('iSalesmanAttendanceTrackId' => 'desc'); // default descending order

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

   private function list_data() {       

    // select 
    // TRUNCATE(TIMESTAMPDIFF(HOUR, dLoginTIme,dLogoutTime)/24,0) AS days,
    // TIMESTAMPDIFF(HOUR, `dLoginTIme`,`dLogoutTime`)-TRUNCATE(TIMESTAMPDIFF(HOUR, `dLoginTIme`,`dLogoutTime`)/24,0)*24 AS hours,
    // (TIMESTAMPDIFF(MINUTE, `dLoginTIme`,`dLogoutTime`)-TRUNCATE(TIMESTAMPDIFF(HOUR, `dLoginTIme`,`dLogoutTime`)/24*60,0)*24) mins
    // from cic_salesman_attendance_track

    // $this->db->select("TRUNCATE(TIMESTAMPDIFF(HOUR, dLoginTIme,dLogoutTime)/24,0) AS days");

    $this->db->select('DATE_FORMAT(sat.dLoginTIme,"%d-%m-%y %h:%i") as dLoginTIme,DATE_FORMAT(sat.dLogoutTime,"%d-%m-%y %h:%i") as dLogoutTime,us.vName,TIMESTAMPDIFF(HOUR, `dLoginTIme`,`dLogoutTime`)-TRUNCATE(TIMESTAMPDIFF(HOUR, `dLoginTIme`,`dLogoutTime`)/24,0)*24 AS hours');
    $this->db->from('cic_salesman_attendance_track as sat');
    $this->db->join('cic_master_users as us','us.iUserId=sat.iUserId','left');
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

    public function salesman_login() {
        $this->list_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        
        $query = $this->db->get();
        return $query->result();
    }

    function count_all() {
        $this->list_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_user($id){
        $this->db->select('*');
        $this->db->where('iUserRoleId',3);
        $this->db->where('eStatus','Active');
        $this->db->from('cic_master_users');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_user_location($data){
        $this->db->select('*');
        $this->db->where('iUserId',$data['user_id']);
        if($data['from_date'] != null && $data['to_date'] == null)
        $this->db->where('dCreatedDate >',$data['from_date']);
        if($data['from_date'] == null && $data['to_date'] != null)
        $this->db->where('dCreatedDate <',$data['to_date']);
        if($data['from_date'] != null && $data['to_date'] != null)
        $this->db->where('dCreatedDate BETWEEN "' .$data['from_date']. '" AND "' .$data['to_date'].'"');
        $this->db->from('cic_salesman_location_update');
        return $this->db->get()->result_array();
        // return $this->db->last_query();

    }

    public function get_location($salesman_id,$date)
    {
        $date = str_replace('/', '-', $date);
        // $this->db->select('*');
        $this->db->select('dLatitude as lat,dLongitude as lng');
        $this->db->where('iSalesmanId',$salesman_id);
        $this->db->where('DATE_FORMAT(dCreatedDate, "%Y-%m-%d") = ',date("Y-m-d",strtotime($date)));
        $this->db->from('cic_salesman_location');
        return $this->db->get()->result_array();
    }
}
