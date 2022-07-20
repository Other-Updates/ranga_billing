<?php
    $theme_path = $this->config->item('theme_locations');
    // echo "<pre>";
    // echo"<pre>";print_r($purchase_order['vSupplierName']);exit;
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
                <h3>View Purchase</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Edit Purchase</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form action=<?php echo base_url('purchase_order/update_purchase_order'); ?> method="post">
    <input type="hidden" name="purchase_details_deleted_id" class="purchase_details_deleted_id">
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
                            <input type="hidden" name="purchase_order_id" class="purchase_order_id" value="<?php echo $purchase_order_id ?>">
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Supplier</label><br>
                                <label class="value-lable"><?php echo $purchase_order['vSupplierName'] ?></label>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">PO number</label>
                                <input class="form-control" id="validationCustom03" type="text" placeholder="" name="purchaseorderno" class="purchaseorderno" readonly value="<?php echo $purchase_order['vPurchaseOrderNo']; ?>" required="">
                                <div class="invalid-feedback">Field is required.</div>
                            </div>                            
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Date</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" disabled value="<?php echo $purchase_order['dDeliveryDate'] ?>" readonly type="text" name="ordered_date" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Address</label>
                                <textarea class="form-control value-lable" id="validationCustom03" type="text" readonly name="address" placeholder="" required=""><?php echo $purchase_order['vAddress']; ?></textarea>
                                <div class="invalid-feedback">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Status</label>
                                    <select class="form-select" disabled name="delivery_status" id="validationCustom04"  >
                                    <option selected="" disabled="" value="">Select</option>
                                    <option value="Not Shipped"<?php if($purchase_order['eDeliveryStatus']=="Not Shipped"){ echo "selected"; } ?>>Not Shipped</option>
                                    <option value="Delivered" <?php if($purchase_order['eDeliveryStatus']=="Delivered"){ echo "selected"; } ?>>Delivered</option>
                                    <option value="Cancelled"<?php if($purchase_order['eDeliveryStatus']=="Cancelled"){ echo "selected"; } ?>>Cancelled</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a valid state.</div>
                                </div>

                               
                            <div class="col-md-8" style="display:none">
                                <select class="form-select category_clone" name="category[]" id="validationCustom04"  >
                                <option selected="" disabled="" value="">Select category</option>
                                <?php foreach ($category as $categories){ ?>
                                <option value="<?php echo $categories['iCategoryId'] ?>"><?php echo $categories['vCategoryName'] ?></option>
                                <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                        </div>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 col-xl-12">
                                <div class="">
                                    <table class="table delivery-order basictable view-table" id="table">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th width="15%" scope="col">Category</th>
                                                <th width="28%" scope="col">Product</th>
                                                <th width="10%" scope="col" class="text-center">Unit</th>
                                                <th width="8%" class="text-right" scope="col">Price</th>
                                                <th width="8%" class="text-center" scope="col">Quantity</th>
                                                <th width="7%" class="text-center" scope="col">CGST</th>
                                                <th width="7%" class="text-center" scope="col">SGST</th>
                                                <th width="7%" class="text-center" scope="col">IGST</th>
                                                <th width="10%" class="text-right" scope="col">Net&nbsp;Value <input type="hidden" name="sales_count" class="sales_count" value="<?php echo count($purchase_order['purchase_details']) ?>"></th>
                                            </tr>
                                        </thead>                                        
                                        <?php foreach($purchase_order['purchase_details'] as $key=>$purchase){ ?>
                                        <tr id="row" class="dynamic-added">
                                            <td scope="col">
                                                <label class=""><?php echo $purchase['vCategoryName'] ?></label>
                                            </td>                                            
                                            <td scope="col">
                                                <input type="hidden" name="iPurchaseOrderDetailsId[]" value="<?php echo $purchase['iPurchaseOrderDetailsId'] ?>">
                                                <div class="relative">
                                                    <label for=""><?php echo strtoupper($purchase['vProductName']); ?></label>
                                                    <input type="hidden" class="product_id_<?php echo $key ?>" name="product_id[]" value="<?php echo $purchase['iProductId'] ?>">
                                                    <input type="hidden" class="product_color_id_<?php echo $key ?>" name="product_color_id[]" value="<?php echo $purchase['iProductColorId'] ?>">
                                                    <input type="hidden" name='taxable_cost[]' tabindex="-1" class="taxable_cost taxable_cost_cls_0 form-control" />
                                                    <div class="search-list"><ul class="suggesstion-box" data-id=<?php echo $key ?>></ul></div>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-center">
                                                <div class="">
                                                   <label for=""><?php echo $purchase['vProductUnitName'] ?></label>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-right">
                                                <div class="">
                                                <label for="" class="price"><?php echo $purchase['iPurchaseCostperQTY'] ?></label>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-center">
                                                <div class="">
                                                <label for="" class="quantity"><?php echo $purchase['iPurchaseQTY'] ?></label>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-center">
                                                <div class="">
                                                <label for="" class="cgst"><?php echo $purchase['CGST'] ?></label>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-center">
                                                <div class="">
                                                <label for="" class="sgst"><?php echo $purchase['SGST'] ?></label>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-center">
                                                <div class="">
                                                <label for="" class="igst"><?php echo $purchase['IGST'] ?></label>
                                                </div>
                                            </td>
                                            <td scope="col" class="text-right">
                                                <div class="">
                                                <label for="" class="net_value"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="p-0">
                                                    <table width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align:right;"><strong>Taxable Price</strong></td>
                                                                <td><input type="text" name="purchase[taxable_price]" tabindex="-1" value="0.00" class="taxable_price text_right w-100p form-control m-0" readonly=""/></td>
                                                                <td style="text-align:right;"> <strong>CGST</strong> </td>
                                                                <td><input type="text" name="purchase[cgst_price]" tabindex="-1"  value="0.00"  readonly class="add_cgst text_right w-100p form-control m-0" /></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>   
                                                <td style="text-align:right;" class="sgst_td"> <strong>SGST</strong> </td>
                                                <td class="sgst_td"><input type="text" name="purchase[sgst_price]" tabindex="-1" value="0.00"  readonly class="sgst_td add_sgst text_right form-control m-0" /></td>
                                                <td style="text-align:right;"  colspan="2" class="igst_td"> <strong>Tax Amount</strong> </td>
                                                <td class="igst_td"><input type="text" name="purchase[igst_price]" tabindex="-1" value="0.00"  readonly class="add_igst text_right form-control m-0" /></td>
                                                <td style="text-align:right;font-weight:bold;">Net Total</td>
                                                <td><input type="text" tabindex="-1"  name="purchase[net_total]" id="net_total" readonly="readonly"  class="final_amt text_right form-control m-0" /> <input type="hidden" tabindex="-1"  name="purchase[net_qty]" id="total_net_qty" readonly="readonly"  class="total_net_qty text_right form-control m-0" /></td>
                                                
                                               
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
                        </div>         
                    </div>
                    <div class="card-footer text-end">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <a href="<?php echo base_url('purchase_order'); ?>" class="pull-left"><button class="btn btn-danger"type="button">Back</button></a>
                        
                            <?php if($purchase_order['eDeliveryStatus']=="Delivered"){ ?><button class="btn btn-info print" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user" type="button">Print</button><?php } ?>
                        </div>                        
                    </div>   
                </div>
            </div>        
        </div>
    </div>
</form>
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title">Add Unit</h5> -->
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="unit_form" novalidate="" method="post" enctype="multipart/form-data" action="<?php echo base_url('purchase_order/print_purchase') ?>">
            <div class="modal-body scroll-y">
                <input type="hidden" name="purchase_order_id" value="<?php echo $purchase_order_id ?>">
                <div class="row g-3">
                    <div class="col">
                        <label class="form-label" for="validationCustom03">Language</label>
                        <label class="d-block" for="edo-ani">
                        <input class="radio_animated radio radio_active" id="edo-ani" type="radio" value='english' name="language" checked="" data-original-title="" title="">English
                        </label>
                        <label class="d-block" for="edo-ani1">
                        <input class="radio_animated radio radio_inactive" id="edo-ani1" type="radio" value='tamil' name="language" data-original-title="" title="">தமிழ்
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary" id="butsave" type="submit">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){      
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


    // function selectProduct(type,dataID,iProductId,val) {
    //     $('.product_details_'+dataID).val(val.toUpperCase());
    //     $(".suggesstion-box").hide();
    //     $('.product_id_'+dataID).val(iProductId);

    //     if(type == "all"){
    //     var category_clone = $('.product_details_'+dataID).closest('tr').find('.category_clone');
    //     $.ajax({
    //             type: "POST",
    //             url: "<?php echo base_url() ?>order/get_category_by_product",
    //             data:{iProductId:iProductId},
    //             success: function(data){
    //                 data = JSON.parse(data);
    //                $(category_clone).val(data.iCategoryId);
    //             }
    //         });
    //     }

    //     //get product unit
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url() ?>purchase_order/get_product_unit",
    //         data:{iProductId:iProductId},
    //         success: function(data){
    //             $(".unit_"+dataID).empty();
    //             data = JSON.parse(data);
    //             var html = '';
    //             html += '<option value="">Choose Unit</option>';
    //             $.each(data,function(key, val)
    //             {
    //                     html += '<option value=' +val['iProductUnitId']+ '>' + val['vProductUnitName'] + '</option>';
    //             });
    //                 $(".unit_"+dataID).html(html);
    //             // $.each(data, function(key,val) {
    //             //     html += '<li value= '+val['iProductId']+' onClick="selectProduct(`'+dataID+'`,`'+val['iProductId']+'`,`'+val['vProductName']+'`)">'+val['vProductName'].toUpperCase()+'</li>';
    //             // });
    //             // $(_this).closest('td').find('.suggesstion-box').show();
    //             // $(_this).closest('td').find('.suggesstion-box').html(html);
    //             // $(".product").css("background","#FFF");
    //             calculate();
    //         }
    //     });

    //     //get gst value
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url() ?>order/get_gst_values",
    //         data:{iProductId:iProductId},
    //         success: function(data){
    //             data = JSON.parse(data);
    //             $('.cgst_cls_'+dataID).val(data['CGST']);
    //             $('.sgst_cls_'+dataID).val(data['SGST']);
    //             $('.igst_cls_'+dataID).val(data['IGST']);
    //         }
    //     });

    //     $(document).on('change','.unit_'+dataID, function(){
    //         $('.quantity_cls_'+dataID).val('1');
    //         unit_id = $(this).val();
    //         $('.price_'+dataID).val('');
    //         $.ajax({
    //         type: "POST",
    //         url: "<?php echo base_url() ?>order/get_unit_price",
    //         data:{iProductId:iProductId,unit_id:unit_id},
    //             success: function(data){
    //                 data = JSON.parse(data);
    //                 // $('.price_'+dataID).val(data['fProductPrice']);
    //                 $('.net_value_cls_'+dataID).val(data['fProductPrice']);
    //                 calculate();
    //             }
    //         });
    //     })

        

    // }
    // $(document).on("keyup",'.quantity,.price,.cgst,.sgst,.igst,.net_value',function() {
    //     quantity = $(this);
    //     calculate();
    // });

    function calculate(){
        // var quantity = $(this);
        var final_qty = 0;
        var final_sub_total = 0;
        var total_gst_price = 0.00;
        var total_cgst_price = 0.00;
        var total_sgst_price = 0.00;
        var total_igst_price = 0.00
       

        $('.delivery-order').find('.price').each(function(){
            var tr_ele = $(this).closest('tr');
            var quantity = tr_ele.find('.quantity');
            var percost = tr_ele.find('.price');
            var per_cgst = tr_ele.find('.cgst');
            var per_sgst = tr_ele.find('.sgst');
            var per_igst =  tr_ele.find('.igst');
            var subtotal =  tr_ele.find('.net_value');
            var taxable_cost =  tr_ele.find('.taxable_cost');
            if (Number(quantity.text()) != 0) {
                // console.log(percost);
                tot = Number(quantity.text()) * Number(percost.text());
                // $(this).closest('tr').find('.gross').val(tot);
                subtotal.text(tot.toFixed(2));
                var total_cgst_per = Number(per_cgst.text());
                var total_sgst_per = Number(per_sgst.text());
                var total_igst_per = Number(per_igst.text());
                var cgst_price = (Number(percost.text()) * Number(total_cgst_per / 100)).toFixed(2);
                var sgst_price = (Number(percost.text()) * Number(total_sgst_per / 100)).toFixed(2);
                var igst_price = (Number(percost.text()) * Number(total_igst_per / 100)).toFixed(2);

                // if($('#customer_state_id').val() == 31){
                    var gst_price = (Number(cgst_price) + Number(sgst_price) + Number(igst_price)).toFixed(2);
                // }else{
                    // var gst_price = (Number(cgst_price) + Number(igst_price)).toFixed(2);
                // }

                $(taxable_cost).val((Number(percost.val()) - Number(gst_price)).toFixed(2));
                var cgst_price = (Number(tot) * Number(total_cgst_per / 100)).toFixed(2);
                var sgst_price = (Number(tot) * Number(total_sgst_per / 100)).toFixed(2);
                var igst_price = (Number(tot) * Number(total_igst_per / 100)).toFixed(2);

                // if($('#customer_state_id').val() == 31){
                    var total_taxgst_per = total_cgst_per + total_sgst_per + total_igst_per;
                    var gst_price = (Number(cgst_price) + Number(sgst_price) + Number(igst_price)).toFixed(2);
                // }else{
                //     var total_taxgst_per = total_cgst_per + total_igst_per;
                //     var gst_price = (Number(cgst_price) + Number(igst_price)).toFixed(2);
                // }
                
                
                var wo_gst_price = (Number(tot) - Number(gst_price)).toFixed(2);

                total_gst_price = (Number(total_gst_price) + Number(gst_price));
                total_cgst_price = (Number(total_cgst_price) + Number(cgst_price));
                total_sgst_price = (Number(total_sgst_price) + Number(sgst_price));
                total_igst_price = (Number(total_igst_per) + Number(igst_price));
                final_sub_total = final_sub_total + tot;
                final_qty = final_qty + Number(quantity.text());

            } else {
                subtotal.text('0.00');
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
        // $('.add_igst').val(total_igst_price.toFixed(2));
        // var tax_amount = total_cgst_price+total_sgst_price;
        var tax_amount = total_gst_price;
        $('.add_igst').val(tax_amount.toFixed(2));
        $('.final_sub_total').val(final_sub_total.toFixed(2));
        var totaltax = $('.totaltax').val();
        // if (totaltax)
        //     final_sub_total = final_sub_total + parseInt(totaltax);
        // final_sub_total = final_sub_total + transport + labour + advance;
        $('.final_amt').val(final_sub_total.toFixed(2));
        // $('.round_off').val(final_sub_total.toFixed(0));
    }

</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>