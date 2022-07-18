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
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h3>Expense Subcategory <button type="button" class="btn btn-sm btn-primary pullsm-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Expense Subcategory</button></h3>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Expense Subcategory<li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
        <div class="row">        
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
            <div class="tabs">
                <div class="tab-content">
                    <div role="tabpanel" aria-labelledby="tab-6-1" class="tab-pane active in" id="sub_category-details">
                        <div class="table-responsive">
                            <table id="categoryTable" class="basictable table display list-table dataTable">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Expense Category</th>
                                        <th>Expense Subcategory</th>
                                        <th class="action-btn-align">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($all_list) && !empty($all_list)) {
                                        $i = 1;
                                        foreach ($all_list as $list) {
                                    ?>
                                            <tr class="subcat<?php echo $list['id']; ?>">
                                                <td class="first_td"><?php echo $i; ?> <input type="hidden" class="d-none cat_id" value="<?php echo $list['category_id']; ?>"></td>
                                                <td><?php echo ucfirst($list['category']); ?></td>
                                                <td><?php echo ucfirst($list['sub_category']); ?></td>
                                                <td class="action-btn-align">
                                                    <a id="<?php echo $list['id']; ?>" class="action-icon kt_modal_edit_cat" title="Edit">
                                                    <i class="fa fa-edit fs-5"></i></a>&nbsp;&nbsp;
                                                    <a name="delete" class="action-icon delete_row" delete_id="<?php echo $list['id']; ?>" title="Delete">
                                                    <i class="fa fa-remove fs-5"></i></a>
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="4">No Data Available</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_edit_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Expense Subcategory</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" name="myform" method="post" action="">
            <div class="modal-body scroll-y">
            <input type="hidden" name="category_id" id="edit_cat_id" class="borderra0 form-control" />
            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 col-form-label">Expense Category <span style="color:#F00; font-style:oblique;">*</span></label>
                                        <div class="col-sm-12">
                                            <div>
                                                <select name="category" class="form-select required" id="edi_category_add">
                                                    <option value="">Select</option>
                                                    <?php
                                                    if (isset($category_list) && !empty($category_list)) {
                                                        foreach ($category_list as $cat_list) {
                                                    ?>
                                                            <option value="<?php echo $cat_list['id']; ?>"> <?php echo $cat_list['category']; ?> </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <span id="edit_category_error" class="val" style="color:#F00;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 col-form-label">Expense Subcategory<span style="color:#F00; font-style:oblique;">*</span></label>
                                        <div class="col-sm-12">
                                                <input type="text" name="sub_category" class=" borderra0 form-control" placeholder="<?php echo $language['enter_sub_category'] ?>" id="edit_subcategory_add" />
                                            <span id="edit_sub_cat_err" class="val" style="color:#F00;"></span>
                                            <span id="edi_dup" class="dup" style="color:#F00;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <button class="btn btn-primary" id="butsave_edit" type="button">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Expense Subcategory</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" name="myform" method="post" action="<?php echo $this->config->item('base_url'); ?>master/manage_sub_category/insert_sub_category/">
            <div class="modal-body scroll-y">
            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 col-form-label">Expense Category <span style="color:#F00; font-style:oblique;">*</span></label>
                                        <div class="col-sm-12">
                                            <div>
                                                <select name="category" class="form-select required" id="category_add">
                                                    <option value="">Select</option>
                                                    <?php
                                                    if (isset($category_list) && !empty($category_list)) {
                                                        foreach ($category_list as $cat_list) {
                                                    ?>
                                                            <option value="<?php echo $cat_list['id']; ?>"> <?php echo $cat_list['category']; ?> </option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <span id="category_error" class="val" style="color:#F00;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 col-form-label">Expense Subcategory<span style="color:#F00; font-style:oblique;">*</span></label>
                                        <div class="col-sm-12">
                                                <input type="text" name="sub_category" class=" borderra0 form-control" placeholder="<?php echo $language['enter_sub_category'] ?>" id="subcategory_add" />
                                            <span id="sub_cat_err" class="val" style="color:#F00;"></span>
                                            <span id="dup" class="dup" style="color:#F00;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" data-bs-original-title="" title="">Close</button>
                <input class="btn btn-primary" id="butsave" type="submit">
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var language_json = <?php echo json_encode($language); ?>;
    var language = language_json;
    $(document).ready(function() {
        var table = $('#categoryTable').DataTable({

            "oLanguage": {
                "sLengthMenu": "show_MENU_entries",
                "sInfoEmpty": "showing 0 to 0 of 0  + entries",
                "sInfo": 'Showing_START_ to  _END_  of  _TOTAL_  Entries',
                "sZeroRecords": 'No Data Available',
                "sSearch": 'Search',
                "oPaginate": {
                    "sPrevious": 'Previous',
                    "sNext": "next",
                }
            },
            "columnDefs": [{
                "targets": [0, 3], //first column / numbering column
                "orderable": false, //set not orderable
            }, 
            {  className:"subcat_name", targets: [2] },
            {  className:"text-center", targets: [0, 3] },
        ],
        });
        // new $.fn.dataTable.FixedHeader(table);

        $(document).on('click', '.delete_row', function() {
            var hidin = $(this).attr('delete_id');
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                        url: "<?php echo base_url() ?>master/manage_sub_category/delete",
                        type: 'POST',
                        data: {
                            value1: hidin
                        },
                        success: function(data) {
                        var result = JSON.parse(data);
                        if(result.statusCode==200){
                            window.location.reload();
                            }
                            else{
                            alert("Your Entry has not been Deleted.");
                            }
                        }
                    });
            }
        });
    });

       $(document).on('click', '.kt_modal_edit_cat', function() {
            var id = $(this).attr("id");
            var category_id = $(this).closest("tr").find('.cat_id').val();
            var subcategory = $(this).closest("tr").find('.subcat_name').text();
            $('#edit_cat_id').val(id);
            $('#edi_category_add option[value='+category_id+']').attr('selected', 'selected');
            $('#edit_subcategory_add').val(subcategory);
            $('#kt_modal_edit_user').modal('show');
        });

        $("#butsave_edit").on("click", function() {
            var id = $('#edit_cat_id').val();
            var category_id = $('#edi_category_add').val();
            var sub_category = $('#edit_subcategory_add').val();
            var i = 0;

        if (sub_category == '' || sub_category == null || sub_category.trim().length == 0) {
            $('#edit_sub_cat_err').html("Required Field");
            i = 1;
        } else {
            $('#edit_sub_cat_err').html(" ");
        }

        if (category_id == "" || category_id == null || category_id.trim().length == 0) {
            $("#edit_category_error").text("Required Field");
            i = 1;
        } else {
            $("#edit_category_error").text("");
        }
        if (i == 1) {
            return false;
        } else {
            $.ajax({
                url: "<?php echo base_url() ?>master/manage_sub_category/update_subcategory",
                type: 'POST',
                data: {
                    category_id: category_id,
                    id:id,
                    sub_category:sub_category
                },
                success: function(data) {
                    var result = JSON.parse(data);
                        if(result.statusCode==200){
                            $('#kt_modal_edit_user').modal('hide');
                            window.location.reload();
                            }
                            else{
                            alert("Your Entry has not been Updated.");
                            }
                }
            });
        }
        });
    });
    $('#subcategory_add').on('blur', function() {

        var sub_category = $('#subcategory_add').val();
        if (sub_category == '' || sub_category == null || sub_category.trim().length == 0) {
            $('#sub_cat_err').html("Required Field");
        } else {
            $('#sub_cat_err').html(" ");
        }
    });

    $("#category_add").on('blur', function() {

        var category = $("#category_add").val();
        if (category == "" || category == null || category.trim().length == 0) {
            $("#category_error").text("Required Field");
        } else {
            $("#category_error").text("");
        }
    });
    $('#subcategory_add').on('blur', function() {
        var subcat_name = $.trim($("#subcategory_add").val());
        var cat_id = $("#category_add").val();
        if ($.trim(subcat_name) != '') {

            $.ajax({
                url: "<?php echo base_url() ?>master/manage_sub_category/add_duplicate_subcategory",
                type: 'POST',
                async: false,
                data: {
                    sub_category: subcat_name,
                    category_id: cat_id
                },
                success: function(result) {
                    $("#dup").html(result);
                }
            });
        } else {
            $("#dup").html('');
        }
    });
    $('#butsave').on('click', function() {

        var subcat_name = $.trim($("#subcategory_add").val());
        var cat_id = $("#category_add").val();
        if ($.trim(subcat_name) != '') {
            $.ajax({
                url: "<?php echo base_url() ?>master/manage_sub_category/add_duplicate_subcategory",
                type: 'POST',
                async: false,
                data: {
                    sub_category: subcat_name,
                    category_id: cat_id
                },
                success: function(result) {
                    $("#dup").html(result);
                }
            });
        } else {
            $("#dup").html('');
        }


        var i = 0;

        var sub_category = $('#subcategory_add').val();
        if (sub_category == '' || sub_category == null || sub_category.trim().length == 0) {
            $('#sub_cat_err').html("Required Field");
            i = 1;
        } else {
            $('#sub_cat_err').html(" ");
        }

        var category = $("#category_add").val();
        if (category == "" || category == null || category.trim().length == 0) {
            $("#category_error").text("Required Field");
            i = 1;
        } else {
            $("#category_error").text("");
        }

        var m = $('#dup').html();
        if ((m.trim()).length > 0) {
            i = 1;
        }
        if (i == 1) {
            return false;
        } else {
            return true;

        }
    });
    $('#cancel').on('click', function() {
        $('.val').html("");
        $('#dup').html("");
    });
</script>