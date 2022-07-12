<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/date-picker.css">
<script src="<?php echo $theme_path ?>/assets/js/datepicker/date-picker/datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/select2.css">
<script src="<?php echo $theme_path ?>/assets/js/select2/select2.full.min.js"></script>
<script src="<?php echo $theme_path ?>/assets/js/select2/select2-custom.js"></script>
<style>
    .form-check,
    .form-radio {
        margin-top: 7px !important;
    }

    .input-group-addon .fa {
        width: 10px !important;
    }
</style>
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
            <h3 class="card-title">New Expense</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Expenses</li>
                <li class="breadcrumb-item active">Add Expense</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="<?php echo $this->config->item('base_url'); ?>expenses/insert_expense" enctype="multipart/form-data" name="form" method="post">
                    <div class="card-body">
                        <div class="row g-3 select2-drpdw">
                            <div class="col-md-3">
                                <div class="form-group" style="display: none;">
                                    <label class="col-sm-12 col-form-label">Company <span style="color:#F00; ">*</span></label>
                                    <div class="col-sm-12">
                                        <select name="firm_id" class="form-control required" id="firm" tabindex="1">
                                            <!-- <option value="">Select</option> -->
                                            <?php
                                            if (isset($firms) && !empty($firms)) {
                                                foreach ($firms as $firm) {
                                            ?>
                                                    <option value="<?php echo $firm['firm_id']; ?>"> <?php echo $firm['firm_name']; ?> </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span id="frim_name_err" class="val" style="color:#F00; "></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 col-form-label">Expense amount<span style="color:#F00; ">*</span></label>
                                    <div class="col-sm-12">
                                            <input type="text" name="amount" class=" form-control dot_val" id="ex_amt" tabindex="5" />
                                        <span id="ex_amt_err" class="error_msg val" style="color:#F00; "></span>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-sm-12 col-form-label">Remarks</label>
                                    <div class="col-sm-12">
                                        <input name="remarks" type="text" class="form-control remark" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <!-- <div class="form-group d-none">
                                    <label class="col-sm-12 col-form-label">Head office<span style="color:#F00; ">*</span></label>
                                    <div class="col-sm-12">
                                <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                <label class="form-control" for="validationCustom04" readonly><?php echo $headoffice[0]['vHeadOfficeName'];?></label>  
                                <input class="form-control headoffice" id="validationCustom01" type="hidden" name="headoffice_id" value="<?php echo $headoffice[0]['iHeadOfficeId'];?>">
                                <?php } else {?>
                                <select class="form-select headoffice head-office-multiple" name="headoffice_id" id="validationCustom04" required>
                                <option value="" >Select</option>
                                <?php foreach ($headoffice as $list){ ?>   
                                    <option value="<?php echo $list['iHeadOfficeId'] ?>" <?php echo ($this->session->userdata('HeadOfficeId') == $list['iHeadOfficeId']) ? 'selected' : '';?> ><?php echo $list['vHeadOfficeName'] ?></option>
                                <?php } ?>
                                </select>
                                <span id="headoffice_err" class="val" style="color:#F00; "></span>
                                <?php } ?>
                                    </div>
                                </div> -->

                                <div class="form-group ">
                                    <label class="col-sm-12 col-form-label">Branch<span style="color:#F00;">*</span></label>
                                    <div class="col-sm-12">
                                    <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){ ?>
                                    <label class="form-control" for="validationCustom04" readonly><?php echo $branches[0]['vBranchName'];?></label>  
                                <input class="form-control branch" id="validationCustom01" type="hidden" name="branch_id" value="<?php echo $branches[0]['iBranchId'];?>">
                                    <?php } else{?>
                                <select class="form-select branch disabled branch-multiple" name="branch_id" id="validationCustom04" required="required">
                                <option value="" >Select</option>
                                <?php foreach ($branches as $list){ ?>   
                                    <option value="<?php echo $list['iBranchId'] ?>" <?php echo ($this->session->userdata('BranchId') == $list['iBranchId']) ? 'selected' : '';?> ><?php echo $list['vBranchName'] ?></option>
                                <?php }  ?>
                                </select>
                                <span id="branch_err" class="val" style="color:#F00; "></span>
                                <?php }?>
                                    </div>
                                </div>

                                <div class="form-inline">
                                    <label class="col-sm-12 col-form-label ">Expense Mode<span style="color:#F00;">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="form-radio">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input " value="credit" name="mode" tabindex="7">
                                                Credit &nbsp;
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-radio">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input " value="debit" name="mode" tabindex="7" checked="">
                                                Debit
                                            </label>
                                        </div>
                                    </div>
                                    <span id="mode_err" class="val"></span>
                                </div>
                                
                            </div>
                            <div class="col-md-3">
                            <div class="form-group">
                                    <label class="col-sm-12 col-form-label">Expense Category<span style="color:#F00; ">*</span></label>
                                    <div class="col-sm-12">
                                        <select name="cat_id" class="form-select ex_category required ex_category-multiple" id="ex_category" tabindex="2">
                                            <option value="">Select</option>
                                            <?php
                                            if (isset($category_list) && !empty($category_list)) {
                                                foreach ($category_list as $cat_list) {
                                            ?>
                                                    <option value="<?php echo $cat_list['id']; ?>" <?php echo ($cat_list['id'] == $get_category[0]['category_id']) ? 'selected' : ''; ?>> <?php echo $cat_list['category']; ?> </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span id="cat_err" class="val" style="color:#F00; "></span>
                                    </div>
                                </div>
                            

                                <div class="form-inline">
                                    <label class="col-sm-12 col-form-label ">Expense Type<span style="color:#F00;">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="form-radio">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input " value="fixed" tabindex="6" name="type" checked="">
                                                Fixed  &nbsp;
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-radio">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input " value="variable" tabindex="6" name="type">
                                                Variable
                                            </label>
                                        </div>
                                    </div>
                                    <span id="type_err" class="val"></span>
                                </div>


                            </div>
                            <div class="col-md-3">

                            <div class="form-group ">
                                    <label class="col-sm-12 col-form-label">Expense Subcategory<span style="color:#F00;">*</span></label>
                                    <div class="col-sm-12">
                                        <select name="sub_cat_id" class="form-select ex_subcat required ex_subcat-multiple" id="ex_subcat" tabindex="3">
                                            <option value="">Select</option>
                                            <?php
                                            if (isset($sub_category_list) && !empty($sub_category_list)) {
                                                foreach ($sub_category_list as $sub_cat_list) {
                                            ?>
                                                    <option value="<?php echo $sub_cat_list['id']; ?>" <?php echo ($sub_cat_list['id'] == $get_category[0]['category_id']) ? 'selected' : ''; ?>> <?php echo $sub_cat_list['sub_category']; ?> </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <span id="subcat_err" class="val" style="color:#F00; "></span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12 col-form-label">Entry Date<span style="color:#F00; ">*</span></label>
                                    <div class="col-sm-12">
                                        <input class="form-control to_date datepicker-here" id="validationCustom03" type="text" value="<?php echo date("d/m/Y") ?>" name="created_at" placeholder="" required>
                                        <div class="invalid-feedback"style="color:red">Field is required.</div>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                    <div class="card-footer text-end"> <div class="col-sm-12 col-lg-12 col-xl-12">
                        <input type="submit" name="submit" class="btn btn-success" value="Save" id="submit" tabindex="8">
                        <a href="<?php echo $this->config->item('base_url') . 'expenses/expenses_list' ?>" class="btn btn-danger  pull-left">Back</a>
                    </div></div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<!-- <link rel="stylesheet" href="<?php echo $theme_path ?>/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" /> -->
<!-- <script src="<?php echo $theme_path ?>/node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> -->
<script>
    // $('.datepicker').datepicker({
    //     format: 'dd-mm-yyyy',
    //     endDate: "today",
    // });

    $(document).ready(function() {
        // category change
        $('#ex_category').change(function() {
            var category_id = $(this).val();
            $('#ex_subcat').empty();
            $('#ex_subcat').val('');
            $.ajax({
                url: "<?php echo base_url() ?>expenses/get_subcategory",
                method: 'post',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function(response) {
                var html = '';
                html += '<option value="">Choose Subcategory</option>';
                $.each(response, function(key,val) {
                    html += '<option value='+val['id']+'>'+val['sub_category']+'</option>';
                });
                    $('#ex_subcat').html(html);
                }
            });
        });
    });
    $("#firm").on('blur', function() {
        var firm = $("#firm").val();
        if (firm == "" || firm == null || firm.trim().length == 0) {
            $("#frim_name_err").text("This field is required");
        } else {
            $("#frim_name_err").text("");
        }
    });
    $("#ex_category").on('blur', function() {
        var category = $("#ex_category").val();
        if (category == "" || category == null || category.trim().length == 0) {
            $("#cat_err").text("This field is required");
        } else {
            $("#cat_err").text("");
        }
    });
    $("#ex_subcat").on('blur', function() {
        var sub_category = $("#ex_subcat").val();
        if (sub_category == "" || sub_category == null || sub_category.trim().length == 0) {
            $("#subcat_err").text("This field is required");
        } else {
            $("#subcat_err").text("");
        }
    });
    // $(".headoffice").on('blur', function() {
    //     var headoffice = $(".headoffice").val();
    //     if (headoffice == "" || headoffice == null || headoffice.trim().length == 0) {
    //         $("#headoffice_err").text("This field is required");
    //     } else {
    //         $("#headoffice_err").text("");
    //     }
    // });
    $(".branch").on('blur', function() {
        var branch = $(".branch").val();
        if (branch == "" || branch == null || branch.trim().length == 0) {
            $("#branch_err").text("This field is required");
        } else {
            $("#branch_err").text("");
        }
    });
    $("#ex_amt").on('blur', function() {
        var ex_amt = $("#ex_amt").val();
        if (ex_amt == "" || ex_amt == null || ex_amt.trim().length == 0) {
            $("#ex_amt_err").text("This field is required");
        } else {
            $("#ex_amt_err").text("");
        }
    });
    $("#entry_date").datepicker({

        format: 'dd-mm-yyyy',

        autoclose: true,

    }).on('changeDate', function(selected) {

        var startDate = new Date(selected.date.valueOf());

        $('#to_date').datepicker('setStartDate', startDate);

    }).on('clearDate', function(selected) {

        $('#to_date').datepicker('setStartDate', null);

    });



    $('#submit').on('click', function() {
        var i = 0;
        // var firm = $("#firm").val();
        // if (firm == "" || firm == null || firm.trim().length == 0) {
        //     $("#frim_name_err").text("This field is required");
        //     i = 1;

        // } else {
        //     $("#frim_name_err").text("");
        // }
        var category = $("#ex_category").val();
        if (category == "" || category == null || category.trim().length == 0) {
            $("#cat_err").text("This field is required");
            i = 1;
            // console.log('cat');

        } else {
            $("#cat_err").text("");
        }
        var sub_category = $("#ex_subcat").val();
        if (sub_category == "" || sub_category == null || sub_category.trim().length == 0) {
            $("#subcat_err").text("This field is required");
            i = 1;
            // console.log('sub');
        } else {
            $("#subcat_err").text("");
        }
        // var ex_amt = $(".headoffice").val();
        // if (ex_amt == "" || ex_amt == null || ex_amt.trim().length == 0) {
        //     $("#headoffice_err").text("This field is required");
        //     i = 1;
        //     // console.log('amt');
        // } else {
        //     $("#headoffice_err").text("");
        // }

        var ex_amt = $(".branch").val();
        if (ex_amt == "" || ex_amt == null || ex_amt.trim().length == 0) {
            $("#branch_err").text("This field is required");
            i = 1;
            // console.log('amt');
        } else {
            $("#branch_err").text("");
        }

        var ex_amt_err = $("#ex_amt").val();
        if (ex_amt_err == "" || ex_amt_err == null || ex_amt_err.trim().length == 0) {
            $("#ex_amt_err").text("This field is required");
            i = 1;
            // console.log('amt');
        } else {
            $("#ex_amt_err").text("");
        }

        //        var company_amt = $('#firm_amt').val();
        //
        //        var type = "";
        //        var selected = $("input[type='radio'][name='type']:checked");
        //        if (selected.length > 0) {
        //            type = selected.val();
        //        }
        //        this_val = $.trim($('#ex_amt').val());
        //        if (this_val != '' && (type == '2') && firm != '') {
        //            if (parseInt(this_val) <= parseInt(company_amt)) {
        //                $(".error_msg").text("");
        //            } else {
        //                $(".error_msg").text('This field is more than the Company amount').css('display', 'inline-block');
        //                i = 1;
        //            }
        //        }

        if (i == 1) {
            return false;
        } else {
            return true;
        }
    });
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
    });
</script>