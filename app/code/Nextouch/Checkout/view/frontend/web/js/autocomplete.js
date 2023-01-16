define([
    'jquery',
    'uiComponent',
    'ShipperHQ_AddressAutocomplete/js/google_maps_loader',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry'
], function (
    $,
    Component,
    GoogleMapsLoader,
    checkoutData,
    uiRegistry
) {
    const componentForm = {
        subpremise: 'short_name',
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'short_name',
        postal_code: 'short_name',
        postal_code_suffix: 'short_name',
        postal_town: 'short_name',
        sublocality_level_1: 'short_name'
    };

    const lookupElement = {
        street_number: 'street_1',
        route: 'street_2',
        locality: 'city',
        administrative_area_level_1: 'region',
        country: 'country_id',
        postal_code: 'postcode'
    };

    let googleMapError = false;
    window.gm_authFailure = function () {
        $('input[name^="street"]').prop('disabled', false).prop('placeholder', '').removeAttr("style")
        google.maps.event.clearInstanceListeners($('input[name^="street"]')[0]);
        $(".pac-container").remove();
        googleMapError = true;
    };

    // MNB-574 Some European countries place the house number after the street name rather than before.
    const numberAfterStreetCountries = ['AT', 'BE', 'DK', 'DE', 'GR', 'IS', 'IT', 'NL', 'NO', 'PT', 'ES', 'SE', 'CH'];
    let numberAfterStreet = false;

    GoogleMapsLoader.done(function () {
        const enabled = window.checkoutConfig.shipperhq_autocomplete.active;

        const geocoder = new google.maps.Geocoder();
        setTimeout(function () {
            if (!googleMapError) {
                if (enabled === '1') {
                    const domID = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street').elems()[0].uid;

                    const street = $('#' + domID);

                    // SHQ18-260.
                    const observer = new MutationObserver(function () {
                        observer.disconnect();
                        $("#" + domID).attr("autocomplete", "new-password");
                    });

                    street.each(function () {
                        const self = this;

                        observer.observe(self, {
                            attributes: true, attributeFilter: ['autocomplete']
                        });

                        autocomplete = new google.maps.places.Autocomplete((this), {types: ['geocode']});
                        autocomplete.addListener('place_changed', fillInAddress);
                    });
                    $('#' + domID).focus(geolocate);
                }//end if
            }//end if
        }, 5000);
    }).fail(function () {
        console.error("ERROR: Google maps library failed to load");
    });

    var fillInAddress = function () {
        document.querySelector('[name="region_id"]').value = '';

        let thisDomID;
        const place = autocomplete.getPlace();

        const street = [];
        let region = '';
        let streetNumber = '';
        let city = '';
        let postcode = '';
        let postcodeSuffix = '';

        for (let i = 0; i < place.address_components.length; i++) {
            const addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                const value = place.address_components[i][componentForm[addressType]];
                if (addressType === 'subpremise') {
                    streetNumber = value + '/';
                } else if (addressType === 'street_number') {
                    streetNumber = streetNumber + value;
                } else if (addressType === 'route') {
                    street[1] = value;
                } else if (addressType === 'administrative_area_level_1') {
                    region = value;
                } else if (addressType === 'sublocality_level_1') {
                    city = value;
                } else if (addressType === 'postal_town') {
                    city = value;
                } else if (addressType === 'locality' && (city === '' || value === 'Montréal')) {
                    // Ignore if we are using one of other city values already.
                    // MNB-2364 Google returns sublocality_level_1 for Montreal. Always want to use Montreal
                    city = value;
                } else if (addressType === 'postal_code') {
                    postcode = value;
                    thisDomID = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.postcode').uid;
                    if ($('#' + thisDomID).length) {
                        $('#' + thisDomID).val(postcode + postcodeSuffix);
                        $('#' + thisDomID).trigger('change');
                    }
                } else if (addressType === 'postal_code_suffix' && window.checkoutConfig.shipperhq_autocomplete.use_long_postcode === '1') {
                    postcodeSuffix = '-' + value;
                    thisDomID = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.postcode').uid;
                    if ($('#' + thisDomID).length) {
                        $('#' + thisDomID).val(postcode + postcodeSuffix);
                        $('#' + thisDomID).trigger('change');
                    }
                } else {
                    const elementId = lookupElement[addressType];
                    if (elementId !== undefined) {
                        thisDomID = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.' + elementId).uid;
                        if ($('#' + thisDomID).length) {
                            $('#' + thisDomID).val(value);
                            $('#' + thisDomID).trigger('change');
                        }

                        if (elementId === 'country_id') {
                            numberAfterStreet = numberAfterStreetCountries.includes(value);
                        }
                    }
                }//end if
            }//end if
        }//end for

        if (street.length > 0) {
            if (numberAfterStreet) {
                street[0] = street[1];
                street[1] = streetNumber;
            } else {
                street[0] = streetNumber;
            }

            const domID = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street').elems()[0].uid;
            const streetString = street.join(' ');
            if ($('#' + domID).length) {
                $('#' + domID).val(streetString);
                $('#' + domID).trigger('change');
            }
        }

        const cityDomID = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city').uid;
        if ($('#' + cityDomID).length) {
            $('#' + cityDomID).val(city);
            $('#' + cityDomID).trigger('change');
        }

        if (region !== '') {
            // MNB-1966 AutoComplete does not fill in Quebec field when an accent mark is returned from Google.
            if (region === 'Québec') {
                region = 'Quebec'
            }

            if (uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id')) {
                var regionDomId = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id').uid;
                if ($('#' + regionDomId).length) {
                    // Search for and select region using text.
                    $('#' + regionDomId + ' option')
                        .filter(function () {
                            return $.trim($(this).text()) == region;
                        })
                        .attr('selected', true);
                    $('#' + regionDomId).trigger('change');
                }
            }

            if (uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id_input')) {
                var regionDomId = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id_input').uid;
                if ($('#' + regionDomId).length) {
                    $('#' + regionDomId).val(region);
                    $('#' + regionDomId).trigger('change');
                }
            }
        }//end if
    }

    geolocate = function () {
        if (navigator.geolocation && window.checkoutConfig.shipperhq_autocomplete.use_geolocation === '1') {
            navigator.geolocation.getCurrentPosition(function (position) {
                const geolocation = {
                    lat: position.coords.latitude, lng: position.coords.longitude
                };
                const circle = new google.maps.Circle({
                    center: geolocation, radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
    return Component;
});
