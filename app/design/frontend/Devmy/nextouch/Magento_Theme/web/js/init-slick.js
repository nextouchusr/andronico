define([
    'jquery',
    'slick',
    'domReady!'
], function ($) {
    'use strict';

    return function(config, element)  {
        $(element).slick(config);
    };


});
