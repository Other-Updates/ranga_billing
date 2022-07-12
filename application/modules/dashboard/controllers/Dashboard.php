<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url('users'));
        }
	    parent::__construct();
        $this->load->model('dashboard_model');
    }

	public function index()
	{
        $data['title'] = 'Dashboard';
        $data['today_sales'] = $this->dashboard_model->get_sales_data('DAY');
        $data['monthly_sales'] = $this->dashboard_model->get_sales_data("MONTH");
        $data['today_purchase'] = $this->dashboard_model->get_purchase_data("DAY");
        $data['monthly_purchase'] = $this->dashboard_model->get_purchase_data("MONTH");
        $data['most_selling'] = $this->dashboard_model->get_most_selling_product();
        $data['products'] = $this->dashboard_model->products_count();
        $data['downloaded_users'] = $this->dashboard_model->downloaded_users();
        $data['monthly_sales_graph'] = $this->dashboard_model->monthly_sales();
        $data['category_count'] = $this->dashboard_model->category_count();
        $data['subcategory_count'] = $this->dashboard_model->subcategory_count();
        // echo "<pre>";print_r($data['monthly_sales_graph']);exit;
        $this->template->write_view('content', 'dashboard', $data);
        $this->template->render();
	}
}