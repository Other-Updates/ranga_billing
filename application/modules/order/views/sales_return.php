<?php
    $theme_path = $this->config->item('theme_locations');
    // echo "<pre>";
    // print_r($sales_order);
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({
        breakpoint: 768
        });
    });
</script>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Return Sales</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Add Sales</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form action=<?php echo base_url('order/return_sales_order'); ?> method="post">
    <input type="hidden" name="sales_details_deleted_id" class="sales_details_deleted_id">
    <input type="hidden" name="total_net_quantity" class="total_net_quantity">
    <div class="container-fluid">
        <div class="row">        
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <form id="reference_form"> -->
                        <div class="row g-3 select2-drpdwn">               
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="validationCustom01">Product Name</label>
                                <input class="form-control" id="validationCustom01" type="text" name="product_name" value=""  >
                                <div class="valid-feedback">Looks good!</div>
                            </div> -->            
                            <input type="hidden" name="sales_order_id" class="sales_order_id" value="<?php echo $sales_order_id ?>">
                            <input type="hidden" name="salesorderno" class="salesorderno" value="<?php echo $sales_order[0]['vSalesOrderNo'] ?>">
                            <input type="hidden" name="branch_id" class="branch_id" value="<?php echo $sales_order[0]['iBranchId'] ?>">
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Head office</label>
                                <select class="form-select headoffice head-office-multiple" disabled name="headoffice_disabled" id="validationCustom04" >
                                    <?php foreach ($headoffice as $list){ ?>
                                    <option data-state="<?php echo $list['iStateId'] ?>" value="<?php echo $list['iHeadOfficeId'] ?>" <?php if($sales_order[0]['iHeadOfficeId']==$list['iHeadOfficeId']){ echo 'selected'; } ?>><?php echo $list['vHeadOfficeName'] ?></option>
                                <?php } ?>
                                <input type="hidden" name="headoffice" value="<?php echo $sales_order[0]['iHeadOfficeId'] ?>">
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Branch</label>
                                <select class="form-select" disabled name="branch" id="validationCustom04">
                                    <option value="<?php echo $sales_order[0]['iBranchId']; ?>"><?php echo $sales_order[0]['vBranchName']; ?></option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Customer</label>
                                <select class="form-select customer_id" name="customer" disabled id="validationCustom04">
                                    <option data-state="<?php echo $sales_order[0]['iStateId'] ?>" value="<?php echo $sales_order[0]['iCustomerId'] ?>"><?php echo $sales_order[0]['vCustomerName']; ?></option>
                                    <input type="hidden" value="<?php echo $sale_order['iCustomerId'] ?>">
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <?php if($this->session->userdata('UserRole') == 1 || $this->session->userdata('UserRole') == 2){ ?>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Salesman</label>
                                <select class="form-select salesman_id" name="salesman" disabled id="validationCustom04">
                                    <option  value="<?php echo $sales_order[0]['iSalesmanId'] ?>"><?php echo $sales_order[0]['vName']; ?></option>
                                    <input type="hidden" value="<?php echo $sale_order['iSalesmanId'] ?>">
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <?php } ?>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Address</label>
                                <textarea class="form-control" id="validationCustom03" type="text" readonly name="adress" placeholder="" required=""><?php echo $sales_order[0]['vAddress']; ?></textarea>
                                <div class="invalid-feedback">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Shipping address</label><span style="color:red">*</span>
                                <textarea class="form-control add_ship_address" id="validationCustom03" type="text" readonly name="shipping_adress" placeholder="" required=""><?php echo $sales_order[0]['vShippingAddress']; ?></textarea>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Date</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" readonly value="<?php echo $sales_order[0]['dOrderedDate'] ?>" readonly type="text" name="ordered_date" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Payment Status</label>
                                <?php if($sales_order[0]['eDeliveryStatus']=="Delivered" && $sales_order[0]['vPayemntStatus']=="FAILED" && $sales_order[0]['receipt_id']!=""){?>
                                <label class="value-lable">Partially Completed</label>
                                <?php } elseif($sales_order[0]['eDeliveryStatus']=="Delivered" && $sales_order[0]['vPayemntStatus']=="FAILED"){?>
                                <label class="value-lable">Pending</label>
                                <?php } elseif($sales_order[0]['eDeliveryStatus']=="Delivered" && $sales_order[0]['vPayemntStatus']=="SUCCESS"){ ?>
                                <label class="value-lable">Completed</label>
                                <?php } else{?>
                                <label class="value-lable">Pending</label>
                                <?php } ?>
                                <div class="invalid-feedback">Please select a valid status.</div>
                            </div>
                            <!-- <div class="col-md-8" style="display:none">
                                <select class="form-select category_clone" name="category[]" id="validationCustom04"  >
                                <option selected="" disabled="" value="">Select category</option>
                                <?php foreach ($category as $categories){ ?>
                                <option value="<?php echo $categories['iCategoryId'] ?>"><?php echo $categories['vCategoryName'] ?></option>
                                <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="">
                                    <table class="table delivery-order basictable" id="table">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th width="15%" scope="col">Category</th>
                                                <th width="28%" scope="col">Product</th>
                                                <th width="13%" scope="col">Unit</th>
                                                <th width="8%" class="text-right" scope="col">Price</th>
                                                <th width="8%" class="text-center" scope="col">Quantity</th>
                                                <th width="6%" class="text-center" scope="col">CGST</th>
                                                <th width="6%" class="text-center" scope="col">SGST</th>
                                                <th width="6%" class="text-center" scope="col">IGST</th>
                                                <th width="10%" class="text-right" scope="col">Net&nbsp;Value</th>
                                            </tr>
                                        </thead>
                                        <input type="hidden" name="sales_count" class="sales_count" value="<?php echo count($sales_order[0]['sales_details']) ?>">
                                        <?php foreach($sales_order[0]['sales_details'] as $key=>$order){ ?>
                                        <tr id="row" class="dynamic-added">
                                            <td scope="col">
                                                <div class="category_id">
                                                    <select class="form-select category_clone" name="category_disabled[]" disabled id="validationCustom04"  >
                                                        <option selected="" disabled="" value="">Select category</option>
                                                        <?php foreach ($category as $categories){ ?>
                                                        <option value="<?php echo $categories['iCategoryId'] ?>"<?php if($order['iCatagoryId']==$categories['iCategoryId']){ echo 'selected';} ?>><?php echo $categories['vCategoryName'] ?></option>
                                                        <?php } ?>
                                                        <input type="hidden" name="category[]" value="<?php echo $order['iCatagoryId'] ?>">
                                                    </select>
                                                <div class="invalid-feedback">Please select a valid state.</div>
                                                </div>
                                                <input type="hidden" name="sales_order_detail_id[]" class="sales_order_detail_id" value="<?php echo $order['iSalesOrderDetailsId'] ?>">
                                            </td>
                                            
                                            <td scope="col">
                                                <div class="relative">
                                                    <input class="form-control product product_details_<?php echo $key ?>" id="validationCustomUsername" readonly name="product[]" type="text" value="<?php echo strtoupper($order['vProductName']); ?>" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                    <input type="hidden" class="product_id_<?php echo $key ?> product_id" name="product_id[]" value="<?php echo $order['iProductId'] ?>">
                                                    <input type="hidden" class="product_color_id product_color_id_0" name="product_color_id[]" value="<?php echo $order['iProductColorId'] ?>">
                                                    <input type="hidden" name='taxable_cost[]' tabindex="-1" class="taxable_cost taxable_cost_cls_0 form-control" />
                                                    <div class="search-list"><ul class="suggesstion-box" data-id=<?php echo $key ?>></ul></div>
                                                    <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                    <select class="form-select product_unit unit_<?php echo $key ?>" name="unit_disabled[]" disabled id="validationCustom04"  >
                                                        <option selected="" disabled="" value="">Select unit</option>
                                                        <?php foreach ($unit as $un){ ?>
                                                        <option value="<?php echo $un['iProductUnitId'] ?>"<?php if($order['iProductUnitId']==$un['iProductUnitId']){ echo 'selected';} ?>><?php echo $un['vProductUnitName'] ?></option>
                                                        <?php } ?>
                                                        <input type="hidden" name="unit[]" value="<?php echo $order['iProductUnitId']  ?>">
                                                    </select>
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control price price_<?php echo $key ?>" readonly id="validationCustomUsername" value="<?php echo $order['iDeliveryCostperQTY'] ?>" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="row quantity-row">
                                                    <div class="col-md-7 wp-r-0">
                                                        <input class="form-control quantity quantity_cls_<?php echo $key ?>" id="validationCustomUsername" value="0" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                    </div>
                                                    <div class="col-md-4 p-0-5"><span class="label label-t-success available_qty" > <?php echo $order['iDeliveryQTY'] ?> </span></div>
                                                    <input type="hidden" class="sales_qty" name="sales_qty[]" value="<?php echo $order['iDeliveryQTY'] ?>">
                                                    <span class="ajax_response_result"></span>          
                                                    <div class="invalid-feedback">Field is required.</div>
                                                    <input type="hidden" class="quantity-err">
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control cgst cgst_cls_0" id="validationCustomUsername" readonly value="<?php echo $order['CGST'] ?>" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control sgst sgst_cls_<?php echo $key ?>" readonly id="validationCustomUsername" value="<?php echo $order['SGST'] ?>" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control igst igst_cls_<?php echo $key ?>" readonly id="validationCustomUsername" value="<?php echo $order['IGST'] ?>" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control net_value net_value_cls_<?php echo $key ?>" readonly  id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tfoot>
                                            <tr>
                                            <td colspan="5" class="p-0" rowspan="2">
                                                    <table width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align:right;"><strong>Taxable&nbsp;Price</strong></td>
                                                                <td><input type="text" name="sales[taxable_price]" tabindex="-1" value="0.00" class="taxable_price text_right form-control m-0" readonly=""/></td>
                                                                <td style="text-align:right;" colspan="1" class="igst_td"> <strong>Tax&nbsp;Amount</strong> </td>
                                                                <td class="igst_td"><input type="text" name="sales[igst_price]" tabindex="-1" value="0.00"  readonly class="add_igst text_right form-control m-0" /></td>                                                               
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4" class="p-0">
                                                                    <table width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td width="17.5%" style="text-align:right;"> <strong>CGST</strong> </td>
                                                                                <td><input type="text" name="sales[cgst_price]" tabindex="-1"  value="0.00"  readonly class="add_cgst text_right form-control m-0" /></td>
                                                                                <td width="17.5%" style="text-align:right;" class="sgst_td"> <strong>SGST</strong> </td>
                                                                                <td class="sgst_td"><input type="text" name="sales[sgst_price]" tabindex="-1" value="0.00"  readonly class="sgst_td add_sgst text_right form-control m-0" /></td>
                                                                                <td width="17.5%" style="text-align:right;" colspan="1" class="igst_td"> <strong>IGST</strong> </td>
                                                                                <td class="igst_td"><input type="text" name="sales[igst_price]" tabindex="-1" value="0.00"  readonly class="add_igst_per text_right form-control m-0" /></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>                                                               
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td colspan="3" style="text-align:right;font-weight:bold;">Additional&nbsp;Charge</td>
                                                <td><input type="text" tabindex="-1"  name="sales[additional_charge]" id="additional_charge" readonly="readonly" class="additional_charge text_right form-control m-0" value="<?php echo $sales_order[0]['fAdditionalCharge'] ?>"/></td>
                                            </tr>
                                            <tr>                                                   
                                                <td colspan="3" style="text-align:right;font-weight:bold;">Net&nbsp;Total</td>
                                                <td><input type="text" tabindex="-1"  name="sales[net_total]" id="net_total" readonly="readonly"  class="final_amt text_right form-control m-0" /><input type="hidden" tabindex="-1"  name="sales[net_qty]" id="total_net_qty" readonly="readonly"  class="total_net_qty text_right form-control m-0" /></td> 
                                            </tr>
                                            <!-- <tr>
                                                <td colspan="10">
                                                    <label class="">Remarks</label>
                                                    <input name="quotation[remarks]" tabindex="-1" type="text" class="form-control m-0" />
                                                </td>
                                            </tr> -->
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                            <label class="form-label" for="validationCustom04">Status</label>
                                <select class="form-select category_clone" name="delivery_status" id="validationCustom04"  >
                                <option selected="" disabled="" value="">Select</option>
                                <option value="Not Shipped"<?php if($sales_order[0]['eDeliveryStatus']=="Not shipped"){ echo "selected"; } ?>>Not Shipped</option>
                                <option value="Delivered" <?php if($sales_order[0]['eDeliveryStatus']=="Delivered"){ echo "selected"; } ?>>Delivered</option>
                                <option value="Cancelled"<?php if($sales_order[0]['eDeliveryStatus']=="Cancelled"){ echo "selected"; } ?>>Cancelled</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
                            <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12"><input type="submit" class="btn btn-t-success submit"> <a href="<?php echo base_url("order"); ?>"><input class="btn btn-danger pull-left" type="button" value="Cancel"></a></div></div>
                        </div>                        
                    </div>
                    <!-- <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12"><input type="submit" class="btn btn-success"> <input class="btn btn-danger pull-left" type="reset" value="Cancel"></div></div> -->
                </div>
            </div>        
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){      
        $(document).on('click','.submit',function(){
            // $.each('.quantity-err', function(key,val))
            $('.quantity').each(function(){
            ordered_quantity = $(this).val();
        })
        var totalfqty = $('.total_net_quantity').val();
        var totalnetqty = parseInt(totalfqty) - parseInt(ordered_quantity)
        if(parseInt(totalfqty) > parseInt(ordered_quantity))
        $('.total_net_quantity').val(totalnetqty);
            qty_err = 0;
            $(".quantity-err").each(function(){
                qty_err += +$(this).val();
            });
            if(qty_err != 0){
                return false;
            }
        })
        calculate();
        // category();
        // $('.headoffice').val('');
        // $(".category_clone:first").clone().appendTo(".category_id");
        var head_office = $('.headoffice').val();
        var branch_id  = $('.branch_id').val();
        if(head_office != ''){
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>stock/get_branch",
            data:{head_office:head_office},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += $('<option value="">Choose branch</option>');
                $.each(data, function(key,val) {
                    html = $('<option value='+val['iBranchId']+'>'+val['vBranchName']+'</option>');
                    if (val['iBranchId'] == branch_id) {
                        html.attr('selected', 'selected');
                    }
                    // $select.append($option);
                });
                    $('.branch').html(html);
            }
        });
        }
        var sales_count = $('.sales_count').val();
        var i=sales_count;  
   
        $('#add').click(function(e){  
            e.preventDefault();
           i++;  
           $('#table').append('<tr id="row'+i+'" class="dynamic-added"><td scope="col"><div class="category_field'+i+'"><div class="invalid-feedback">Please select a valid state.</div></div></td><td scope="col"><div class="relative"><input class="form-control product product_details_'+i+'" id="validationCustomUsername" name="product[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><input type="hidden" class="product_id_'+i+'" name="product_id[]"><input type="hidden" name="taxable_cost[]" tabindex="-1" class="taxable_cost taxable_cost_cls_'+i+' form-control" /><div class="search-list"><ul class="suggesstion-box" data-id="'+i+'"></ul></div><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><select class="form-select product_unit unit_'+i+'" name="unit[]" id="validationCustom04"><option selected="" disabled="" value="">Choose...</option></select><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control price price_'+i+'" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class="row quantity-row"><div class="col-md-7 wp-r-0"><input class="form-control quantity quantity_cls_'+i+'" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"></div><div class="col-md-4 p-0-5"><span class="label label-t-success available_qty"> 0 </span></div><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control cgst cgst_cls_'+i+'" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control sgst sgst_cls_'+i+'" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col" style="display:none"><div class=""><input class="form-control igst igst_cls_'+i+'" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control net_value net_value_cls_'+i+'" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
            $(".category_clone:first").clone().appendTo('.category_field'+i);
        });

        function name(value = null) {
            i++;  
           $('#table').append('<tr id="row'+i+'" class="dynamic-added"><td scope="col"><div class="category_field'+i+'"><div class="invalid-feedback">Please select a valid state.</div></div></td><td scope="col"><div class="relative"><input class="form-control product product_details_'+i+'" id="validationCustomUsername" name="product[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><input type="hidden" class="product_id_'+i+'" name="product_id[]"><input type="hidden" name="taxable_cost[]" tabindex="-1" class="taxable_cost taxable_cost_cls_'+i+' form-control" /><div class="search-list"><ul class="suggesstion-box" data-id="'+i+'"></ul></div><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><select class="form-select product_unit unit_'+i+'" name="unit[]" id="validationCustom04"><option selected="" disabled="" value="">Choose...</option></select><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control price price_'+i+'" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class="row quantity-row"><div class="col-md-7 wp-r-0"><input class="form-control quantity quantity_cls_'+i+'" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"></div><div class="col-md-4 p-0-5"><span class="label label-t-success available_qty"> 0 </span></div><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control cgst cgst_cls_'+i+'" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control sgst sgst_cls_'+i+'" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col" style="display:none"><div class=""><input class="form-control igst igst_cls_'+i+'" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control net_value net_value_cls_'+i+'" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
            $(".category_clone:first").clone().appendTo('.category_field'+i);
        }
  
        $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();

        var sales_details_del = $(this).attr('data-id');
        var deleted_id = $('.sales_details_deleted_id').val();
        if(deleted_id != ''){
            var deleted_arr = deleted_id.split(",");
            deleted_arr.push(sales_details_del);
            var sales_details_del = deleted_arr.join(",");
            $('.sales_details_deleted_id').val(sales_details_del);

        }else{
            $('.sales_details_deleted_id').val(sales_details_del);
        }


        calculate();
        });  

    //   $(document).on('click','#add',function(){
    //     category();
    //   })
        // function category(){
        //     $.ajax({
        //         type: 'POST',
        //         url: '<?php echo base_url(); ?>report/stock_report/get_category',
        //         success: function(data) {
        //             var result = JSON.parse(data);
        //             if (result != null && result.length > 0) {
        //                 option_text = '<option value="">Select Category</option>';
        //                 $.each(result, function(key, value) {
        //                     option_text += '<option value="' + value.iCategoryId + '">' + value.vCategoryName + '</option>';
        //                 });
        //                 $('.category_id').html(option_text);
        //             } else {
        //                 $('.category_id').val('');
        //             }
        //         }
        //     });
        // }
    });  

    $(document).ready(function(){
        // var sales_order_id = $('.sales_order_id').val();
        // $.ajax({
        //     type: "POST",
        //     url: "<?php echo base_url() ?>order/get_sales_order_values",
        //     data:{sales_order_id:sales_order_id},
        //     success: function(data){
        //         data = JSON.parse(data);
        //         for (s = 0; s < data.length; ++s) {
        //             name(value)
        //             $("#add").trigger("click");
        //         }
        //     }
        // });
        var sum = 0;
    $(".sales_qty").each(function(){
        sum += +$(this).val();
    });
    $('.total_net_quantity').val(sum);
    });  
    //get product by category
    $(document).on('change','.category_clone', function(){
        var category = $(this).val();   
        //$('.product').val('');
        $(this).closest('tr').find('.product').val('');
        $(this).closest('tr').find('.product_unit').empty();
        $(this).closest('tr').find('.quantity').val('');
        $(this).closest('tr').find('.price ').val('');
        $(this).closest('tr').find('.net_value ').val('');
        $(this).closest('tr').find('.cgst ').val('');
        $(this).closest('tr').find('.igst ').val('');
        $(this).closest('tr').find('.sgst ').val('');
        $(".suggesstion-box").hide();
    });


    $(document).on("keyup",'.product',function() {
        _this = $(this);
        var product = $(this).val();    
        var category = $(this).closest('tr').find('.category_clone').val();  
        var dataID = $(_this).closest('td').find('.suggesstion-box').attr('data-id');
        var headoffice = $('.headoffice').val();
        var branch = $('.branch').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_product",
            data:{category:category,product:product,headoffice:headoffice,branch:branch},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                $.each(data, function(key,val) {
                    html += '<li value= '+val['iProductId']+' onClick="selectProduct(`'+dataID+'`,`'+val['iProductId']+'`,`'+val['vProductName']+'`)">'+val['vProductName'].toUpperCase()+'</li>';
                });
                $(_this).closest('td').find('.suggesstion-box').show();
                $(_this).closest('td').find('.suggesstion-box').html(html);
                $(".product").css("background","#FFF");
            }
        });
    });

    function selectProduct(dataID,iProductId,val) {
        $('.product_details_'+dataID).val(val.toUpperCase());
        $(".suggesstion-box").hide();
        $('.product_id_'+dataID).val(iProductId);

        //get product unit
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>stock/get_product_unit",
            data:{iProductId:iProductId},
            success: function(data){
                $(".unit_"+dataID).empty();
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose Unit</option>';
                $.each(data,function(key, val)
                {
                        html += '<option value=' +val['iProductUnitId']+ '>' + val['vProductUnitName'] + '</option>';
                });
                    $(".unit_"+dataID).html(html);
                // $.each(data, function(key,val) {
                //     html += '<li value= '+val['iProductId']+' onClick="selectProduct(`'+dataID+'`,`'+val['iProductId']+'`,`'+val['vProductName']+'`)">'+val['vProductName'].toUpperCase()+'</li>';
                // });
                // $(_this).closest('td').find('.suggesstion-box').show();
                // $(_this).closest('td').find('.suggesstion-box').html(html);
                // $(".product").css("background","#FFF");
                calculate();
            }
        });

        //get gst value
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_gst_values",
            data:{iProductId:iProductId},
            success: function(data){
                data = JSON.parse(data);
                $('.cgst_cls_'+dataID).val(data['CGST']);
                $('.sgst_cls_'+dataID).val(data['SGST']);
                $('.igst_cls_'+dataID).val(data['IGST']);
            }
        });

        $(document).on('change','.unit_'+dataID, function(){
            $('.quantity_cls_'+dataID).val('1');
            unit_id = $(this).val();
            $('.price_'+dataID).val('');
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_unit_price",
            data:{iProductId:iProductId,unit_id:unit_id},
                success: function(data){
                    data = JSON.parse(data);
                    $('.price_'+dataID).val(data['fProductPrice']);
                    $('.net_value_cls_'+dataID).val(data['fProductPrice']);
                    calculate();
                }
            });
        })

        

    }
    $(document).on("keyup",'.quantity,.price,.cgst,.sgst,.igst,.net_value',function() {
        if ($(this).hasClass("quantity")) {
            var _this = $(this);
            var quantity = $(this).val();
            var sale_order_detailsid = $(this).closest('tr').find('.sales_order_detail_id').val();
            var product_id = $(this).closest('tr').find('.product_id').val();
            var product_unit = $(this).closest('tr').find('.product_unit').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>order/check_sale_return_quantity",
                data:{product_id:product_id,quantity:quantity,product_unit:product_unit,sale_order_detailsid:sale_order_detailsid},
                success: function(data){
                        data = JSON.parse(data);
                    if(data.status == 'failure'){
                        _this.closest("tr").find(".quantity-err").val(1);
                        _this.closest("tr").find(".ajax_response_result").html('').html(data.message).css('color','red');
                    } else {
                        _this.closest("tr").find(".quantity-err").val(0);
                        _this.closest('tr').find(".ajax_response_result").html('');
                    }
                }
            });
        }
        calculate();
    });

    function calculate(dataID,quantity=''){
        // var quantity = $(this);
        var final_qty = 0;
        var final_sub_total = 0;
        var total_gst_price = 0.00;
        var total_cgst_price = 0.00;
        var total_sgst_price = 0.00;
        var total_igst_price = 0.00
        var customer_state = $('.customer_id').find('option:selected').attr('data-state');

        $('.delivery-order').find('.price').each(function(){
            var tr_ele = $(this).closest('tr');
            var quantity = tr_ele.find('.quantity');
            var percost = tr_ele.find('.price');
            var per_cgst = tr_ele.find('.cgst');
            var per_sgst = tr_ele.find('.sgst');
            var per_igst =  tr_ele.find('.igst');
            var subtotal =  tr_ele.find('.net_value');
            var taxable_cost =  tr_ele.find('.taxable_cost');
            if (Number(quantity.val()) != 0) {
                // console.log(percost);
                tot = Number(quantity.val()) * Number(percost.val());
                // $(this).closest('tr').find('.gross').val(tot);
                subtotal.val(tot.toFixed(2));
                var total_cgst_per = Number(per_cgst.val());
                var total_sgst_per = Number(per_sgst.val());
                var total_igst_per = Number(per_igst.val());
                var cgst_price = (Number(percost.val()) * Number(total_cgst_per / 100)).toFixed(2);
                var sgst_price = (Number(percost.val()) * Number(total_sgst_per / 100)).toFixed(2);
                var igst_price = (Number(percost.val()) * Number(total_igst_per / 100)).toFixed(2);

                // if($('#customer_state_id').val() == 31){
                    // var gst_price = (Number(cgst_price) + Number(sgst_price)).toFixed(2);
                // }else{
                    // var gst_price = (Number(cgst_price) + Number(igst_price)).toFixed(2);
                // }
                if(customer_state == 2){
                    var gst_price = (Number(cgst_price) + Number(sgst_price)).toFixed(2);
                }else{
                    var gst_price = Number(igst_price).toFixed(2);
                }

                $(taxable_cost).val((Number(percost.val()) - Number(gst_price)).toFixed(2));
                var cgst_price = (Number(tot) * Number(total_cgst_per / 100)).toFixed(2);
                var sgst_price = (Number(tot) * Number(total_sgst_per / 100)).toFixed(2);
                var igst_price = (Number(tot) * Number(total_igst_per / 100)).toFixed(2);

                // if($('#customer_state_id').val() == 31){
                    var total_taxgst_per = total_cgst_per + total_sgst_per;
                    var gst_price = (Number(cgst_price) + Number(sgst_price)).toFixed(2);
                // }else{
                //     var total_taxgst_per = total_cgst_per + total_igst_per;
                //     var gst_price = (Number(cgst_price) + Number(igst_price)).toFixed(2);
                // }
                if(customer_state == 2){
                    var total_taxgst_per = total_cgst_per + total_sgst_per;
                    var gst_price = (Number(cgst_price) + Number(sgst_price)).toFixed(2);
                }else{
                    var total_taxgst_per =  total_igst_per;
                    var gst_price = Number(igst_price).toFixed(2);
                }
                
                var wo_gst_price = (Number(tot) - Number(gst_price)).toFixed(2);

                total_gst_price = (Number(total_gst_price) + Number(gst_price));
                total_cgst_price = (Number(total_cgst_price) + Number(cgst_price));
                total_sgst_price = (Number(total_sgst_price) + Number(sgst_price));
                total_igst_price = (Number(total_igst_per) + Number(igst_price));
                final_sub_total = final_sub_total + tot;
                final_qty = final_qty + Number(quantity.val());

            } else {
                subtotal.val('0.00');
            }
        });
        var sum = 0;
        $(".quantity").each(function(){
            sum += +$(this).val();
        });
        $('.total_net_qty').val(sum);
        var taxable_price = final_sub_total - Number(total_gst_price).toFixed(2);
        $('.taxable_price').val(taxable_price.toFixed(2));
        $('.add_cgst').val(total_cgst_price.toFixed(2));
        $('.add_sgst').val(total_sgst_price.toFixed(2));
        // var tax_amount = total_cgst_price+total_sgst_price;
        var tax_amount = total_gst_price;
        $('.add_igst').val(tax_amount.toFixed(2));
        if(customer_state == 2)
        $('.add_igst_per').val('0.00');
        else
        $('.add_igst_per').val(tax_amount.toFixed(2));
        $('.final_sub_total').val(final_sub_total.toFixed(2));
        var totaltax = $('.totaltax').val();
        var additional_charge = $('.additional_charge').val();
        final_sub_total = final_sub_total + parseInt(additional_charge);
        // if (totaltax)
        //     final_sub_total = final_sub_total + parseInt(totaltax);
        // final_sub_total = final_sub_total + transport + labour + advance;
        $('.final_amt').val(final_sub_total.toFixed(2));
        // $('.round_off').val(final_sub_total.toFixed(0));
    }

    //get branch name by head office
    $(".headoffice").on('change', function(){
        var headoffice = $(this).val();  
        $('.branch').empty();
            $('.branch').val('');
            $( ".branch" ).removeClass( "disabled" )
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>stock/get_branch",
            data:{headoffice:headoffice},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose Unit</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['iBranchId']+'>'+val['vBranchName']+'</option>';
                });
                    $('.branch').html(html);
            }
        });
    })
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>