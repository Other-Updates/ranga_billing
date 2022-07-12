<?php
    $theme_path = $this->config->item('theme_locations');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?php echo $theme_path ?>/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $theme_path ?>/assets/images/favicon.png" type="image/x-icon">
    <title>Coolincool Masala</title>
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/style.css">
    <link id="color" rel="stylesheet" href="<?php echo $theme_path ?>/assets/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/custom.css">
  </head>
  <body>
    <div class="container-fluid p-0">
      <?php echo $content; ?>
      <script src="<?php echo $theme_path ?>/assets/js/jquery-3.5.1.min.js"></script>
      <script src="<?php echo $theme_path ?>/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
      <script src="<?php echo $theme_path ?>/assets/js/icons/feather-icon/feather.min.js"></script>
      <script src="<?php echo $theme_path ?>/assets/js/icons/feather-icon/feather-icon.js"></script>
      <script src="<?php echo $theme_path ?>/assets/js/config.js"></script>
      <script src="<?php echo $theme_path ?>/assets/js/script.js"></script>
    </div>
  </body>
</html>