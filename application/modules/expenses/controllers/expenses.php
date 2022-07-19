<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expenses extends MX_Controller
{
    public function __construct(){
        if(empty($this->session->userdata('LoggedId'))){
             redirect(base_url('users'));
         }
         parent::__construct();
         $this->load->model('expense_model');
         $this->load->model('order/order_model');  
     }

    public function index()
    {
        $head_office_id = $this->session->userdata('HeadOfficeId');
        $branch_id = $this->session->userdata('BranchId');
        $client_id = $this->session->userdata('LoggedId');
        $data = array();
        $data["category_list"] = $this->expense_model->get_all_category_list();
        $data['headoffice'] = $this->order_model->get_headOffice($head_office_id);
        $data['branches'] = $this->order_model->get_branch($branch_id);
        $data['sub_category_list'] = $this->expense_model->get_all_subcategory_list();
        // print_r($data); exit;
        $this->template->write_view('content', 'expenses/index', $data);
        $this->template->render();
    }

    public function expenses_list()
    {
        $client_id = $this->session->userdata('LoggedId');
        $branch_id = $this->session->userdata('BranchId');
        $data = array();
        // $data['firms'] = $firms = $this->expense_model->get_all_firms();
        // $data["firms"] = $firms = $this->manage_firms_model->get_all_firms($client_id);
        $data["category_list"] = $this->expense_model->get_all_category_list();
        $data['expense_details'] = $this->expense_model->get_all_expenses();
        $data['sub_category_list'] = $this->expense_model->get_all_subcategory_list();
        $data['branches'] = $this->order_model->get_branch($branch_id);
        // echo'<pre>';
        // print_r($data);
        //    exit;
        $this->template->write_view('content', 'expenses/expenses_list', $data);
        $this->template->render();
    }

    public function insert_expense()
    {
        $client_id = $this->session->userdata('LoggedId');
        if ($this->input->post()) {
            $input = $this->input->post();
            $entry_date = $input['created_at'];
            $ed = str_replace("/","-",$entry_date);
            $expense_amt = $input['amount'];
            $mode = $input['mode'];
            $input['created_at'] = date("Y-m-d",strtotime($ed));
            unset($input['submit']);
            $input['user_id'] = $client_id;
            $insertid = $this->expense_model->insert_expense($input);
            if(!empty($insertid))
            $this->session->set_flashdata('flashSuccess', 'New Expense successfully added!');
            else
            $this->session->set_flashdata('flashError', 'Operation Failed!');
            redirect($this->config->item('base_url') . 'expenses/expenses_list');
        }
    }

    public function get_subcategory()
    {
        $input = $this->input->post('category_id');
        $data = $this->expense_model->getSubCategory($input);
        echo json_encode($data);
    }

    public function get_company_amount()
    {

        $input = $this->input->post('firm_id');
        $data = $this->expense_model->getCompanyAmt($input);
        echo json_encode($data);
    }

    public function edit($id)
    {
        $head_office_id = $this->session->userdata('HeadOfficeId');
        $branch_id = $this->session->userdata('BranchId');
        $client_id = $this->session->userdata('LoggedId');
        $data = array();
        $data['headoffice'] = $this->order_model->get_headOffice($head_office_id);
        $data['branches'] = $this->order_model->get_branch($branch_id);
        $data["category_list"] = $this->expense_model->get_all_category_list();
        $data['expense_edit'] = $expense_edit = $this->expense_model->edit_expenses($id);
        $category_id = $sub_category_list = '';
        if (!empty($expense_edit)) {
            $category_id = $expense_edit[0]['cat_id'];
            $sub_category_list = $this->expense_model->getSubCategory($category_id);
        }
        $data['sub_category_list'] = $sub_category_list;
        $this->template->write_view('content', 'expenses/edit_expense', $data);
        $this->template->render();
    }

    public function update_expenses($id)
    {
        $client_id = $this->session->userdata('LoggedId');
        $input = array();
        if ($this->input->post()) {
            $input = $this->input->post();
            $entry_date = $input['created_at'];
            $ed = str_replace("/","-",$entry_date);
            $expense_amt = $input['amount'];
            $mode = $input['mode'];
            $input['created_at'] = date("Y-m-d",strtotime($ed));
            $input['updated_at'] = date('Y-m-d H:i:s');
            unset($input['submit']);
            $updateid = $this->expense_model->update_expenses($input, $id);
            if($updateid)
            $this->session->set_flashdata('flashSuccess', 'Expense successfully updated!');
            else
            $this->session->set_flashdata('flashError', 'Expense updated Failed!');
            redirect($this->config->item('base_url') . 'expenses/expenses_list');
        }
    }

    public function balance_list()
    {
        $data = array();
        $client_id = $this->session->userdata('LoggedId');
        $data["firms"] = $firms = $this->manage_firms_model->get_all_firms($client_id);
        // $data['firms'] = $firms = $this->expense_model->get_all_firms();
        $data['balance_list'] = $this->expense_model->get_all_balance();
        $this->template->write_view('content', 'expenses/balance_list', $data);
        $this->template->render();
    }
    public function delete()
    {
        $id = $this->input->POST('delete_id');
        $delete = $this->expense_model->delete($id);
            if($delete){
                echo json_encode(array(
                    "statusCode"=>200
                ));
                exit;
            }
}

    function expenses_ajaxList()
    {
        $search_data = array();
        $search_data = $this->input->post();
        $list = $this->expense_model->get_datatables($search_data);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $ass) {
            if($this->session->userdata('UserRole') == 1 || $this->session->userdata('UserRole') == 2) {
                $edit_row = '<a class="action-icon" href="' . base_url() . 'expenses/edit/' . $ass->id . '"><i class="fa fa-pencil td-icon"></i></a>';
            } else {
                $edit_row = '<a class="action-icon alerts" href=""><i class="fa fa-pencil td-icon"></i></a>';
            }
            $delete_row = '';
            if($this->session->userdata('UserRole') == 1 || $this->session->userdata('UserRole') == 2) {
                // $delete_row = '<a class="action-icon" href="' . base_url() . 'expenses/delete/'  . $ass->id . '"><i class="fa fa-trash"></i></a>';
                $delete_row = '<a  id="' . $ass->id . '" class="delete_row deleteexp action-icon" delete_id="' . $ass->id . '"><i class="fa fa-trash td-icon"></i></a>';
            } else {
                $delete_row = '<a class="alerts action-icon" ><i class="fa fa-trash td-icon"></i></a>';
            }
            $no++;
            $row = array();
            $row[] = $ass->vBranchName;
            $row[] = ($ass->created_at != '' && $ass->created_at != '0000-00-00 00:00:00') ? date('d-m-y', strtotime($ass->created_at)) : '-';
            $row[] = ucfirst($ass->type);
            $row[] = ucfirst($ass->category);
            $row[] = ucfirst($ass->sub_category);
            $row[] = ucfirst($ass->mode);
            if($ass->mode=='debit')
            $row[] = number_format($ass->amount ? $ass->amount : '0.00', 2);
            else
            $row[] = '';
            if($ass->mode=='credit')
            $row[] = number_format($ass->amount ? $ass->amount : '0.00', 2);
            else
            $row[] = '';
            $row[] = $edit_row .'&nbsp;'. $delete_row;
            // $row[] = $delete_row;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->expense_model->count_all(),
            "recordsFiltered" => $this->expense_model->count_filtered($search_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    function balancesheet_ajaxList()
    {
        $search_data = array();
        $search_data = $this->input->post();
        $list = $this->expense_model->get_balance_datatables($search_data);
        $get_company_bal = $this->expense_model->getCompanyAmt($search_data["firm_id"]);

        $com_bal = '';
        if (!empty($get_company_bal))
            $com_bal[] = [1, $get_company_bal[0]['firm_name'], "Opening Balance", "-", "-", "0.00", $get_company_bal[0]['opening_balance'], $get_company_bal[0]['opening_balance']];

        $data = array();
        $no = $_POST['start'];
        if ($com_bal) {
            $no = $_POST['start'] + 1;
        }
        foreach ($list as $ass) {
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = $ass->prefix;
            //            $row[] = $ass->company_amount;
            if ($ass->comments == 1) {
                $type = 'Sales';
            } elseif ($ass->comments == 2) {
                $type = 'Purchase';
            } else {
                $type = 'Expenses';
            }
            $row[] = $type;
            if ($ass->comments == 1) {
                $details = ' ' . number_format($ass->opening_balance, 2) . ' ' . $ass->inv_id . ' ' . $ass->cust_name;
            } elseif ($ass->comments == 2) {
                $details = ' ' . number_format($ass->opening_balance, 2) . ' ' . $ass->po_no . ' ' . $ass->store_name;
            } else {
                $details = ' ' . number_format($ass->opening_balance, 2) . ' ' . $ass->type . ' ' . $ass->category . ' ' . $ass->sub_category . ' ' . $ass->remarks;
            }
            $row[] = $details;
            $row[] = ($ass->created_at != '' && $ass->created_at != '0000-00-00 00:00:00') ? date('d-M-Y', strtotime($ass->created_at)) : '-';
            if ($ass->mode == 'debit' && ($ass->amount > 0)) {
                $debit_amount = ($ass->amount);
            } elseif ($ass->mode == 'credit' && ($ass->amount < 0)) {
                $debit_amount = (abs($ass->amount));
            } else {
                $debit_amount = '0.00';
            }
            $row[] = number_format($debit_amount, 2);
            if ($ass->mode == 'credit' && ($ass->amount > 0)) {
                $credit_amount = ($ass->amount);
            } elseif ($ass->mode == 'debit' && ($ass->amount < 0)) {
                $credit_amount = (abs($ass->amount));
            } else {
                $credit_amount = '0.00';
            }
            $row[] = number_format($credit_amount, 2);
            $row[] = $ass->balance ? $ass->balance : '0.00';
            $data[] = $row;
        }
        if ($com_bal) {
            $data = array_merge($com_bal, $data);
        }
        if (!empty($_POST['order']['0']['column']) && ($_POST['order']['0']['column'] == 1)) {
            if (!empty($_POST['order']['0']['dir']) && $_POST['order']['0']['dir'] == 'desc')
                array_multisort(array_column($data, 1), SORT_DESC, $data);
            else
                array_multisort(array_column($data, 1), SORT_ASC, $data);
        }

        if (!empty($_POST['order']['0']['column']) && ($_POST['order']['0']['column'] == 2)) {
            if (!empty($_POST['order']['0']['dir']) && $_POST['order']['0']['dir'] == 'desc')
                array_multisort(array_column($data, 2), SORT_DESC, $data);
            else
                array_multisort(array_column($data, 2), SORT_ASC, $data);
        }
        // echo "<pre>";
        // print_r($data);
        // exit;
        if (!empty($_POST['order']['0']['column']) && ($_POST['order']['0']['column'] == 3)) {
            if (!empty($_POST['order']['0']['dir']) && $_POST['order']['0']['dir'] == 'desc')
                array_multisort(array_column($data, 3), SORT_DESC, $data);
            else
                array_multisort(array_column($data, 3), SORT_ASC, $data);
        }
        if (!empty($_POST['order']['0']['column']) && ($_POST['order']['0']['column'] == 4)) {
            if (!empty($_POST['order']['0']['dir']) && $_POST['order']['0']['dir'] == 'desc')
                array_multisort(array_column($data, 4), SORT_DESC, $data);
            else
                array_multisort(array_column($data, 4), SORT_ASC, $data);
        }
        if (!empty($_POST['order']['0']['column']) && ($_POST['order']['0']['column'] == 5)) {
            if (!empty($_POST['order']['0']['dir']) && $_POST['order']['0']['dir'] == 'desc')
                array_multisort(array_column($data, 5), SORT_DESC, $data);
            else
                array_multisort(array_column($data, 5), SORT_ASC, $data);
        }
        if (!empty($_POST['order']['0']['column']) && ($_POST['order']['0']['column'] == 6)) {
            if (!empty($_POST['order']['0']['dir']) && $_POST['order']['0']['dir'] == 'desc')
                array_multisort(array_column($data, 6), SORT_DESC, $data);
            else
                array_multisort(array_column($data, 6), SORT_ASC, $data);
        }
        /*Array split*/
        if ($_POST['length'] != -1)
            $data = array_slice($data, $_POST['start'], $_POST['length']);
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->expense_model->count_all_balance(),
            "recordsFiltered" => $this->expense_model->count_filtered_balance($search_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    function getall_expenses_entries()
    {

        $search_data = array();
        $search_data = $this->input->get();
        $expenses_data = $this->expense_model->get_expenses_datas($search_data);
        //        echo "<pre>"; print_r($data);  exit;
        $this->export_all_expenses_csv($expenses_data);
    }

    function export_all_expenses_csv($query, $timezones = array())
    {

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Expenses Report.csv');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        // output the column headings
        //        Order has been changes
        fputcsv($output, array('S.No', 'Expense Type', 'Category', 'Subcategory', 'Mode', 'Expense Amount', 'Created Date'));
        // fetch the data
        //$rows = mysql_query($query);
        // loop over the rows, outputting them

        foreach ($query as $key => $value) {
            $row = array($key + 1, $value['type'], $value['category'], $value['sub_category'], $value['mode'], number_format($value['amount'], 2), ($value['created_at'] != '1970-01-01') ? date('d-M-Y', strtotime($value['created_at'])) : '');
            fputcsv($output, $row);
        }

        exit;
    }

    function getall_balance_entries()
    {
        $search_data = array();
        $search_data = $this->input->get();

        $balance_data = $this->expense_model->get_balance_datas($search_data);
        //        echo "<pre>"; print_r($data);  exit;
        $this->export_all_balance_data_csv($balance_data);
    }

    function export_all_balance_data_csv($query, $timezones = array())
    {

        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Balance Report.csv');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        //Order has been changes
        fputcsv($output, array('S.No', 'Company', 'Type', 'Details', 'Created Date', 'Debit Amt', 'Credit Amt', 'Balance'));

        // fetch the data
        //$rows = mysql_query($query);
        // loop over the rows, outputting them

        foreach ($query as $key => $value) {
            if ($value['comments'] == 1) {
                $type = 'Sales';
            } elseif ($value['comments'] == 2) {
                $type = 'Purchase';
            } else {
                $type = 'Expenses';
            }

            if ($value['comments'] == 1) {
                $details = ' ' . number_format($value['opening_balance'], 2) . ' ' . $value['inv_id'] . ' ' . $value['cust_name'];
            } elseif ($value['comments'] == 2) {
                $details = ' ' . number_format($value['opening_balance'], 2) . ' ' . $value['po_no'] . ' ' . $value['store_name'];
            } else {

                $details = ' ' . number_format($value['opening_balance'], 2) . ' ' . $value['type'] . ' ' . $value['category'] . ' ' . $value['sub_category'] . ' ' . $value['remarks'];
            }
            if ($value['mode'] == 'debit' && ($value['amount'] > 0)) {
                $debit_amount = ($value['amount']);
            } elseif ($value['mode'] == 'credit' && ($value['amount'] < 0)) {
                $debit_amount = (abs($value['amount']));
            } else {
                $debit_amount = '0.00';
            }
            if ($value['mode'] == 'credit' && ($value['amount'] > 0)) {
                $credit_amount = ($value['amount']);
            } elseif ($value['mode'] == 'debit' && ($value['amount'] < 0)) {
                $credit_amount = (abs($value['amount']));
            } else {
                $credit_amount = '0.00';
            }
            $row = array($key + 1, $value['prefix'], $type, $details, ($value['created_at'] != '1970-01-01') ? date('d-M-Y', strtotime($value['created_at'])) : '', number_format($debit_amount, 2), number_format($credit_amount, 2), number_format($value['balance'] ? $value['balance'] : '0.00', 2));
            fputcsv($output, $row);
        }
        exit;
    }
}
