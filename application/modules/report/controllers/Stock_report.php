<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_report extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url('master/user'));
        }
	    parent::__construct();
        $this->load->model('stock_report_model');
    }

	public function index()
	{
        $branch_id = $this->session->userdata('BranchId');
        $data['title'] = 'stock_report';
        $data['branch'] = $this->stock_report_model->get_branch();
        $data['categories'] = $this->stock_report_model->get_category();
        $data['subcategories'] = $this->stock_report_model->get_subcategory();
        $this->template->write_view('content', 'stock_report', $data);
        $this->template->render();
	}

    public function warehouse_stock_report()
    {
        $data['categories'] = $this->stock_report_model->get_category();
        $data['subcategories'] = $this->stock_report_model->get_subcategory();
        $this->template->write_view('content', 'warehouse_stock_report', $data);
        $this->template->render();
    }

    public function get_category(){
        $category = $this->stock_report_model->get_category();
        echo json_encode($category);
        exit;
    }

    public function get_branch_stock(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->stock_report_model->stock_branch($input_data);
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            $delete = '<a href="" data-id="'.$post->iStockId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            $edit = '<a href="'.base_url('stock/edit_stock/'.$post->iStockId).'" data-id="'.$post->iStockId.'" class="action-icon addAttr"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vHeadOfficeName;
            $row[] = $post->vBranchName;
            $row[] = $post->vCategoryName;
            $row[] = $post->vSubcategoryName;
            $row[] = $post->vProductName;
            $row[] = $post->vProductUnitName;
            $row[] = $post->dProductQty;
            // $row[] = $edit.$delete;
            $row[] = number_format($post->dProductQty * $post->fProductPrice,2);
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stock_report_model->count_all_stock($input_data),
            "recordsFiltered" => $this->stock_report_model->count_all_stock($input_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function get_warehouse_stock(){
        $data = $input_arr = array();
        $input_data = $this->input->post();
        $list=$this->stock_report_model->warehouse_stock($input_data);
        $sno = $input_data['start'] + 1;
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $key=>$post) {
            // $delete = '<a href="" data-id="'.$post->iStockId.'" class="action-icon removeAttr " ><i class="icofont icofont-ui-delete"></i></a>';
            // $edit = '<a href="'.base_url('stock/edit_stock/'.$post->iStockId).'" data-id="'.$post->iStockId.'" class="action-icon addAttr"><i class="icofont icofont-ui-edit"></i></a>';
            $row = array();
            $row[] = $sno++;
            $row[] = $post->vCategoryName;
            $row[] = $post->vSubcategoryName;
            $row[] = $post->vProductName;
            $row[] = $post->vProductUnitName;
            $row[] = $post->dProductQty;
            $row[] = number_format($post->dProductQty * $post->iPurchaseCostperQTY,2);
            // $row[] = $edit.$delete;
            $row[] = $post->dProductQty * $post->iPurchaseCostperQTY;
            $data[] = $row;
        }
        $output = array(    
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stock_report_model->count_all_warestock($input_data),
            "recordsFiltered" => $this->stock_report_model->count_all_warestock($input_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

}