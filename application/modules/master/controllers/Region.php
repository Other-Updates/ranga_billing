<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Region extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('region_model'); 
    }

	public function index()
	{
        $data['title'] = 'Region';
        $this->template->write_view('content', 'region', $data);
        $this->template->render();
	}

    public function add_region(){
        $region['vRegionName'] = $_POST['region_name'];
        $region['vRegionName_Tamil'] = $_POST['region_name_tamil'];
        $this->region_model->get_region($region);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect('master/region');
    }

    public function get_regions(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->region_model->region_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iRegionId.'" class="action-icon removeAttr " ><i class="fa fa-trash td-icon"></i></a>';
            $edit = '<a href="" data-id="'.$post->iRegionId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-pencil td-icon"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vRegionName;   
            $row[] = $post->tStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->region_model->count_all_regions(),
            "recordsFiltered" => $this->region_model->count_all_regions(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_region(){
        $region_id = $_POST['id'];
        $data = $this->region_model->region_by_id($region_id);
        echo json_encode($data);
        exit;
    }

    public function update_region(){
        $id = $_POST['region_id'];
        $region = array(
            'vRegionName' => $_POST['region_name'],
            'vRegionName_Tamil' => $_POST['region_name_tamil'],
            'eStatus' => $_POST['status'],
        );
        $this->region_model->update_region($id,$region);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/region'));
    }

    public function delete_region(){
        $id = $_POST['id'];
        $region = array(
            'eStatus' => 'Deleted',
        );
        $this->region_model->update_region($id,$region);    }
}
