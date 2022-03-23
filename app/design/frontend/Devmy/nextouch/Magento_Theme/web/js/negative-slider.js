define([
    'jquery',
    'matchMedia',
    'domReady!'
], function ($, mediaCheck) {
    'use strict';

    return function(config, element)  {
        mediaCheck({
            media: '(min-width: 768px)',
            entry: $.proxy(function () {
                if(!config.onlyMobile) {
                    let offsetSlider = $(element).offset().top * (-1);
                    $(element).css('margin-top', offsetSlider);
                    console.log
                }
            }, this),
            exit: $.proxy(function () {
                let offsetSlider = $(element).offset().top * (-1);
                $(element).css('margin-top', offsetSlider);
            }, this)
        });
    };


});
