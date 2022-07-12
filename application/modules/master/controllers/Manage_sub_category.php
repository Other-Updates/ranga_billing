<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_sub_category extends MX_Controller
{

    public function __construct(){
        if(empty($this->session->userdata('LoggedId'))){
             redirect(base_url('users'));
         }
         parent::__construct();
         $this->load->model('expense_subcategory_model'); 
     }

    public function index()
    {
        $data = array();
        // $data['language'] = $language = $this->language;
        $client_id = $this->session->userdata('LoggedId');
        $data['all_list'] = $this->expense_subcategory_model->get_all_list();
        $data['category_list'] = $this->expense_subcategory_model->get_all_category_list();
        // echo '<pre>';
        // print_r($data);
        // exit;
        $this->template->write_view('content', 'master/manage_sub_category_view', $data);
        $this->template->render();
    }

    public function insert_sub_category()
    {
        // $data['language'] = $language = $this->language;
        $client_id = $this->session->userdata('LoggedId');
        if ($this->input->post()) {
            $input = $this->input->post();
            $datas['category_id'] = $input['category'];
            $datas['sub_category'] = $input['sub_category'];
            $insert_id = $this->expense_subcategory_model->insert_category($datas);
            if (!empty($insert_id)) {
                $this->session->set_flashdata('flashSuccess', 'Expense Subcategory Successfully added!');
                redirect($this->config->item('base_url') . 'master/manage_sub_category');
            } else {
                $this->session->set_flashdata('flashError', 'Expense Subcategory not added');
                redirect($this->config->item('base_url') . 'master/manage_sub_category');
            }
        }
    }

    public function edit($id)
    {
        $data = array();
        $data['language'] = $language = $this->language;
        $client_id = $this->session->userdata('LoggedId');
        $data["get_category"] = $this->expense_subcategory_model->get_subcategory_by_id($id);
        $data["category_list"] = $this->expense_subcategory_model->get_all_category_list($client_id);
        // echo '<pre>';
        // print_r($data);
        // exit;
        $this->template->write_view('content', 'master/edit_subcategory_list', $data);
        $this->template->render();
    }

    public function update_subcategory($id)
    {
        $data = array();
        if ($this->input->post()) {
            $input = $this->input->post();
            $datas['sub_category'] = $input['sub_category'];
            $datas['category_id'] = $input['category_id'];
            $datas['status'] = '1';
            $id = $input['id'];
            $update_id = $this->expense_subcategory_model->update_subcategory($datas, $id);
            if (!empty($update_id)) {
                echo json_encode(array(
                    "statusCode"=>200
                ));
                exit;
            } else {
                echo json_encode(array(
                    "statusCode"=>400
                ));
                exit;
            }
        }
    }

    public function delete()
    {
        $id = $this->input->POST('value1');
        $delete = $this->expense_subcategory_model->delete_subcategory($id);
        if ($delete) {
            echo json_encode(array(
                "statusCode"=>200
            ));
            exit;
        } else {
            echo json_encode(array(
                "statusCode"=>400
            ));
            exit;
        }
    }

    public function add_duplicate_subcategory()
    {
        $data['language'] = $language = $this->language;
        $client_id = $this->session->userdata('LoggedId');
        $input = $this->input->post();
        $validation = $this->expense_subcategory_model->add_duplicate_subcategory($input, $client_id);
        if ($validation) {
            echo 'category_already_exists';
        }
    }

    public function update_duplicate_subcategory()
    {
        $data['language'] = $language = $this->language;
        $client_id = $this->session->userdata('LoggedId');
        $input = $this->input->post();
        $validation = $this->expense_subcategory_model->update_duplicate_subcategory($input, $client_id);
        if ($validation) {
            echo 'category_already_exists';
        }
    }
}
