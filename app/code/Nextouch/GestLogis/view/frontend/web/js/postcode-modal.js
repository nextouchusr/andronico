define([
    "jquery", 
    "Magento_Ui/js/modal/modal",
    "mage/translate"
], function($, modal, $t) {
    var formKey = $("input[name=form_key]").val();

    var PostcodeModal = {
        initModal: function(config) {
            var $target = $(config.target);
            $target.modal({
                autoOpen: false,
                responsive: true,
                innerScroll: true,
                clickableOverlay: true,
                title: $t('Check the area served'),
                modalClass: 'postcode-modal-class',
                buttons: []
            });

            $(".ship-availability-checker").on("click", function () {
                $target.modal("openModal");
            });

            if(!$(".shipping-dynamic-price").hasClass('price-available')) {
                $(".shipping-dynamic-price").on("click", function () {
                    $target.modal("openModal");
                });
            }

            // if (localStorage.getItem("postcodeState") != "shown") {
            //     $target.modal("openModal");
            // }
        }
    };

    var postcode = 0;
    $(document).on('keyup', '.postcode-content input.postcode-input-checker', function() {
        postcode = $(this).val();
        if(postcode.length >= 1) {
            $('.postcode-content button.check-postcode-btn').attr('disabled', false);
        } else {
            $('.postcode-content button.check-postcode-btn').attr('disabled', true);
        }
    });

    $(document).on('click', '.postcode-content button.check-postcode-btn', function() {
        $(".availability-validation").hide();
        var attributeSetId = window.attributeSetId;
        var productId = window.productId;
        $.ajax({
            url: window.saveShippingPriceAjaxUrl,
            showLoader: true,
            data: {
                form_key: formKey,
                postcode: postcode,
                attribute_set_id: attributeSetId,
                product_id: productId
            },
            dataType: 'json',
            success: function (response) {
                if(response.success == true) {
                    localStorage.setItem("postcodeState", "shown");
                    $('.postcode-modal-class .action-close').attr('disabled', false);
                    $("body").trigger("processStart");
                    location.reload();
                } else {
                    $(".availability-validation").show();
                    $(".availability-validation").text(response.message);
                    $(".availability-validation").css('color', 'red');
                }
            }
        });
    });

    return PostcodeModal;
});