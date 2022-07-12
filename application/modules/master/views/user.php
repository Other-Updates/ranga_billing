<?php
    $theme_path = $this->config->item('theme_locations');
?>
<div class="row m-0">
    <div class="col-12 p-0">    
        <div class="login-card">
        <div>
            <div><a class="logo" href="index.html"><img class="img-fluid for-light" src="<?php echo $theme_path ?>/assets/images/logo/login.png" alt="looginpage"><img class="img-fluid for-dark" src="<?php echo $theme_path ?>/assets/images/logo/logo_dark.png" alt="looginpage"></a></div>
            <div class="login-main"> 
            <form class="theme-form" action="<?php echo base_url('master/user/login') ?>" method="post">
                <h4>Sign in to account</h4>
                <p>Enter your email & password to login</p>
                <div class="form-group">
                    <label class="col-form-label">Username</label>
                    <input class="form-control" type="username" name= "username" required="" placeholder="Test@gmail.com">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                        <input class="form-control" type="password" name="password" required="" placeholder="*********">
                        <div class="show-hide"><span class="show">                         </span></div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <div class="checkbox p-0">
                        <input id="checkbox1" type="checkbox">
                        <!-- <label class="text-muted" for="checkbox1">Remember password</label> -->
                    </div><a class="link" href="<?php echo base_url('master/user/forgot_password'); ?>">Forgot password?</a>
                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                    </div>
                </div>                
                <p class="mt-4 mb-0 text-center">Powered By<a class="ms-2" target="_blank" href="http://f2fsolutions.co.in/">F2F Solutions</a></p>
            </form>
            </div>
        </div>
        </div>
    </div>
</div>