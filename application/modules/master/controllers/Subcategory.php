<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategory extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('subcategory_model'); 
    }

	public function index()
	{
        $data['title'] = 'Dashboard';
        $data['category'] = $this->subcategory_model->get_category();
        $this->template->write_view('content', 'subcategory', $data);
        $this->template->render();
	}

    public function add_subcategory(){
        $config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;
        $config['file_name'] = $_POST['subcategory_name'].'-'.rand(10000,10000000);
        
        $this->load->library('upload');
        $files = $_FILES;
        
        $_FILES['subcategory_image']['name']= $files['subcategory_image']['name'];
        $_FILES['subcategory_image']['type']= $files['subcategory_image']['type'];
        $_FILES['subcategory_image']['tmp_name']= $files['subcategory_image']['tmp_name'];
        $_FILES['subcategory_image']['error']= $files['subcategory_image']['error'];
        $_FILES['subcategory_image']['size']= $files['subcategory_image']['size'];    
        
        $this->upload->initialize($config);
        $this->upload->do_upload('subcategory_image');
        $upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];

        $subcategory['vSubcategoryName'] = $_POST['subcategory_name'];
        $subcategory['vSubcategoryName_Tamil'] = $_POST['subcategory_name_tamil'];
        $subcategory['iCategoryId'] = $_POST['category'];
        $subcategory['vImage'] = $file_name;
        $this->subcategory_model->get_subcategory($subcategory);
        // redirect('master/subcategory');
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function get_subcategories(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->subcategory_model->subcategory_list();
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iSubcategoryId.'" class="action-icon removeAttr " ><i class="fa fa-remove fs-5"></i></a>';
            $edit = '<a href="" data-id="'.$post->iSubcategoryId.'" class="action-icon addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><i class="fa fa-edit fs-5"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vSubcategoryName;   
            $row[] = $post->vCategoryName; 
            $row[] = $post->eStatus;
            $row[] = $edit.$delete;         
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->subcategory_model->count_all_subcategories(),
            "recordsFiltered" => $this->subcategory_model->count_all_subcategories(),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function edit_subcategory(){
        $region_id = $_POST['id'];
        $data = $this->subcategory_model->subcategory_by_id($region_id);
        echo json_encode($data);
        exit;
    }

    public function update_subcategory(){
        // print_r($_FILES['subcategory_image']['name']);exit;
        if(!empty($_FILES['subcategory_image']['name'])){
            $config = array();
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']      = '0';
            $config['overwrite']     = FALSE;
            $config['file_name'] = $_POST['subcategory_name'].'-'.rand(10000,10000000);
            
            $this->load->library('upload');
            $files = $_FILES;
            
            $_FILES['subcategory_image']['name']= $files['subcategory_image']['name'];
            $_FILES['subcategory_image']['type']= $files['subcategory_image']['type'];
            $_FILES['subcategory_image']['tmp_name']= $files['subcategory_image']['tmp_name'];
            $_FILES['subcategory_image']['error']= $files['subcategory_image']['error'];
            $_FILES['subcategory_image']['size']= $files['subcategory_image']['size'];    
            
            $this->upload->initialize($config);
            $this->upload->do_upload('subcategory_image');
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
        }else{
            $file_name = $_POST['subcategory_old_img'];
        }

        $id = $_POST['subcategory_id'];
        $subcategory = array(
            'vsubcategoryName' => $_POST['subcategory_name'],
            'vsubcategoryName_Tamil' => $_POST['subcategory_name_tamil'],
            'iCategoryId' => $_POST['category'],
            'vImage' => $file_name,
            'eStatus' => $_POST['status'],
        );
        $this->subcategory_model->update_subcategory($id,$subcategory);
        // redirect(base_url('master/subcategory'));
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function delete_subcategory(){
        $id = $_POST['id'];
        $this->subcategory_model->delete_subcategory($id);
    }
}
