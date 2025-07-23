jQuery(document).ready(function($) {
jQuery.extend(jQuery.validator.messages, {
    required: frontend_ajax_object.required,
    remote: frontend_ajax_object.remote,
    email: frontend_ajax_object.email,
    maxlength: jQuery.validator.format(frontend_ajax_object.maxlength),
    max: jQuery.validator.format(frontend_ajax_object.max),
    min: jQuery.validator.format(frontend_ajax_object.min)
});

    var form = $("#voucher-multistep-form").show();
form.steps({
    headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Always allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex)
    {
        if(currentIndex === 1) {
            var template_id = $('input[name=template_id]:checked').val();
            $.ajax({
                url: frontend_ajax_object.ajaxurl,
                type: "POST",
                data: "action=wpgv_doajax_front_template&template_id="+template_id,
                success: function(data) {
                    $.each(data.images, function( key, value ) {
                        $(".voucherstyle"+(parseInt(key)+1)+" .cardImgTop img").attr('src', value);
                    });
                    console.log(data.images);
                    $('.voucherBottomDiv h2').html(data.title);
                    $('.wpgv-itemtitle').html(data.title);
                }
            });
        }
        if(currentIndex === 3) {
            $('.wizard>.actions a[href="#finish"]').hide();
            var link = $('.voucherPreviewButton button').data('src'),
                nonce = $('input[name=voucher_form_verify]').val(),
                templates_id = wpgv_b64EncodeUnicode($('input[name=template_id]:checked').val()),
                buying_for = wpgv_b64EncodeUnicode($('#buying_for').val()),
                forName = wpgv_b64EncodeUnicode($('#voucherForName').val()),
                fromName = wpgv_b64EncodeUnicode($('#voucherFromName').val()),
                voucherValue = wpgv_b64EncodeUnicode($itempricespan.html()),
                message = wpgv_b64EncodeUnicode($('#voucherMessage').val()),
                expiry = wpgv_b64EncodeUnicode($('.expiryCard').val()),
                code = wpgv_b64EncodeUnicode($('.codeCard').val()),
                style = wpgv_b64EncodeUnicode($('#chooseStyle').val()),
                fulllink = link+'?action=preview&nonce='+nonce+'&template='+templates_id+'&buying_for='+buying_for+'&for='+forName+'&from='+fromName+'&value='+voucherValue+'&message='+message+'&expiry='+expiry+'&code='+code+'&style='+style;
            $('.voucherPreviewButton button').data('fullurl', fulllink);
        }
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex) {
        alert(frontend_ajax_object.submitted);
    },
    labels: {
        finish: frontend_ajax_object.finish,
        next: frontend_ajax_object.next,
        previous: frontend_ajax_object.previous,
    }
    }).validate({
    errorPlacement: function errorPlacement(error, element) { element.before(error); },
    rules: {
        template_id: {
            required: true,
        },
        acceptVoucherTerms: {
            required: true,
        },
    },
    messages: {
        template_id: {
            required: frontend_ajax_object.select_template
        },
        acceptVoucherTerms: {
            required: frontend_ajax_object.accept_terms,
        }
    }   
});
jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != $(param).val();
}, frontend_ajax_object.not_equal_email);

$('#chooseStyle').on('change', function() {
    $('.secondRightDiv').hide();
    $('.wizard>.content>.body.current').addClass('loading');
    setTimeout( function() {
        $('.wizard>.content>.body.current').removeClass('loading');
    }, 1000);
    $('.voucherstyle'+(parseInt($(this).val())+1)).show();
    
});
$('.sin-template label').click(function(){ $('.sin-template label').removeClass('selectImage'); $(this).addClass('selectImage'); });
    var $autoyourname = $('#autoyourname'),
        $wpgv_total_price = $('#total_price'),
        $website_commission_price = $('#website_commission_price'),
        $shipping = $('#shipping'),
        $shippingbox = $('.order_details_preview .wpgv_shipping_box'),
        $totalbox = $('.order_details_preview .wpgv_total_box'),
        $itempricespan = $("#itemprice span"),
        $shippingpricespan = $("#shippingprice span"),
        $voucherPaymentButtonSpan = $("#voucherPaymentButton span"),
        $totalpricespan = $("#totalprice span");
$('#voucherForName').on('input blur', function() {
    var dInput = this.value;
    $(".forNameCard").val(dInput);
    $(".voucherYourNameInfo").html(dInput);
    $autoyourname.html(dInput);
});
$('#voucherFromName').on('input blur', function() {
    var dInput = this.value;
    $(".fromNameCard").val(dInput);
    $(".voucherReceiverInfo").html(dInput);
});
$('#voucherAmount').on('input blur', function() {
    var dInput = this.value,
        totalprice = parseFloat(dInput)+parseFloat($website_commission_price.data('price'));
    $(".voucherValueCard").val(dInput);
    $(".voucherAmountInfo span").html(dInput);
    $wpgv_total_price.val(totalprice);
    $itempricespan.html(dInput);
    $totalpricespan.html(totalprice);
    $voucherPaymentButtonSpan.html(totalprice);
});
$('#voucherMessage').on('input blur', function() {
    var dInput = this.value;
    $('.maxchar').html(frontend_ajax_object.total_character+": " + (this.value.length));
    $(".personalMessageCard").val(dInput);
    $(".voucherMessageInfo").html(dInput);
});
$('#voucherFirstName').on('input blur', function() {
    var dInput = this.value;
    $(".voucherFirstNameInfo").html(dInput);
});
$('#voucherLastName').on('input blur', function() {
    var dInput = this.value;
    $(".voucherLastNameInfo").html(dInput);
});
$('#voucherEmail').on('input blur', function() {
    var dInput = this.value;
    $(".voucherEmailInfo").html(dInput);
});
$('#voucherAddress').on('input blur', function() {
    var dInput = this.value;
    $(".voucherAddressInfo").html(dInput);
});
$('#voucherPincode').on('input blur', function() {
    var dInput = this.value;
    $(".voucherPincodeInfo").html(dInput);
});
$('#shipping_email').on('input blur', function() {
    var dInput = this.value;
    $(".voucherShippingEmailInfo").html(dInput);
});
$('#voucherPayment').on('change', function() {
    var dInput = this.value;
    $(".voucherPaymentInfo").html($('#voucherPayment option:selected').html());
});
$('#voucher-multistep-form.wizard>.steps a').on('click', function(e) {
    e.preventDefault();
    return false;
});
$(".voucherPaymentInfo").html($('#voucherPayment option:selected').html());
$('.codeCard').val(Math.floor(1000000000000000 + Math.random() * 9000000000000000));

    $('.buying-options div').click(function(e){
        $('.buying-options div').removeClass('selected');
        $(this).addClass('selected');
        $('#buying_for').val($(this).data('value'));
        if($(this).data('value') == 'yourself') {
            $('#voucherFromName').closest('.form-group').fadeOut();
            $('.nameFormRight').css('opacity', 0);
            $('.voucherReceiverInfo').closest('.half').hide();
            $('#shipping_email').closest('.form-group').addClass('mailhidden');
            $('.shippingasemail').addClass('mailhidden');
        } else {
            $('#voucherFromName').closest('.form-group').fadeIn();
            $('.voucherReceiverInfo').closest('.half').show();
            $('.nameFormRight').css('opacity', 1);
            $('#shipping_email').closest('.form-group').removeClass('mailhidden');
            $('.shippingasemail').removeClass('mailhidden');
        }
    });
    if($('#buying_for').val() == 'yourself') {
        $('#voucherFromName').closest('.form-group').fadeOut();
        $('.nameFormRight').css('opacity', 0);
        $('.voucherReceiverInfo').closest('.half').hide();
        $('#shipping_email').closest('.form-group').addClass('mailhidden');
        $('.shippingasemail').addClass('mailhidden');
    } else {
        $('#voucherFromName').closest('.form-group').fadeIn();
        $('.voucherReceiverInfo').closest('.half').show();
        $('.nameFormRight').css('opacity', 1);
        $('#shipping_email').closest('.form-group').removeClass('mailhidden');
        $('.shippingasemail').removeClass('mailhidden');
    }
    $('.shipping-options div').click(function(e){
        $('.shipping-options div').removeClass('selected');
        $(this).addClass('selected');
        $shipping.val($(this).data('value'));
        var $shipping_method_wrapper = $('#shipping_method').closest('.form-group');
        var $totalprice = 0;
        if($(this).data('value') == 'shipping_as_post') {
            $('#shipping_email').closest('.form-group').hide();
            $('.wpgv-post-data').show();
            $shippingbox.css('display', 'flex');
            $totalbox.css('display', 'flex');
            $shipping_method_wrapper.show();
            $shippingpricespan.html($shipping_method_wrapper.find(':nth-child(2)').data('value'));
            $shipping_method_wrapper.find(':nth-child(2) input').prop("checked", true);
            $totalprice = (parseFloat($itempricespan.html())+parseFloat($shippingpricespan.html())+parseFloat($website_commission_price.data('price')));
            $totalpricespan.html($totalprice);
            $voucherPaymentButtonSpan.html($totalprice);
            $wpgv_total_price.val($totalprice);
            // $('.voucherValueCard').val($totalprice);
            // $(".voucherAmountInfo span").html($totalprice);
            $('.shippingaspost').show();
            $('.shippingasemail').hide();
            $('.voucherShippingInfo').html(frontend_ajax_object.via_post);
            $('.voucherShippingMethodInfo').html($shipping_method_wrapper.find(':nth-child(2)').text());
        } else {
            $('#shipping_email').closest('.form-group').show();
            $('.wpgv-post-data').hide();
            $totalprice = (parseFloat($itempricespan.html())+parseFloat($website_commission_price.data('price')));
            $shipping_method_wrapper.hide();
            $shippingbox.hide();
            $totalpricespan.html($totalprice);
            // $totalbox.hide();
            $wpgv_total_price.val($totalprice);
            $voucherPaymentButtonSpan.html($totalprice);
            // $('.voucherValueCard').val($itempricespan.html());
            // $(".voucherAmountInfo span").html($itempricespan.html());
            $('.shippingaspost').hide();
            $('.shippingasemail').show();
            $('.voucherShippingInfo').html(frontend_ajax_object.via_email);
        }
    });

    $('input[name="shipping_method"]').change(function() {
        var $shippingprice = $(this).closest('label').data('value');
        var $totalprice = (parseFloat($itempricespan.html())+parseFloat($website_commission_price.data('price'))+parseFloat($shippingprice));
        $shippingpricespan.html($shippingprice);
        $totalpricespan.html($totalprice);
        $voucherPaymentButtonSpan.html($totalprice);
        $wpgv_total_price.val($totalprice);
        // $('.voucherValueCard').val($totalprice);
        // $(".voucherAmountInfo span").html($totalprice);
        $('.voucherShippingMethodInfo').html($(this).closest('label').text());
    });
    $('.voucherPreviewButton button').click(function(){
        var $url = $(this).data('fullurl');
        window.open($url, '_blank');
    });

$('#voucherPaymentButton').on('click', function() {

    if(!$('input[name=acceptVoucherTerms]').is(':checked')) {
        alert(frontend_ajax_object.accept_terms);
        return false;
    }

    var nonce = $('input[name=voucher_form_verify]').val(),
        templates_id = wpgv_b64EncodeUnicode($('input[name=template_id]:checked').val()),
        buying_for = wpgv_b64EncodeUnicode($('#buying_for').val()),
        forName = wpgv_b64EncodeUnicode($('#voucherForName').val()),
        fromName = wpgv_b64EncodeUnicode($('#voucherFromName').val()),
        voucherValue = wpgv_b64EncodeUnicode($itempricespan.html()),
        message = wpgv_b64EncodeUnicode($('#voucherMessage').val()),
        shipping = wpgv_b64EncodeUnicode($('#shipping').val()),
        shipping_email = wpgv_b64EncodeUnicode($('#shipping_email').val()),
        firstName = wpgv_b64EncodeUnicode($('#voucherFirstName').val()),
        lastName = wpgv_b64EncodeUnicode($('#voucherLastName').val()),
        email = wpgv_b64EncodeUnicode($('#voucherEmail').val()),
        address = wpgv_b64EncodeUnicode($('#voucherAddress').val()),
        pincode = wpgv_b64EncodeUnicode($('#voucherPincode').val()),
        shipping_method = wpgv_b64EncodeUnicode($('input[name=shipping_method]:checked').val()),
        paymentMethod = wpgv_b64EncodeUnicode($('#voucherPayment').val()),
        expiry = wpgv_b64EncodeUnicode($('.expiryCard').val()),
        style = wpgv_b64EncodeUnicode($('#chooseStyle').val()),
        code = wpgv_b64EncodeUnicode($('.codeCard').val());
    $('#voucher-multistep-form #voucherPaymentButton').addClass('clicked');
    $.ajax({
        url: frontend_ajax_object.ajaxurl,
        type: "POST",
        data: 'action=wpgv_doajax_voucher_pdf_save_func&nonce='+nonce+'&template='+templates_id+'&buying_for='+buying_for+'&for='+forName+'&from='+fromName+'&value='+voucherValue+'&message='+message+'&expiry='+expiry+'&code='+code+'&shipping='+shipping+'&shipping_email='+shipping_email+'&firstname='+firstName+'&lastname='+lastName+'&email='+email+'&address='+address+'&pincode='+pincode+'&shipping_method='+shipping_method+'&style='+style+'&paymentmethod='+paymentMethod,
        success: function(a) {
            if($('#voucherPayment').val() == 'Stripe') {
                $('body').append(a);
            } else {
                window.location.replace(a);
            }
        },
        error: function() {
            alert(frontend_ajax_object.error_occur);
            $('#voucher-multistep-form #voucherPaymentButton').removeClass('clicked');
        }
    });
});
$('#voucher-multistep-form').ajaxStart(function () { $('.wizard>.content>.body.current').addClass('loading'); })
           .ajaxStop(function () { $('.wizard>.content>.body.current').removeClass('loading'); });

    $('#voucherMessage').keydown(function(e) {
        newLines = $(this).val().split("\n").length;
        if(e.keyCode == 13 && newLines >= 3) {
            return false;
        }
    });
});

function wpgv_b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode(parseInt(p1, 16))
    }))
}
function wpgv_b64DecodeUnicode(str) {
    return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
    }).join(''))
}