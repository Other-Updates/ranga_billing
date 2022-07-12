<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class user_role extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
        redirect(base_url('users'));
    }
	    parent::__construct();
        $this->load->model('user_role_model'); 
    }

	public function index()
	{
        $data['title'] = 'User role';
        $data['user_roles'] = $this->user_role_model->get_user_roles();
        $this->template->write_view('content', 'user_role', $data);
        $this->template->render();
	}

    public function add_roles(){
        $role['vUserRole'] = $_POST['user_role'];
        $role['vUserRole_Tamil'] = $_POST['user_role_tamil'];
        $this->user_role_model->get_roles($role);
        // redirect('master/user_role');
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function edit_user_role(){
        $id = $_POST['id'];
        $details = $this->user_role_model->edit_user();
        echo json_encode($details);
    }

    public function edit_roles(){
        $id = $_POST['id']; 
        $details = $this->user_role_model->edit_roles($id);
        echo json_encode($details);
        exit;
    }

    public function update_roles(){
        $id = $_POST['userrole_id'];
        $roles = array(
            'vUserRole' => $_POST['user_role'],
            'vUserRole_Tamil' => $_POST['user_role_tamil'],
            'eStatus' => $_POST['status'],
        );
        $this->user_role_model->update_roles($id,$roles);
        // redirect(base_url('master/user_role'));
        echo json_encode(array(
            "statusCode"=>200
        ));
        exit;
    }

    public function delete_roles(){
        $id = $_POST['userrole_id'];
        $roles = array(
            'eStatus' => 'Deleted',
        );
        $this->user_role_model->update_roles($id,$roles);
        redirect(base_url('master/user_role'));
    }

    public function user_permission($role){
        // echo"<pre>";print_r($this->input->post());exit;
        if ($this->input->post('permissions', TRUE)) {
            $permissions = $this->input->post('permissions');
            $grand_all = $this->input->post('grand_all');
            $grand_all = !empty($grand_all) ? $grand_all : 0;
            $user_role = array('iGrandAll' => $grand_all);
            $this->user_role_model->update_user_role($user_role, $role);
            if (!empty($permissions)) {
                $this->user_role_model->delete_user_permission_by_role($role);
                foreach ($permissions as $module_id => $sections) {
                    if (!empty($sections)) {
                        foreach ($sections as $section_id => $item) {
                            $permission_arr = array(
                                'iUserRoleId' => $role,
                                'iModuleId' => $module_id,
                                'iSectionId' => $section_id,
                                'iAccAll' => !empty($item['iAccAll']) ? 1 : 0,
                                'iAccView' => !empty($item['iAccView']) ? 1 : 0,
                                'iAccAdd' => !empty($item['iAccAdd']) ? 1 : 0,
                                'iAccEdit' => !empty($item['iAccEdit']) ? 1 : 0,
                                'iAccDelete' => !empty($item['iAccDelete']) ? 1 : 0,
                                'dCreatedDate' => date('Y-m-d H:i:s')
                            );
                            $this->user_role_model->insert_user_permission($permission_arr);
                        }
                    }
                }
            }
            $this->session->set_flashdata('flashSuccess', 'User Role Permissions successfully updated!');
            redirect($this->config->item('base_url') . 'master/user_role');
        }
        $data['user_role'] = $this->user_role_model->edit_roles($role);
        $data['user_sections'] = $this->user_role_model->get_all_user_sections_with_modules();
        $user_permissions = $this->user_role_model->get_user_permissions_by_role($role);
        $user_permissions_arr = array();
        if (!empty($user_permissions)) {
            foreach ($user_permissions as $key => $value) {
                $user_permissions_arr[$value['iModuleId']][$value['iSectionId']] = array('acc_all' => $value['iAccAll'], 'acc_view' => $value['iAccView'], 'acc_add' => $value['iAccAdd'], 'acc_edit' => $value['iAccEdit'], 'acc_delete' => $value['iAccDelete']);
            
            }
        }
        $data['user_permissions'] = $user_permissions_arr;
        // echo"<pre>";print_r($data);exit;
        $this->template->write_view('content', 'user_permission',$data);
        $this->template->render();  
    }
}
