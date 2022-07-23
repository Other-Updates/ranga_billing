<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_receipt extends MX_Controller {

    public function __construct(){
        if(empty($this->session->userdata('LoggedId'))){
             redirect(base_url('users'));
         }
         parent::__construct();
         $this->load->model('purchase_receipt/purchase_receipt_model'); 
         //$this->load->library('mpdf');
     }

    public function index() {
        $this->load->model('purchase_receipt/purchase_receipt_model');
        $this->load->model('master_style/master_model');
        $data["last_id"] = $this->master_model->get_last_id('rp_code');
        $no[1] = substr($data["last_id"][0]['value'], 3);
        if (date('m') > 3) {
            $check_no = 'RP' . date('y') . (date('y') + 1) . '0001';
            $check_res = $this->purchase_receipt_model->check_so_no($check_no);
            if (empty($check_res)) {
                $data['last_no'] = 'RP' . date('y') . (date('y') + 1) . '0001';
            } else
                $data['last_no'] = 'RP' . date('y') . (date('y') + 1) . str_pad(substr($no[1], 4, 8) + 1, 4, '0', STR_PAD_LEFT);
        }else {
            $check_no = 'RP' . (date('y') - 1) . date('y') . '0001';
            $check_res = $this->purchase_receipt_model->check_so_no($check_no);
            if (empty($check_res)) {
                $data['last_no'] = 'RP' . (date('y') - 1) . date('y') . '0001';
            } else
                $data['last_no'] = 'RP' . (date('y') - 1) . date('y') . str_pad(substr($no[1], 4, 8) + 1, 4, '0', STR_PAD_LEFT);
        }
        if ($this->input->post()) {
            $input = $this->input->post();
            $this->purchase_receipt_model->update_invoice_status($input['inv_no']);
            if ($input['balance'] == 0)
                $input['receipt']['complete_status'] = 1;
            else
                $input['receipt']['complete_status'] = 0;
            $input['receipt']['due_date'] = date('Y-m-d', strtotime($input['receipt']['due_date']));


            $data["last_id"] = $this->master_model->get_last_id('rp_code');
            $no[1] = substr($data["last_id"][0]['value'], 3);
            if (date('m') > 3) {
                $check_no = 'RP' . date('y') . (date('y') + 1) . '0001';
                $check_res = $this->purchase_receipt_model->check_so_no($check_no);
                if (empty($check_res)) {
                    $data['last_no'] = 'RP' . date('y') . (date('y') + 1) . '0001';
                } else
                    $data['last_no'] = 'RP' . date('y') . (date('y') + 1) . str_pad(substr($no[1], 4, 8) + 1, 4, '0', STR_PAD_LEFT);
            }else {
                $check_no = 'RP' . (date('y') - 1) . date('y') . '0001';
                $check_res = $this->purchase_receipt_model->check_so_no($check_no);
                if (empty($check_res)) {
                    $data['last_no'] = 'RP' . (date('y') - 1) . date('y') . '0001';
                } else
                    $data['last_no'] = 'RP ' . (date('y') - 1) . date('y') . str_pad(substr($no[1], 4, 8) + 1, 4, '0', STR_PAD_LEFT);
            }
            $this->purchase_receipt_model->update_receipt_id($data['last_no']);
            $input['receipt']['receipt_no'] = $data['last_no'];
            if (isset($input['inv_no']) && !empty($input['inv_no'])) {
                $i = 0;
                $order_list = '';
                foreach ($input['inv_no'] as $key => $val) {

                    if ($i == 0) {
                        $order_list = $order_list . $val;
                        $i = 1;
                    } else
                        $order_list = $order_list . '-' . $val;
                }
            }
            $input['receipt']['inv_list'] = $order_list;
            //echo "<pre>";

            $insert_id = $this->purchase_receipt_model->insert_receipt($input['receipt']);
            $input['receipt_bill']['receipt_id'] = $insert_id;
            //print_r($insert_id);
            //print_r($input);
            $insert_id = $this->purchase_receipt_model->insert_receipt_bill($input['receipt_bill']);

            redirect($this->config->item('base_url') . 'purchase_receipt/receipt_list');
        }


        $data['all_customer'] = $this->customer_model->get_customer();
//        $data['all_agent'] = $this->agent_model->get_agent();
        $this->template->write_view('content', 'purchase_receipt/index', $data);
        $this->template->render();
    }

    public function receipt_list() {
//        $user_info = $this->user_auth->get_from_session('user_info');
//        $this->load->model('purchase_receipt/purchase_receipt_model');
//        // if ($user_info[0]['role'] == 1) {
//        //$data['all_receipt'] = $this->purchase_receipt_model->get_all_receipt();
//        //  } else {
//        // $data['all_receipt'] = $this->purchase_receipt_model->get_receipt_by_user_id($user_info[0]['id']);
//        // }

        $this->template->write_view('content', 'receipt_list', $data);
        $this->template->render();
    }

    function ajaxList() {
        $search_data = $this->input->post();
        $search_arr = array();
        //$user_info = $this->user_auth->get_from_session('user_info');

        if (empty($search_arr)) {
            $search_arr = array();
        }

        $list = $this->purchase_receipt_model->get_datatables($search_arr);
       
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $val) {

            $paid = $bal = $inv = $advance = 0;

            $advance = $advance + $val['advance'];
            $inv = $inv + $val['fNetCost'];
            $paid = $paid + $val['receipt_bill'][0]['receipt_paid'];
            $bal = $bal + ($val['fNetCost'] - ($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount']));
           
            $paid_amount = $val['fNetCost'];
            $received_amt = round($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount'], 2);
            $balance_amt = number_format($paid_amount - ($received_amt), 2, '.', ',');
            if ($balance_amt > 0.00) {
                $status = '<a href="#" data-toggle="modal" class="tooltips ahref border0" title="In-Complete"><span class="fa fa-thumbs-down text-danger">&nbsp;</span></a>';
            } else {
                $status = '<a href="#" data-toggle="modal" class="tooltips ahref border0" title="Complete"><span class="fa fa-thumbs-up green">&nbsp;</span></a>';
            }

            $edit = $this->config->item('base_url') . 'purchase_receipt/manage_receipt/' . $val['iPurchaseOrderId'];
            $view = $this->config->item('base_url') . 'purchase_receipt/view_receipt/' . $val['iPurchaseOrderId'];

            $edit_url = '<a href="' . $edit . '" data-toggle="tooltip" class="action-icon" title="Edit" data-original-title="Edit"><span class="fa fa-pencil td-icon"></span></a>';
            $views_url = '<a href="' . $view . '" data-toggle="tooltip" class="action-icon" title="View" data-original-title="View" ><span class="fa fa-eye fs-5"></span></a>';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $val['vPurchaseOrderNo'];
            $row[] = ucfirst($val['vSupplierName']);
            $row[] = number_format($val['fNetCost'], 2, '.', ',');

            $row[] = number_format(($val['receipt_bill'][0]['receipt_paid'] + $val['advance']), 2, '.', ',');
            $row[] = number_format($val['receipt_bill'][0]['receipt_discount'], 2, '.', ',');
//            $row[] = number_format($paid_amount - ($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount'] + $val['advance']), 2, '.', ',');
            $row[] = $balance_amt;
            $row[] = ($val['dCreatedDate'] != '') ? date('d-M-Y', strtotime($val['dCreatedDate'])) : '-';
            $row[] = ($val['receipt_bill'][0]['paid_date'] != '') ? date('d-M-Y', strtotime($val['receipt_bill'][0]['paid_date'])) : '-';
            $row[] = ($val['receipt_bill'][0]['next_date'] != '') ? date('d-M-Y', strtotime($val['receipt_bill'][0]['next_date'])) : '-';
            $row[] = $status;
            if ($balance_amt > 0.00) {
                $row[] = $edit_url . ' ' . $views_url;
            }
            else {
                $row[] = $views_url;
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->purchase_receipt_model->count_all(),
            "recordsFiltered" => $this->purchase_receipt_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
        exit;
    }

    public function cash_payment_list() {
        $user_info = $this->user_auth->get_from_session('user_info');
        $this->load->model('purchase_receipt/purchase_receipt_model');
        $data['all_receipt'] = $this->purchase_receipt_model->get_all_cashpay_receipt();
        $this->template->write_view('content', 'cash_payment_list', $data);
        $this->template->render();
    }

    public function cash_receipt_list() {
        $user_info = $this->user_auth->get_from_session('user_info');
        $this->load->model('purchase_receipt/purchase_receipt_model');
        $data['all_receipt'] = $this->purchase_receipt_model->get_all_receipt();
        $this->template->write_view('content', 'cash_receipt_list', $data);
        $this->template->render();
    }

    public function bank_payment_list() {
        $user_info = $this->user_auth->get_from_session('user_info');
        $this->load->model('purchase_receipt/purchase_receipt_model');
        $data['all_receipt'] = $this->purchase_receipt_model->get_all_receipt();
        $this->template->write_view('content', 'bank_payment_list', $data);
        $this->template->render();
    }

    public function bank_receipt_list() {
        $user_info = $this->user_auth->get_from_session('user_info');
        $this->load->model('purchase_receipt/purchase_receipt_model');
        $data['all_receipt'] = $this->purchase_receipt_model->get_all_receipt();
        $this->template->write_view('content', 'bank_receipt_list', $data);
        $this->template->render();
    }

    public function sales_list() {
        $values = $this->input->post();
        $user_info = $this->user_auth->get_from_session('user_info');
        $this->load->model('purchase_receipt/purchase_receipt_model');
        if ($user_info[0]['role'] == 1) {
            $all_receipt = $this->purchase_receipt_model->get_sales_list($values);
        } else {
            $all_receipt = $this->purchase_receipt_model->get_sales_list_by_user_id($user_info[0]['id'], $values);
        }
        $sales = array();
        if (isset($all_receipt) && !empty($all_receipt)) {
            $i = 1;
            $j = -1;
            foreach ($all_receipt as $val) {
                if ($val['receipt_bill'][0]['due_date'] != '') {
                    $j++;
                    //$sales[$j]['name'] = $val['name'];
                    if ($val['name'] == '') {
                        $val['name'] = $val['store_name'];
                    }
                    $bal = ($val['net_total'] - ($val['receipt_bill'][0]['receipt_paid'] + $val['receipt_bill'][0]['receipt_discount']));
                    $sales[$j] = array('c_name' => $val['name'] . '- (' . $val['inv_id'] . ')', 'pending' => $bal, 'c_id' => $val['customer']);
                }
            }
        }
        echo json_encode($sales);
        exit;
    }

    public function manage_receipt($r_id) {
        $this->load->model('purchase_receipt/purchase_receipt_model');
        if ($this->input->post()) {
            $input = $this->input->post();
            $total_paid_amt = $this->purchase_receipt_model->get_receipt_details($input['receipt_bill']['receipt_id']);

            if ($input['balance'] == 0 || $input['balance'] == 0.00 || $input['balance'] == '0.00')
                $receipt_status = 'SUCCESS';
            else
                $receipt_status = 'FAILED';
            // $input['receipt_bill']['due_date'] = !empty(($input['receipt_bill']['due_date'])) ? date("Y-m-d", strtotime($input['receipt_bill']['due_date'])) : date('Y-m-d', strtotime($input['receipt_bill']['created_date']));
            // echo $input['receipt_bill']['due_date'];exit;
            if($input['receipt_bill']['due_date'] != ''){
                $input['receipt_bill']['due_date'] = $this->change_date_formate($input['receipt_bill']['due_date']);
            }else{
                $input['receipt_bill']['due_date'] = $this->change_date_formate($input['receipt_bill']['created_date']);
            }
            $input['receipt_bill']['created_date'] = $this->change_date_formate($input['receipt_bill']['created_date']);
            $input['receipt_bill']['total_paid_amt'] = $total_paid_amt + $input['receipt_bill']['bill_amount'];
            $balance = $input['balance'];
            $inv_details = $this->purchase_receipt_model->get_inv_details($input['receipt_bill']['receipt_id']);
            $paid_amount = round($input['receipt_bill']['discount'] + $input['receipt_bill']['bill_amount'], 2) + $inv_details[0]['paid_amount'];
            
            $insert_id = $this->purchase_receipt_model->insert_receipt_bill($input['receipt_bill']);
            $discount_sum = $this->purchase_receipt_model->get_discount($input['receipt_bill']['receipt_id']);
            $balance_amount = $inv_details[0]['fNetCost']  - $discount_sum['total_bill_amt'] - $discount_sum['receipt_discount'];
            
            $this->purchase_receipt_model->update_invoice(array('vPayemntStatus' => $receipt_status, 'paid_amount' => $discount_sum['total_bill_amt'], 'balance' => $balance_amount, 'discount' => $discount_sum['receipt_discount']), $input['receipt_bill']['receipt_id']);



            $insert_id++;
            $inc['vType'] = 'Purchase Bill';
            $inc['code'] = 'PO-REC000' . $insert_id;

            $this->purchase_receipt_model->update_increment($inc, $inc['vType']);
            //insert notification
            $notification = array();
            $credit_notify = array();
            /* if (isset($input['receipt_bill']['due_date']) && !empty($input['receipt_bill']['due_date'])) {
              $notification = array();
              //$notification['notification_date']=$input['receipt_bill']['due_date'];
              $due_date = $input['receipt_bill']['due_date'];
              $notification['notification_date'] = date('Y-m-d', strtotime('-2 days', strtotime($due_date)));
              $notification['due_date'] = $due_date;
              $notification['type'] = 'payment';
              $receiver_list = array(1, 2);
              $notification['receiver_id'] = json_encode($receiver_list);
              $notification['link'] = 'purchase_receipt/receipt_list';
              $notification['Message'] = date('d-M-Y', strtotime($input['receipt_bill']['due_date'])) . ' is due date for payment';
              $this->notification_model->insert_notification($notification);
              if (isset($input['receipt_bill']['created_date']) && !empty($input['receipt_bill']['created_date'])) {
              if (strtotime($due_date) < strtotime($input['receipt_bill']['created_date'])) {
              $notification['notification_date'] = date('Y-m-d');
              $notification['due_date'] = $due_date;
              $notification['type'] = 'credit_days_exceeded';
              $receiver_list = array(1, 2);
              $notification['receiver_id'] = json_encode($receiver_list);
              $notification['link'] = 'purchase_receipt/manage_receipt/' . $input['receipt_bill']['receipt_id'];
              $notification['Message'] = 'Due date Exceeded than Credit days';
              $this->notification_model->insert_notification($notification);
              }
              }
              } */
            $input_comp = $this->input->post();
            if (!empty($input_comp['receipt_bill'])) {

                unset($input_comp['receipt_bill']['terms']);
                unset($input_comp['receipt_bill']['ac_no']);
                unset($input_comp['receipt_bill']['branch']);
                unset($input_comp['receipt_bill']['dd_no']);
                unset($input_comp['receipt_bill']['due_date']);
                unset($input_comp['receipt_bill']['discount_per']);
                unset($input_comp['receipt_bill']['discount']);
                unset($input_comp['balance']);
                unset($input_comp['receipt_bill']['remarks']);
                $input_comp['receipt_bill']['receiver_type'] = "Sales Reciver";
                $input_comp['receipt_bill']['type'] = "credit";
                $input_comp['receipt_bill']['receipt_id'] = $input_comp['receipt_bill']['receipt_no'];
                unset($input_comp['receipt_bill']['receipt_no']);
            }

            redirect($this->config->item('base_url') . 'purchase_receipt/receipt_list');
        }
        $data["last_id"] = $this->purchase_receipt_model->get_last_id('Purchase Bill');

//        $data['all_agent'] = $this->agent_model->get_agent();
        $data['receipt_details'] = $this->purchase_receipt_model->get_receipt_by_id($r_id);
        
        $data['customer_details'] = $this->purchase_receipt_model->get_customer_by_id($data['receipt_details'][0]['iSupplierId']);

        $this->template->write_view('content', 'update_receipt', $data);
        $this->template->render();
    }

    //change formate to yyyy-mm-dd
    public function change_date_formate($date) {
        $d = explode('/', $date);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
    }

    public function view_receipt($r_id) {
        $this->load->model('purchase_receipt/purchase_receipt_model');


        if ($this->input->post()) {
            $input = $this->input->post();
            if ($input['balance'] == 0 || $input['balance'] == 0.00 || $input['balance'] == '0.00')
                $receipt_status = 'Completed';
            else
                $receipt_status = 'Pending';

            $this->purchase_receipt_model->update_invoice(array('payment_status' => $receipt_status), $input['receipt_bill']['receipt_id']);
            $this->purchase_receipt_model->insert_receipt_bill($input['receipt_bill']);
            redirect($this->config->item('base_url') . 'purchase_receipt/receipt_list');
        }
//        $data['all_agent'] = $this->agent_model->get_agent();
        $data['receipt_details'] = $this->purchase_receipt_model->get_receipt_by_id($r_id);
        $firm_id = $data['receipt_details'][0]['firm_id'];
//        $data['company_details'] = $this->purchase_order_model->get_company_details_by_firmid($firm_id);
        $this->template->write_view('content', 'view_receipt', $data);
        $this->template->render();
    }

    public function download_receipt($r_id, $rec_id) {
        $this->load->model('purchase_receipt/purchase_receipt_model');
//        $data['all_agent'] = $this->agent_model->get_agent();
        $data['receipt_details'] = $this->purchase_receipt_model->get_receipt_download_by_id($r_id, $rec_id);
        $header = $this->load->view('purchase_receipt/pdf_header_view', $data, TRUE);
        $msg = $this->load->view('purchase_receipt/receipt_pdf', $data, TRUE);
        $mpdf = new mPDF('', 'A4', '0', '"Roboto", "Noto", sans-serif', '8', '8', '40', '10', '5', '3', 'P');   
        $mpdf->restrictColorSpace =  1; 
        $mpdf->setTitle('Sales Receipt');
        $mpdf->SetHTMLHeader($header);      
        $mpdf->setFooter('{PAGENO} / {nb}');
        $mpdf->WriteHTML($msg); 
        $filename = 'Receipt-' . date('d-M-Y-H-i-s') . '.pdf';
        $newFile = $this->config->item('theme_path') . 'attachements/' . $filename; 
        $mpdf->Output($newFile, 'D');
    }

    public function print_receipt($r_id, $rec_id) {
        $this->load->model('purchase_receipt/purchase_receipt_model');
//        $data['all_agent'] = $this->agent_model->get_agent();
        $data['receipt_details'] = $this->purchase_receipt_model->get_receipt_download_by_id($r_id, $rec_id);
        $header = $this->load->view('purchase_receipt/pdf_header_view', $data, TRUE);
        $msg = $this->load->view('purchase_receipt/receipt_pdf', $data, TRUE);
        $mpdf = new mPDF('', 'A4', '0', '"Roboto", "Noto", sans-serif', '8', '8', '40', '10', '5', '3', 'P');   
        $mpdf->restrictColorSpace =  1; 
        $mpdf->setTitle('Sales Receipt');
        $mpdf->SetHTMLHeader($header);      
        $mpdf->setFooter('{PAGENO} / {nb}');
        $mpdf->WriteHTML($msg); 
        $mpdf->Output();
    }

    function clear_cache() {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

    function update_receipt_payment($id) {

        $input_data = $this->input->post();
        // echo"<pre>";print_r($input_data);exit;

        $inv_id = $input_data['inv_id'];
        $insert['bill_amount'] = $input_data['bill_amount'];
        $insert['receipt_no'] = $input_data['receipt_no'];
        $insert['terms'] = $input_data['terms'];
        $insert['discount_per'] = $input_data['discount_per'];
        $insert['discount'] = $input_data['discount'];
        $insert['remarks'] = $input_data['remarks'];
        $insert['ac_no'] = $input_data['ac_no'];
        $insert['branch'] = $input_data['branch'];
        $insert['dd_no'] = $input_data['dd_no'];
        $insert['due_date'] = date('Y-m-d', strtotime($input_data['due_date']));
        $insert['created_date'] = date('Y-m-d', strtotime($input_data['created_date']));

        if ($input_data != '') {

            $data['receipt_bill'] = $this->purchase_receipt_model->update_receipt_entry($insert, $id);

            if ($data) {
                redirect($this->config->item('base_url') . 'purchase_receipt/manage_receipt/' . $inv_id);
            }
        }
    }

}
