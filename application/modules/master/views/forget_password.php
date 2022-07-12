<form class="needs-validation" novalidate="" action="<?php echo base_url('master/user/check_mail') ?>" method="post">
    <div class="row g-3">
    <div class="col-md-3 mb-3">
        <label class="form-label" for="validationCustom05">Email</label>
        <input class="form-control" id="validationCustom05" type="text" name="email" placeholder="Email" required="">
        <div class="invalid-feedback">Please provide a valid email.</div>
    </div>
    </div>
    <div class="mb-3">
    </div>
    <button class="btn btn-primary" type="submit">Submit</button>
</form>