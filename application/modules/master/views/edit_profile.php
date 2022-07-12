<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Edit Profile</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">profile</li>
                <!-- <li class="breadcrumb-item active">Default</li> -->
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <form class="needs-validation" action="<?php echo base_url('master/user/update_admin') ?>" enctype="multipart/form-data" method="post" novalidate="">
                    <div class="card-body">
                        <input type="hidden" value="<?php echo $profile['iUserId']?>" name="user_id">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom01">First name</label>
                                <input class="form-control" id="validationCustom01" name="name" type="text" value="<?php echo $profile['vName'] ?>" required="">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom02">Username</label>
                                <input class="form-control" id="validationCustom02" type="text" name="username" value="<?php echo $profile['vUserName'] ?>" required="">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="validationCustomUsername">Username</label>
                                <div class="input-group"><span class="input-group-text" id="inputGroupPrepend">@</span>
                                <input class="form-control" id="validationCustomUsername" type="text" placeholder="Username" aria-describedby="inputGroupPrepend" required="">
                                <div class="invalid-feedback">Please choose a username.</div>
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom03">Phone</label>
                                <input class="form-control" id="validationCustom03" type="text" name="phone" value="<?php echo $profile['iPhoneNumber'] ?>" placeholder="Phone" required="">
                                <div class="invalid-feedback">Please provide a valid city.</div>
                            </div>
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="validationCustom04">State</label>
                                <select class="form-select" id="validationCustom04" required="">
                                <option selected="" disabled="" value="">Choose...</option>
                                <option>...</option>
                                </select>
                                <div class="invalid-feedback">Please select a valid state.</div>
                            </div> -->
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom05">Address</label>
                                <input class="form-control" id="validationCustom05" type="text" name="address" value="<?php echo $profile['vAddress'] ?>" placeholder="Address" required="">
                                <div class="invalid-feedback">Please provide a valid zip.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="validationCustom05">Email</label>
                                <input class="form-control" id="validationCustom05" type="text" name="email" value="<?php echo $profile['vEmail'] ?>" placeholder="Email" required="">
                                <div class="invalid-feedback">Please provide a valid zip.</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="col-sm-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <input class="btn btn-danger pull-left" type="reset" value="Cancel" data-bs-original-title="" title="">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>