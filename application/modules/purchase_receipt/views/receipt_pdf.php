<?php  $theme_path = $this->config->item('theme_locations'); ?>
<style type="text/css">
    table {border-collapse:collapse; width:100%; font-size:8.5px }
    table tr th{ text-align:center; border:1px solid #000; padding:5px 5px 5px 5px; vertical-align:middle;}
    table tr td { text-align:center; border:1px solid #000; padding:5px 5px 5px 5px; vertical-align:middle;}
    .pdf-f{font-weight:bold}
</style>
<?php 
                $company_logo = base_url().'/themes/assets/images/logo/login.png';            
        ?>
<table style="padding: 2px 2px;" row-style="page-break-inside:avoid;">
    <tr style="background-color:#e6e6ff;"><th  colspan="2"><strong>Purchase Invoice Details</strong></th></tr>
    <tr>
        <td align="left" ><b>Name:</b>
            <b><?php echo ucfirst($receipt_details[0]['vSupplierName']); ?></b><br/><br/>
            <b>Address:</b> <?php echo ucfirst($receipt_details[0]['vAddress']); ?>
        </td>
        <td align="center" valign="middle"> <img src="<?php echo $company_logo; ?>" alt="Chain Logo" width="125px"></td>
    </tr>
    <tr>
        <td align="left"><b>Invoice NO : </b><?php echo $receipt_details[0]['vPurchaseOrderNo'] ?></td>
        <td align="left"><b>Receipt Number : </b><?php echo $receipt_details[0]['receipt_history'][0]['receipt_no'] ?></td>
    </tr>
    <tr>
        <td align="left"><b>Received Date : </b><?php echo date('d-M-Y', strtotime($receipt_details[0]['receipt_history'][0]['created_date'])) ?></td>
        <td></td>

    </tr>
</table>
<table style="padding: 2px 2px;" row-style="page-break-inside:avoid;">
    <tr style="background-color:#e6e6ff;">
        <td colspan="6" align="center"><b>INVOICE DETAILS</b></td>
    </tr>
    <tr align="center">
        <td width="7%"><b>S.No</b></td>
        <td width="33%" align="left" ><b>Product&nbsp;Name</b></td>
        <td width ="8%"><b>QTY</b></td>
        <td width ="20%" align="right"><b>Cost/QTY</b></td>
<!--        <td width ="11%"><b>CGST&nbsp;%</b></td>
        <td width ="11%"><b>SGST&nbsp;%</b></td>-->
        <td width ="32%" align="right"><b>Net&nbsp;Value</b></td>
        
    </tr>
    <tbody>
        <?php
        $i = 1;
        if (isset($receipt_details[0]['po_details']) && !empty($receipt_details[0]['po_details'])) {
            foreach ($receipt_details[0]['po_details'] as $vals) {
                ?>
                <tr>
                    <td align="center">
                        <?php echo $i; ?>
                    </td>
                    <td align="left">
                        <?php echo $vals['vProductName']; ?>
                    </td>
                    <td align="center">
                        <?php echo $vals['iPurchaseQTY'] ?>
                    </td>
                    <td align="right">
                        <?php echo number_format($vals['iPurchaseCostperQTY'], 2); ?>
                    </td>
                    <td align="right">
                        <?php echo number_format($vals['iPurchaseSubTotal'], 2) ?>
                    </td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr><?php //echo "<pre>";print_r($receipt_details);exit; ?>
            <td colspan="4" align="right"><strong>Net Total</strong></td>
            <td align="right"><?php echo number_format($receipt_details[0]['fNetCost'], 2); ?></td>
        </tr>
        <tr>
            <td colspan="4" align="right"><strong>Received Amount</strong></td>
            <td align="right"><?php echo number_format($receipt_details[0]['receipt_history'][0]['bill_amount'], 2, '.', ',') ?></td>
        </tr>
        <tr>
            <td colspan="4" align="right"><strong>Discount ( <?php echo $receipt_details[0]['receipt_history'][0]['discount_per'] ?> %)</strong></td>
            <td align="right"><?php echo number_format($receipt_details[0]['receipt_history'][0]['discount'], 2, '.', ',') ?> </td>
            <?php $bal_amt = $receipt_details[0]['fNetCost'] - $receipt_details[0]['receipt_history'][0]['total_paid_amt'] - $receipt_details['sum_discount_amount']['sum_discount']//$receipt_details[0]['receipt_history'][0]['discount']; //$receipt_details[0]['total_disc']?>
        <tr>
            <td colspan="4" align="right"><strong>Balance Amount</strong></td>
            <!-- <td align="right"><?php //echo number_format($receipt_details[0]['balance']); ?></td> -->
            <td align="right"><?php echo number_format($bal_amt,2, '.', ','); ?></td>
        </tr>
        <tr>
            <td colspan="6" align="left">
                <span class="pdf-f">Remarks : </span>
                <?php echo $receipt_details[0]['receipt_history'][0]['remarks']; ?>
            </td>
        </tr>
    </tfoot>
</table>
