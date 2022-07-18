<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reference_report extends MY_Controller {
   public function __construct(){
       if(empty($this->session->userdata('LoggedId'))){
           redirect(base_url());
        }
	    parent::__construct();
        $this->load->model('reference_report_model'); 
    }

	public function index()
	{
        $salesman_id = $this->session->userdata('LoggedId');
        $branch_id = $this->session->userdata('BranchId');
        $data['title'] = 'Reference';
        $data['salesman'] = $this->reference_report_model->get_salesman_list();
        $data['distributor'] = $this->reference_report_model->get_distributor_list($branch_id);

        $this->template->write_view('content', 'reference_report', $data);
        $this->template->render();
	}

    public function get_reference(){
        $search_data = $this->input->post();
        $search_arr = array();
        // echo "<pre>**";print_r($input_data);exit;
        $list=$this->reference_report_model->reference_list($search_data);
        $data = array();
        $no = $_POST['start'];
        // echo "<pre>";print_r($list);exit;
        foreach ($list as $val) {

            $paid = $bal = $inv = $advance = 0;

            $advance = $advance + $val['advance'];
            $inv = $inv + $val['fNetCost'];
            $paid = $paid + $val['receipt_paid'];
            $bal = $bal + ($val['fNetCost'] - ($val['receipt_paid'] + $val['receipt_discount']));
           
            $paid_amount = $val['fNetCost'];
            $received_amt = round($val['receipt_paid'] + $val['receipt_discount'], 2);
            $balance_amt = number_format($paid_amount - ($received_amt), 2, '.', ',');
            if ($val['vPayemntStatus'] == 'FAILED') {
                $status = '<a href="#" data-toggle="modal" class="tooltips ahref border0" title="In-Complete"><span class="fa fa-thumbs-down text-danger">&nbsp;</span></a>';
            } if ($val['vPayemntStatus'] == 'SUCCESS'){
                $status = '<a href="#" data-toggle="modal" class="tooltips ahref border0" title="Complete"><span class="fa fa-thumbs-up green">&nbsp;</span></a>';
            }
            $delete = '<a href="" data-id="'.$val['iSalesOrderId'].'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 removeAttr " ><span class="svg-icon svg-icon-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black"></path><path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black"></path><path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black"></path></svg></span></a>';
            $edit = '<a href="" data-id="'.$val['iSalesOrderId'].'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 addAttr" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_user"><span class="svg-icon svg-icon-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black"></path><path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black"></path></svg></span></a>';
            $view = '<a href="'.base_url('order/view_sales_order/').$val['iSalesOrderId'].'" data-id="'.$val['iSalesOrderId'].'" class="action-icon" ><i class="fa fa-eye fs-5"></i></a>';
            $no++;
            $row = array();
            // $row[] = $no;
            $row[] = $val['vSalesOrderNo'];
            $row[] = ucfirst($val['vCustomerName']);
            $row[] = ucfirst($val['vHeadOfficeName']);
            $row[] = ucfirst($val['vBranchName']);
            $row[] = ucfirst($val['fNetQty']);
            $row[] = $val['CGST'];
            $row[] = $val['SGST'];
            $row[] = number_format($val['fNetCostwithoutGST'], 2, '.', ',');
            $row[] = number_format($val['fNetCost'],2);
            $row[] = number_format(($val['receipt_paid']), 2, '.', ',');
            $row[] = number_format($val['receipt_discount'], 2, '.', ',');
            $row[] = number_format($bal, 2, '.', ',');
            $row[] = $status;
            $row[] = date("d-m-Y",strtotime($val['dOrderedDate']));
            $row[] = date("d-m-Y",strtotime($val['dCreatedDate']));
            $row[] = $val['eDeliveryStatus'];
            $row[] = $view;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->reference_report_model->count_all($search_data),
            "recordsFiltered" => $this->reference_report_model->count_filtered($search_data),
            "data" => $data,
        );
        echo json_encode($output);
        exit;
    }

    public function change_date_formate($date) {
        $d = explode('/', $date);
        return $d[2] . '-' . $d[1] . '-' . $d[0];
    }
    // Get Details Report
    public function export_sales_details()
    {
        $input = $this->input->get();
        $get_all_sales_details = $this->reference_report_model->get_all_sales_gst_reports($input);
        // echo"<pre>";print_r($get_all_sales_details);exit;
        if(empty($get_all_sales_details))
        {
            $this->session->set_flashdata('error', 'No Sales Details Found!');
            redirect($this->config->item('base_url') . 'report/reference_report');
        }
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel();
        $sheet=0;
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
            //Row Center 
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $BorderArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            // Summary Report
            if($input['type']=="summary"){
            $objPHPExcel->getActiveSheet()->mergeCells('A1:K1')->setCellValue('A1', 'GST SALES SUMMARY REPORT');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2')->setCellValue('A2', 'From Date:'.$input['from_date']);
            $objPHPExcel->getActiveSheet()->mergeCells('E2:H2')->setCellValue('E2', 'To Date:'.$input['to_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('A6', 'InvNo:');
            $objPHPExcel->getActiveSheet()->setCellValue('A6', 'InvNo:');
            $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Date:');
            $objPHPExcel->getActiveSheet()->setCellValue('C6', 'Customer Name: ');
            $objPHPExcel->getActiveSheet()->setCellValue('D6', 'TINNO: ');
            $objPHPExcel->getActiveSheet()->setCellValue('E6', 'Taxable Amount: ');
            $objPHPExcel->getActiveSheet()->setCellValue('F6', 'CGST Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('G6', 'SGST Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('H6', 'IGST Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('I6', 'Frieght Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('J6', 'P&F Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('K6', 'TCS%: ');
            $objPHPExcel->getActiveSheet()->setCellValue('L6', 'TCSAmt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('M6', 'DisAmt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('N6', 'NetAmount: ');
            $objPHPExcel->getActiveSheet()->setCellValue('O6', 'State\Code: ');
            $i=7;
            $fnetcostwithout_gst=0;$fnetcost=0;$cgst=0;$sgst=0;$igst=0;$discount=0; $tax_amount_igst=0;$tax_amount_gst=0;$taxable_amt=0;
            foreach($get_all_sales_details as $sales_data)
            {
            $objPHPExcel->getActiveSheet()->getStyle("D$i:E$i")->getAlignment()->setWrapText(true); 
            $netcost = $sales_data['fNetCost'];   
            // $fnetcostwithout_gst = $sales_data['fNetCostwithoutGST'] + $fnetcostwithout_gst;
            $discount = $sales_data['receipt_discount'] + $discount;
            $fnetcost = $sales_data['fNetCost'] + $fnetcost;
            $cgst_percent = $netcost - ($netcost*(100/(100+$sales_data['CGST'])));
            $sgst_percent = $netcost - ($netcost*(100/(100+$sales_data['SGST'])));
            $igst_percent = $netcost - ($netcost*(100/(100+$sales_data['IGST'])));
            if($sales_data['iStateId']=='2'){
                $tax_amount_gst = $sales_data['CGST'] + $sales_data['SGST'];
                $taxable_amt = $netcost - $tax_amount_gst;
                }
                else{
                $tax_amount_igst = $sales_data['IGST'];
                $taxable_amt = $netcost - $tax_amount_igst;
                }
            $cgst = $tax_amount_gst/2 + $cgst;
            $sgst = $tax_amount_gst/2 + $sgst;
            $igst = $tax_amount_igst + $igst;
            $fnetcostwithout_gst = $taxable_amt + $fnetcostwithout_gst;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$sales_data['vSalesOrderNo']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,date('d-m-Y',strtotime($sales_data['dOrderedDate'])));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$sales_data['vCustomerName']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,'GSTNo:' .$sales_data['gst_no']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,number_format((float)$taxable_amt, 2, '.', ''));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,($sales_data['iStateId']=="2")?number_format((float)$tax_amount_gst/2, 2, '.', ''):'0');
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,($sales_data['iStateId']=="2")?number_format((float)$tax_amount_gst/2, 2, '.', ''):'0');
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,($sales_data['iStateId']!="2")?number_format((float)$tax_amount_igst, 2, '.', ''):'0');
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,'');
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,'');
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,'');
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,'');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,'');
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,number_format((float)$sales_data['fNetCost'], 2, '.', ''));
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i,$sales_data['vStateName']);
            $sheet++;
            $i++;
        }
        //Sales Gst Footer Details 
        $i = $i+1;
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,number_format((float)$fnetcostwithout_gst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,number_format((float)$cgst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,number_format((float)$sgst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,number_format((float)$igst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,number_format((float)$fnetcost, 2, '.', ''));
        }
        // Details Report
        if($input['type']=="details"){
            $objPHPExcel->getActiveSheet()->mergeCells('A1:K1')->setCellValue('A1', 'ITEM WISE SALES REPORT');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:D2')->setCellValue('A2', 'From Date:'.$input['from_date']);
            $objPHPExcel->getActiveSheet()->mergeCells('E2:H2')->setCellValue('E2', 'To Date:'.$input['to_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('A6', 'Ref No:');
            $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Date:');
            $objPHPExcel->getActiveSheet()->setCellValue('C6', 'Customer Name: ');
            $objPHPExcel->getActiveSheet()->setCellValue('D6', 'TINNO: ');
            $objPHPExcel->getActiveSheet()->setCellValue('E6', 'Product: ');
            $objPHPExcel->getActiveSheet()->setCellValue('F6', 'Qty: ');
            $objPHPExcel->getActiveSheet()->setCellValue('G6', 'Rate: ');
            $objPHPExcel->getActiveSheet()->setCellValue('H6', 'Taxable Amount: ');
            $objPHPExcel->getActiveSheet()->setCellValue('I6', 'CGST %: ');
            $objPHPExcel->getActiveSheet()->setCellValue('J6', 'CGST Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('K6', 'SGST %: ');
            $objPHPExcel->getActiveSheet()->setCellValue('L6', 'SGST Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('M6', 'IGST %: ');
            $objPHPExcel->getActiveSheet()->setCellValue('N6', 'IGST Amt: ');
            $objPHPExcel->getActiveSheet()->setCellValue('O6', 'Total Amount: ');
            $i=7;
            $fnetcostwithout_gst=0;$fnetcost=0;$cgst=0;$sgst=0;$igst=0;$tax_amount_igst=0;$tax_amount_gst=0;$taxable_amt=0;
            foreach($get_all_sales_details as $sales_data)
            {
            $objPHPExcel->getActiveSheet()->getStyle("D$i:E$i")->getAlignment()->setWrapText(true);
            $netcost = $sales_data['iDeliveryQTY'] * $sales_data['iDeliveryCostperQTY'];
            $cgst_percent = $sales_data['cgst'] / 100 * $netcost;
            $sgst_percent = $sales_data['sgst'] / 100 * $netcost;
            $igst_percent = $sales_data['igst'] / 100 * $netcost;
            if($sales_data['iStateId']=='2'){
            $tax_amount_gst = $cgst_percent + $sgst_percent;
            $taxable_amt = $netcost - $tax_amount_gst;
            }
            else{
            $tax_amount_igst = $igst_percent;
            $taxable_amt = $netcost - $tax_amount_igst;
            }
            $fnetcostwithout_gst = $taxable_amt + $fnetcostwithout_gst;
            $cgst = $tax_amount_gst/2 + $cgst;
            $sgst = $tax_amount_gst/2 + $sgst;
            $igst = $tax_amount_igst + $igst;
            $fnetcost = $sales_data['iDeliverySubTotal'] + $fnetcost;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$sales_data['vSalesOrderNo']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,date('d-m-Y',strtotime($sales_data['dOrderedDate'])));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$sales_data['vCustomerName']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,'GSTNo:' .$sales_data['gst_no']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,$sales_data['vProductName'].$sales_data['vProductUnitName'] . '-' . 'HSN NO:'.$sales_data['vHSNNO']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,$sales_data['iDeliveryQTY']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,$sales_data['iDeliveryCostperQTY']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,number_format((float)$taxable_amt, 2, '.', ''));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,$sales_data['cgst']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,($sales_data['iStateId']=="2")?number_format((float)$tax_amount_gst/2, 2, '.', ''):'0');
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,$sales_data['sgst']);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,($sales_data['iStateId']=="2")?number_format((float)$tax_amount_gst/2, 2, '.', ''):'0');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,$sales_data['igst']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,($sales_data['iStateId']!="2")?number_format((float)$tax_amount_igst, 2, '.', ''):'0');
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$i,number_format($sales_data['iDeliverySubTotal']), 2, '.', ',');
            $sheet++;
            $i++;
        }
        //Sales Gst Footer Details 
        $i = $i+1;
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,number_format((float)$fnetcostwithout_gst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,number_format((float)$cgst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,number_format((float)$sgst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,number_format((float)$igst, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('O'.$i,number_format((float)$fnetcost, 2, '.', ''));
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:O$i")->applyFromArray($BorderArray);
        $objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A2:D2")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("E2:H2")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("E$i:O$i")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A6:O6")->getFont()->setBold( true );
        header('Content-type: application/vnd.ms-excel');
        $file_name = 'Sales Gst Report Item Wise - ' . Date('d-M-Y');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    
}
