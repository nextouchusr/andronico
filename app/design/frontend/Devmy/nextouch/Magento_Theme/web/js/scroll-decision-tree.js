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
                    $(element).css('height',$(element).innerHeight);
                    $(window).on('scroll', function() {
                        var initialPos = $(element).offset().top;
                        if($(window).scrollTop() > (initialPos - 260)) {
                            $(element).find('.decision-tree-btn').addClass('stickyTree');
                        }
                        else {
                            $(element).find('.decision-tree-btn').removeClass('stickyTree');
                        }
                    });
            }, this),
            exit: $.proxy(function () {

            }, this)
        });
    };


});
