"use strict";
setTimeout(function(){
        (function($) {
            "use strict";
            // Single Search Select
            $(".js-example-basic-single").select2();
            $(".js-example-disabled-results").select2();

            // Multi Select
            $(".js-example-basic-multiple").select2();

            // With Placeholder
            $(".head-office-multiple").select2({
                placeholder: "Select Head Office"
            });
            // With Placeholder
            $(".category-multiple").select2({
                placeholder: "Select Category"
            });
            $(".subcategory-multiple").select2({
                placeholder: "Select Subcategory"
            });
            $(".branch-multiple").select2({
                placeholder: "Select Branch"
            });
            $(".salesman-multiple").select2({
                placeholder: "Select Staff Name"
            });
            $(".customer-multiple").select2({
                placeholder: "Select Patient"
            });

            //Limited Numbers
            $(".js-example-basic-multiple-limit").select2({
                maximumSelectionLength: 2
            });

            //RTL Suppoort
            $(".js-example-rtl").select2({
                dir: "rtl"
            });
            // Responsive width Search Select
            $(".js-example-basic-hide-search").select2({
                minimumResultsForSearch: Infinity
            });
            $(".js-example-disabled").select2({
                disabled: true
            });
            $(".js-programmatic-enable").on("click", function() {
                $(".js-example-disabled").prop("disabled", false);
            });
            $(".js-programmatic-disable").on("click", function() {
                $(".js-example-disabled").prop("disabled", true);
            });
        })(jQuery);
    }
    ,350);