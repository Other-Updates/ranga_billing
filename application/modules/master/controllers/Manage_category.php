<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_category extends MX_Controller
{

    public function __construct(){
        if(empty($this->session->userdata('LoggedId'))){
             redirect(base_url('users'));
         }
         parent::__construct();
         $this->load->model('expense_category_model'); 
     }

    public function index()
    {
        $data = array();
        // $data['language'] = $language = $this->language;
        $client_id = $this->session->userdata('LoggedId');
        $data['category_list'] = $this->expense_category_model->get_all_category_list();
        // echo '<pre>';
        // print_r($data);
        // exit;
        $this->template->write_view('content', 'master/manage_category_view', $data);
        $this->template->render();
    }

    public function add()
    {
        $client_id = $this->session->userdata('LoggedId');
        if ($this->input->post()) {
            $input = $this->input->post();
            $input['client_id'] = $client_id;
            $input['status'] = '1';
            $insert_id = $this->expense_category_model->insert_category($input);
            if (!empty($insert_id)) {
                $this->session->set_flashdata('flashSuccess', 'Expense Category successfully added!');
                redirect($this->config->item('base_url') . 'master/manage_category');
            } else {
                $this->session->set_flashdata('flashError', 'Expense Category not added');
                redirect($this->config->item('base_url') . 'master/manage_category');
            }
        }
    }

    public function edit($id)
    {
        $data = array();
        $client_id = $this->session->userdata('LoggedId');
        $data["category_list"] = $this->expense_category_model->get_all_category_list();
        $data["get_category"] = $this->expense_category_model->get_category_by_id($id);
        // echo '<pre>';
        // print_r($data);
        // exit;
        $this->template->write_view('content', 'master/edit_category_list', $data);
        $this->template->render();
    }

    public function update_category()
    {
        $client_id = $this->session->userdata('LoggedId');
        $data['language'] = $language = $this->language;
        if ($this->input->post()) {
            $input = $this->input->post();
            $input['client_id'] = $client_id;
            $id = $input['id'];
            unset($input['submit']);
            $update_id = $this->expense_category_model->update_category($input, $id);
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
        $delete = $this->expense_category_model->delete_category($id);
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

    public function add_duplicate_category()
    {
        $client_id = $this->session->userdata('LoggedId');
        $input = $this->input->post();
        $validation = $this->expense_category_model->add_duplicate_category($input, $client_id);
        if ($validation) {
            echo 'category_already_exists';
        }
    }

    public function update_duplicate_category()
    {
        $client_id = $this->session->userdata('LoggedId');
        $cat_name = $this->input->get('category');
        $id = $this->input->get('id');
        $validation = $this->expense_category_model->update_duplicate_category($cat_name, $id);
        if ($validation) {
            echo 'category_already_exists';
        }
    }
}
