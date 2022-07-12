<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_report extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url());
        }
	    parent::__construct();
        $this->load->model('attendance_report_model'); 
    }
    public function index(){
        $data['title'] = 'Attendance';
        $this->template->write_view('content', 'attendance_report', $data);
        $this->template->render();
    }

    public function get_salesman_login(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->attendance_report_model->salesman_login();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {

            $row = array();
            $row[] = $sno++;   
            $row[] = $post->vName;               
            $row[] = $post->dLoginTIme;
            $row[] = $post->dLogoutTime;
            $row[] = $post->hours;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->attendance_report_model->count_all(),
            "recordsFiltered" => $this->attendance_report_model->count_all(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function track_salesman(){
        $data['title'] = 'track';
        $data['salesman'] = $this->attendance_report_model->get_user();
        $this->template->write_view('content', 'track_salesman', $data);
        $this->template->render();
    }

    public function user_location(){
        $location = array(
            'user_id' => $_POST['user_id'],
            'from_date' => $_POST['from_date'],
            'to_date' => $_POST['to_date'],
        );
        $user_location = $this->attendance_report_model->get_user_location($location);
        echo json_encode($user_location);
        exit;
    }

    public function get_salesman_location()
    {
        $salesman_id = $this->input->post('salesman');
        $date = $this->input->post('date');
        $result = $this->attendance_report_model->get_location($salesman_id,$date);
        echo json_encode($result,JSON_NUMERIC_CHECK );
        exit;
    }
}