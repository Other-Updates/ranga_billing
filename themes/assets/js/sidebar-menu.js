$(".toggle-nav").click(function () {

    $('#sidebar-links .nav-menu').css("left", "0px");

});

$(".mobile-back").click(function () {

    $('#sidebar-links .nav-menu').css("left", "-410px");

});

$(".page-wrapper").attr("class", "page-wrapper " + localStorage.getItem('page-wrapper'));

if (localStorage.getItem("page-wrapper") === null) {

    $(".page-wrapper").addClass("compact-wrapper");

}



// left sidebar and vertical menu

if ($('#pageWrapper').hasClass('compact-wrapper')) {

    jQuery('.sidebar-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

    jQuery('.sidebar-title').click(function () {

        jQuery('.sidebar-title').removeClass('active').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

        jQuery('.sidebar-submenu, .menu-content').slideUp('normal');

        jQuery('.menu-content').slideUp('normal');

        if (jQuery(this).next().is(':hidden') == true) {

            jQuery(this).addClass('active');

            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

            jQuery(this).next().slideDown('normal');

        } else {

            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

        }

    });

    jQuery('.sidebar-submenu, .menu-content').hide();

    jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

    jQuery('.submenu-title').click(function () {

        jQuery('.submenu-title').removeClass('active').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

        jQuery('.submenu-content').slideUp('normal');

        if (jQuery(this).next().is(':hidden') == true) {

            jQuery(this).addClass('active');

            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

            jQuery(this).next().slideDown('normal');

        } else {

            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

        }

    });

    jQuery('.submenu-content').hide();

} else if ($('#pageWrapper').hasClass('horizontal-wrapper')) {

    $(window).on('load', function(){ 

        $(document).load($(window).bind("resize", checkPosition));

        function checkPosition() {

            if (window.matchMedia('(max-width: 991px)').matches) {

                $('#pageWrapper').removeClass('horizontal-wrapper').addClass('compact-wrapper');

            $('.page-body-wrapper').removeClass('horizontal-menu').addClass('sidebar-icon');

            jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

            jQuery('.submenu-title').click(function () {

                jQuery('.submenu-title').removeClass('active');

                jQuery('.submenu-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

                jQuery('.submenu-content').slideUp('normal');

                if (jQuery(this).next().is(':hidden') == true) {

                    jQuery(this).addClass('active');

                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

                    jQuery(this).next().slideDown('normal');

                } else {

                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

                }

            });

            jQuery('.submenu-content').hide();



            jQuery('.sidebar-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

            jQuery('.sidebar-title').click(function () {

                jQuery('.sidebar-title').removeClass('active');

                jQuery('.sidebar-title').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

                jQuery('.sidebar-submenu, .menu-content').slideUp('normal');

                if (jQuery(this).next().is(':hidden') == true) {

                    jQuery(this).addClass('active');

                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

                    jQuery(this).next().slideDown('normal');

                } else {

                    jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

                }

            });

            jQuery('.sidebar-submenu, .menu-content').hide();

            } 

        }

    })

    

} else if ($('#pageWrapper').hasClass('compact-sidebar')) {

    

    var contentwidth = jQuery(window).width();

    if ((contentwidth) > 992) {

            $('<div class="bg-overlay1"></div>').appendTo($('body'));

    }



    jQuery('.sidebar-title').click(function () {

        jQuery('.sidebar-title').removeClass('active');

        $(".bg-overlay1").removeClass("active");

        jQuery('.sidebar-submenu').removeClass('close-submenu').slideUp('normal');

        jQuery('.sidebar-submenu, .menu-content').slideUp('normal');

        jQuery('.menu-content').slideUp('normal');



        if (jQuery(this).next().is(':hidden') == true) {

            jQuery(this).addClass('active');

            jQuery(this).next().slideDown('normal');

            $(".bg-overlay1").addClass("active");



             $(".bg-overlay1").on("click", function () {

                 jQuery('.sidebar-submenu, .menu-content').slideUp('normal');

                $(this).removeClass("active");

            });

        } 

        if ((contentwidth) < '992') { 

            $(".bg-overlay").addClass("active");

        }

        

    });

    jQuery('.sidebar-submenu, .menu-content').hide();

    jQuery('.submenu-title').append('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

    jQuery('.submenu-title').click(function () {

        jQuery('.submenu-title').removeClass('active').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

        jQuery('.submenu-content').slideUp('normal');

        if (jQuery(this).next().is(':hidden') == true) {

            jQuery(this).addClass('active');

            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

            jQuery(this).next().slideDown('normal');

        } else {

            jQuery(this).find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-right"></i></div>');

        }

    });

    jQuery('.submenu-content').hide();



    $(".sidebar-wrapper nav").find("a").removeClass("active");

    $(".sidebar-wrapper nav").find("li").removeClass("active");



    var current = window.location.pathname

    $(".sidebar-wrapper nav ul>li a").filter(function () {



        var link = $(this).attr("href");

        if (link) {

            if (current.indexOf(link) != -1) {

                $(this).parents().children('a').addClass('active');

                $(this).parents().parents().children('.nav-sub-childmenu').css('display', 'block');

                $(this).addClass('active');

                $(this).parent().parent().parent().children('a').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

                return false;

            }

        }

    });

} 



// toggle sidebar

$nav = $('.sidebar-wrapper');

$header = $('.page-header');

$toggle_nav_top = $('.toggle-sidebar');

$toggle_nav_top.click(function () {

    // $this = $(this);

    // $nav = $('.sidebar-wrapper');

    $nav.toggleClass('close_icon');

    $header.toggleClass('close_icon');

    $(window).trigger('overlay');

});



// $(window).resize(function () {

//     $nav = $('.sidebar-wrapper');

//     $header = $('.page-header');

//     $toggle_nav_top = $('.toggle-sidebar');

//     $toggle_nav_top.click(function () {

//         $this = $(this);

//         $nav = $('.sidebar-wrapper');

//         $nav.toggleClass('close_icon');

//         $header.toggleClass('close_icon');

//     });

// });

$(window).on('overlay', function() {

    $bgOverlay = $(".bg-overlay");

    $isHidden = $nav.hasClass('close_icon');

    if ($(window).width() <= 991 && !$isHidden && $bgOverlay.length === 0) {

        $('<div class="bg-overlay active"></div>').appendTo($('body'));

    }



    if ($isHidden && $bgOverlay.length > 0) {

        $bgOverlay.remove();

    }

});



$('.sidebar-wrapper .back-btn').on('click', function (e) {

    $(".page-header").toggleClass("close_icon");

    $(".sidebar-wrapper").toggleClass("close_icon");

    $(window).trigger('overlay');

});



$("body").on("click", ".bg-overlay", function (){

    $header.addClass("close_icon");

    $nav.addClass("close_icon");

    $(this).remove();

});



/////



$body_part_side = $('.body-part');

$body_part_side.click(function () {

    $toggle_nav_top.attr('checked', false);

    $nav.addClass('close_icon');

    $header.addClass('close_icon');

});



//    responsive sidebar

var $window = $(window);

var widthwindow = $window.width();

(function ($) {

    "use strict";

    if (widthwindow <= 991) {

        $toggle_nav_top.attr('checked', false);

        $nav.addClass("close_icon");

        $header.addClass("close_icon");

    }

})(jQuery);

$(window).resize(function () {

    var widthwindaw = $window.width();

    if (widthwindaw <= 991) {

        $toggle_nav_top.attr('checked', false);

        $nav.addClass("close_icon");

        $header.addClass("close_icon");

    } else {

        $toggle_nav_top.attr('checked', true);

        $nav.removeClass("close_icon");

        $header.removeClass("close_icon");

    }

});



// horizontal arrows

var view = $("#sidebar-menu");

var move = "500px";

var leftsideLimit = -500



// var Windowwidth = jQuery(window).width();

// get wrapper width

var getMenuWrapperSize = function () {

    return $('.sidebar-wrapper').innerWidth();

}

var menuWrapperSize = getMenuWrapperSize();



if ((menuWrapperSize) >= '1660') {

    var sliderLimit = -3000

    

} else if ((menuWrapperSize) >= '1440') {

    var sliderLimit = -3600

} else {

    var sliderLimit = -4200

}



$("#left-arrow").addClass("disabled");

$("#right-arrow").click(function () {

    var currentPosition = parseInt(view.css("marginLeft"));

    if (currentPosition >= sliderLimit) {

        $("#left-arrow").removeClass("disabled");

        view.stop(false, true).animate({

            marginLeft: "-=" + move

        }, {

            duration: 400

        })

        if (currentPosition == sliderLimit) {

            $(this).addClass("disabled");

            console.log("sliderLimit", sliderLimit);

        }

    }

});



$("#left-arrow").click(function () {

    var currentPosition = parseInt(view.css("marginLeft"));

    if (currentPosition < 0) {

        view.stop(false, true).animate({

            marginLeft: "+=" + move

        }, {

            duration: 400

        })

        $("#right-arrow").removeClass("disabled");

        $("#left-arrow").removeClass("disabled");

        if (currentPosition >= leftsideLimit) {

            $(this).addClass("disabled");

        }

    }



});



// page active

if ($('#pageWrapper').hasClass('compact-wrapper')) {

    $(".sidebar-wrapper nav").find("a").removeClass("active");

    $(".sidebar-wrapper nav").find("li").removeClass("active");



    var current = window.location.pathname

    $(".sidebar-wrapper nav ul>li a").filter(function () {



        var link = $(this).attr("href");

        if (link) {

            if (current.indexOf(link) != -1) {

                $(this).parents().children('a').addClass('active');

                $(this).parents().parents().children('ul').css('display', 'block');

                $(this).addClass('active');

                $(this).parent().parent().parent().children('a').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

                $(this).parent().parent().parent().parent().parent().children('a').find('div').replaceWith('<div class="according-menu"><i class="fa fa-angle-down"></i></div>');

                return false;

            }

        }

    });

}





$('.left-header .mega-menu .nav-link').on('click', function (event) {

    event.stopPropagation();

    $(this).parent().children('.mega-menu-container').toggleClass("show");

});



$('.left-header .level-menu .nav-link').on('click', function (event) {

    event.stopPropagation();

    $(this).parent().children('.header-level-menu').toggleClass("show");

});



$(document).click(function(){

    $('.mega-menu-container').removeClass("show");

    $('.header-level-menu').removeClass("show");

});



$(window).scroll(function() { 

    var scroll = $(window).scrollTop();

    if (scroll >= 50) {

        $('.mega-menu-container').removeClass('show');

        $('.header-level-menu').removeClass('show');

    }

});



$('.left-header .level-menu .nav-link').click(function() {

    if($('.mega-menu-container').hasClass("show")) {

         $('.mega-menu-container').removeClass("show");

    }

});



$('.left-header .mega-menu .nav-link').click(function() {

    if($('.header-level-menu').hasClass("show")) {

         $('.header-level-menu').removeClass("show");

    }

});





$(document).ready(function(){

    $(".outside").click(function(){

        $(this).find(".menu-to-be-close").slideToggle("fast");

    });

});

$(document).on("click", function(event){

    var $trigger = $(".outside");

    if($trigger !== event.target && !$trigger.has(event.target).length){

        $(".menu-to-be-close").slideUp("fast");

    }            

});





$('.left-header .link-section > div').on('click', function (e) {

    if ($(window).width() <= 1199) {

        $(".left-header .link-section > div").removeClass("active");

        $(this).toggleClass("active");

        $(this).parent().children('ul').toggleClass("d-block").slideToggle();

    }

});



if ($(window).width() <= 1199) {

    $(".left-header .link-section").children('ul').css('display', 'none');

    $(this).parent().children('ul').toggleClass("d-block").slideToggle();

}

// if ($(window).width() <= 991) {

//     $('.sidebar-wrapper .back-btn').on('click', function (e) {

//         $(".page-header").toggleClass("close_icon");

//         $(".sidebar-wrapper").toggleClass("close_icon");

//     });

// }



if($('#sidebar-menu .simplebar-content-wrapper').hasClass('a.sidebar-link.sidebar-title.active')) {

    $('#sidebar-menu .simplebar-content-wrapper').animate({

        scrollTop: $('a.sidebar-link.sidebar-title.active').offset().top - 200

    }, 1000);

}

var pathname = window.location.pathname;

var path = pathname.split("/");

if(path[1] == "report" || path[1] == "stock" || path[1] == "order" || path[1] == "purchase_order" || path[1] == "home_page_offers" || path[1] == "purchase_receipt"){

    hide_sidebar();

}

// alert(path[4]);

// if(path[3] == "master"){

//     $('.master').addClass('active');

//     $('.master-submenu').css("display", "");

// }

if(path[3] == "product"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

}

if(path[3] == "users-master"){

    $('.users-master').addClass('active');

    $('.users-master-submenu').css("display", "");

}

if(path[1] == "order"){

    $('.orders').addClass('active');

    $('.order-submenu').css("display", "");

}

if(path[1] == "report"){

    $('.report').addClass('active');

    $('.report-submenu').css("display", "");

}

if(path[2]== "branch"){

    $('.master').addClass('active');

    $('.master-submenu').css("display", "");

    $('.branch-class').addClass('active');

}

if(path[2]== "brand"){

    $('.master').addClass('active');

    $('.master-submenu').css("display", "");

    $('.brand').addClass('active');

}

if(path[2]== "category"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.category').addClass('active');

}

if(path[2]== "distributor"){

    $('.users-master').addClass('active');

    $('.users-master-submenu').css("display", "");

    $('.customers').addClass('active');

}

if(path[2]=="product_colour"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.colour-sidebar').addClass('active');

}

if(path[2]== "grade"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.grade').addClass('active');

}

if(path[2]== "headoffice"){

    $('.master').addClass('active');

    $('.master-submenu').css("display", "");

    $('.head-office').addClass('active');

}

if(path[2]== "home_page_offers"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.home-page-offers').addClass('active');

}

if(path[2]== "model"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.model').addClass('active');

}

if(path[2]== "product"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.products').addClass('active');

}

if(path[2]== "region"){

    $('.master').addClass('active');

    $('.master-submenu').css("display", "");

    $('.region').addClass('active');

}

if(path[2]== "subcategory"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.sub-category').addClass('active');

}

if(path[2]== "supplier"){

    $('.users-master').addClass('active');

    $('.users-master-submenu').css("display", "");

    $('.suppliers').addClass('active');

}

if(path[2]== "product_unit"){

    $('.products-master').addClass('active');

    $('.products-master-submenu').css("display", "");

    $('.unit').addClass('active');

}

if(path[2]== "user_role"){

    $('.users-master').addClass('active');

    $('.users-master-submenu').css("display", "");

    $('.user-role').addClass('active');

}

if(path[3]== "salesman"){

    $('.users-master').addClass('active');

    $('.users-master-submenu').css("display", "");

    $('.users').addClass('active');

}

function hide_sidebar(){

    $('.page-header').addClass('close_icon');

    $('.sidebar-wrapper').addClass('close_icon');

}