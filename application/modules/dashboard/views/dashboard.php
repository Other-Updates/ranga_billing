<?php
    $theme_path = $this->config->item('theme_locations');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>/assets/css/vendors/chartist.css">
<link rel="stylesheet" href="<?php echo $theme_path ?>/assets/css/style.min.css">
<div class="container-fluid">        
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Dashboard</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('master/dashboard')  ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <!-- <li class="breadcrumb-item active">Default</li> -->
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php if($this->session->userdata('UserRole') == 1){ ?>
    <div class="row stat-cards">
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon primary">
                        <i data-feather="bar-chart-2" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $today_sales ?></p>
                    <p class="stat-cards-info__title">Today Pharmacy Bill</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit success">
                        <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                    </span>
                    Yesterday
                    </p>
                </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon warning">
                        <i data-feather="database" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $monthly_sales ?></p>
                    <p class="stat-cards-info__title">Monthly Pharmacy Bill</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit success">
                        <i data-feather="trending-up" aria-hidden="true"></i>0.24%
                    </span>
                    Last month
                    </p>
                </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon purple">
                        <i data-feather="archive" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $products ?></p>
                    <p class="stat-cards-info__title">Products</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit danger">
                        <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                    </span>
                    Last month
                    </p>
                </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon success">
                        <i data-feather="feather" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $today_purchase ?></p>
                    <p class="stat-cards-info__title">Today Purchase</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit warning">
                        <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                    </span>
                    Yesterday
                    </p>
                </div>
                </article>
            </div>
    </div>
    <div class="row stat-cards my-2">
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon warning">
                        <i data-feather="layers" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $monthly_purchase ?></p>
                    <p class="stat-cards-info__title">Monthly Purchase</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit success">
                        <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                    </span>
                    Last month
                    </p>
                </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon purple">
                        <i data-feather="file" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $category_count ?></p>
                    <p class="stat-cards-info__title">Today Consultancy</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit success">
                        <i data-feather="trending-up" aria-hidden="true"></i>0.24%
                    </span>
                    Yesterday
                    </p>
                </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon success">
                        <i data-feather="printer" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $subcategory_count ?></p>
                    <p class="stat-cards-info__title">Today Lab Test</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit danger">
                        <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                    </span>
                    Yesterday
                    </p>
                </div>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="stat-cards-item">
                <div class="w-lg-100">
                    <div class="m-auto stat-cards-icon primary">
                        <i data-feather="command" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="stat-cards-info w-lg-100">
                    <p class="stat-cards-info__num"><?php echo $downloaded_users; ?></p>
                    <p class="stat-cards-info__title">Monthly Consultancy</p>
                    <p class="stat-cards-info__progress">
                    <span class="stat-cards-info__profit warning">
                        <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                    </span>
                    Last month
                    </p>
                </div>
                </article>
            </div>
    </div>
<?php } ?>
    <div class="row d-none">
        <?php if($this->session->userdata('UserRole') == 1){ ?>
        <div class="col-sm-6 col-xl-3 col-lg-6">
        <div class="card o-hidden">
            <div class="bg-primary b-r-4 card-body">
            <div class="media static-top-widget">
                <div class="align-self-center text-center"><i data-feather="database"></i></div>
                <div class="media-body"><span class="m-0">Today Sales</span>
                <h4 class="mb-0 counter"><?php echo $today_sales ?></h4><i class="icon-bg" data-feather="database"></i>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
        <div class="card o-hidden">
            <div class="bg-secondary b-r-4 card-body">
            <div class="media static-top-widget">
                <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                <div class="media-body"><span class="m-0">Monthly Sales</span>
                <h4 class="mb-0 counter"><?php echo $monthly_sales ?></h4><i class="icon-bg" data-feather="shopping-bag"></i>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-warning b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="archive"></i></div>
                    <div class="media-body"><span class="m-0">Products</span>
                    <h4 class="mb-0 counter"><?php echo $products ?></h4><i class="icon-bg" data-feather="archive"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-danger b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="check-square"></i></div>
                    <div class="media-body"><span class="m-0">Today Purchase</span>
                    <h4 class="mb-0 counter"><?php echo $today_purchase ?></h4><i class="icon-bg" data-feather="check-square"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-none">
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-info b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="calendar"></i></div>
                    <div class="media-body"><span class="m-0">Monthly Purchase</span>
                    <h4 class="mb-0 counter"><?php echo $monthly_purchase ?></h4><i class="icon-bg" data-feather="calendar"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-dark b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="package"></i></div>
                    <div class="media-body"><span class="m-0">Categories</span>
                    <h4 class="mb-0 counter"><?php echo $category_count ?></h4><i class="icon-bg" data-feather="package"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-info b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="server"></i></div>
                    <div class="media-body"><span class="m-0">Subcategories</span>
                    <h4 class="mb-0 counter"><?php echo $subcategory_count ?></h4><i class="icon-bg" data-feather="server"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-success b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="download"></i></div>
                    <div class="media-body"><span class="m-0">App Downloads</span>
                    <h4 class="mb-0 counter"><?php echo $downloaded_users; ?></h4><i class="icon-bg" data-feather="download"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($this->session->userdata('UserRole') == 2 || $this->session->userdata('UserRole') == 3){?>
        <div class="row">
        <div class="col-sm-6 col-xl-4 col-lg-6">
        <div class="card o-hidden">
            <div class="bg-primary b-r-4 card-body">
            <div class="media static-top-widget">
                <div class="align-self-center text-center"><i data-feather="database"></i></div>
                <div class="media-body"><span class="m-0">Today Sales</span>
                <h4 class="mb-0 counter"><?php echo $today_sales ?></h4><i class="icon-bg" data-feather="database"></i>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-xl-4 col-lg-6">
        <div class="card o-hidden">
            <div class="bg-secondary b-r-4 card-body">
            <div class="media static-top-widget">
                <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                <div class="media-body"><span class="m-0">Monthly Sales</span>
                <h4 class="mb-0 counter"><?php echo $monthly_sales ?></h4><i class="icon-bg" data-feather="shopping-bag"></i>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="col-sm-6 col-xl-4 col-lg-6">
            <div class="card o-hidden">
                <div class="bg-success b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="download"></i></div>
                    <div class="media-body"><span class="m-0">App Downloads</span>
                    <h4 class="mb-0 counter"><?php echo $downloaded_users; ?></h4><i class="icon-bg" data-feather="download"></i>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="row size-column">
            <div class="col-xl-7 box-col-12 xl-100">
                <div class="row dash-chart">
                <div class="col-xl-6 col-md-12 box-col-12">
                    <div class="card o-hidden">
                    <div class="card-header p-3">
                        <h5>Monthly Sales Amount Report</h5>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                        <div class="row">
                            <div class="col-12">
                            <div id="chart-widget4"></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 box-col-12">
                    <div class="card o-hidden">
                    <div class="card-header p-3">
                        <h5>Monthly Sales Items Report</h5>
                    </div>
                    <div class="bar-chart-widget">
                        <div class="bottom-content card-body">
                        <div class="row">
                            <div class="col-12">
                            <div id="chart-widget6"></div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 xl-50 box-col-12 d-none">
            <div class="card">
                <div class="card-header p-3">
                    <h5>Most selling Product</h5>
                </div>
                <div class="card-body">
                    <div class="our-product">
                        <div class="table-responsive">
                        <table class="table table-bordernone">
                            <tbody class="f-w-500">
                                <?php foreach($most_selling as $sold){ ?>
                            <tr>
                                <td>
                                    <?php if (file_exists(FCPATH.'uploads/'.$sold['product_image'])){ ?>
                                <div class="media"><img class="img-fluid m-r-15 rounded-circle" width="40" src="<?php echo base_url('uploads/').$sold['product_image']; ?>" alt="">
                                <?php }else{ ?>
                                <div class="media"><img class="img-fluid m-r-15 rounded-circle" width="40" src="<?php echo $theme_path ?>/assets/images/spice-blend.jpg" alt="">
                                <?php } ?>
                                    <div class="media-body"><span><?php echo $sold['vProductName'] ?></span>
                                    <p class="font-roboto"><?php echo $sold['sale_quantity'] ?> Items</p>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <!-- <p>coupon code</p><span>PIX001</span> -->
                                </td>
                                <td>
                                <!-- <p>-51%</p><span>₹99.00</span> -->
                                </td>
                            </tr>
                            <?php } ?>
                            <!-- <tr>
                                <td>
                                <div class="media"><img class="img-fluid m-r-15 rounded-circle" width="40" src="<?php echo $theme_path ?>/assets/images/pure-spices.jpg" alt="">
                                    <div class="media-body"><span>Pure Spices</span>
                                    <p class="font-roboto">105 item</p>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <p>coupon code</p><span>PIX002</span>
                                </td>
                                <td>
                                <p>-78%</p><span>₹66.00</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="media"><img class="img-fluid m-r-15 rounded-circle" width="40" src="<?php echo $theme_path ?>/assets/images/other-masala.jpg" alt="">
                                    <div class="media-body"><span>Other Masala</span>
                                    <p class="font-roboto">604 item</p>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <p>coupon code</p><span>PIX003</span>
                                </td>
                                <td>
                                <p>-04%</p><span>₹116.00</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="media"><img class="img-fluid m-r-15 rounded-circle" width="40" src="<?php echo $theme_path ?>/assets/images/pickle.png" alt="">
                                    <div class="media-body"><span>Pickle</span>
                                    <p class="font-roboto">541 item</p>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <p>coupon code</p><span>PIX004</span>
                                </td>
                                <td>
                                <p>-60%</p><span>₹99.00</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <div class="media"><img class="img-fluid m-r-15 rounded-circle" width="40" src="<?php echo $theme_path ?>/assets/images/oil.jpg" alt="">
                                    <div class="media-body"><span>Oil</span>
                                    <p class="font-roboto">999 item</p>
                                    </div>
                                </div>
                                </td>
                                <td>
                                <p>coupon code</p><span>PIX005</span>
                                </td>
                                <td>
                                <p>-50%</p><span>₹58.00</span>
                                </td>
                            </tr> -->
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 xl-50 box-col-12 d-none">
            <div class="card">
                <div class="card-header p-3">
                    <h5>App Downloads</h5>                    
                </div>
                <div class="card-body appl-download">
                    <div class="">
                        <div class="appl-download-count"><h1><?php echo $downloaded_users; ?></h1> Downloads</div>
                        <div class="appl-download-icon"><a href="https://play.google.com/store/apps/details?id=com.app.coolincool" target="_blank"> <img src="<?php echo $theme_path ?>/assets/images/google-play-store.png" alt=""></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
       
    
        // column chart
        var optionscolumnchart = {
                series: [ {
                name: 'Revenue',
                data: <?php echo json_encode($monthly_sales_graph['amount']); ?>
                }],
                
            legend: {
                show: false
            },
            chart: {
                type: 'bar',
                height: 300
                },
            plotOptions: {
                bar: {
                    radius: 10,
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                colors: ['transparent'],
                curve: 'smooth',
                lineCap: 'butt'
            },
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            yaxis: {
                title: {
                    text: '₹'
                }
            },
            fill: {
                colors:[CubaAdminConfig.primary, CubaAdminConfig.secondary, '#51bb25'],
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.1,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 0.9,
                    stops: [0, 100]
                }
            },
        
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "₹ " + val + ""
                    }
                }
            }
        };

        var chartcolumnchart = new ApexCharts(
        document.querySelector("#chart-widget4"),
        optionscolumnchart
        );
        chartcolumnchart.render();

                // product chart
        var optionsproductchart = {
            chart: {
                height: 300,
                type: 'line'
            },
            stroke: {
                curve: 'smooth'
            },

            series: [{
                name: 'Items',
                type: 'area',
                data: <?php echo json_encode($monthly_sales_graph['qty']); ?>
            }],
            fill: {
                colors:[CubaAdminConfig.primary, CubaAdminConfig.secondary],
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    inverseColors: false,
                    opacityFrom: 0.9,
                    opacityTo: 0.8,
                    stops: [0, 100]
                }
            },

            colors:[CubaAdminConfig.primary, CubaAdminConfig.secondary],
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            markers: {
                size: 0
            },
            // yaxis: [
            //     {
            //         title: {
            //             text: 'Series A'
            //         }
            //     },
            //     {
            //         opposite: true,
            //         title: {
            //             text: 'Series B'
            //         }
            //     }
            // ],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if(typeof y !== "undefined") {
                            return  y.toFixed(0) + " items";
                        }
                        return y;

                    }
                }
            }

        }
        var chartproductchart = new ApexCharts(
            document.querySelector("#chart-widget6"),
            optionsproductchart
        );
        chartproductchart.render();
    })
</script>