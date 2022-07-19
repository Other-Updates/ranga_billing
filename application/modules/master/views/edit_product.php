<?php
    $theme_path = $this->config->item('theme_locations');
    // print_r($product);exit; 
?>
<link rel="stylesheet" href="<?php echo $theme_path ?>/assets/js/imagesloader/jquery.imagesloader.css">
<script src="<?php echo $theme_path ?>/assets/js/imagesloader/jquery.imagesloader-1.0.1.js"></script>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Edit Product</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Products</li>
                <li class="breadcrumb-item active">Edit Product</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    
        <form class="needs-validation" novalidate="" id="form_id" method="post" enctype="multipart/form-data" action="<?php echo base_url('master/product/update_product'); ?>">       
            <div class="card"> 
                <input type="hidden" value="<?php echo $product['iProductId'] ?>" name="product_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label tamil-lang" for="validationCustom01">பொருளின் பெயர்</label>
                            <input class="form-control tamil-lang" id="validationCustom01" type="text" name="product_name_tamil" value="<?php echo $product['vProductName_Tamil'] ?>">
                            <div class="valid-feedback">Looks good!</div>
                        </div>         
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom01">Product Name</label>
                            <input class="form-control" id="validationCustom01" type="text" name="product_name" value="<?php echo $product['vProductName'] ?>" required="">
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Category</label>
                            <select class="form-select category_id" name="product_category" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($category as $list){ ?>
                            <option value="<?php echo $list['iCategoryId'] ?>"<?php if($product['iCategoryId'] == $list['iCategoryId']){ echo "selected"; } ?>><?php echo $list['vCategoryName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Subcategory</label>
                            <select class="form-select subcategory_id" name="subcategory" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($subcategory as $subcategories){ ?>
                            <option value="<?php echo $subcategories['iSubcategoryId'] ?>"<?php if($product['iSubcatagoryId'] == $subcategories['iSubcategoryId']){ echo "selected"; } ?>><?php echo $subcategories['vSubcategoryName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Brand</label>
                            <select class="form-select" name="brand_name" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($brand as $brands){ ?>
                            <option value="<?php echo $brands['iBrandId'] ?>"<?php if($product['iBrandId'] == $brands['iBrandId']){ echo "selected"; } ?>><?php echo $brands['vBrandName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom04">Model</label>
                            <select class="form-select" name="model_name" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach ($model as $models){ ?>
                            <option value="<?php echo $models['iModelId'] ?>"<?php if($product['iModelId'] == $models['iModelId']){ echo "selected"; } ?>><?php echo $models['vModelName'] ?></option>
                            <?php } ?>
                            </select>
                            <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">IGST</label>
                            <input class="form-control add_igst" id="validationCustom03" type="text" name="igst" value="<?php echo $product['IGST'] ?>" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">CGST</label>
                            <input class="form-control add_cgst" id="validationCustom03" type="text" name="cgst" placeholder="" value="<?php echo $product['CGST'] ?>" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">SGST</label>
                            <input class="form-control add_sgst" id="validationCustom03" type="text" name="sgst" placeholder="" value="<?php echo $product['SGST'] ?>" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom03">HSN No</label>
                            <input class="form-control" id="validationCustom03" type="text" value="<?php echo $product['vHSNNO']; ?>" name="hsn_no" placeholder="" required="">
                            <div class="invalid-feedback">Field is required.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="validationCustom03">Description</label>
                            <textarea class="form-control" id="validationCustom03" type="text" name="description" placeholder="" required=""><?php echo $product['vDescription'] ?></textarea>
                            <div class="invalid-feedback">Field is required.</div>
                        </div>  
                        <div class="col-md-12">
                            <label class="form-label tamil-lang" for="validationCustom03">விளக்கம்</label>
                            <textarea class="form-control tamil-lang" id="validationCustom03" type="text" name="description_tamil" placeholder=""><?php echo $product['vDescription_Tamil'] ?></textarea>
                            <div class="invalid-feedback">Field is required.</div>
                        </div>                     
                    </div>
                    <div class="col">
                            <label class="form-label" for="validationCustom03">Status</label>
                            <label class="d-block" for="edo-ani">
                            <input class="radio_animated radio_active" id="edo-ani" type="radio" data-value=<?php echo $product['status']; ?> value="Active" <?php if($product['eStatus'] == 'Active'){ echo "checked"; } ?> name="status" data-original-title="" title="">Active
                            </label>
                            <label class="d-block" for="edo-ani1">
                            <input class="radio_animated radio_inactive" id="edo-ani1" type="radio" value="Inactive" <?php if($product['status'] == 'Inactive'){ echo "checked"; } ?> name="status" data-original-title="" title="">Inactive
                            </label>
                        </div>
                    <!--Image Upload-->
                    <div class="row mt-3 mb-2">
                        <div class="col-12 pr-0 text-left">
                        <label for="Images" class="col-form-label text-nowrap">Product Image</label>
                        <span style=color:red>*</span>
                        </div>
                    </div>
                    <!--Multi Image Upload -->
                    <div class="row image-upload"
                        data-type="imagesloader"
                        data-errorformat="Accepted file formats"
                        data-errorsize="Maximum size accepted"
                        data-errorduplicate="File already loaded"
                        data-errormaxfiles="Maximum number of images you can upload"
                        data-errorminfiles="Minimum number of images to upload"
                        data-modifyimagetext="Modify Image">
                        <div class="col-12 order-1 mt-2">
                        <div data-type="progress" class="progress" style="height: 25px; display:none;">
                            <div data-type="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%;">Load in progress...</div>
                        </div>
                        </div>
                        <!-- Model -->
                        <div data-type="image-model" class="col-4 pl-2 pr-2 pt-2" style="max-width:200px; display:none;">
                            <div class="ratio-box text-center" data-type="image-ratio-box">
                                <img data-type="noimage" class="btn btn-light ratio-img img-fluid p-2 image border rounded" src="<?php echo $theme_path ?>/assets/images/photo-camera-gray.svg" style="cursor:pointer;">
                                <div data-type="loading" class="img-loading" style="color:#218838; display:none;">
                                <span class="fa fa-2x fa-spin fa-spinner"></span>
                                </div>
                                <img data-type="preview" class="btn btn-light ratio-img img-fluid p-2 image border rounded" src="" style="display: none; cursor: default;">
                                <span class="badge badge-pill badge-success p-2 w-50 main-tag" style="display:none;">Main</span>
                            </div>
                            <!-- Buttons -->
                            <div data-type="image-buttons" class="row justify-content-center mt-2">
                                <button data-type="add" class="btn btn-outline-t-success" type="button"><span class="fa fa-camera mr-2"></span>Add</button>
                                <button data-type="btn-modify" type="button" class="btn btn-outline-success m-0" data-toggle="popover" data-placement="right" style="display:none;">
                                <span class="fa fa-pencil td-icon mr-2"></span>Modify
                                </button>
                            </div>
                        </div>
                        <!-- Popover operations -->
                        <div data-type="popover-model" style="display:none">
                            <div data-type="popover" class="ml-3 mr-3" style="min-width:150px;">
                                <div class="row">
                                <div class="col p-0">
                                    <button data-operation="main" class="btn btn-block btn-success btn-sm rounded-pill" type="button"><span class="fa fa-angle-double-up mr-2"></span>Main</button>
                                </div>
                                </div>
                                <div class="row mt-2">
                                <div class="col-6 p-0 pr-1">
                                    <button data-operation="left" class="btn btn-block btn-outline-success btn-sm rounded-pill" type="button"><span class="fa fa-chevron-left mr-2"></span>Left</button>
                                </div>
                                <div class="col-6 p-0 pl-1">
                                    <button data-operation="right" class="btn btn-block btn-outline-success btn-sm rounded-pill" type="button">Right<span class="fa fa-chevron-right ml-2"></span></button>
                                </div>
                                </div>
                                <div class="row mt-2">
                                <div class="col-6 p-0 pr-1">
                                    <button data-operation="rotateanticlockwise" class="btn btn-block btn-outline-success btn-sm rounded-pill" type="button"><span class="fa fa-rotate-left mr-2"></span>Rotate</button>
                                </div>
                                <div class="col-6 p-0 pl-1">
                                    <button data-operation="rotateclockwise" class="btn btn-block btn-outline-success btn-sm rounded-pill" type="button">Rotate<span class="fa fa-rotate-right ml-2"></span></button>
                                </div>
                                </div>
                                <div class="row mt-2">
                                <button data-operation="remove" class="btn btn-outline-danger btn-sm btn-block" type="button"><span class="fa fa-times mr-2"></span>Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="input-group">
                        <!--Hidden file input for images-->
                        <input type="hidden" id="product_files" name = "product_hidden_files" value="<?php echo $product['vImages']; ?>">
                        <input id="files" type="file" name="product_image[]" data-button="" multiple="" accept="image/jpeg, image/png, image/gif," style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body animate-chk">
                    <div class="branch-box">
                        <div class="section-title">Colour</div>

                        <?php 
                        $product_colors = explode(",",$product['iProductColorId']);
                        $selectedcolor = array();
                        foreach($product_colors as $clrs){
                            $selectedcolor[] =  $clrs['iProductColorId'];
                        } ?>
                        
                        <?php foreach($color as $cr){ ?>
                            <div class="branch-list">
                                <label for="<?php echo $cr['iProductColorId']."_color"; ?>">
                                    <input type="checkbox" class="checkbox_animated" name="color_check[]" <?php if( in_array( $cr['iProductColorId'],$selectedcolor)){ echo "checked"; } ?> value="<?php echo $cr['iProductColorId'] ?>" id="<?php echo $cr['iProductColorId']."_color"; ?>"><?php echo $cr['vColorName'] ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body animate-chk">
                    <div class="branch-box">
                        <div class="section-title">Branch</div>
                        <?php 
                        $selectedbranch = array();
                        foreach($product_branch as $branch_data){
                            $selectedbranch[] =  $branch_data['iBranchId'];
                        } ?>
                        <?php foreach($branch as $br){ ?>
                            <div class="branch-list">
                                <label for="<?php echo $br['iBranchId']."_branch"; ?>">
                                    <input type="checkbox" class="checkbox_animated" name="branch_check[]" <?php if( in_array( $br['iBranchId'],$selectedbranch)){ echo "checked"; } ?> value="<?php echo $br['iBranchId'] ?>" id="<?php echo $br['iBranchId']."_branch"; ?>"><?php echo $br['vBranchName'] ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="product_unit_deletedid" class="product_unit_deletedid">
            <input type="hidden" name="minqty_deletedid" class="minqty_deletedid">
            <div class="" style="display:none">
                <select class="form-select unit_clone product_unit unit_0" name="unit[]" id="validationCustom04">
                    <option selected="" disabled="" value="">Choose...</option>
                    <?php foreach($unit as $un) {?>
                    <option value="<?php echo $un['iProductUnitId']; ?>"><?php echo $un['vProductUnitName']; ?></option>
                    <?php } ?>
                </select>
                <div class="invalid-feedback">Field is required.</div>
            </div>
            <div class="" style="display:none">
                <select class="form-select unit_clone_minqty product_unit" name="unit_minqty[]" id="validationCustom04">
                    <option selected="" disabled="" value="">Choose...</option>
                    <?php foreach($unit as $un) {?>
                    <option value="<?php echo $un['iProductUnitId']; ?>"><?php echo $un['vProductUnitName']; ?></option>
                    <?php } ?>
                </select>
                <div class="invalid-feedback">Field is required.</div>
            </div>
            <div class="" style="display:none">
                <select class="form-select grade_clone product_grade" name="grade[]" id="validationCustom04">
                    <option selected="" disabled="" value="">Choose...</option>
                    <?php foreach($grade as $gr) {?>
                    <option value="<?php echo $gr['iGradeId']; ?>"><?php echo $gr['vGradeName']; ?></option>
                    <?php } ?>
                </select>
                <div class="invalid-feedback">Field is required.</div>
            </div>
            <div class="" style="display:none">
                <select class="form-select branch_clone product_branch" name="branch[]" id="validationCustom04">
                    <option selected="" disabled="" value="">Choose...</option>
                    <?php foreach($branch as $br) {?>
                    <option value="<?php echo $br['iBranchId']; ?>"><?php echo $br['vBranchName']; ?></option>
                    <?php } ?>
                </select>
                <div class="invalid-feedback">Field is required.</div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="section-title">Price</div>
                            <input type="hidden" text="count_price_list" class="count_price_list" value="<?php echo count($price); ?>">
                            <div class="">
                                <table class="table delivery-order" id="table">
                                    <thead>
                                        <tr>
                                            <!-- <th scope="col">#</th> -->
                                            <th width="30%" scope="col">Unit</th>
                                            <th width="30%" class="" scope="col">Price</th>
                                            <th width="30%" class="" scope="col">Pack</th>
                                            <th width="20%" class="d-none" scope="col">Grade</th>
                                            <th width="10%" scope="col"><button type="button" class="btn btn-t-success btn-sm" id="add"><i class="icofont icofont-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody class="price_details">
                                    <?php foreach($price as $pr){ ?>
                                        <tr id="row" class="dynamic-added">
                                            <td scope="col">
                                                <input type="hidden" name="price_list_id[]" value="<?php echo $pr['iProductPriceListId']; ?>">
                                                <div class="">
                                                <select class="form-select unit_clone product_unit unit_0" name="unit[]" id="validationCustom04">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <?php foreach($unit as $un) {?>
                                                    <option value="<?php echo $un['iProductUnitId']; ?>" <?php if($pr['iProductUnitId'] == $un['iProductUnitId']){ echo "selected"; } ?>><?php echo $un['vProductUnitName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control price price_0" id="validationCustomUsername" name="price[]" value ="<?php echo $pr['fProductPrice']; ?>" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control pack" id="validationCustomUsername" name="pack[]" value ="<?php echo $pr['vPacketCount']; ?>" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col" class="d-none">
                                                <div class="">
                                                <select class="form-select grade_clone product_grade" name="grade[]" id="validationCustom04">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <?php foreach($grade as $gr) {?>
                                                    <option value="<?php echo $gr['iGradeId']; ?>" <?php if($pr['iGradeId'] == $gr['iGradeId']){ echo 'selected'; } ?>><?php echo $gr['vGradeName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td><button type="button" data-id="<?php echo $pr['iProductPriceListId']; ?>" name="remove" id="" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                    <div class="section-title">Minimum Quantity</div>
                        <input type="hidden" text="count_minqty" class="count_minqty" value="<?php echo count($min_qty); ?>">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="">
                                <table class="table delivery-order table_minqty" id="table_minqty">
                                    <thead>
                                        <tr>
                                            <th width="20%" scope="col">Branch</th>
                                            <th width="13%" scope="col">Unit</th>
                                            <th width="8%" class="text-right" scope="col">Minimum Quantity</th>
                                            <th width="4%" scope="col"><button type="button" class="btn btn-t-success btn-sm" id="add_min_qty"><i class="icofont icofont-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody class="table_minqty_details">
                                    <?php foreach($min_qty as $qty){ ?>
                                        <tr id="row" class="dynamic-added">
                                            <td scope="col">
                                                <input type="hidden" name="min_qty_id[]" value="<?php echo $qty['iProductMinQtyId'] ?>">
                                                <div class="">
                                                <select class="form-select branch_clone product_branch" name="branch[]" id="validationCustom04">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <?php foreach($branch as $br) {?>
                                                    <option value="<?php echo $br['iBranchId']; ?>" <?php if($qty['iBranchId']==$br['iBranchId']){ echo 'selected'; } ?>><?php echo $br['vBranchName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <select class="form-select unit_clone_minqty product_unit" name="unit_minqty[]" id="validationCustom04">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <?php foreach($unit as $un) {?>
                                                    <option value="<?php echo $un['iProductUnitId']; ?>" <?php if($qty['iProductUnitId'] == $un['iProductUnitId']){ echo 'selected';} ?>><?php echo $un['vProductUnitName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="">
                                                <input class="form-control minimum_quantity" id="validationCustomUsername" value="<?php echo $qty['iMinQty'] ?>" name="minimum_quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  >
                                                <div class="invalid-feedback">Field is required.</div>
                                                </div>
                                            </td>
                                            <td><button type="button" data-id="<?php echo $qty['iProductMinQtyId']; ?>"name="remove" id="'+i+'" class="btn btn-danger btn_remove_min_qty btn-sm"><i class="icofont icofont-minus"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-end">
                        <div class="col-sm-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                            <!-- <a href="<?php echo base_url('master/product'); ?>"><input class="btn btn-danger pull-left" type="reset" value="Cancel" data-bs-original-title="" title=""></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>
<!-- Image Upload Custom javascript -->
<script type="text/javascript">
    $(document).ready(function () {

        $(".unit_clone:first").clone().appendTo(".clone_unit");
        $(".grade_clone:first").clone().appendTo(".clone_grade");
    //   var i=0;  
   
        $('#add').click(function(e){  
        //     e.preventDefault();
        //    i++;  
           $('#table').find('.price_details').append('<tr id="row" class="dynamic-added"><td scope="col"><div class="product_unit"><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control price price_0" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control pack" id="validationCustomUsername" name="pack[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class="clone_grade"><div class="invalid-feedback">Field is required.</div></div></td><td><button type="button" name="remove" id="" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
                var unit_td = $(this).closest('table').find("tbody tr:last .product_unit");
                var grade_td = $(this).closest('table').find("tbody tr:last .clone_grade");
        
           $(".unit_clone:first").clone().appendTo(unit_td);
           $(".grade_clone:first").clone().appendTo(grade_td);
        });

        //add minimum quantity
        $(".branch_clone:first").clone().appendTo(".clone_branch");
        $(".unit_clone_minqty:first").clone().appendTo(".clone_unit_minqty");

        var count_minqty = $('.count_minqty').val();
        $(document).on("click","#add_min_qty",function() {
            $('.table_minqty').find('.table_minqty_details').append('<tr id="row" class="dynamic-added"><td scope="col"><div class="clone_branch"><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class="clone_unit_minqty"><div class="invalid-feedback">Field is required.</div></div></td><td scope="col"><div class=""><input class="form-control minimum_quantity" id="validationCustomUsername" name="minimum_quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td><button type="button" name="remove" id="" class="btn btn-danger btn_remove_min_qty btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
            var branch_td_first = $(this).closest('table').find("tbody tr:last .clone_branch");
            var unit_td_minqty_first = $(this).closest('table').find("tbody tr:last .clone_unit_minqty");
            $(".branch_clone:first").clone().appendTo(branch_td_first);
            $(".unit_clone_minqty:first").clone().appendTo(unit_td_minqty_first);
        });

  
        $(document).on('click', '.btn_remove_min_qty', function(){  
            $(this).closest("tr").remove();
            var minqty_id = $(this).attr('data-id');
            var del_minqty_id = $('.minqty_deletedid').val();
            if(del_minqty_id != ''){
                var deleted_minqty_arr = del_minqty_id.split(",");
                deleted_minqty_arr.push(minqty_id);
                var minqty_id = deleted_minqty_arr.join(",");
                $('.minqty_deletedid').val(minqty_id);
            }else{
                $('.minqty_deletedid').val(minqty_id);
            }
        }); 
  
      $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();
        var unit_id = $(this).attr('data-id');
        var deleted_id = $('.product_unit_deletedid').val();
        if(deleted_id != ''){
            var deleted_arr = deleted_id.split(",");
            deleted_arr.push(unit_id);
            var unit_id = deleted_arr.join(",");
            $('.product_unit_deletedid').val(unit_id);

        }else{
            $('.product_unit_deletedid').val(unit_id);
        }
      });  

    //Image loader var to use when you need a function from object
    var product_iamges = $('#product_files').val();
    var baseurl = "<?php echo base_url(); ?>";
    var images_array = null;
    if(product_iamges != ''){
        product_iamges_array = product_iamges.split(',');
        var images_array = [];
        $.each(product_iamges_array ,function(key,value){
            var image_url = baseurl+"uploads/"+value;
            var image_obj = {};
            image_obj.Url = image_url;
            image_obj.Name = value;
            images_array.push(image_obj);
        });
    }
        // Create image loader plugin
        var imagesloader = $('[data-type=imagesloader]').imagesloader({
            imagesToLoad: images_array
        });
        //Form
        $frm = $('#form_id');
        // Form submit
        $frm.submit(function (e) {
            var $form = $(this);
            var FileListing = imagesloader.data('format.imagesloader').AttachmentArray;
            var fileData = [];
            if(FileListing.length > 0){
                $.each(FileListing,function(key,val){
                    if(val.File == null) {
                        var file_type_name = product_iamges_array[key];
                        var filename_ar = (file_type_name).split('.');
                        file_name = filename_ar[0];
                        var imageFiles = filename_ar[1];
                        var _Filelist = new File([""], file_type_name, {type: imageFiles});
                        fileData.push(_Filelist);
                    }else
                        fileData.push(val.File);
                });
            }
            if(fileData.length > 0){
                var fileList = new FileListItem(fileData);
                var product_img = document.querySelector('input[type=file]#files');
                product_img.files = fileList;
            }else{
                return false;
            }
           
        });

        $(document).on('change','.category_id',function(){
            var cat_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>master/product/get_category_gst_values",
                data:{cat_id:cat_id},
                success: function(data){
                    data = JSON.parse(data);
                    $('.add_cgst').val(data.CGST);
                    $('.add_igst').val(data.IGST);
                    $('.add_sgst').val(data.SGST);
                }
            })
        })
    });
     //Set Files List From fileData
     function FileListItem(a) {
        a = [].slice.call(Array.isArray(a) ? a : arguments)
        for (var c, b = c = a.length, d = !0; b-- && d;) d = a[b] instanceof File
        if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
        for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(a[c])
        return b.files
    }
    $(document).on('change','.category_id',function(){
        category = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>master/product/subcategory_by_category",
            data:{category:category},
            success: function(data){
                $('.subcategory_id').empty();
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose...</option>';
                $.each(data,function(key, val)
                {
                        html += '<option value=' +val['iSubcategoryId']+ '>' + val['vSubcategoryName'] + '</option>';
                });
                $('.subcategory_id').html(html);
            }
        });
    })
</script>