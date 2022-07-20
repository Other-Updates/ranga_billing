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
                <h3>Expense Category&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary pull-sm-right" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">Add Expense Category</button></h3>
                
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Expense Category<li>
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
                <!-- Nav tabs -->
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" aria-labelledby="tab-6-1" class="tab-pane active in" id="category-details">
                        <div class="">
                            <div class="">
                                <div class="table-responsive">
                                    <table id="categorytable" class="basictable table display list-table dataTable">
                                        <thead>
                                            <tr>
                                                <th>S.NO</th>
                                                <th>Expense Category</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            <?php
                                            // echo "<pre>";
                                            // print_r($category_list);
                                            // exit;
                                            if (isset($category_list) && !empty($category_list)) {

                                                $i = 1;
                                                foreach ($category_list as $list) {
                                            ?>
                                                    <tr class="category<?php echo $list['id']; ?>">
                                                        <td class="first_td"><?php echo $i; ?></td>
                                                        <td><?php echo ucfirst($list['category']); ?></td>
                                                        <td>
                                                            <a id="<?php echo $list['id']; ?>" href="#" class="action-icon kt_modal_edit_cat" title="Edit">
                                                            <i class="fa fa-pencil td-icon"></i></a>&nbsp;&nbsp;
                                                            
                                                            <a name="delete" class="action-icon delete_row" delete_id="<?php echo $list['id']; ?>" title="<?php echo $language['delete']; ?>">
                                                            <i class="fa fa-trash td-icon"></i></a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $i++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="view"></div>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Expense Category</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" name="myform" method="post" action="<?php echo $this->config->item('base_url'); ?>master/manage_category/add/">
            <div class="modal-body scroll-y">
            <input type="hidden" name="category_id" id="cat_id" class="borderra0 form-control" />
                <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-12 col-form-label">Edit Expense Category <span style="color:#F00; font-style:oblique;">*</span></label>
                                        <div class="col-sm-12">
                                                <input type="text" name="category" id="edit_manage_category" class="borderra0 form-control" />
                                            <span id="edit_category_error" class="val" style="color:#F00;font-size: 14px;"></span>
                                            <span id="edit_duplica_category" class="edit_duplica_category" style="color:#F00;font-size: 14px;"></span>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Expense Category</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <form class="needs-validation" name="myform" method="post" action="<?php echo $this->config->item('base_url'); ?>master/manage_category/add/">
            <div class="modal-body scroll-y">
                <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-12 col-form-label">Add Expense Category <span style="color:#F00; font-style:oblique;">*</span></label>
                                        <div class="col-sm-12">
                                                <input type="text" name="category" id="manage_category" class="borderra0 form-control" />
                                            <span id="category_error" class="val" style="color:#F00;font-size: 14px;"></span>
                                            <span id="duplica_category" class="duplica_category" style="color:#F00;font-size: 14px;"></span>
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
<script>
    var language_json = <?php echo json_encode($language); ?>;
    var language = language_json;
    $(document).ready(function() {
        var table = $('#categorytable').DataTable({
            "searchDelay": 500,
            "oLanguage": {
                "sLengthMenu": "Show_MENU_ Entries",
                "sInfoEmpty": ' Showing  0 to  0  of  0  Entries',
                "sInfo": 'Showing_START_ to  _END_  of  _TOTAL_  Entries',
                "sZeroRecords": 'No Data Available',
                "sSearch": 'Search',
                "oPaginate": {
                    "sPrevious": 'Previous',
                    "sNext": 'Next',
                }
            },
            "columnDefs": [{
                "targets": [0, 2], //first column / numbering column
                "orderable": false, //set not orderable
            }, 
            {  className:"cat_name", targets: [1] },
            {  className:"text-center", targets: [0, 2] },
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
                        url: "<?php echo base_url() ?>master/manage_category/delete",
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
            var category = $(this).closest("tr").find('.cat_name').text();
            $('#cat_id').val(id);
            $('#edit_manage_category').val(category);
            $('#kt_modal_edit_user').modal('show');
        });

        $("#butsave_edit").on("click", function() {
            var id = $('#cat_id').val();
            var category = $('#edit_manage_category').val();
            if (category == "" || category == null || category.trim().length == 0) {
                $("#edit_category_error").text('Required Field');
                i = 1;
            } else {
                $("#edit_category_error").text("");
                i = 0;
            }
            var m = $('#duplica_category').html();
            if ((m.trim()).length > 0) {
                i = 1;
            }
            if (i == 1) {
                return false;
            } else {
            $.ajax({
                url: "<?php echo base_url() ?>master/manage_category/update_category",
                type: 'POST',
                data: {
                    category: category,
                    id:id
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
    $('#cancel').on('click', function() {
        $('.val').text("");
        $('#duplica_category').text("");
    });
    $("#manage_category").on('blur', function() {
        var category = $("#manage_category").val();
        if (category == "" || category == null || category.trim().length == 0) {
            $("#category_error").text('Required Field');
        } else {
            $("#category_error").text("");
        }
    });
    $('#manage_category').on('blur', function() {
        var cat_name = $.trim($("#manage_category").val());
        var comments = $.trim($("#comments").val());
        if ((cat_name) != '') {
            $.ajax({
                url: "<?php echo base_url() ?>master/manage_category/add_duplicate_category",
                type: 'POST',
                async: false,
                data: {
                    category: cat_name,
                    comments: comments
                },
                success: function(result) {
                    $("#duplica_category").html(result);
                }
            });
        } else {
            $("#duplica_category").html('');
        }
    });
    $('#comments').on('blur', function() {
        var comments = $('#comments').val();
        if (comments == '' || comments == null || comments.trim().length == 0) {
            $('#comments_err').html('Required Field');
        } else {
            $('#comments_err').html(" ");
        }
    });
    $('#butsave').on('click', function() {
        var cat_name = $.trim($("#manage_category").val());
        if (cat_name != '') {
            $.ajax({
                url: "<?php echo base_url() ?>master/manage_category/add_duplicate_category",
                type: 'POST',
                data: {
                    category: cat_name
                },
                success: function(result) {
                    $("#duplica_category").html(result);
                }
            });
        } else {
            $("#duplica_category").html('');
            i = 0;
        }
        var manage_category = $("#manage_category").val();
        if (manage_category == "" || manage_category == null || manage_category.trim().length == 0) {
            $("#category_error").text('Required Field');
            i = 1;
        } else {
            $("#category_error").text("");
        }
        var m = $('#duplica_category').html();
        if ((m.trim()).length > 0) {
            i = 1;
        }
        if (i == 1) {
            return false;
        } else {
            return true;
        }
    });
</script>