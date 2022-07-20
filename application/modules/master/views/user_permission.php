<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>User Permissions</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard')  ?>"><i class="fa fa-home"></i></a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">User Permissions</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<form action=<?php echo base_url('master/user_role/user_permission/').$user_role['iUserRoleId'] ?> method="post">
    <div class="container-fluid">
        <div class="row">        
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php $sno = 1; ?>
                            <table class="display table table-striped user-permission mb-3" id="basic">
                                <thead>
                                    <tr>
                                    <th>Modules</th>                            
                                    <th>Sections</th>                            
                                    <th class="text-center">Enable Menu</th>                 
                                    <th class="text-center">View</th>
                                    <th class="text-center">Add</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // echo "<pre>";print_r($user_sections);exit;
                                    if (!empty($user_sections)) {
                                        foreach ($user_sections as $key => $value) {
                                            if (!empty($value['sections'])) {
                                                $k = 1;
                                                foreach ($value['sections'] as $section) {
                                                    // echo "<pre>";print_r($user_permissions[$key][$section['iUserSectionsId']]);exit;
                                                    if (($section['vUserSectionKey'] == 'user_modules') || ($section['user_section_key'] == 'user_sections') || (!in_array($section['user_section_key'], array('user_modules', 'user_sections')))) {
                                                        $checked_all = (isset($user_permissions[$key][$section['iUserSectionsId']]['acc_all']) && $user_permissions[$key][$section['iUserSectionsId']]['acc_all'] == 1) ? 'checked' : '';
                                                        $checked_view = (isset($user_permissions[$key][$section['iUserSectionsId']]['acc_view']) && $user_permissions[$key][$section['iUserSectionsId']]['acc_view'] == 1) ? 'checked' : '';
                                                        $checked_add = (isset($user_permissions[$key][$section['iUserSectionsId']]['acc_add']) && $user_permissions[$key][$section['iUserSectionsId']]['acc_add'] == 1) ? 'checked' : '';
                                                        $checked_edit = (isset($user_permissions[$key][$section['iUserSectionsId']]['acc_edit']) && $user_permissions[$key][$section['iUserSectionsId']]['acc_edit'] == 1) ? 'checked' : '';
                                                        $checked_delete = (isset($user_permissions[$key][$section['iUserSectionsId']]['acc_delete']) && $user_permissions[$key][$section['iUserSectionsId']]['acc_delete'] == 1) ? 'checked' : '';
                                                        ?>
                                                        <tr class="danger">
                                                            <td><strong><?php echo ($k == 1) ? ucfirst($value['vUseModuleName']) : ''; ?></strong></td>
                                                            <td><?php echo ucfirst($section['vUserSectionName']); ?></td>
                                                            <td class="text-center"><input type="checkbox" name="permissions[<?php echo $key; ?>][<?php echo $section['iUserSectionsId']; ?>][iAccAll]" class="menu_all" value="1" <?php echo $checked_all; ?> /></td>
                                                            <?php if ($section['iAccView'] == 1): ?>
                                                                <td class="text-center"><input type="checkbox" name="permissions[<?php echo $key; ?>][<?php echo $section['iUserSectionsId']; ?>][iAccView]" class="allow_access" value="1" <?php echo $checked_view; ?> /></td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccView'] == 0): ?>
                                                                <td class="text-center">NA</td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccAdd'] == 1): ?>
                                                                <td class="text-center"><input type="checkbox" name="permissions[<?php echo $key; ?>][<?php echo $section['iUserSectionsId']; ?>][iAccAdd]" class="allow_access" value="1" <?php echo $checked_add; ?> /></td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccAdd'] == 0): ?>
                                                                <td class="text-center">NA</td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccEdit'] == 1): ?>
                                                                <td class="text-center"><input type="checkbox" name="permissions[<?php echo $key; ?>][<?php echo $section['iUserSectionsId']; ?>][iAccEdit]" class="allow_access" value="1" <?php echo $checked_edit; ?> /></td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccEdit'] == 0): ?>
                                                                <td class="text-center">NA</td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccDelete'] == 1): ?>
                                                                <td class="text-center"><input type="checkbox" name="permissions[<?php echo $key; ?>][<?php echo $section['iUserSectionsId']; ?>][iAccDelete]" class="allow_access" value="1" <?php echo $checked_delete; ?> /></td>
                                                            <?php endif; ?>
                                                            <?php if ($section['iAccDelete'] == 0): ?>
                                                                <td class="text-center">NA</td>
                                                            <?php endif; ?>
                                                        </tr>
                                                        <?php
                                                        $k++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary pull-right" type="submit">Submit</button>
                            <input class="btn btn-danger pull-left" type="reset" value="Cancel">
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
    
</form>
<script>
    $(document).ready( function () {
        // var table = $('#basic').DataTable({
        // "columnDefs":[  
		// 		{  
		// 			"targets":[0,1,2,3],  
		// 			"orderable":false,
		// 		},
        //         {  
		// 			"targets":[2],  
		// 			"className":"text-center"
		// 		},  
		// 	], 
        // })
    });
    $('.menu_all').click(function () {
        if ($(this).prop('checked') == true) {
            $(this).closest('tr').find('input.allow_access').prop('checked', true);
        } else {
            $(this).closest('tr').find('input.allow_access').prop('checked', false);
        }

        total_checkbox = Number($('input[type=checkbox].allow_access,input[type=checkbox].menu_all').length);
        checked_checkbox = Number($('input[type=checkbox].allow_access:checked,input[type=checkbox].menu_all:checked').length);
        if (total_checkbox == checked_checkbox) {
            $('input.grand_all').prop('checked', true);
        } else {
            $('input.grand_all').prop('checked', false);
        }
    });
</script>