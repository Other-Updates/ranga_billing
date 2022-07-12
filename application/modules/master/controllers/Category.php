<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('category_model'); 
    }

	public function index()
	{
        $data['title'] = 'category';
        $data['headoffice'] = $this->category_model->get_headoffice();
        $this->template->write_view('content', 'category', $data);
        $this->template->render();
	}

    public function add_category(){
        $config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $_POST['category_name'].'-'.rand(10000,10000000);
        
        $this->load->library('upload');
        $files = $_FILES;
        
        $_FILES['category_image']['name']= $files['category_image']['name'];
        $_FILES['category_image']['type']= $files['category_image']['type'];
        $_FILES['category_image']['tmp_name']= $files['category_image']['tmp_name'];
        $_FILES['category_image']['error']= $files['category_image']['error'];
        $_FILES['category_image']['size']= $files['category_image']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('category_image');
        $upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];
        $category['vcategoryName'] = $_POST['category_name'];
        $category['IGST'] = $_POST['igst'];
        $category['CGST'] = $_POST['cgst'];
        $category['SGST'] = $_POST['sgst'];
        $category['vcategoryName_Tamil'] = $_POST['category_name_tamil'];
        $category['vImage'] = $file_name;
        $category['dCreatedDate'] = date('Y-m-d h:i:s');
        $this->category_model->get_category($category);
        // redirect('master/category');
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function get_categories(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->category_model->category_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iCategoryId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="" data-id="'.$post->iCategoryId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vCategoryName;   
            if(file_exists(FCPATH."uploads/".$post->vImage)){
                $row[] = '<img src="'.base_url().'uploads/'.$post->vImage.'" class="img-thumbnail" width="50" height="50" />';
                }else{
                    $row[] = '<img src="'.base_url().'uploads/logo/logo.png" class="img-thumbnail" width="50" height="50" />';
                }            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->category_model->count_all_categories(),
            "recordsFiltered" => $this->category_model->count_all_categories(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_category(){
        $category_id = $_POST['id'];
        $data = $this->category_model->category_by_id($category_id);
        echo json_encode($data);
        exit;
    }

    public function update_category(){
        $config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $_POST['category_name'].'-'.rand(10000,10000000);
        
        $this->load->library('upload');
        $files = $_FILES;
        
        $_FILES['category_image']['name']= $files['category_image']['name'];
        $_FILES['category_image']['type']= $files['category_image']['type'];
        $_FILES['category_image']['tmp_name']= $files['category_image']['tmp_name'];
        $_FILES['category_image']['error']= $files['category_image']['error'];
        $_FILES['category_image']['size']= $files['category_image']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('category_image');
        $upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];
        $id = $_POST['category_id'];
        $category = array(
            'vCategoryName' => $_POST['category_name'],
            'vCategoryName_Tamil' => $_POST['category_name_tamil'],
            'IGST' => $_POST['igst'],
            'CGST' => $_POST['cgst'],
            'SGST' => $_POST['sgst'],
            'eStatus' => $_POST['status'],
            'vImage' => $file_name,
        );
        $this->category_model->update_category($id,$category);
        // redirect(base_url('master/category'));
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function delete_category(){
        $id = $_POST['id'];
        $category = array(
            'eStatus' => 'Deleted',
        );
        $this->category_model->update_category($id,$category);
        redirect(base_url('master/category'));
    }

    public function get_branch(){
        $headoffice_id = $_POST['headoffice'];
        $data = $this->category_model->get_branch_by_headoffice($headoffice_id);
        echo json_encode($data);
        exit;
    }
}
