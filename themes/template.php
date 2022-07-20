<?php
    $theme_path = $this->config->item('theme_locations');
    $this->db->select('us.*,ur.vUserRole');
    $this->db->where('us.iUserId',$this->session->userdata('LoggedId'));
    $this->db->join('cic_master_user_role as ur','ur.iUserRoleId=us.iUserRoleId','left');
    $this->db->from('cic_master_users as us');
    $user = $this->db->get()->row_array();
    $uri = end($this->uri->segment_array());
    $record_num = $this->uri->segment(count($this->uri->segment_array())-1);
    $record_num1 = $this->uri->segment(count($this->uri->segment_array())-2);
    $side_bar='close_icon';

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
    <title>Ranga Hospital</title>
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/datatables.css">
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/datatables.css"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/style.css">
    <link id="color" rel="stylesheet" href="<?php echo $theme_path ?>/assets/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/custom.css">
    <script src="<?php echo $theme_path ?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="/assets/font-awesome.min.css">
    <script>
      $(document).ready(function(){
        $(".menu-button").click(function () {
          $(".menu-container").toggleClass("open");
        });
      });
  </script>
  </head>
  <body>
    <div class="loader-wrapper">
      <div class="loader"><img src="<?php echo $theme_path ?>/assets/images/loader.gif" alt="loader"></div>
      <svg>
        <defs></defs>
        <filter id="goo">
          <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
          <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo"> </fecolormatrix>
        </filter>
      </svg>
    </div>
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <div class="page-header <?php echo $side_bar ?>">
        <div class="header-wrapper row m-0">
          <form class="form-inline search-full col" action="#" method="get">
            <div class="form-group w-100">
              <div class="Typeahead Typeahead--twitterUsers">
                <div class="u-posRelative">
                  <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus>
                  <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading<?php echo $theme_path ?>.</span></div><i class="close-search" data-feather="x"></i>
                </div>
                <div class="Typeahead-menu"></div>
              </div>
            </div>
          </form>
          <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="#"><img class="img-fluid" src="<?php echo $theme_path ?>/assets/images/logo/logo.png" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
          </div>
          <div class="left-header col horizontal-wrapper ps-0">
            <ul class="horizontal-menu">
                <li class="header-tit">Ranga Hospital</li>
                <!-- <li class="mega-menu outside"><a class="nav-link" href="#!"><i data-feather="layers"></i><span>Add Product</span></a></li>
                <li class="level-menu outside"><a class="nav-link" href="#!"><i data-feather="inbox"></i><span>Distributor</span></a></li> -->
              </ul>
          </div>
          <div class="nav-right col-4 pull-right right-header p-0">
            <ul class="nav-menus">
              <!-- <li class="onhover-dropdown">
                <div class="notification-box"><i data-feather="bell"> </i><span class="badge rounded-pill badge-secondary">4</span></div>
                <ul class="notification-dropdown onhover-show-div">
                  <li><i data-feather="bell"></i>
                    <h6 class="f-18 mb-0">Notitications</h6>
                  </li>
                  <li>
                    <p><i class="fa fa-circle-o me-3 font-primary"> </i>Delivery processing <span class="pull-right">10 min.</span></p>
                  </li>
                  <li>
                    <p><i class="fa fa-circle-o me-3 font-success"></i>Order Complete<span class="pull-right">1 hr</span></p>
                  </li>
                  <li>
                    <p><i class="fa fa-circle-o me-3 font-info"></i>Tickets Generated<span class="pull-right">3 hr</span></p>
                  </li>
                  <li>
                    <p><i class="fa fa-circle-o me-3 font-danger"></i>Delivery Complete<span class="pull-right">6 hr</span></p>
                  </li>
                  <li><a class="btn btn-primary" href="#">Check all notification</a></li>
                </ul>
              </li> -->
              
              <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize" class="text-white"></i></a></li>
              <li class="profile-nav onhover-dropdown p-0 me-0">
              <script>
                var menuButton = document.querySelector(".menu-button");
                menuButton.addEventListener("click", function(event) {
                event.preventDefault();
                var parent = document.querySelector(".menu-container");
                if (parent.classList.contains("open")) {
                    parent.classList.remove("open");
                } else {
                    parent.classList.add("open");
                }
                });
              </script>
                <div class="menu-container">
                  <button class="menu-button"><img  src="<?php echo $theme_path ?>/assets/images/dashboard/profile.jpg" alt="Profile"><span class="title"><?php echo $user['vName']; ?></span></button>
                  
                  <div class="menu-dropdown">
                  
                    
                    <div class="content">
                      <ul>
                        <li><a href=""><i class="fa fa-cog"></i>&nbsp;&nbsp;&nbsp;Settings</a></li>
                        <li><a href="<?php echo base_url('master/user/edit_profile') ?>"><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Profile</a></li>
                        <li><a href="<?php echo base_url('users/logout'); ?>"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;&nbsp;Logout</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName">{{name}}</div>
            </div>
            </div>
          </script>
          <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
        </div>
      </div>
      <div class="page-body-wrapper">
        <div class="sidebar-wrapper <?php echo $side_bar ?>">
          <div>
            <div class="logo-wrapper"><img class="img-fluid for-light" src="<?php echo $theme_path ?>/assets/images/logo/logo.png" alt=""><img class="img-fluid for-dark" src="<?php echo $theme_path ?>/assets/images/logo/logo.png" alt="">
              <div class="back-btn"><i class="fa fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle text-white" data-feather="minimize"> </i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="<?php echo $theme_path ?>/assets/images/logo/logo-icon.png" alt=""></a></div>
              <nav class="sidebar-main">
                <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                <div id="sidebar-menu">
                  <ul class="sidebar-links" id="simple-bar">
                    <?php if($this->session->userdata('UserRole') == 1){ ?>
                    <li class="back-btn"><a href="index.html"><img class="img-fluid" src="<?php echo $theme_path ?>/assets/images/logo/logo-icon.png" alt=""></a>
                      <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link " href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-home icon"></i><span class="lan">Dashboard</span></a>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title master" href="#"><i class="fa fa-modx icon"></i></i><span class="lan">Master</span></a>
                      <ul class="sidebar-submenu master-submenu">
                        <!-- <li><a class="lan head-office" href="<?php echo base_url('master/headoffice') ?>">Head Office</a></li>   
                        <li><a class="lan branch-class" href="<?php echo base_url('master/branch') ?>">Branch</a></li>
                        <li><a class="lan region" href="<?php echo base_url('master/region') ?>">Region</a></li>
                        <li><a class="lan brand" href="<?php echo base_url('master/brand') ?>">Brand</a></li>   -->
                        <li><a class="lan manage_cat_class" href="<?php echo base_url('master/manage_category') ?>">Expenses Category</a></li>                     
                        <li><a class="lan manage_subcat_class" href="<?php echo base_url('master/manage_sub_category'); ?>">Expenses Subcategory</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title products-master" href="#"><i class="fa fa-cube icon"></i><span class="lan">Products</span></a>
                      <ul class="sidebar-submenu products-master-submenu">
                        <li><a class="lan category" href="<?php echo base_url('master/category') ?>">Category</a></li>
                        <li><a class="lan sub-category" href="<?php echo base_url('master/subcategory'); ?>">Subcategory</a></li> 
                        <li><a class="lan colour colour-sidebar" href="<?php echo base_url('master/product_colour'); ?>">Colour</a></li>                        
                        <!-- <li><a class="lan grade" href="<?php echo base_url('master/grade'); ?>">Grade</a></li>   -->
                        <li><a class="lan model" href="<?php echo base_url('master/model') ?>">Model</a></li>
                        <li><a class="lan unit" href="<?php echo base_url('master/product_unit'); ?>">Unit</a></li>
                        <li><a class="lan products" href="<?php echo base_url('master/product'); ?>">Products</a></li>                        
                        <li><a class="lan home-page-offers" href="<?php echo base_url('master/brand') ?>">Brand</a></li>                       
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title users-master" href="#"><i class="fa fa-user icon"></i><span class="lan">Users</span></a>
                      <ul class="sidebar-submenu users-master-submenu">
                        <li><a class="lan user-role" href="<?php echo base_url('master/user_role') ?>">User Role</a></li>
                        <!-- <li><a class="lan users" href="<?php echo base_url('master/user/salesman') ?>">Users</a></li> -->
                        <li><a class="lan customers" href="<?php echo base_url('master/distributor'); ?>">Customers</a></li>  
                        <li><a class="lan suppliers" href="<?php echo base_url('master/supplier'); ?>">Suppliers</a></li>  
                        <li><a class="lan Staff" href="#">Staff Name</a></li>  
                      </ul>
                    </li>
                    <!-- <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title master" href="#"><i data-feather="server"></i><span class="lan">Products</span></a>
                      <ul class="sidebar-submenu master-submenu">
                        <li><a class="lan branch-class" href="<?php echo base_url('master/branch') ?>">Branch</a></li>
                        <li><a class="lan brand" href="<?php echo base_url('master/brand') ?>">Brand</a></li>
                        <li><a class="lan category" href="<?php echo base_url('master/category') ?>">Category</a></li>
                        <li><a class="lan colour" href="<?php echo base_url('master/product_colour'); ?>">Colour</a></li>
                        <li><a class="lan customers" href="<?php echo base_url('master/distributor'); ?>">Customers</a></li>
                        <li><a class="lan grade" href="<?php echo base_url('master/grade'); ?>">Grade</a></li>
                        <li><a class="lan head-office" href="<?php echo base_url('master/headoffice') ?>">Head Office</a></li>
                        <li><a class="lan home-page-offers" href="<?php echo base_url('master/home_page_offers') ?>">Offers</a></li>
                        <li><a class="lan model" href="<?php echo base_url('master/model') ?>">Model</a></li>
                        <li><a class="lan products" href="<?php echo base_url('master/product'); ?>">Products</a></li>
                        <li><a class="lan region" href="<?php echo base_url('master/region') ?>">Region</a></li>
                        <li><a class="lan sub-category" href="<?php echo base_url('master/subcategory'); ?>">Subcategory</a></li>
                        <li><a class="lan suppliers" href="<?php echo base_url('master/supplier'); ?>">Suppliers</a></li>
                        <li><a class="lan unit" href="<?php echo base_url('master/product_unit'); ?>">Unit</a></li>
                        <li><a class="lan user-role" href="<?php echo base_url('master/user_role') ?>">User Role</a></li>
                        <li><a class="lan users" href="<?php echo base_url('master/user/salesman') ?>">Users</a></li>
                      </ul>
                    </li> -->
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title" href="#"><i class="fa fa-shopping-cart icon"></i><span class="lan">Purchase order</span></a>
                      <ul class="sidebar-submenu order-submenu">
                      <li><a class="lan" href="<?php echo base_url('purchase_order') ?>">Purchase</a></li>
                        <li><a class="lan" href="<?php echo base_url('purchase_receipt/receipt_list') ?>">Purchase Payment</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link delivery-order" href="<?php echo base_url('stock') ?>"><i class="fa fa-truck icon"></i><span class="lan">Delivery Order</span></a>
                      <!-- <ul class="sidebar-submenu">
                        <li><a class="lan" href="<?php echo base_url('order') ?>">Stock</a></li>
                      </ul> -->
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title" href="#"><i class="fa fa-sticky-note icon"></i><span class="lan">Billing</span></a>
                      <ul class="sidebar-submenu order-submenu">
                      <li><a class="lan sales" href="#">Sales Bill</a></li>
                      <li><a class="lan pharmacy" href="#">Pharmacy Billing</a></li>
                      <li><a class="lan consulting" href="#">Consulting Fee</a></li>
                      <li><a class="lan xray" href="#">XRay Fee</a></li>
                      <li><a class="lan lab" href="#">Lab Fee</a></li>
                      </ul>
                    </li>
                    <!-- <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title cash_hand_over" href="#"><i data-feather="dollar-sign"></i><span class="lan">Cash In Hand</span></a>
                      <ul class="sidebar-submenu cash-hand-over-submenu">
                        <li><a class="lan attendance" href="<?php echo base_url('sales_receipt/cash_handover') ?>">Hand Over</a></li>
                        <li><a class="lan sales" href="<?php echo base_url('sales_receipt/cash_handover_view') ?>">Details</a></li>
                      </ul>
                    </li> -->
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title report" href="#"><i class="fa fa-pie-chart icon"></i><span class="lan">Report</span></a>
                      <ul class="sidebar-submenu report-submenu">
                        <li><a class="lan " href="#">Pharmacy Bill Report</a></li>
                        <li><a class="lan " href="#">XRay Report</a></li>
                        <li><a class="lan " href="#">Lab Report</a></li>
                        <li><a class="lan " href="#">Consultancy Report</a></li>
                        <li><a class="lan " href="#">Pharmacy Stock Report</a></li>
                        <li><a class="lan " href="#">Warehouse Report</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title expenses" href="#"><i class="fa fa-rupee icon"></i><span class="lan">Expenses</span></a>
                      <ul class="sidebar-submenu expenses-submenu">
                        <li><a class="lan expense_new" href="<?php echo base_url('expenses/') ?>">New Expense</a></li>
                        <li><a class="lan expense_list" href="<?php echo base_url('expenses/expenses_list') ?>">Expenses List</a></li>
                        <li><a class="lan balance d-none" href="<?php echo base_url('expenses/balance_list') ?>">Balance List</a></li>
                      </ul>
                    </li>   
                    <?php } ?>
                    <?php if($this->session->userdata('UserRole') == 2){?>
                    <li></li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link " href="<?php echo base_url('dashboard'); ?>"><i data-feather="home"></i><span class="lan">Dashboard</span></a>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title master" href="#"><i data-feather="server"></i><span class="lan">Master</span></a>
                      <ul class="sidebar-submenu master-submenu">
                        <li><a class="lan manage_cat_class" href="<?php echo base_url('master/manage_category') ?>">Expenses Category</a></li>                     
                        <li><a class="lan manage_subcat_class" href="<?php echo base_url('master/manage_sub_category'); ?>">Expenses Subcategory</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list"> 
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title users-master" href="#"><i data-feather="users"></i><span class="lan">Users</span></a>
                      <ul class="sidebar-submenu users-master-submenu">
                        <li><a class="lan customers" href="<?php echo base_url('master/distributor'); ?>">Customers</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link delivery-order" href="<?php echo base_url('stock') ?>"><i data-feather="truck"></i><span class="lan">Delivery Order</span></a>
                      <!-- <ul class="sidebar-submenu">
                        <li><a class="lan" href="<?php echo base_url('order') ?>">Stock</a></li>
                      </ul> -->
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title" href="#"><i data-feather="file-text"></i><span class="lan">Sales order</span></a>
                      <ul class="sidebar-submenu order-submenu">
                        <li><a class="lan orders" href="<?php echo base_url('order') ?>">Orders</a></li>
                        <li><a class="lan receipt_list" href="<?php echo base_url('sales_receipt/receipt_list') ?>">Sales Payment</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title expenses" href="#"><i data-feather="shopping-bag"></i><span class="lan">Expenses</span></a>
                      <ul class="sidebar-submenu expenses-submenu">
                        <li><a class="lan expense_new" href="<?php echo base_url('expenses/') ?>">New Expense</a></li>
                        <li><a class="lan expense_list" href="<?php echo base_url('expenses/expenses_list') ?>">Expenses List</a></li>
                        <li><a class="lan balance d-none" href="<?php echo base_url('expenses/balance_list') ?>">Balance List</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link " href="<?php echo base_url('sales_receipt/cash_handover'); ?>"><i data-feather="dollar-sign"></i><span class="lan">Cash In Hand</span></a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('UserRole') == 3){?>
                      <li></li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link " href="<?php echo base_url('dashboard'); ?>"><i data-feather="home"></i><span class="lan">Dashboard</span></a>
                    </li>
                    <li class="sidebar-list"> 
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title users-master" href="#"><i data-feather="users"></i><span class="lan">Users</span></a>
                      <ul class="sidebar-submenu users-master-submenu">
                        <li><a class="lan customers" href="<?php echo base_url('master/distributor'); ?>">Customers</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title" href="#"><i data-feather="file-text"></i><span class="lan">Sales order</span></a>
                      <ul class="sidebar-submenu order-submenu">
                        <li><a class="lan orders" href="<?php echo base_url('order') ?>">Orders</a></li>
                        <li><a class="lan receipt_list" href="<?php echo base_url('sales_receipt/receipt_list') ?>">Sales Payment</a></li>
                      </ul>
                    </li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link " href="<?php echo base_url('sales_receipt/cash_handover'); ?>"><i data-feather="dollar-sign"></i><span class="lan">Cash In Hand</span></a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){?>
                      <li></li>
                    <li class="sidebar-list">
                      <label class="badge badge-success"></label><a class="sidebar-link sidebar-title report" href="#"><i data-feather="bar-chart"></i><span class="lan">Report</span></a>
                      <ul class="sidebar-submenu report-submenu">
                        <li><a class="lan sales" href="<?php echo base_url('report/reference_report') ?>">Sales Report</a></li>
                        <li><a class="lan stock_report" href="<?php echo base_url('report/stock_report') ?>">Stock Report</a></li>
                     </ul>
                    </li>
                    <?php } ?> 
                  </ul>
                </div>
                <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
              </nav>
          </div>
        </div>
        <div class="page-body">
            <?php echo $content?>          
        </div>
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 footer-copyright text-center">
                <p class="mb-0">Copyright © <?php echo date('Y') ?> Ranga Hospital. All rights reserved</p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <div class="modal fade" id="popup-loader" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
          <div class="modal-content">
              <div class="modal-body pb-4">
                  <div class="loader-icon"><img src="<?php echo $theme_path ?>/assets/images/loader.webp" alt=""></div>
                  <b>Loading...</b> Please Wait
              </div>
          </div>
      </div>
  </div>
    <script src="<?php echo $theme_path ?>/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/icons/feather-icon/feather-icon.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/scrollbar/simplebar.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/scrollbar/custom.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/config.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/sidebar-menu.js"></script>
    <!-- <script src="<?php echo $theme_path ?>/assets/js/datatable/datatables/jquery.dataTables.min.js"></script> -->
    <!-- <script src="<?php echo $theme_path ?>/assets/js/datatable/datatables/datatable.custom.js"></script> -->
    <script src="<?php echo $theme_path ?>/assets/js/form-validation-custom.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/counter/jquery.waypoints.min.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/counter/jquery.counterup.min.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/counter/counter-custom.js"></script>
    <script src="<?php echo $theme_path ?>/assets/js/dashboard/dashboard_2.js"></script>
    <!-- <script src="<?php echo $theme_path ?>/assets/js/chart-widget.js"></script> -->
    <script src="<?php echo $theme_path ?>/assets/js/script.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.10/sweetalert2.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.10/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.10/sweetalert2.js"></script>
  </body>
</html>