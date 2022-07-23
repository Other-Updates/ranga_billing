<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/jquery.basictable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.basictable').basictable({
        breakpoint: 768
        });
    });
</script>
<style>
.input-group-addon button.btn.btn-primary.btn-xs.btn-fw {
    padding: 6px 15px;
}
</style>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Add Lab Fee</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Billing</li>
                <li class="breadcrumb-item active">Add Lab Fee</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form action=<?php echo base_url('order/add_sales_order'); ?> autocomplete="off" class="needs-validation" novalidate="" method="post">
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
                                <label class="form-label" for="validationCustom04">SO Number</label>
                                <input class="form-control" id="validationCustom03" type="text" name="so_number" value="<?php echo date('Y')."-".sprintf("%03s",($sales_order_number['iOrderNumber'] + 1)); ?>" readonly placeholder="" >
                                <div class="invalid-feedback" >Please select a valid state.</div>
                            </div>            
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Head office</label><span style="color:red">*</span>
                                <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $headoffice[0]['vHeadOfficeName'];?></label>  
                                <input data-state="<?php echo $headoffice[0]['iStateId'] ?>" class="form-control headoffice" id="validationCustom01" type="hidden" name="headoffice" value="<?php echo $headoffice[0]['iHeadOfficeId'];?>">
                                <?php } else {?>
                                <select class="form-select headoffice head-office-multiple" name="headoffice" id="validationCustom04" required>
                                <option value="" >Select</option>
                                <?php foreach ($headoffice as $list){ ?>   
                                    <option data-state="<?php echo $list['iStateId'] ?>" value="<?php echo $list['iHeadOfficeId'] ?>" <?php echo ($this->session->userdata('HeadOfficeId') == $list['iHeadOfficeId']) ? 'selected' : '';?> ><?php echo $list['vHeadOfficeName'] ?></option>
                                <?php } ?>
                                </select>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                                <?php } ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Branch</label><span style="color:red">*</span>
                                <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                    <label class="form-control" for="validationCustom04" readonly><?php echo $branches[0]['vBranchName'];?></label>  
                                <input class="form-control branch" id="validationCustom01" type="hidden" name="branch" value="<?php echo $branches[0]['iBranchId'];?>">
                                    <?php } else{?>
                                <select class="form-select branch disabled branch-multiple" name="branch" id="validationCustom04" required="required">
                                <option value="" >Select</option>
                                <?php foreach ($branches as $list){ ?>   
                                    <option value="<?php echo $list['iBranchId'] ?>" <?php echo ($this->session->userdata('BranchId') == $list['iBranchId']) ? 'selected' : '';?> ><?php echo $list['vBranchName'] ?></option>
                                <?php }  ?>
                            </select>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                                <?php }?>
                            </div>
                            <div class="col-md-4">
                            <label class="form-label" for="validationCustom04">Customer</label><span style="color:red">*</span>
                            <div class="d-flex">
                                <select class="form-select disabled customer-multiple customer_id" name="customer" id="validationCustom04" required>
                                    <option selected disbaled value="">Choose</option>
                                    <?php foreach ($customer as $customers){ ?>
                                        <option data-state="<?php echo $customers['iStateId'] ?>" data-grade="<?php echo $customers['iGradeId'] ?>" value="<?php echo $customers['iCustomerId'] ?>"><?php echo $customers['vCustomerName'] ?></option>
                                    <?php } ?>
                                </select>                                
                                <div class="input-group-addon">
                                    <button type="button" class="btn btn-primary btn-xs btn-fw" title="Add Customer" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user"><i class="fa fa-plus"></i></button>
                                </div>
                                <br><div class="invalid-feedback" style="color:red">Field is required.</div>
                            </div>
                            </div>                           
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Date</label>
                                <input class="form-control" id="validationCustom03" type="date" name="ordered_date" placeholder="" required="">
                                <div class="invalid-feedback">Field is required.</div>
                            </div> -->
                            <?php if($this->session->userdata('UserRole') == 1 || $this->session->userdata('UserRole') == 2){ ?>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Salesman</label><span style="color:red">*</span>
                                <select class="form-select disabled salesman salesman-multiple salesman_id" name="salesman" id="validationCustom04" required>
                                    <option selected disbaled value="">Choose</option>
                                    <?php foreach ($salesman as $salesmans){ ?>
                                        <option  value="<?php echo $salesmans['iUserId'] ?>"><?php echo $salesmans['vName'] ?></option>
                                    <?php } ?>
                                </select>
                                <br><div class="invalid-feedback" style="color:red">Field is required.</div>
                             </div>
                             <?php } ?>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">Date</label><span style="color:red">*</span>
                                <input class="form-control to_date datepicker-here" id="validationCustom03" type="text" value="<?php echo date("d/m/Y") ?>" name="ordered_date" placeholder="" required>
                                <div class="invalid-feedback"style="color:red">Field is required.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">Status</label><span style="color:red">*</span>
                                <select class="form-select delivery_status" name="delivery_status" id="validationCustom04"  >
                                <option selected="" disabled="" value="">Select</option>
                                <option value="Not shipped" selected>Not Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="validationCustom04">Payment Status</label><span style="color:red">*</span>
                                <select class="form-select payment_status" name="payment_status" id="validationCustom04"  >
                                <option selected="" disabled="" value="">Select</option>
                                <option value="Pending" selected>Pending</option>
                                <option value="Completed">Completed</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Address</label><span style="color:red">*</span>
                                <textarea class="form-control add_address" id="validationCustom03" type="text" name="adress" placeholder="" required=""></textarea>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Shipping address</label><span style="color:red">*</span>
                                <textarea class="form-control add_ship_address" id="validationCustom03" type="text" name="shipping_adress" placeholder="" required=""></textarea>
                                <div class="invalid-feedback" style="color:red">Field is required.</div>
                            </div>
                            
                            <div class="col-md-8" style="display:none">
                                <select class="form-select category_clone" name="category[]" id="validationCustom04" >
                                <option selected="" disabled="" value="">Select category</option>
                                <?php foreach ($category as $categories){ ?>
                                <option value="<?php echo $categories['iCategoryId'] ?>"><?php echo $categories['vCategoryName'] ?></option>
                                <?php } ?>
                                </select>
                                <!-- <div class="invalid-feedback">Please select a valid state.</div> -->
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
                                    <button class="btn btn-success btn-block wnone mobile-add-btn pull-right" id="add">Add Product</button>
                                    <table class="table delivery-order basictable" id="table">
                                        <thead>
                                            <tr>
                                                <!-- <th scope="col">#</th> -->
                                                <th width="15%" scope="col">Category<span style="color:red">*</span></th>
                                                <th width="27%" scope="col">Product<span style="color:red">*</span></th>
                                                <th width="10%" scope="col">Unit<span style="color:red">*</span></th>
                                                <th width="8%" class="text-right" scope="col">Price</th>
                                                <th width="8%" class="text-center" scope="col">Quantity</th>
                                                <th width="6%" class="text-center" scope="col">CGST</th>
                                                <th width="6%" class="text-center" scope="col">SGST</th>
                                                <th width="6%" class="text-center" scope="col">IGST</th>
                                                <th width="10%" class="text-right" scope="col">Net&nbsp;Value</th>
                                                <th width="4%"  scope="col"><b class="wnone">Remove</b> <button type="button" class="btn btn-success btn-sm" id="add"><i class="icofont icofont-plus"></i></button></th>
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
                                                    <input class="form-control product product_details_0" id="validationCustomUsername" name="product[]" type="text" placeholder="" required aria-describedby="inputGroupPrepend"  >
                                                    <input type="hidden" class="product_id product_id_0" name="product_id[]">
                                                    <input type="hidden" class="product_color_id product_color_id_0" name="product_color_id[]">
                                                    <input type="hidden" name='taxable_cost[]' tabindex="-1" class="taxable_cost taxable_cost_cls_0 form-control" />
                                                    <div class="search-list"><ul class="suggesstion-box" data-id=0></ul></div>
                                                    <!-- <div class="invalid-feedback">Field is required.</div> -->
                                                </div>
                                            </td>
                                            <td scope="col">
                                                <div class="relative">
                                                <select class="form-select product_unit unit_0" name="unit[]" id="validationCustom04" required>
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                </select>
                                                <span class="duplicate_value"></span>
                                                <input type="hidden" class="duplicate_value_err">
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
                                                <div class="row quantity-row">
                                                    <div class="col-md-7 wp-r-0"><input class="form-control quantity quantity_cls_0" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div>
                                                    <div class="col-md-4 p-0-5"><span class="label label-success available_qty" > 0 </span></div>                                                    
                                                </div>
                                                <input type="hidden" class="quantity-err">
                                                <span class="ajax_response_result"></span>
                                                <div class="invalid-feedback">Field is required.</div>
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
                                        <tfoot>
                                            <tr>
                                            <td colspan="5" class="p-0" rowspan="2">
                                                    <table width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align:right;" colspan="1"><strong>Taxable&nbsp;Price</strong></td>
                                                                <td data-th="Taxable Price"><input type="text" name="sales[taxable_price]" tabindex="-1" value="0.00" class="taxable_price text_right form-control m-0" readonly=""/></td>
                                                                <td style="text-align:right;" colspan="1" class="igst_td"> <strong>Tax&nbsp;Amount</strong> </td>
                                                                <td class="igst_td"><input type="text" name="sales[igst_price]" tabindex="-1" value="0.00"  readonly class="add_igst text_right form-control m-0" /></td>                                                                
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4" class="p-0 b-0">
                                                                    <table width="100%">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="text-align:right;" width="17.5%"> <strong>CGST</strong> </td>
                                                                                <td><input type="text" name="sales[cgst_price]" tabindex="-1"  value="0.00"  readonly class="add_cgst text_right form-control m-0" /></td>
                                                                                <td style="text-align:right;" class="sgst_td" width="17.5%"> <strong>SGST</strong> </td>
                                                                                <td class="sgst_td"><input type="text" name="sales[sgst_price]" tabindex="-1" value="0.00"  readonly class="sgst_td add_sgst text_right form-control m-0" /></td>
                                                                                <td style="text-align:right;" width="17.5%" colspan="1" class="igst_td"> <strong>IGST</strong> </td>
                                                                                <td class="igst_td"><input type="text" name="sales[igst_price]" tabindex="-1" value="0.00"  readonly class="add_igst_per text_right form-control m-0" /></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>                                                               
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td colspan="3" style="text-align:right;font-weight:bold;">Additional Charge</td>
                                                <td><input type="text" tabindex="-1"  name="sales[additional_charge]" id="additional_charge" class="additional_charge text_right form-control m-0" value="0.00"/></td>
                                                <td></td>
                                            </tr>
                                            <tr>                                                   
                                                <td  colspan="3" style="text-align:right;font-weight:bold;">Net&nbsp;Total</td>
                                                <td><input type="text" tabindex="-1"  name="sales[net_total]" id="net_total" readonly="readonly"  class="final_amt text_right form-control m-0" /></td>
                                                <td><input type="hidden" tabindex="-1"  name="sales[net_qty]" id="total_net_qty" readonly="readonly"  class="total_net_qty text_right form-control m-0" /></td>
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
                                <div class="row g-3">
                                    <div class="col">
                                        <label class="form-label" for="validationCustom03"><b>Language</b></label>
                                        <label class="d-block" for="edo-ani">
                                        <input class="radio_animated radio radio_active" id="edo-ani" type="radio" value='english' name="language" checked="" data-original-title="" title="">English
                                        </label>
                                        <label class="d-block" for="edo-ani1">
                                        <input class="radio_animated radio radio_inactive" id="edo-ani1" type="radio" value='tamil' name="language" data-original-title="" title="">தமிழ்
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12"><input type="submit" class="btn btn-success submit"> <a href="<?php echo base_url('order') ?>"><input class="btn btn-danger pull-left" type="button" value="Cancel"></a></div></div>
                        </div>                        
                    </div>
                    <!-- <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12"><input type="submit" class="btn btn-success"> <input class="btn btn-danger pull-left" type="reset" value="Cancel"></div></div> -->
                </div>
            </div>        
        </div>
    </div>
</form>
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Customer</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" id="distributor_form" novalidate="" method="post" enctype="multipart/form-data" >
            <div class="modal-body scroll-y">
                <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('LoggedId'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Customer Name</label>
                        <input class="form-control add_name" id="validationCustom03" type="text" name="name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">விநியோகஸ்தர் பெயர்</label>
                        <input class="form-control add_name_tamil tamil-lang" id="validationCustom03" type="text" name="name" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">GSTIN Number</label>
                        <input class="form-control add_gstin" id="validationCustom03" type="text" name="gstin" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                        </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">State</label>
                        <select class="form-select add_state" name ="type" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($state as $states){ ?>
                            <option value="<?php echo $states['iStateId'] ?>"><?php echo $states['vStateName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>    
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Role</label>
                        <select class="form-select add_type" name ="type" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($roles as $role){ ?>
                            <option value="<?php echo $role['iUserRoleId'] ?>"><?php echo $role['vUserRole'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustomUsername">Phone</label>                        
                        <input class="form-control add_phone" id="validationCustomUsername" name="phone" type="text" name="unit" placeholder="" aria-describedby="inputGroupPrepend" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Company Name</label>
                        <input class="form-control add_company_name" id="validationCustom03" type="text" name="company_name" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">நிறுவனத்தின் பெயர்</label>
                        <input class="form-control add_company_name_tamil tamil-lang" id="validationCustom03" type="text" name="company_name_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Grade</label>
                        <select class="form-select add_grade" name ="grade" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($grade as $grades){ ?>
                            <option value="<?php echo $grades['iGradeId'] ?>"><?php echo $grades['vGradeName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Branch</label>
                        <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $branches[0]['vBranchName'];?></label>  
                                <input class="form-control add_branch" id="validationCustom01" type="hidden" name="branch" value="<?php echo $branches[0]['iBranchId'];?>">
                                <?php } else {?>
                        <select class="form-select add_branch" name ="branch" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($branches as $branch){ ?>
                                <option value="<?php echo $branch['iBranchId'] ?>"><?php echo $branch['vBranchName'] ?></option>
                            <?php } } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Address</label>
                        <input class="form-control add_address_cus" id="validationCustom03" type="text" name="address" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tamil-lang" for="validationCustom03">முகவரி</label>
                        <input class="form-control add_address_tamil tamil-lang" id="validationCustom03" type="text" name="address_tamil" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom04">Salesman</label>
                        <select class="form-select add_salesman_id" name ="salesman_id" id="validationCustom04" required="">
                            <option selected="" disabled="" value="">Choose...</option>
                            <?php foreach($user as $users){ ?>
                            <option value="<?php echo $users['iUserId'] ?>" <?php if($this->session->userdata('LoggedId')==$users['iUserId']){ echo 'selected';} ?>><?php echo $users['vName'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">Please select a valid state.</div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Reference Distributer</label>
                        <input class="form-control" id="validationCustom03" type="text" name="reference_distributor" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div> -->
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Email</label>
                        <input class="form-control add_email" id="validationCustom03" type="text" name="email" placeholder="" >
                        <div class="invalid-feedback">Field is required.</div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label class="form-label" for="validationCustom03">Commission</label>
                        <input class="form-control" id="validationCustom03" type="text" name="commission" placeholder="" required="">
                        <div class="invalid-feedback">Field is required.</div>
                    </div> -->
                    <div class="card">
                        <div class="card-body animate-chk p-0">
                            <div class="branch-box">
                                <div class="section-title">Category</div>
                                <?php foreach($category as $ct){ ?>
                                    <div class="branch-list">
                                        <label><input type="checkbox" class="category_check checkbox_animated" name="category_check[]" value="<?php echo $ct['iCategoryId'] ?>" id=""><?php echo $ct['vCategoryName'] ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                            <span class="category_err"></span>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary" id="butsave" type="button">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
        $(document).ready(function(){    
        $('#butsave').on('click', function() {
            var name = $('.add_name').val();
            var name_tamil = $('.add_name_tamil').val();
            var type = $('.add_type').val();
            var phone = $('.add_phone').val();
            var company_name = $('.add_company_name').val();
            var branch = $('.add_branch').val();
            var company_name_tamil = $('.add_company_name_tamil').val();
            var grade = $('.add_grade').val();
            var address = $('.add_address_cus').val();
            var address_tamil = $('.add_address_tamil').val();
            var salesman_id = $('.add_salesman_id').val();
            var email = $('.add_email').val();
            var gstin = $('.add_gstin').val();
            var state = $('.add_state').val();
            var category = [];
            $('.category_check:checked').each(function(i){
                category[i] = $(this).val();
            });
            // var status = $('.radio:checked').val();
            
            if($('input[name="category_check[]"]:checked').length <= 0){
                // alert($('input[name="category_check[]"]:checked').length);
                $('.category_err').css('color','red').text('Please select atleast one category');
                return false;
            } else 
                $('.category_err').text('');
            if(name!="" && type!="" && phone!="" && company_name!="" && grade!="" && address!="" && salesman_id!="" && salesman_id!=null){
                $("#butsave").attr("disabled", "disabled");
                $.ajax({
                    url: "<?php echo base_url('master/distributor/add_distributor'); ?>",
                    type: "POST",
                    data: {
                        name: name,
                        type: type,
                        phone: phone,
                        company_name: company_name,
                        grade: grade,
                        address: address,
                        salesman_id: salesman_id,
                        email: email,
                        name_tamil:name_tamil,
                        company_name_tamil:company_name_tamil,
                        address_tamil:address_tamil,
                        category:category,
                        branch:branch,
                        gstin:gstin,
                        state:state
                    },
                    cache: false,
                    success: function(dataResult){
                        // alert(dataResult);
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode==200){
                            $("#butsave").removeAttr("disabled");
                            $('#distributor_form').find('input:text').val('');
                            $('#kt_modal_add_user').modal('hide');
                            location.reload();        
                        }
                    }
                });
            }
            else{
                $('#distributor_form').addClass('was-validated');
            }
        });
    });  
    $(document).ready(function(){      
        $(document).on('click','.submit',function(){
            // $.each('.quantity-err', function(key,val))
            qty_err = 0;
            var stock_availability_err = 0;
            $(".quantity-err").each(function(){
                qty_err += +$(this).val();
            });
            duplicate_value_err = 0;
            $(".duplicate_value_err").each(function(){
                duplicate_value_err += +$(this).val();
            });
            // $(".available_qty").each(function(){
            //     stock_availability_err = $(this).text();
            //     if(stock_availability_err == 0){
            //         return false;
            //     }
            // })
            if(qty_err != 0 || duplicate_value_err != 0){
                return false;
            }
        });
        // category();
        //  $('.headoffice').val('');
        $(".category_clone:first").clone().appendTo(".category_id");
        $('.category_id').find('.category_clone').prop('required',true);

        var status = $('.delivery_status').val();
        if(status!=''){
        if(status=='Not shipped')
        $('.payment_status').html('<option selected="" disabled="" value="">Select</option><option value="Pending" selected>Pending</option>');
        else if(status=='Cancelled')
        $('.payment_status').html('<option selected="" disabled="" value="">Select</option><option value="Pending" selected>Pending</option>');
        else
        $('.payment_status').html('<option selected="" disabled="" value="">Select</option><option value="Pending" selected>Pending</option><option value="Completed">Completed</option>');
        }

    //get Salesman name by Branch
    var branch_id  = $('.branch').val();
    var salesman_id  = $('.salesman_id').val();
    if(branch_id != ''){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_salesman",
            data:{branch:branch_id},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += $('<option value="">Choose Salesman</option>');
                $.each(data, function(key,val) {
                    var selected = '';
                    console.log(salesman_id+" "+val['iUserId']);
                    if(val['iUserId'] == salesman_id)
                        selected='selected';
                    html += '<option value="'+val["iUserId"]+'" '+ selected+'>'+val['vName']+'</option>';
                });
                    $('.salesman_id').html(html);
            }
        });
    }
    });

      var i=0;  
   
        $(document).on('click', '#add', function(e){  
            e.preventDefault(); 
           i++;  
           $('#table').append('<tr id="row'+i+'" class="dynamic-added"><td data-th="Category*" scope="col"><div class="category_field'+i+'"><div class="invalid-feedback">Please select a valid state.</div></div></td><td data-th="Product*" scope="col"><div class="relative"><input class="form-control product product_details_'+i+'" id="validationCustomUsername" name="product[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required><input type="hidden" class="product_id product_id_'+i+'" name="product_id[]"><input type="hidden" class="product_color_id product_color_id_'+i+'" name="product_color_id[]"><input type="hidden" name="taxable_cost[]" tabindex="-1" class="taxable_cost taxable_cost_cls_'+i+' form-control" /><div class="search-list"><ul class="suggesstion-box" data-id="'+i+'"></ul></div></div></td><td data-th="Unit*" scope="col"><div class="relative"><select class="form-select product_unit unit_'+i+'" name="unit[]" id="validationCustom04" required><option selected="" disabled="" value="">Choose...</option></select><span class="duplicate_value"></span><input type="hidden" class="duplicate_value_err"></div></td><td data-th="Price" scope="col"><div class=""><input class="form-control price price_'+i+'" id="validationCustomUsername" name="price[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td data-th="Quantity" scope="col"><div class="row quantity-row"><div class="col-md-7 wp-r-0"><input class="form-control quantity quantity_cls_'+i+'" id="validationCustomUsername" name="quantity[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required ></div><div class="col-md-4 p-0-5"><span class="label label-success available_qty"> 0 </span></div><input type="hidden" class="quantity-err"><span class="ajax_response_result"></span><div class="invalid-feedback">Field is required.</div></div></td><td data-th="CGST" scope="col"><div class=""><input class="form-control cgst cgst_cls_'+i+'" id="validationCustomUsername" name="cgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td data-th="SGST" scope="col"><div class=""><input class="form-control sgst sgst_cls_'+i+'" id="validationCustomUsername" name="sgst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend" required ></div></td><td scope="col" data-th="IGST"><div class=""><input class="form-control igst igst_cls_'+i+'" id="validationCustomUsername" name="igst[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  ><div class="invalid-feedback">Field is required.</div></div></td><td data-th="Net Value" scope="col"><div class=""><input class="form-control net_value net_value_cls_'+i+'" id="validationCustomUsername" name="net_value[]" type="text" placeholder="" aria-describedby="inputGroupPrepend"  required></div></td><td data-th="Remove"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove btn-sm"><i class="icofont icofont-minus"></i></button></td></tr>');
            $(".category_clone:first").clone().appendTo('.category_field'+i);
            $('.category_field'+i).find('.category_clone').prop('required',true);
        });
  
      $(document).on('click', '.btn_remove', function(){  
        $(this).closest("tr").remove();
        calculate();
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

    //get address by customer
    $(document).on('change','.customer_id',function(){
        var customer_id = $(this).val();
        $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>order/get_address_by_customer",
                data:{customer_id:customer_id},
                success: function(data){
                    data = JSON.parse(data);
                   $('.add_address').val(data.vAddress);
                   $('.salesman').val(data.iSalesmanId);
                   $('.salesman').select2().trigger('change');
                   $('.add_ship_address').val(data.vAddress);
                }
            });
    })


    $(document).on("keyup",'.product',function() {
        _this = $(this);
        var product = $(this).val();    
        var customer_id = $('.customer_id').val();
        var category = $(this).closest('tr').find('.category_clone').val();  
        var dataID = $(_this).closest('td').find('.suggesstion-box').attr('data-id');
        var headoffice = $('.headoffice').val();
        var branch = $('.branch').val();
        var type = (category)  ?  'category' : 'all';
        
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>order/get_product",
                data:{category:category,product:product,headoffice:headoffice,branch:branch,type:type,customer_id:customer_id},
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
                        html += '<li value= '+val['iProductId']+' onClick="selectProduct(`'+type+'`,`'+dataID+'`,`'+val['iProductId']+'`,`'+product_color_value+'`,`'+val['iCategoryId']+'`,`'+color_id+'`)">'+product_color_value.toUpperCase()+'</li>';
                    });
                    $(_this).closest('td').find('.suggesstion-box').show();
                    $(_this).closest('td').find('.suggesstion-box').html(html);
                    $(".product").css("background","#FFF");
                }
            });
        
        
    });

    function selectProduct(type,dataID,iProductId,val,category_id,color_id) {
        // console.log($(this).closest('.suggesstion-box').attr('data-id'));
        // $category_dropdown = 
        $('.product_details_'+dataID).val(val.toUpperCase());
        $('.product_color_id_'+dataID).val(color_id);
        $(".suggesstion-box").hide();
        $('.product_id_'+dataID).val(iProductId);
        if(type == "all"){
        $('.product_details_'+dataID).closest('tr').find('.category_clone').val(category_id);
        // var category_clone = $('.product_details_'+dataID).closest('tr').find('.category_clone');
        // $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url() ?>order/get_category_by_product",
        //         data:{iProductId:iProductId},
        //         success: function(data){
        //             data = JSON.parse(data);
        //            $(category_clone).val(data.iCategoryId);
        //         }
        //     });
        }
        // console.log(val__);

        //get product unit
        var customer_id = $('.customer_id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_product_unit_by_customer",
            data:{iProductId:iProductId,customer_id:customer_id},
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

        var customer_state = $('.customer_id').find('option:selected').attr('data-state');
        if(customer_state=='')
        var customer_state = $('.customer_id').attr('data-state');

        // get gst value
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_gst_values",
            data:{iProductId:iProductId},
            success: function(data){
                data = JSON.parse(data);
                // $('.cgst_cls_'+dataID).val(data['CGST']);
                // $('.sgst_cls_'+dataID).val(data['SGST']);
                // $('.igst_cls_'+dataID).val(data['IGST']);
                if(customer_state==2)
                {
                $('.cgst_cls_'+dataID).val(data['CGST']);
                $('.sgst_cls_'+dataID).val(data['SGST']);
                $('.igst_cls_'+dataID).val('0.00').attr('readonly',true);
                }
                else{
                $('.cgst_cls_'+dataID).val('0.00').attr('readonly',true);
                $('.sgst_cls_'+dataID).val('0.00').attr('readonly',true);
                $('.igst_cls_'+dataID).val(data['IGST']);
                }
            }
        });

        // $('.cgst_cls_'+dataID).val(5);
        // $('.sgst_cls_'+dataID).val(5);
        // $('.igst_cls_'+dataID).val(data['IGST']);

        $(document).on('change','.unit_'+dataID, function(){
             var branch = $('.branch').val();
             var grade = $('.customer_id').find('option:selected').attr('data-grade');
            $('.quantity_cls_'+dataID).val('');
            $('.price_'+dataID).closest('tr').find('.available_qty').text(0);
            unit_id = $(this).val();
            $('.price_'+dataID).val('');
            $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_unit_price",    
            data:{iProductId:iProductId,unit_id:unit_id,color_id:color_id,branch:branch,grade:grade},
                success: function(data){
                    if(data == false){
                        $('.quantity_cls_'+dataID).val(0);
                        price = 0;
                        qty = 0;
                    }else{
                        data = JSON.parse(data);
                        price = data['fProductPrice'];
                        qty = data['dProductQty'];
                    }
                    $('.price_'+dataID).val(price);
                    $('.price_'+dataID).closest('tr').find('.available_qty').text(qty);
                    $('.net_value_cls_'+dataID).val(price);
                    calculate();
                }
            });
        })

        $(document).on("keyup",'.quantity,.price,.cgst,.sgst,.igst,.net_value,.additional_charge',function() {
            
            var quantity_class = $(this).hasClass('quantity');
            var branch_id =  $('.branch').val();
            if(quantity != "" && quantity_class){
                var _this = $(this);
                var quantity = $(this).val();
                var product_id = $(this).closest('tr').find('.product_id').val();
                var product_unit = $(this).closest('tr').find('.product_unit').val();
                var color_id = $(this).closest('tr').find('.product_color_id').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() ?>order/check_product_quantity",
                    data:{product_id:product_id,branch_id,quantity:quantity,product_unit:product_unit,color_id:color_id},
                    success: function(data){
                            data = JSON.parse(data);
                            // console.log(data.status);
                        if(data.status == 'failure'){
                            console.log(data.message);
                            _this.closest("tr").find(".quantity-err").val(1);
                            _this.closest("tr").find(".ajax_response_result").html('').html(data.message).css('color','red');
                        } else {
                            console.log(data.status);

                            _this.closest("tr").find(".quantity-err").val(0);
                            _this.closest('tr').find(".ajax_response_result").html('');
                        }
                    }
                });
            }
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
        var customer_state = $('.customer_id').find('option:selected').attr('data-state');
        if(customer_state=='')
        var customer_state = $('.customer_id').attr('data-state');
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
        $('.total_net_qty').val(sum);
        var totaltax = $('.totaltax').val();
        var additional_charge = $('.additional_charge').val();
        if(additional_charge!='')
        final_sub_total = final_sub_total + parseInt(additional_charge);
        // if (totaltax)
        // final_sub_total = final_sub_total + transport + labour + advance;
        $('.final_amt').val(final_sub_total.toFixed(2));
        // $('.round_off').val(final_sub_total.toFixed(0));
    }

    //check duplicate value adding on sales order
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
    });
    $(document).ready(function(){    
        var headoffice = '<?php echo $this->session->userdata('HeadOfficeId')?>'; 
        var userid = '<?php echo $this->session->userdata('UserRole')?>';
        if(userid==2 || userid==3)
        $('.head-office-multiple').select2().prop("disabled", true).select2('val',headoffice);
    });
    //get branch name by head office
    $(".headoffice").on('change', function(){
        var headoffice = $(this).val();  
        var stateid = $(this).find('option:selected').attr('data-state');
        // if(stateid==2)
        //         {
        //         $('.cgst').val(2.5).attr('disabled',false);
        //         $('.sgst').val(2.5).attr('disabled',false);
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
                html += '<option value="">Choose Unit</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['iBranchId']+'>'+val['vBranchName']+'</option>';
                });
                    $('.branch').html(html);
            }
        });
    });
    //get Salesman name by Branch
    $(".branch").on('change', function(){
        var branch = $(this).val();  
        $('.salesman').empty();
            $('.salesman').val('');
            $( ".salesman" ).removeClass( "disabled" )
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_salesman",
            data:{branch:branch},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose Salesman</option>';
                $.each(data, function(key,val) {
                    html += '<option value='+val['iUserId']+'>'+val['vName']+'</option>';
                });
                    $('.salesman').html(html);
            }
        });
        // Load Customer
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>order/get_customer_by_branch",
            data:{branch:branch},
            success: function(data){
                data = JSON.parse(data);
                var html = '';
                html += '<option value="">Choose Customer</option>';
                $.each(data, function(key,val) {
                    html += '<option data-state='+val['iStateId']+' data-grade='+val['iGradeId']+' value='+val['iCustomerId']+'>'+val['vCustomerName']+'</option>';
                });
                    $('.customer_id').html(html);
            }
        });
    });
    //Deliebery Status Based payment status
    $(document).on('change','.delivery_status',function(){
        $('.payment_status').empty();
        var status = $(this).val();
        if(status!=''){
        if(status=='Not shipped')
        $('.payment_status').html('<option selected="" disabled="" value="">Select</option><option value="Pending" selected>Pending</option>');
        else if(status=='Cancelled')
        $('.payment_status').html('<option selected="" disabled="" value="">Select</option><option value="Pending" selected>Pending</option>');
        else
        $('.payment_status').html('<option selected="" disabled="" value="">Select</option><option value="Pending" selected>Pending</option><option value="Completed">Completed</option>');
        }
    });
</script>
