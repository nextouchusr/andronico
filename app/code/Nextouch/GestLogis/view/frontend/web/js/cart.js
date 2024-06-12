require([
    'jquery',
], function ($) {
    'use strict';

    const hidePostcode = function() {
        var intervalForHidePostcode = setInterval(()=>{
            let shippingPostcode = $('div[name="shippingAddress.postcode"]')
            if(shippingPostcode.length > 0){
                clearInterval(intervalForHidePostcode)
                $('div[name="shippingAddress.postcode"]').hide()
            }
        }, 1)
    }

    hidePostcode()  
});
