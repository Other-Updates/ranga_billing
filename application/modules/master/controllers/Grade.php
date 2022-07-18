<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('grade_model'); 
    }

	public function index()
	{
        $data['title'] = 'grade';
        $this->template->write_view('content', 'grade', $data);
        $this->template->render();
	}

    public function add_grade(){
        $grade['vGradeName'] = $_POST['grade_name'];
        $this->grade_model->get_grade($grade);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect('master/grade');
    }

    public function get_grades(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->grade_model->grade_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iGradeId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iGradeId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            if($post->vGradeName == "A Grade" || $post->vGradeName == "B Grade" || $post->vGradeName == "C Grade" || $post->vGradeName == "D Grade"){
                $delete = '';
                $edit = '';
            }
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vGradeName;   
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->grade_model->count_all_grades(),
            "recordsFiltered" => $this->grade_model->count_all_grades(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_grade(){
        $grade_id = $_POST['id'];
        $data = $this->grade_model->grade_by_id($grade_id);
        echo json_encode($data);
        exit;
    }

    public function update_grade(){
        $id = $_POST['grade_id'];
        $grade = array(
            'vGradeName' => $_POST['grade_name'],
            'eStatus' => $_POST['status'],
        );
        $this->grade_model->update_grade($id,$grade);
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
        // redirect(base_url('master/grade'));
    }

    public function delete_grade(){
        $id = $_POST['id'];
        $grade = array(
            'eStatus' => 'Deleted',
        );
        $this->grade_model->update_grade($id,$grade);
    }
}
