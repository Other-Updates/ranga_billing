<?php
    $theme_path = $this->config->item('theme_locations');
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
                <h3>Purchase Order</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active">Add Purchase</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form action=<?php echo base_url('purchase_order/add_purchase_order'); ?> autocomplete="off" class="needs-validation" novalidate="" method="post">
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
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Suppliers</label>
                                <select class="form-select Suppliers multiple" name="supplier" id="validationCustom04" required>
                                    <option disabld selected value="">Select</option>
                                    <?php foreach ($supplier as $suppliers){ ?>
                                    <option value="<?php echo $suppliers['iSupplierId'] ?>"><?php echo $suppliers['vSupplierName'] ?></option>
                                <?php } ?>
                                </select>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">PO Number</label>
                                <input class="form-control" id="validationCustom03" type="text" name="po_number" value="<?php echo "PO-".($purchase_order_number['iOrderNumber'] + 1); ?>" readonly placeholder="" >
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>                            
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Date</label>
                                <input class="form-control" id="validationCustom03" type="date" name="ordered_date" placeholder="" required="">
                                <div class="invalid-feedback">Field is required.</div>
                            </div> -->
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Date</label>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" value="<?php echo date("d/m/Y") ?>" type="text" name="delivered_date" placeholder="" required>
                                <div class="invalid-feedback"style="color:red">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Address</label>
                                <textarea class="form-control add_address" id="validationCustom03" type="text" name="address" placeholder="" required=""></textarea>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Status</label>
                                    <select class="form-select" name="delivery_status" id="validationCustom04"  >
                                    <option selected="" disabled="" value="">Select</option>
                                    <option value="Not Shipped" selected>Not Shipped</option>
                                    <option value="Delivered" >Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
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
                                <div class="add-sales-table">
                                    <button class="btn btn-success wnone mobile-add-btn" id="add">Add Product</button>
                                    <table class="table delivery-order basictable" id="table">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th width="15%" scope="col">Category</th>
                                                <th width="27%" scope="col">Product</th>
                                                <th width="10%" scope="col">Unit</th>
                                                <th width="8%" class="text-right" scope="col">Price</th>
                                                <th width="8%" class="text-center" scope="col">Quantity</th>
                                                <th width="6%" class="text-center" scope="col">CGST</th>
                                                <th width="6%" class="text-center" scope="col">SGST</th>
                                                <th width="6%" class="text-center" scope="col">IGST</th>
                                                <th width="10%" class="text-right" scope="col">Net&nbsp;Value</th>
                                                <th width="4%" scope="col"><b class="wnone">Remove</b> <button type="" class="btn btn-success btn-sm" id="add"><i class="icofont icofont-plus"></i></button></th>
                                            </tr>
                                        </thead>
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
                                                    <input class="form-control product product_details_0" id="validationCustomUsername" name="product[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required>
                                                    <input type="hidden" class="product_id product_id_0" name="product_id[]">
                                                    <input type="hidden" class="product_color_id product_color_id_0" name="product_color_id[]">
                                                    <input type="hidden" name='taxable_cost[]' tabindex="-1" class="taxable_cost taxable_cost_cls_0 form-control" />
                                                    <div class="search-list"><ul class="suggesstion-box" data-id=0></ul></div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="relative">
                                                <select class="form-select product_unit unit_0" name="unit[]" id="validationCustom04" required>
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                </select>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control price price_0" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required >
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control quantity quantity_cls_0" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required >
                                                <input type="hidden" class="quantity-err">
                                                <span class="ajax_response_result"></span>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control cgst cgst_cls_0" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" >
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control sgst sgst_cls_0" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend">
                                                </div>
                                            </td>
                                            <td scope="col" >
                                                <div class="">
                                                <input class="form-control igst igst_cls_0" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control net_value net_value_cls_0" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required>
                                                </div>
                                            </td>
                                            <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td>
                                        </tr>
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
                                                <td><input type="text" tabindex="-1"  name="purchase[net_total]" id="net_total" readonly="readonly"  class="final_amt text_right form-control m-0" /></td>
                                                <td><input type="hidden" tabindex="-1"  name="purchase[net_qty]" id="total_net_qty" readonly="readonly"  class="total_net_qty text_right form-control m-0" /></td>
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
                            <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12"><input type="submit" class="btn btn-success submit"> <a href="<?php echo base_url('purchase_order') ?>"><input class="btn btn-danger pull-left" type="button" value="Cancel"></a></div></div>
                        </div>                        
                    </div>
                    <!-- <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12"><input type="submit" class="btn btn-success"> <input class="btn btn-danger pull-left" type="reset" value="Cancel"></div></div> -->
                </div>
            </div>        
        </div>
    </div>
</form>
<script type="text/javascript">
    // $(document).on("keyup",'.cgst,.sgst',function() {
    //     var value = $(this).val();
    //     if(value!=''){
    //     $(this).closest("tr").find('.cgst').prop('readonly', false);
    //     $(this).closest("tr").find('.sgst').prop('readonly', false);
    //     $(this).closest("tr").find('.igst').val('').prop('readonly', true);
    //     }
    //     else{
    //     $(this).closest("tr").find('.cgst').prop('readonly', false);
    //     $(this).closest("tr").find('.sgst').prop('readonly', false);
    //     $(this).closest("tr").find('.igst').prop('readonly', false);
    //     }
    // });
    // $(document).on("keyup",'.igst',function() {
    //     var value = $(this).val();
    //     if(value!=''){
    //     $(this).closest("tr").find('.igst').prop('readonly', false);
    //     $(this).closest("tr").find('.cgst').val('').prop('readonly', true);
    //     $(this).closest("tr").find('.sgst').val('').prop('readonly', true);
    //     }
    //     else{
    //     $(this).closest("tr").find('.cgst').prop('readonly', false);
    //     $(this).closest("tr").find('.sgst').prop('readonly', false);
    //     $(this).closest("tr").find('.igst').prop('readonly', false);
    //     }
    // });
    $(document).ready(function(){      
        $(document).on('click','.submit',function(){
            // $.each('.quantity-err', function(key,val))
            qty_err = 0;
            $(".quantity-err").each(function(){
                qty_err += +$(this).val();
            });
            if(qty_err != 0){
                return false;
            }
        })
        $(document).on('change','.Suppliers',function(){
            supplier_id = $(this).val();
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>purchase_order/get_supplier_address",
            data:{supplier_id:supplier_id},
            success: function(data){
                data = JSON.parse(data);
                $('.add_address').val(data['vAddress']);
            }
        });
        })
        // category();
        $('.headoffice').val('');
        $(".category_clone:first").clone().appendTo(".category_id");
        $('.category_id').find('.category_clone').prop('required',true);

      var i=0;  
   
      $(document).on('click', '#add', function(e){  
            e.preventDefault();
           i++;  
           $('#table').append('<tr id="row'+i+'" class="dynamic-added"><td scope="col" data-th="Category"><div class="w-100 category_field'+i+'"><div class="invalid-feedback">Please select a valid state.</div></div></td><td scope="col" data-th="Product"><div class="relative"><input class="form-control product product_details_'+i+'" id="validationCustomUsername" name="product[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required><input type="hidden" class="product_id product_id_'+i+'" name="product_id[]"><input type="hidden" name="product_color_id[]" class="product_color_id product_color_id_'+i+'"><input type="hidden" name="taxable_cost[]" tabindex="-1" class="taxable_cost taxable_cost_cls_'+i+' form-control" /><div class="search-list"><ul class="suggesstion-box" data-id="'+i+'"></ul></div></div></td><td scope="col" data-th="Unit"><div class="relative"><select class="form-select product_unit unit_'+i+'" name="unit[]" id="validationCustom04" required><option selected="" disabled="" value="">Choose...</option></select></div></td><td scope="col" data-th="Price"><div class="relative"><input class="form-control price price_'+i+'" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td scope="col" data-th="Quantity"><div class="relative"><input class="form-control quantity quantity_cls_'+i+'" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required><input type="hidden" class="quantity-err"><span class="ajax_response_result"></span></div></td><td scope="col" data-th="CGST"><div class="relative"><input class="form-control cgst cgst_cls_'+i+'" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required ></div></td><td scope="col" data-th="SGST"><div class="relative"><input class="form-control sgst sgst_cls_'+i+'" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required ></div></td><td scope="col" data-th="IGST"><div class="relative"><input class="form-control igst igst_cls_'+i+'" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col" data-th="Net Value"><div class="relative"><input class="form-control net_value net_value_cls_'+i+'" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required ></div></td><td data-th="Remove"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
            $(".category_clone:first").clone().appendTo('.category_field'+i);
            $('.category_field'+i).find('.category_clone').prop('required',true);
        });
  
      $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();
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

    //get product by category
    $(document).on('change','.category_clone', function(){
        var category = $(this).val();   
        //$('.product').val('');
        $(this).closest('tr').find('.product').val('');
        $(this).closest('tr').find('.product_unit').empty();
        $(this).closest('tr').find('.quantity').val('');
        $(".suggesstion-box").hide();
    });


    $(document).on("keyup",'.product',function() {
        _this = $(this);
        var product = $(this).val();    
        var category = $(this).closest('tr').find('.category_clone').val();  
        var dataID = $(_this).closest('td').find('.suggesstion-box').attr('data-id');
        // var headoffice = $('.headoffice').val();
        // var branch = $('.branch').val();
        var type = (category)  ?  'category' : 'all';
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>purchase_order/get_product",
            data:{category:category,product:product,type:type},
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
                    html += '<li value= '+val['iProductId']+' onClick="selectProduct(`'+type+'`,`'+dataID+'`,`'+val['iProductId']+'`,`'+product_color_value+'`,`'+color_id+'`)">'+product_color_value.toUpperCase()+'</li>';
                });
                $(_this).closest('td').find('.suggesstion-box').show();
                $(_this).closest('td').find('.suggesstion-box').html(html);
                $(".product").css("background","#FFF");
            }
        });
    });

    function selectProduct(type,dataID,iProductId,val,color_id) {
        // console.log($(this).closest('.suggesstion-box').attr('data-id'));
        // $category_dropdown = 
        $('.product_details_'+dataID).val(val.toUpperCase());
        $(".suggesstion-box").hide();
        $('.product_id_'+dataID).val(iProductId);
        $('.product_color_id_'+dataID).val(color_id);
        if(type == "all"){
        var category_clone = $('.product_details_'+dataID).closest('tr').find('.category_clone');
        $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>order/get_category_by_product",
                data:{iProductId:iProductId},
                success: function(data){
                    data = JSON.parse(data);
                   $(category_clone).val(data.iCategoryId);
                }
            });
        }
        // console.log(val__);

        //get product unit
        var customer_id = $('.customer_id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>purchase_order/get_product_unit",
            data:{iProductId:iProductId},
            success: function(data){
                $(".unit_"+dataID).empty();
                data = JSON.parse(data);
                var html = '';
                html += '<option selected value="" disabled>Choose Unit</option>';
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
            // unit_id = $(this).val();
            // $('.price_'+dataID).val('');
            // $.ajax({
            // type: "POST",
            // url: "<?php echo base_url() ?>order/get_unit_price",
            // data:{iProductId:iProductId,unit_id:unit_id},
            //     success: function(data){
            //         data = JSON.parse(data);
            //         $('.price_'+dataID).val(data['fProductPrice']);
            //         $('.net_value_cls_'+dataID).val(data['fProductPrice']);
            //         calculate();
            //     }
            // });
        })

        $(document).on("keyup",'.quantity,.price,.cgst,.sgst,.igst,.net_value',function() {
            
            // var quantity_class = $(this).hasClass('quantity');
            // var branch_id =  $('.branch').val();
            // if(quantity != "" && quantity_class){
            //     var _this = $(this);
            //     var quantity = $(this).val();
            //     var product_id = $(this).closest('tr').find('.product_id').val();
            //     var product_unit = $(this).closest('tr').find('.product_unit').val();
            //     $.ajax({
            //         type: "POST",
            //         url: "<?php echo base_url() ?>order/check_product_quantity",
            //         data:{product_id:product_id,branch_id,quantity:quantity,product_unit:product_unit},
            //         success: function(data){
            //                 data = JSON.parse(data);
            //                 // console.log(data.status);
            //             if(data.status == 'failure'){
            //                 console.log(data.message);
            //                 _this.closest("tr").find(".quantity-err").val(1);
            //                 _this.closest("tr").find(".ajax_response_result").html('').html(data.message).css('color','red');
            //             } else {
            //                 console.log(data.status);

            //                 _this.closest("tr").find(".quantity-err").val(0);
            //                 _this.closest('tr').find(".ajax_response_result").html('');
            //             }
            //         }
            //     });
            // }
            calculate();
        });

    }
    function calculate(dataID,quantity=''){
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
        // var tax_amount = total_cgst_price+total_sgst_price;
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

</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>