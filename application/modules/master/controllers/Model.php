<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('model_model'); 
    }

	public function index()
	{
        $data['title'] = 'Dashboard';
        $this->template->write_view('content', 'model', $data);
        $this->template->render();
	}

    public function add_model(){
        $model['vModelName'] = $_POST['model_name'];
        $model['vModelName_Tamil'] = $_POST['model_name_tamil'];
        $model['dUpdatedDate'] = date('Y-m-d h:i:s');
        $this->model_model->get_model($model);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect('master/model');
    }

    public function get_models(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->model_model->model_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iModelId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="" data-id="'.$post->iModelId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vModelName;   
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->model_model->count_all_models(),
            "recordsFiltered" => $this->model_model->count_all_models(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_model(){
        $region_id = $_POST['id'];
        $data = $this->model_model->model_by_id($region_id);
        echo json_encode($data);
        exit;
    }

    public function update_model(){
        $id = $_POST['model_id'];
        $model = array(
            'vModelName' => $_POST['model_name'],
            'vModelName_Tamil' => $_POST['model_name_tamil'],
            'eStatus' => $_POST['status'],
        );
        $this->model_model->update_model($id,$model);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/model'));
    }

    public function delete_model(){
        $id = $_POST['id'];
        $update = array(
            'dUpdatedDate' => date('Y-m-d h:i:s'),
            'eStatus' => 'Deleted',
        );
        $this->model_model->update_model($id,$update);
    }
}
