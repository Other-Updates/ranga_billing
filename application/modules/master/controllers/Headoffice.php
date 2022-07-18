<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Headoffice extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('headoffice_model');
    }

	public function index()
	{
        $data['title'] = 'headoffice';
        $data['state'] = $this->headoffice_model->get_state();
        $data['region'] = $this->headoffice_model->get_region();
        $this->template->write_view('content', 'headoffice', $data);
        $this->template->render();
	}

    public function add_headoffice(){
        $headoffice['vHeadOfficeName'] = $_POST['headoffice_name'];
        $headoffice['vHeadOfficeName_Tamil'] = $_POST['headoffice_name_tamil'];
        $headoffice['iStateId'] = $_POST['state'];
        $headoffice['iRegionId'] = $_POST['region'];
        $headoffice['dCreatedDate'] = date('Y-m-d');

        $this->headoffice_model->get_headoffice($headoffice);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect('master/headoffice');
    }

    public function get_headoffices(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->headoffice_model->headoffice_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iHeadOfficeId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iHeadOfficeId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vHeadOfficeName;   
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->headoffice_model->count_all_headoffices(),
            "recordsFiltered" => $this->headoffice_model->count_all_headoffices(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_headoffice(){
        $headoffice_id = $_POST['id'];
        $data = $this->headoffice_model->headoffice_by_id($headoffice_id);
        echo json_encode($data);
        exit;
    }

    public function update_headoffice(){
        $id = $_POST['headoffice_id'];
        $headoffice['vheadofficeName'] = $_POST['headoffice_name'];
        $headoffice['vheadofficeName_Tamil'] = $_POST['headoffice_name_tamil'];
        $headoffice['iStateId'] = $_POST['state'];
        $headoffice['iRegionId'] = $_POST['region'];
        $headoffice['eStatus'] = $_POST['status'];
        $this->headoffice_model->update_headoffice($id,$headoffice);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/headoffice'));
    }

    public function delete_headoffice(){
        $id = $_POST['id'];
        $this->headoffice_model->delete_headoffice($id);
    }
}
