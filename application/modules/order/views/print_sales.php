<?php
    $theme_path = $this->config->item('theme_locations');
?>
<style>
    .btn-danger {background-color: #dc3545; border-color: #dc3545;padding: 0.275rem 1.75rem;font-size: 14px;color: #fff;font-weight: 400;border: 1px solid transparent;border-radius: 0.25rem;cursor: pointer;}
    body {font-family:Arial, Helvetica, sans-serif; line-height: 30px;;}
    p, h2, h4 {margin: 0;padding-bottom: 5px;}
    table, .product-list thead th, .product-list tbody td {border: 0.5px solid #000;}
    table td {padding: 5px;}
    table {width: 100%;font-size : 12px;}
    .text-right, .total td.text-right {text-align: right;}    
    .product-list thead th{text-align: center; padding: 5px;}
    .total td, .footer-sign td {text-align: center;border: 0.5px solid #000;}
    .text-center {text-align: center;}
    .footer-sign td {padding: 10px; font-size: 14px;font-weight: 600; vertical-align: top;}
    .fw-300{
        font-weight: 300;
    }
    .footer-sign td.text-start{
        text-align:left;
    }
@media print {    
    .btn-danger {display: none;}
 }
    
</style>
<body onload="">
<table class="header-print">
    <tbody>
        <tr>
            <td width="20%"><img src="https://store.coolincool.com/themes/assets/images/logo/login.png"></td>
            <?php if($language == "english"){ ?>
                <td width="80%">
                    <h2>COOL IN COOL STORES</h2>
                    <p>9/10, M1,RAJ COMPLEX, NAGERCOIL SERVICE ROAD,</p>
                    <p>KAVALKINARU JUNCTION, Tirunelveli, Tamil Nadu, 627105</p>
                    <p>Ph : 9655007712, E-Mail : email:coolincoolstore@gmail.com</p>
                    <h4>GST No : 33AANFC7448M2ZW</h4>
                </td>
            <?php } ?>
            <?php if($language == "tamil"){ ?>
                <td width="80%">
                    <h2>கூல் இன் கூல் ஆர்கானிக் உணவுகள்</h2>
                    <p>9/10, M1, ராஜ் காம்ப்ளக்ஸ், நாகர்கோயில் சர்வீஸ் சாலை</p>
                    <p>காவல்கிணறு சந்திப்பு, திருநெல்வேலி, தமிழ்நாடு, 627105</p>
                    <p>Ph : 9655007712, மின்னஞ்சல் : coolincoolstore@gmail.com</p>
                    <h4>GST No : 33AANFC7448M2ZW</h4>
                </td>
            <?php } ?>
        </tr>
    </tbody>
</table>
<table class="quotation">
    <tbody>
        <?php if($language == "english"){ ?>
            <tr><td colspan="4" style="text-align:center;background: #ddd;padding: 10px;font-size: 16px;font-weight: bold;">INVOICE</td></tr>
            <tr>
                <td width="13%" style="text-align: right;font-weight: bold;">Invoice To : </td>
                <td width="37%"><?php echo $sales_order[0]['vCustomerName']; ?></td>
                <td width="13%" style="text-align: right;font-weight: bold;">Invoice No :</td>
                <td width="37%"><?php echo $sales_order[0]['vSalesOrderNo']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;font-weight: bold;">Bill To  : </td>
                <td><?php echo $sales_order[0]['vAddress'] ?></td>
                <td style="text-align: right;font-weight: bold;">Shipped To  : </td>
                <td width="20%"><?php echo (!empty($sales_order[0]['vShippingAddress']))?$sales_order[0]['vShippingAddress']:$sales_order[0]['vAddress']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;font-weight: bold;">Phone No : </td>
                <td><?php echo $sales_order[0]['vPhoneNumber'] ?></td>
                <td style="text-align: right;font-weight: bold;">Invoice Date :</td>
                <td><?php echo date("d-m-Y",strtotime($sales_order[0]['dOrderedDate'])); ?></td>
                
            </tr>
            <tr>
                <td style="text-align: right;font-weight: bold;">State : </td>
                <td><?php echo $sales_order[0]['vStateName'] ?></td>
                <td class="d-none state_id" style="display:none;"><?php echo $sales_order[0]['iStateId'] ?></td>
                <td style="text-align: right;font-weight: bold;"></td>
                <td></td>
            </tr>
        <?php }if($language == "tamil"){ ?>
            <tr><td colspan="4" style="text-align:center;background: #ddd;padding: 10px;font-size: 16px;font-weight: bold;">விலைப்பட்டியல்</td></tr>
            <tr>
                <td width="20%" style="text-align: right;font-weight: bold;">வாடிக்கையாளர் : </td>
                <td width="30%"><?php echo $sales_order[0]['vCustomerName']; ?></td>
                <td width="15%" style="text-align: right;font-weight: bold;">விலைப்பட்டியல் எண் :</td>
                <td width="35%"><?php echo $sales_order[0]['vSalesOrderNo']; ?></td>
            </tr>
            <tr>
                <td style="text-align: right;font-weight: bold;">முகவரி  : </td>
                <td><?php echo $sales_order[0]['vAddress'] ?></td>
                <td style="text-align: right;font-weight: bold;">அனுப்பப்பட்ட முகவரி  : </td>
                <td width="20%"><?php echo (!empty($sales_order[0]['vShippingAddress']))?$sales_order[0]['vShippingAddress']:$sales_order[0]['vAddress']; ?></td>
            </tr>
            <tr>
            <td style="text-align: right;font-weight: bold;">விலைப்பட்டியல் தேதி :</td>
                <td><?php echo date("d-m-Y",strtotime($sales_order[0]['dOrderedDate'])) ?></td>
                <td style="text-align: right;font-weight: bold;">தொலைபேசி எண் : </td>
                <td><?php echo $sales_order[0]['vPhoneNumber'] ?></td>
                <!-- <td style="text-align: right;font-weight: bold;">குறிப்பு எண் :</td>
                <td>1234</td> -->
            </tr>
            <tr>
                <td style="text-align: right;font-weight: bold;">மாநிலம் : </td>
                <td>தமிழ்நாடு</td>
                <td class="d-none state_id" style="display:none;"><?php echo $sales_order[0]['iStateId'] ?></td>
                <td></td>
            </tr>
            <?php } if(!empty($sales_order[0]['gst_no'])){ ?>
            <tr>
                <td style="text-align: right;font-weight: bold;">GSTIN : </td>
                <td><?php echo $sales_order[0]['gst_no']; ?></td>
                <td style="text-align: right;font-weight: bold;"></td>
                <td></td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<table class="product-list" id="product-list" cellpadding ="0" cellspacing="0">
    <thead>
        <?php if($language == "english"){ ?>
            <tr>
                <th width="5%">S.No</th>
                <th width="40%">Particulars</th>
                <th width="10%">HSN</th>
                <th width="15%">Qty</th>
                <th width="10%">Rate</th>
                <?php if($sales_order[0]['iStateId']==2){ ?>
                <th width="10%">GST%</th>
               <?php } else {?>
                <th width="10%">IGST%</th>
                <?php } ?>
                <th width="10%">Amount</th>
            </tr>
        <?php } ?>
        <?php if($language == "tamil"){ ?>
            <tr>
                <th width="5%">S.No</th>
                <th width="40%">விவரங்கள்</th>
                <th width="10%">HSN</th>
                <th width="15%">அளவு</th>
                <th width="10%">விகிதம்</th>
                <?php if($sales_order[0]['iStateId']==2){ ?>
                <th width="10%">GST%</th>
               <?php } else {?>
                <th width="10%">IGST%</th>
                <?php } ?>
                <th width="10%">தொகை</th>
            </tr>
        <?php } ?>
    </thead>
    <tbody>
        <?php $i=1; foreach($sales_order[0]['sales_details'] as $key=>$order){ ?>
            
            <tr data-id="<?php echo $i; ?>">
                <td class="text-center"><?php echo $i; ?></td>
                <?php if($language == "english"){ ?>
                <td><?php echo $order['vProductName']; ?> - <?php echo $order['vProductUnitName']; ?></td>
                <?php }if($language == "tamil"){ 
                    if(!empty($order['vProductName_Tamil'] && $order['vProductUnitName_Tamil'])){ ?>
                <td><?php echo $order['vProductName_Tamil']; ?> - <?php echo $order['vProductUnitName_Tamil']; ?></td>
                <?php }else{ ?> 
                    <td><?php echo $order['vProductName']; ?> - <?php echo $order['vProductUnitName']; ?></td>
                <?php } } ?>
                <td class="text-center"><?php echo $order['vHSNNO']; ?></td>
                <td class="text-center quantity"><?php echo $order['iDeliveryQTY'] ?></td>
                <td class="text-right price"><?php echo $order['iDeliveryCostperQTY'] ?></td>
                <td class="text-right igst"> 5.00</td>
                <td class="text-right net_value"><?php echo $order['iDeliverySubTotal']?></td>
            </tr>
        <?php $i++; } ?>
        </tbody>
    </table>
    <table class="total product-list" cellpadding ="0" cellspacing="0">
        <tbody>
            <tr>
                <td width="15%" rowspan="6"></td>
                <!-- <?php if($language == "english"){ ?>
                    <td rowspan="2">Taxable Amount</td>
                <?php } ?>
                <?php if($language == "tamil"){ ?>
                    <td rowspan="2">வரிக்குரிய தொகை</td>
                <?php } ?> -->
                <!-- <td colspan="2">CGST</td>
                <td colspan="2">SGST</td>
                <td colspan="2">IGST</td> -->
                <?php if($language == "english"){ ?>
                    <td class="text-right">Taxable Amt : </td>
                <?php } ?>
                    <?php if($language == "tamil"){ ?>
                    <td class="text-right">வரிக்குரிய தொகை : </td>
                <?php } ?>
                <td class="text-right taxable_price"></td>
            </tr>
            <tr>
                <?php if($language == "english"){ ?>
                    <!-- <td>%</td>
                    <td>Amount</td>
                    <td>%</td>
                    <td>Amount</td>
                    <td>%</td>
                    <td>Amount</td> -->
                    <td class="text-right">Additional Charge :</td> 
                    <td class="text-right additional_charge"><?php echo $sales_order[0]['fAdditionalCharge']; ?></td>
                <?php } ?>
                <?php if($language == "tamil"){ ?>
                    <!-- <td>%</td>
                    <td>தொகை</td>
                    <td>%</td>
                    <td>தொகை</td>
                    <td>%</td>
                    <td>தொகை</td> -->
                    <td class="text-right">கூடுதல் கட்டணம் :</td>
                    <td class="text-right additional_charge"><?php echo $sales_order[0]['fAdditionalCharge']; ?></td>
                <?php } ?>
            </tr>
            <?php if($language == "english"){ ?>
            <tr>
                <td class="text-right">Total CGST :</td>
                <td class="text-right total_cgst"></td>
            </tr>
            <tr>
                <td class="text-right">Total SGST :</td>
                <td class="text-right total_sgst"></td>
            </tr>
            <tr>
                <td class="text-right">Total IGST :</td>
                <td class="text-right total_igst"></td>
            </tr>
            <?php }if($language == "tamil"){ ?>
                <tr>
                    <td class="text-right">மொத்த CGST விலை :</td>
                    <td class="text-right total_cgst"></td>
                </tr>
                <tr>
                    <td class="text-right">மொத்த SGST விலை :</td>
                    <td class="text-right total_sgst"></td>
                </tr>
                <tr>
                    <td class="text-right">மொத்த IGST விலை :</td>
                    <td class="text-right total_igst"></td>
                </tr>
            <?php } ?>
            <?php $j=1; foreach ($sales_order[0]['sales_details'] as $key => $order_) { ?>
                <tr data-id="<?php echo $j; ?>" style="display:none">
                    <!-- <td width="5%">28 %</td> -->
                    <td class="taxable_cost" width="10%" class="text-right">0.00</td>
                    <td class="cgst" width="5%"><?php echo $order_['CGST'] ?></td>
                    <td width="10%" class="text-right cgst_price">0.00</td>
                    <td class="sgst" width="5%"><?php echo $order_['SGST'] ?></td>
                    <td width="10%" class="sgst_price">0.0</td>
                    <td class="" width="5%" class="text-right"><?php echo $order_['IGST'] ?></td>
                    <td width="10%" class="text-right igst_price">0.00</td>
                    <td width="15%" class="text-right">Loading Chrg :</td>
                    <td width="10%" class="text-right">0.00</td>
                    <td width="10%" class="quantity_based_price">0.00</td>
                </tr>
            <?php $j++; } ?>
        </tbody>
    </table>
<table class="footer" cellpadding ="0" cellspacing="0">
    <tbody>
        <tr>
            <td width="50%" class="rupeesinwords"><b>Amount in Words : </b ></td>
            <?php if($language == "english"){ ?>
                <td width="30%" style="font-weight: bold;font-size: 20px;" class="text-right">Net Amt :</td>
                <td width="20%" style="font-weight: bold;font-size: 20px;" class="text-right final_amt"></td>
            <?php }if($language == "tamil"){ ?>
                <td width="30%" style="font-weight: bold;font-size: 20px;" class="text-right">நிகர தொகை :</td>
                <td width="20%" style="font-weight: bold;font-size: 20px;" class="text-right final_amt"></td>
            <?php } ?>
        </tr>
    </tbody>
</table>
<table class="footer-sign" cellpadding ="0" cellspacing="0">
    <tr>
        <?php if($language == "english"){ ?>
        <td width="33.33%" class="text-start">
            COOL IN COOL STORES<br><br>
            <p class="fw-300">AC NO: 7151909524<br>
            IFSC CODE: IDIB000P250<br>
            BRANCH : PERUNGUDI(2862) KAVALKINARU JUNCTION<br>
            INDIAN BANK</p>
        </td>
        <td width="33.33%">Customer Signature </td>
        <td width="33.33%" style="text-align: right;">For COOL IN COOL STORE <br><br><br><br><br><br>Authorised Signatory</td>
        <?php } ?>
        <?php if($language == "tamil"){ ?>
        <td width="33.33%" class="text-start">
        கூல் இன் கூல் கடைகள்<br><br>
        <p class="fw-300">AC எண்: 7151909524<br>
        IFSC குறியீடு: IDIB000P250<br>
        கிளை : பெருங்குடி(2862) காவல்கிணறு சந்திப்பு<br>
        இந்தியன் வங்கி</p>
        </td>
        <td width="33.33%">வாடிக்கையாளர் கையொப்பம் </td>
        <td width="33.33%" style="text-align: right;">கூல் இன் கூல் ஆர்கானிக் உணவுகள்<br><br><br><br><br><br>அங்கீகரிக்கப்பட்ட கையொப்பமிட்டவர்</td>
        <?php } ?>
    </tr>
</table><br>
<div><a href="<?php echo base_url('order'); ?>"><button class="btn btn-danger">Back</button></a></div>
</body>
<script src="<?php echo $theme_path ?>/assets/js/jquery-3.5.1.min.js"></script>
<!-- <script src="assets/js/inword.js"></script>
<link href="assets/css/inword.css" rel="stylesheet" /> -->
<script>
    $(document).ready(function(){
        var i=0;
        calculate();
        function calculate(dataID,quantity=''){
            var final_qty = 0;
            var final_sub_total = 0;
            var total_gst_price = 0.00;
            var total_cgst_price = 0.00;
            var total_sgst_price = 0.00;
            var total_igst_price = 0.00;
            var state = $('.state_id').text();
            
            
            $('.product-list').find('.price').each(function(){
                i++;
                // var tr_ele = $(this).closest('tr').attr("data-id");
                var tr_ele = $('tr[data-id="'+i+'"]');
                var quantity = tr_ele.find('.quantity');
                // alert(quantity.text());
                var percost = tr_ele.find('.price');
                var per_cgst = tr_ele.find('.cgst');
                var per_sgst = tr_ele.find('.sgst');
                var per_igst =  tr_ele.find('.igst');
                var subtotal =  tr_ele.find('.net_value');
                var taxable_cost =  tr_ele.find('.taxable_cost');
                var quantity_based_price =  tr_ele.find('.quantity_based_price');
                if (Number(quantity.text()) != 0) {
                    // console.log(percost);
                    total = Number(quantity.text()) * Number(percost.text());
                    // $(this).closest('tr').find('.gross').val(tot);
                    // subtotal.text(tot.toFixed(2));
                    var total_cgst_per = Number(per_cgst.text());
                    var total_sgst_per = Number(per_sgst.text());
                    var total_igst_per = Number(per_igst.text());

                    var cgst_price = (Number(total) * Number(total_cgst_per / 100)).toFixed(2);
                    var sgst_price = (Number(total) * Number(total_sgst_per / 100)).toFixed(2);
                    var igst_price = (Number(total) * Number(total_igst_per / 100)).toFixed(2);

                    var qty_based_price = (Number(quantity.text())) * Number(percost.text()).toFixed(2);
                    quantity_based_price.text(qty_based_price);

                    var per_net_cost = Number(percost.text())*Number(quantity.text());
                    // var gst_price = per_net_cost -(per_net_cost*(100/(100+total_cgst_per+total_sgst_per)));
                    if(state==2){
                        var total_taxgst_per = total_cgst_per + total_sgst_per;
                        //var gst_price = (Number(cgst_price) + Number(sgst_price)).toFixed(2);
                        var gst_price = per_net_cost -(per_net_cost*(100/(100+total_cgst_per+total_sgst_per)));
                    }else{
                        //var gst_price = Number(igst_price).toFixed(2);
                        var gst_price = per_net_cost -(per_net_cost*(100/(100+total_igst_per)));
                    }
                    console.log(gst_price);
                    $(taxable_cost).text(((Number(quantity.text()) * Number(percost.text())) - Number(gst_price)).toFixed(2));
                    var taxable_cost_qty = (Number(percost.text()) - Number(gst_price)).toFixed(2);
                    tot = ((Number(quantity.text()) * Number(percost.text())) - Number(gst_price));
                    subtotal.text(tot.toFixed(2));

                    $(tr_ele).find('.cgst_price').text(cgst_price);
                    $(tr_ele).find('.sgst_price').text(sgst_price);
                    $(tr_ele).find('.igst_price').text(igst_price);

                    total_gst_price = (Number(total_gst_price) + Number(gst_price));
                    total_cgst_price = (Number(total_cgst_price) + Number(cgst_price));
                    total_sgst_price = (Number(total_sgst_price) + Number(sgst_price));
                    total_igst_price = (Number(total_igst_per) + Number(igst_price));
                    final_sub_total = final_sub_total + tot;
                    final_qty = final_qty + Number(quantity.val());

                } else {
                    subtotal.text('0.00');
                }
            });
            var sum = 0;

            $(".quantity").each(function(){

                sum += +$(this).val();

            });

            $('.total_net_qty').val(sum);

            var taxable_price = final_sub_total - Number(total_gst_price).toFixed(2);

            $('.taxable_price').text(taxable_price.toFixed(2));
            if(state==2){
            $('.total_cgst').text((total_gst_price/2).toFixed(2));
            $('.total_sgst').text((total_gst_price/2).toFixed(2));
            $('.total_igst').text(0.00.toFixed(2));
            }
            else{
            $('.total_cgst').text(0.00.toFixed(2));
            $('.total_sgst').text(0.00.toFixed(2));
            $('.total_igst').text(total_gst_price.toFixed(2));
            }

            $('.final_sub_total').text(final_sub_total.toFixed(2));

            var totaltax = $('.totaltax').text();

            $('.taxable_price').text(final_sub_total.toFixed(2));

                net_price = 0;

            $('.quantity_based_price').each(function(){

                net_price += +$(this).text();

            })

            var additional_charge = $('.additional_charge').text();
            net_price += +additional_charge;

            $('.final_amt').text(Number(net_price).toFixed(2));
            
            var words = numberToWords(net_price.toFixed(2));
            words = words.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

            $('.rupeesinwords').append(words);
            // $('.round_off').val(final_sub_total.toFixed(0));
        }
        // $(document).on('click','.cancel-button',function(){
            // history.back(); 
            // history.go(-1); 
        // });
        function numberToWords(number) {  
        var digit = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];  
        var elevenSeries = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];  
        var countingByTens = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];  
        var shortScale = ['', 'thousand', 'million', 'billion', 'trillion'];  
  
        number = number.toString(); number = number.replace(/[\, ]/g, ''); if (number != parseFloat(number)) return 'not a number'; var x = number.indexOf('.'); if (x == -1) x = number.length; if (x > 15) return 'too big'; var n = number.split(''); var str = ''; var sk = 0; for (var i = 0; i < x; i++) { if ((x - i) % 3 == 2) { if (n[i] == '1') { str += elevenSeries[Number(n[i + 1])] + ' '; i++; sk = 1; } else if (n[i] != 0) { str += countingByTens[n[i] - 2] + ' '; sk = 1; } } else if (n[i] != 0) { str += digit[n[i]] + ' '; if ((x - i) % 3 == 0) str += 'hundred '; sk = 1; } if ((x - i) % 3 == 1) { if (sk) str += shortScale[(x - i - 1) / 3] + ' '; sk = 0; } }  str = str.replace(/\number+/g, ' '); return str.trim() + " only";  
  
    }
    window.print();
    });
</script>

