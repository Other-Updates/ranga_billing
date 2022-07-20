<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Offer Banner</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Offer Banner</li>
                </ol>
            </div>
        </div>
    </div>
    <form method="post" action="<?php echo base_url('master/home_page_offers/add_offer_list') ?>" enctype="multipart/form-data">
        <input type="hidden" name="offer_details_deleted_id" class="offer_details_deleted_id">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <!-- <div class="col-md-4">                            
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" value="Today Offer">
                        </div>
                    </div>
                    <div class="col-md-2">                            
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">                            
                        <div class="form-group">
                            <label class="form-label">Order</label>
                            <input type="text" class="form-control" value="6">
                        </div>
                    </div> -->
                </div>
                <div class="ads-offer-box">
                    <table class="table offer-table" id="offer_table">
                        <thead>
                            <tr>
                                <th width="20%">Offer Name</th>
                                <th width="20%">Offer Badge</th>
                                <th width="13%">Duration</th>
                                <th width="10%">Banner</th>
                                <th width="10%">Type</th>
                                <th width="12%" align="center">Offer&nbsp;For</th>
                                <th width="15%">Branch</th>
                                <th width="15%">Offer</th>
                                <th width="5%" class="text-center"><button type="button" class="btn btn-success btn-sm" id="add"><i class="icofont icofont-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($offer_details)){
                                $increment = 0;
                                foreach($offer_details as $offer){ ?>
                                <tr>
                                    <input type="hidden" name="offer_id[]" value="<?php echo $offer['iOfferId'] ?>">
                                    <td><input type="text" class="form-control" name="offer_name[]" value="<?php echo $offer['vOfferName'] ?>"> </td>
                                    <td><input type="text" class="form-control" name="offer_badge[]" value="<?php echo $offer['vOfferBadge'] ?>"> </td>
                                    <td class="p-0">
                                        <table class="offer-inner-table">
                                            <tbody>
                                                <tr>
                                                    <td>From</td>
                                                    <td><input class="datepicker-here form-control" value="<?php echo $offer['dFromDate'] ?>" type="text" name="from_date[]"></td>
                                                </tr>
                                                <tr>
                                                    <td>To</td>
                                                    <td><input type="text" class="form-control datepicker-here" name="to_date[]" value="<?php echo $offer['dToDate'] ?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="p-0">
                                        <table class="offer-table">
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="old_offer_img[]" value="<?php echo $offer['vImage']; ?>">
                                                    <div class="td-upload-image"><img class="img-fluid" src="<?php echo base_url('uploads/'.$offer['vImage']) ?> " alt=""></div>
                                                    <!-- <div class="td-upload-image"><img class="img-fluid" src="<?php echo $theme_path ?>/assets/images/premium-range-of-masala.png" alt=""></div> -->
                                                </td>
                                                <td>
                                                    <div class="td-upload-img">
                                                        <i class="fa fa-upload"></i>
                                                        <input name="offer_image[]" class="form-control" type="file">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>                                    
                                    </td>
                                    <td>
                                        <select name="offer_type[]" class="form-control offer_type">
                                            <option selected value="">Select</option>
                                            <option <?php if($offer['eType']=="product"){ echo "selected"; } ?> value="product">Product</option>
                                            <option <?php if($offer['eType']=="category"){ echo "selected"; } ?> value="category">Category</option>
                                            <option <?php if($offer['eType']=="subcategory"){ echo "selected"; } ?> value="subcategory">Subcategory</option>
                                        </select>
                                    </td>
                                    <td>
                                        <?php if($offer['eType'] == 'product'){ ?>
                                            <select name="offer_type_id[]" class="form-control selected_offer">
                                                <?php foreach($product as $products){ ?>
                                                    <option <?php if($offer['iOfferTypeId'] == $products['iProductId']){ echo "selected"; } ?> value="<?php echo $products['iProductId'].','.$products['vProductName'] ?>"><?php echo $products['vProductName'] ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php }if($offer['eType'] == 'category'){ ?>
                                            <select name="offer_type_id[]" class="form-control selected_offer">
                                                <?php foreach($category as $categories){ ?>
                                                    <option <?php if($offer['iOfferTypeId'] == $categories['iCategoryId']){ echo "selected"; } ?> value="<?php echo $categories['iCategoryId'] ?>"><?php echo $categories['vCategoryName'] ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php }if($offer['eType'] == 'subcategory'){ ?>
                                            <select name="offer_type_id[]" class="form-control selected_offer">
                                                <?php foreach($subcategory as $subcategories){ ?>
                                                    <option <?php if($offer['iOfferTypeId'] == $subcategories['iSubcategoryId']){ echo "selected"; } ?> value="<?php echo $subcategories['iSubcategoryId'] ?>"><?php echo $subcategories['vSubcategoryName'] ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </td>
                                    <?php $branch_arr = explode(',',$offer['iBranchId']);
                                            $branch_result = array();
                                            foreach($branch_arr as $key=>$branch_data){
                                                $branch_result[] = $branch_data['iBranchId'];
                                            }
                                    ?>
                                    <td>
                                        <select name="branch_id_<?php echo $increment.'[]'; ?>" class="form-select branch-multiple form-control branch_id" multiple>
                                            <option value="">Choose</option>
                                            <?php foreach ($branch as $key => $br) { ?>
                                                <option <?php if(in_array($br['iBranchId'],$branch_result)){ echo "selected"; } ?> value="<?php echo $br['iBranchId'] ?>"><?php echo $br['vBranchName'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="p-0">
                                        <input type="hidden" class="offer_value_name_id" name="offer_value_name_id[]" value="<?php echo $increment ?>">
                                        <table class="offer-inner-table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <label class="d-block" for="edo-ani">
                                                            <input class="radio_animated radio radio_active" id="edo-ani" <?php if($offer['vOfferType'] == "Flat"){ echo "checked"; } ?> type="radio" value='Flat' name="offer_value_<?php echo $increment; ?>" checked="" data-original-title="" title=""><br>Flat
                                                        </label>
                                                    </td>
                                                    <td><input type="text" class="offer_price form-control" value="<?php if($offer['vOfferType'] == "Flat"){ echo $offer['vOfferValue']; } ?>"  name="offer_price[]" placeholder="50"></td>
                                                    <td>
                                                        <label class="d-block" for="edo-ani1">
                                                            <input class="radio_animated radio radio_inactive" id="edo-ani1" <?php if($offer['vOfferType'] == "Percent"){ echo "checked"; } ?> type="radio" value='Percent' name="offer_value_<?php echo $increment; ?>" data-original-title="" title=""><br>%
                                                        </label>
                                                    </td>
                                                    <td><input type="text" class="offer_percent form-control" value="<?php if($offer['vOfferType'] == "Percent"){ echo $offer['vOfferValue']; } ?>" name="offer_percent[]" placeholder="%"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td align="center"><button data-id="<?php echo $offer['iOfferId'] ?>" type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td>
                                </tr>

                                <?php $increment++; }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="text-end">
                    <div class="col-sm-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>   
            </div>
        </div>  
    </form>
    <div class="static_offers dnone" style="display:none !important">
        <table>
            <tr class="offer_row">
                <td><input type="text" class="form-control" name="offer_name[]" value=""> </td>
                <td><input type="text" class="form-control" name="offer_badge[]"> </td>
                <td class="p-0">
                    <table class="offer-inner-table">
                        <tbody>
                            <tr>
                                <td>From</td>
                                <td><input class="datepicker-here form-control" value="" type="text" name="from_date[]"></td>
                            </tr>
                            <tr>
                                <td>To</td>
                                <td><input type="text" class="form-control datepicker-here" name="to_date[]" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="p-0">
                    <table class="offer-table">
                        <tr>
                            <td>
                                <div class="td-upload-image"><img class="img-fluid" src="<?php echo $theme_path ?>/assets/images/premium-range-of-masala.png" alt=""></div>
                            </td>
                            <td>
                                <div class="td-upload-img">
                                    <i class="fa fa-upload"></i>
                                    <input name="offer_image[]" class="form-control" type="file">
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <select name="offer_type[]" class="form-control offer_type">
                        <option selected value="">Select</option>
                        <option value="product">Product</option>
                        <option value="category">Category</option>
                        <option value="subcategory">Subcategory</option>
                    </select>
                </td>
                <td>
                    <select name="offer_type_id[]" class="form-control selected_offer disabled">
                        <option value="">Choose</option>
                    </select>
                </td>
                <td>
                    <select name="branch_id[]" class="form-select branch-multiple form-control branch_id" multiple>
                        <option value="">Choose</option>
                        <?php foreach ($branch as $key => $br) { ?>
                            <option value="<?php echo $br['iBranchId'] ?>"><?php echo $br['vBranchName'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="p-0">
                    <input type="hidden" class="offer_value_name_id" name="offer_value_name_id[]" value="0">
                    <table class="offer-inner-table">
                        <tbody>
                            <tr>
                                <td>
                                    <label class="d-block" for="edo-ani">
                                        <input class="radio_animated radio radio_active" id="edo-ani" type="radio" value='Flat' name="offer_value_0" checked="" data-original-title="" title=""><br>Flat
                                    </label>
                                </td>
                                <td><input type="text" class="offer_price form-control" name="offer_price[]" placeholder="50"></td>
                                <td>
                                    <label class="d-block" for="edo-ani1">
                                        <input class="radio_animated radio radio_inactive" id="edo-ani1" type="radio" value='Percent' name="offer_value_0" data-original-title="" title=""><br>%
                                    </label>
                                </td>
                                <td><input type="text" class="offer_percent form-control" name="offer_percent[]" placeholder="%"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="10%" align="center"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td>
            </tr>
        </table>
    </div>
</div>
<script>
    $(document).ready(function(){
        var offer_val = $('#offer_table tr');
            // $.each(offer_val,function(index, tr){
            //     if(tr.find('.radio_active').is(':checked')){
            //         tr.find(".offer_price").show();
            //         tr.find(".offer_percent").hide();
            //     }
            // })
        
        // $.each(offer_val, function (key,value){
        // if(value.find('.radio_active').is(':checked')){
        //     value.find(".offer_price").show();
        //     value.find(".offer_percent").hide();
        // }
        // })

        // if($(document).(".radio_active").is(':checked')){
        //     $(this).(".offer_price").show();
        //     $(this).(".offer_percent").hide();
        // }else if($(document).(".radio_inactive").is(':checked')){
        //     $(this).(".offer_price").hide();
        //     $(this).(".offer_percent").show();
        // }

        // $(".offer_price").show();
        // $(".offer_percent").hide();
        // $('#add').trigger('click');
    });
        $(document).on('change','.radio',function(){
            _this = $(this);
        if(_this.closest('tr').find(".radio_active").is(':checked')){
            _this.closest('tr').find(".offer_price").show();
            _this.closest('tr').find(".offer_percent").hide();
        }else if(_this.closest('tr').find(".radio_inactive").is(':checked')){
            _this.closest('tr').find(".offer_price").hide();
            _this.closest('tr').find(".offer_percent").show();
        }
        });
        // i=0;
    $(document).on('click','#add',function(e){
        // i++;
        _this = $(this);
        e.preventDefault();
        var clone_data = $('.static_offers').find('.offer_row').clone();
        var i = $('#offer_table tr').length;
        clone_data.find('.radio').attr('name','offer_value_'+i+'[]');
        clone_data.find('.branch_id').attr('name','branch_id_'+i+'[]');
        clone_data.find('.offer_value_name_id').val(i);
            $('#offer_table').append(clone_data);
    });
    $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();
    });  
    $(document).on('change','.offer_type',function(){
        var type = $(this).val();
        _this = $(this);
        $(this).closest('tr').find('.selected_offer').empty();
        $(this).closest('tr').find( ".selected_offer" ).removeClass( "disabled" )
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>master/home_page_offers/get_selected_type_records",
            data:{type:type},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['id']+","+val['name']+'>'+val['name']+'</option>';
                });
                _this.closest('tr').find('.selected_offer').html(html);
            }
        });
    });
    $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();

        var offer_details_del = $(this).attr('data-id');
        var deleted_id = $('.offer_details_deleted_id').val();
        if(deleted_id != ''){
            var deleted_arr = deleted_id.split(",");
            deleted_arr.push(offer_details_del);
            var offer_details_del = deleted_arr.join(",");
            $('.offer_details_deleted_id').val(offer_details_del);

        }else{
            $('.offer_details_deleted_id').val(offer_details_del);
        }

        });  
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>