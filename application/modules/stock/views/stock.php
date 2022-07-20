<?php
    $theme_path = $this->config->item('theme_locations');
?>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({
        breakpoint: 768
        });
    });
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3>Add Delivery Order</h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Delivery Order</li>
                <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form action=<?php echo base_url('stock/add_branch_products'); ?> autocomplete="off" class="needs-validation" novalidate="" method="post">
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
                            <div class="col-md-6">
                                <label class="form-label" for="validationCustom04">Head office</label>
                                <select class="form-select headoffice head-office-multiple" name="headoffice" id="validationCustom04" required>
                                    <?php foreach ($headoffice as $list){ ?>
                                        <option selected value="">Select</option>
                                    <option data-state="<?php echo $list['iStateId'] ?>" value="<?php echo $list['iHeadOfficeId'] ?>"><?php echo $list['vHeadOfficeName'] ?></option>
                                <?php } ?>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="validationCustom04">Branch</label>
                                <select class="form-select branch disabled branch-multiple" name="branch" id="validationCustom04">
                                    
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="validationCustom04">DO Number</label>
                                <input type="text" class="form-control" id="validationCustom03" name="delivery_order_number" readonly value="<?php echo "DO-".($delivery_order_number['iOrderNumber']+ 1); ?>">
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Staus</label>
                                <select class="form-select delivery-status" name="delivery_status" id="validationCustom04">
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                <div class="invalid-feedback">Please select a status.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="validationCustom04">Date</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" value="<?php echo date("d-m-Y") ?>" type="text" name="delivery_order_date" placeholder="" required>
                                <div class="invalid-feedback" style="color:red">Field is required</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Shipping address</label><span style="color:red">*</span>
                                <textarea class="form-control add_ship_address" id="validationCustom03" type="text" name="shipping_adress" placeholder=""></textarea>
                            </div>
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Date</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" readonly type="text" name="date" placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
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
                                <div class="add-sales-table">
                                    <button type="button" class="btn btn-success wnone mobile-add-btn" id="add">Add Product</button>
                                    <table class="table delivery-order basictable" id="table">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th width="15%" scope="col">Category</th>
                                                <th width="27%" scope="col">Product</th>
                                                <th width="10%" scope="col">Unit</th>
                                                <th width="8%" class="text-center" scope="col">Quantity</th>
                                                <th width="8%" class="text-right" scope="col">Price</th>
                                                <th width="6%" class="text-center" scope="col">CGST</th>
                                                <th width="6%" class="text-center" scope="col">SGST</th>
                                                <th width="6%" class="text-center" scope="col">IGST</th>
                                                <th width="10%" class="text-right" scope="col">Net&nbsp;Value</th>
                                                <th width="4%" scope="col"><b class="wnone">Remove</b> <button type="button" class="btn btn-t-success btn-sm" id="add"><i class="icofont icofont-plus"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="row" class="dynamic-added">
                                                <td scope="col">
                                                    <div class="category_id">
                                                    <!-- <select class="form-select category_id" name="category[]" id="validationCustom04"  >
                                                    </select> -->
                                                    <div class="invalid-feedback">Please select a valid state.</div>
                                                    </div>
                                            </td>
                                            <td scope="col">
                                                <div class="relative">
                                                    <input class="form-control product product_details_0" id="validationCustomUsername" name="product[]" required type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                    <input type="hidden" class="product_id_0 product_id" name="product_id[]">
                                                    <input type="hidden" class="product_color_id_0 product_color_id" name="product_color_id[]">
                                                    <div class="search-list"><ul class="suggesstion-box" data-id=0></ul></div>
                                                    <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="form-label" for="validationCustom04">
                                                <select class="form-select product_unit unit_0" name="unit[]" id="validationCustom04" required="required">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                </select>
                                                <span class="duplicate_value"></span>
                                                <input type="hidden" class="duplicate_value_err">
                                                <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="row quantity-row">
                                                    <div class="col-md-7 wp-r-0"><input class="form-control quantity" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" required aria-describedby="inputGroupPrepend"  ></div>
                                                    <div class="col-md-4 p-0-5"><span class="label label-t-success available_qty" > 0 </span></div>                                                    
                                                    <input type="hidden" class="quantity-err">
                                                    <span class="ajax_response_result"></span>
                                                    <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control price price_0" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required>
                                                <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control cgst cgst_cls_0" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required >
                                                <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control sgst sgst_cls_0" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required>
                                                <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control igst igst_cls_0" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control net_value net_value_cls_0" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required>
                                                <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="p-0">
                                                    <table width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align:right;"><strong>Taxable Price</strong></td>
                                                                <td><input type="text" name="deleivery[taxable_price]" tabindex="-1" value="0.00" class="taxable_price text_right w-100p form-control m-0" readonly=""/></td>
                                                                <td style="text-align:right;"> <strong>CGST</strong> </td>
                                                                <td><input type="text" name="deleivery[cgst_price]" tabindex="-1"  value="0.00"  readonly class="add_cgst text_right w-100p form-control m-0" /></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>   
                                                <td style="text-align:right;" class="sgst_td"> <strong>SGST</strong> </td>
                                                <td class="sgst_td"><input type="text" name="deleivery[sgst_price]" tabindex="-1" value="0.00"  readonly class="sgst_td add_sgst text_right form-control m-0" /></td>
                                                <td style="text-align:right;" colspan="2" class="igst_td"> <strong>Tax Amount</strong> </td>
                                                <td class="igst_td"><input type="text" name="deleivery[igst_price]" tabindex="-1" value="0.00"  readonly class="add_igst text_right form-control m-0" /></td>
                                                <td style="text-align:right;font-weight:bold;">Net Total</td>
                                                <td><input type="text" tabindex="-1"  name="deleivery[net_total]" id="net_total" readonly="readonly"  class="final_amt text_right form-control m-0" /></td>
                                                <td><input type="hidden" tabindex="-1"  name="deleivery[net_qty]" id="total_net_qty" readonly="readonly"  class="total_net_qty text_right form-control m-0" /></td>
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
                            <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12 submit"><input type="submit" class="btn btn-t-success"> <a href="<?php echo base_url('stock') ?>"><input class="btn btn-danger pull-left" type="button" value="Cancel"></a></div></div>
                        </div>                        
                    </div>
                </div>
            </div>        
        </div>
    </div>
</form>
<script type="text/javascript">
               function calculate(dataID,quantity=''){
        // var quantity = $(this);
        var final_qty = 0;
        var final_sub_total = 0;
        var total_gst_price = 0.00;
        var total_cgst_price = 0.00;
        var total_sgst_price = 0.00;
        var total_igst_price = 0.00
       var headoffice = $('.headoffice').find('option:selected').attr('data-state');

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
                tot = Number(quantity.val()) * Number(percost.val());
                // $(this).closest('tr').find('.gross').val(tot);
                subtotal.val(tot.toFixed(2));
                var total_cgst_per = Number(per_cgst.val());
                var total_sgst_per = Number(per_sgst.val());
                var total_igst_per = Number(per_igst.val());
                var cgst_price = (Number(percost.val()) * Number(total_cgst_per / 100)).toFixed(2);
                var sgst_price = (Number(percost.val()) * Number(total_sgst_per / 100)).toFixed(2);
                var igst_price = (Number(percost.val()) * Number(total_igst_per / 100)).toFixed(2);

                if(headoffice == 2){
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
                if(headoffice == 2){
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
        var taxable_price = final_sub_total - Number(total_gst_price).toFixed(2);
        $('.taxable_price').val(taxable_price.toFixed(2));
        $('.add_cgst').val(total_cgst_price.toFixed(2));
        $('.add_sgst').val(total_sgst_price.toFixed(2));
        var tax_amount = total_gst_price;
        $('.add_igst').val(tax_amount.toFixed(2));
        $('.final_sub_total').val(final_sub_total.toFixed(2));
        $('.total_net_qty').val(sum);
        var totaltax = $('.totaltax').val();
        // if (totaltax)
        //     final_sub_total = final_sub_total + parseInt(totaltax);
        // final_sub_total = final_sub_total + transport + labour + advance;
        $('.final_amt').val(final_sub_total.toFixed(2));
        // $('.round_off').val(final_sub_total.toFixed(0));
    }
    $(document).on("keyup",'.quantity,.price,.cgst,.sgst,.igst,.net_value',function() {
        calculate();  
    });
    $(document).ready(function(){      
        $(document).on('click','.submit',function(){
            // $.each('.quantity-err', function(key,val))
            qty_err = 0;
            $(".quantity-err").each(function(){
                qty_err += +$(this).val();
            });
            duplicate_value_err = 0;
            $(".duplicate_value_err").each(function(){
                duplicate_value_err += +$(this).val();
            });
            
            if(qty_err != 0 || duplicate_value_err != 0){
                return false;
            }
        })
        // category();
        $(".category_clone:first").clone().appendTo(".category_id");
        $('.category_id').find('.category_clone').prop('required',true);
      var i=0;  
   
        $(document).on('click', '#add', function(e){  
            e.preventDefault();
           i++;  
           $('#table').append('<tr id="row'+i+'" class="dynamic-added"><td scope="col" data-th="Category"><div class="w-100 category_field'+i+'"><div class="invalid-feedback">Please select a valid state.</div></div></td><td scope="col" data-th="Product"><div class="relative"><input class="form-control product product_details_'+i+'" id="validationCustomUsername" required name="product[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><input type="hidden" class="product_id_'+i+' product_id" name="product_id[]"><input type="hidden" class="product_color_id_'+i+' product_color_id" name="product_color_id[]"><div class="search-list"><ul class="suggesstion-box" data-id="'+i+'"></ul></div></div></td><td scope="col" data-th="Unit"><div class="relative"><select class="form-select product_unit unit_'+i+'" name="unit[]" required id="validationCustom04"><option selected="" disabled="" value="">Choose...</option></select><span class="duplicate_value"></span><input type="hidden" class="duplicate_value_err"></div></td><td scope="col" data-th="Quantity"><div class="row quantity-row"><div class="col-md-7 wp-r-0"><input class="form-control quantity" id="validationCustomUsername" name="quantity[]" required type="text" placeholder="" aria-describedby="inputGroupPrepend"  ></div><div class="col-md-4 p-0-5"><span class="label label-t-success available_qty" > 0 </span></div><input type="hidden" class="quantity-err"><span class="ajax_response_result"></span></div></td><td scope="col" data-th="Price"><div class=""><input class="form-control price price_'+i+'" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td data-th="CGST" scope="col"><div class=""><input class="form-control cgst cgst_cls_'+i+'" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td scope="col" data-th="SGST"><div class=""><input class="form-control sgst sgst_cls_'+i+'" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required ></div></td><td scope="col" data-th="IGST"><div class=""><input class="form-control igst igst_cls_'+i+'" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col" data-th="Net Value"><div class=""><input class="form-control net_value net_value_cls_'+i+'" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td data-th="Remove "><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
            $(".category_clone:first").clone().appendTo('.category_field'+i);
            $('.category_field'+i).find('.category_clone').prop('required',true);
        });
  
        $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();
        calculate();
        });
    });  

    //get product by category
    $(document).on('change','.category_clone', function(){
        var category = $(this).val();   
        //$('.product').val('');
        $(this).closest('tr').find('.product').val('');
        $(this).closest('tr').find('.product_unit').val('');
        $(this).closest('tr').find('.quantity').val('');
        $(".suggesstion-box").hide();
    });
    $(document).on("keyup",'.quantity',function(){
        var sum = 0;
        $(".quantity").each(function(){
            sum += +$(this).val();
        });
        $('.total_net_quantity').val(sum);
    })

    $(document).on("keyup",'.product',function() {
        _this = $(this);
        var branch = $('.branch').val();
        var product = $(this).val();    
        var category = $(this).closest('tr').find('.category_clone').val();  
        var dataID = $(_this).closest('td').find('.suggesstion-box').attr('data-id');
        var type = (category)  ?  'category' : 'all';

        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>stock/get_product",
            data:{category:category,product:product,branch:branch,type:type},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                $.each(data, function(key,val) {
                    var product_color_value = val['vProductName'];
                    var color_id = 0;
                    if(val['vColorName'] != null){
                        product_color_value = val['vProductName']+' ('+val['vColorName']+')';
                        color_id = val['iProductColorId'];
                    }
                    html += '<li value= '+val['iProductId']+' onClick="selectProduct(`'+dataID+'`,`'+val['iProductId']+'`,`'+product_color_value+'`,`'+type+'`,`'+val['prod_category']+'`,`'+color_id+'`)">'+product_color_value.toUpperCase()+'</li>';
                });
                $(_this).closest('td').find('.suggesstion-box').show();
                $(_this).closest('td').find('.suggesstion-box').html(html);
                $(".product").css("background","#FFF");
            }
        });
    });

    function selectProduct(dataID,iProductId,val,type,category_id,color_id) {
        $('.product_details_'+dataID).val(val.toUpperCase());
        $(".suggesstion-box").hide();
        $('.product_id_'+dataID).val(iProductId);
        $('.product_color_id_'+dataID).val(color_id);
        if(type == "all"){
           $('.product_details_'+dataID).closest('tr').find('.category_clone').val(category_id);
        }
        
        //get product unit
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>stock/get_product_unit",
            data:{iProductId:iProductId},
            success: function(data){
                $(".unit_"+dataID).empty();
                console.log(data);
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose...</option>';
                $.each(data,function(key, val)
                {
                        html += '<option value=' +val['iProductUnitId']+ '>' + val['vProductUnitName'] + '</option>';
                });
                $(".unit_"+dataID).html(html);
                // tr_ele.addClass('product_added').addClass('product_added_'category_id+'_'+iProductId);
            }
        });

        var headoffice = $('.headoffice').find('option:selected').attr('data-state');
        // get gst value
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_gst_values",
            data:{iProductId:iProductId},
            success: function(data){
                data = JSON.parse(data);
                console.log(data);
                // $('.cgst_cls_'+dataID).val(data['CGST']);
                // $('.sgst_cls_'+dataID).val(data['SGST']);
                // $('.igst_cls_'+dataID).val(data['IGST']);
                if(headoffice==2)
                {
                $('.cgst_cls_'+dataID).val(data['CGST']);
                $('.sgst_cls_'+dataID).val(data['SGST']);
                $('.igst_cls_'+dataID).val('0.00');
                }
                else{
                $('.cgst_cls_'+dataID).val('0.00');
                $('.sgst_cls_'+dataID).val('0.00');
                $('.igst_cls_'+dataID).val(data['IGST']);
                }
            }
        });
    }
    
    //check duplicate value adding on delivery order
    $(document).on('change','.product_unit',function(){
        var unit = $(this).val();
        if(unit>0){
            var tr_ele = $(this).closest('tr');
            var old_class = tr_ele.attr('dynamic_class');
            tr_ele.addClass('dynamic-added');
            category_id = tr_ele.find('.category_clone').val();
            iProductId = tr_ele.find('.product_id').val();
            color_id = tr_ele.find('.product_color_id').val();
            dynamic_class = 'product_added_'+category_id+'_'+iProductId+'_'+unit+'_'+color_id;
            product_class = $('.'+dynamic_class);
            tr_ele.removeClass(old_class);
            if(product_class.length >= 1){
                tr_ele.find(".duplicate_value").html("Already exist").css('color','red');
                tr_ele.find(".duplicate_value_err").val(1);
                return false;
            }else{
                tr_ele.find(".duplicate_value").html("");
                tr_ele.addClass(dynamic_class);
                tr_ele.attr('dynamic_class',dynamic_class);
                tr_ele.find(".duplicate_value_err").val(0);
            }
        }
    })
    //get branch name by head office
    $(".headoffice").on('change', function(){
        var headoffice = $(this).val();  
        var stateid = $(this).find('option:selected').attr('data-state');
        // if(stateid==2)
        //         {
        //         $('.cgst').val(5).attr('disabled',false);
        //         $('.sgst').val(5).attr('disabled',false);
        //         $('.igst').val(0).attr('disabled',true);
        //         }
        //         else{
        //         $('.cgst').val(0).attr('disabled',true);
        //         $('.sgst').val(0).attr('disabled',true);
        //         $('.igst').val(5).attr('disabled',false);
        //         }
                calculate(); 
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
                html += '<option value="">Choose...</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['iBranchId']+'>'+val['vBranchName']+'</option>';
                });
                    $('.branch').html(html);
            }
        });
    })

    $(document).on("keyup",'.quantity',function() {
        var quantity = $(this).val();
        if(quantity != ""){
            var _this = $(this);
            var product_id = $(this).closest('tr').find('.product_id').val();
            var product_unit = $(this).closest('tr').find('.product_unit').val();
            var product_color = $(this).closest('tr').find('.product_color_id').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>stock/check_product_quantity",
                data:{product_id:product_id,quantity:quantity,product_unit:product_unit,product_color:product_color},
                success: function(data){
                        data = JSON.parse(data);
                        // console.log(data.status);
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

    $(document).on('change','.product_unit', function(){
        _this = $(this);
        $(this).closest('tr').find('.quantity').val('');
        $(this).closest('tr').find('.available_qty').text(0);
        var unit_id = $(this).val();
        var product_id = $(this).closest('tr').find('.product_id').val();
        var color_id = $(this).closest('tr').find('.product_color_id').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>stock/get_product_quantity",
        data:{product_id:product_id,unit_id:unit_id,color_id:color_id},
            success: function(data){
                console.log(data);
                if(data == false){             
                    qty = 0;
                }else{
                    data = JSON.parse(data);
                    qty = data['dProductQty'];
                    price = data['fProductPrice'];
                }
                _this.closest('tr').find('.available_qty').text(qty);
                _this.closest('tr').find('.price').val(price);

                calculate();
            }
        });
    })


</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>