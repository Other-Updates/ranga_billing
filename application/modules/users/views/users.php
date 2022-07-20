<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" href="<?php echo $theme_path ?>/assets/css/login.css">
<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12 col-lg-10">
				<div class="wrap d-md-flex">
					<div class="text-wrap text-center d-flex align-items-center">
						<div class="w-100 h-100">
                            <img src="<?php echo $theme_path ?>/assets/images/login/login.jpg" alt="Ranga Hospital" class="w-100 h-100">
						</div>
					</div>
					<div class="login-wrap p-4 p-lg-5 order-md-last">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center mb-4">
                            <img class="img-fluid for-light" src="<?php echo $theme_path ?>/assets/images/logo/login.png" alt="looginpage">
                        </div>
                    </div>
						<form class="theme-form" action="<?php echo base_url('users/login') ?>" method="post">
							<div class="form-group mb-3">
								<label class="label" for="name">Username</label>
								<input class="form-control" type="username" name= "username" required="" placeholder="Username">
							</div>
							<div class="form-group mb-3">
								<label class="label" for="password">Password</label>
								<input class="form-control" type="password" name="password" required="" placeholder="*********">
							</div>
							<div class="form-group">
								<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
							</div>
							<div class="form-group d-md-flex">
								<div class="w-50 text-left">
									<label class="checkbox-wrap checkbox-primary mb-0">Remember Me
									<input type="checkbox" checked>
									<span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#">Forgot Password</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>