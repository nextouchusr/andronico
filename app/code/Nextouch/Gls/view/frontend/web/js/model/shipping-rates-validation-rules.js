define([], function () {
        'use strict';

        return {
            getRules: function () {
                return {
                    'country_id': {
                        'required': true
                    },
                    'region_id': {
                        'required': true
                    },
                    'city': {
                        'required': true
                    },
                    'postcode': {
                        'required': true
                    }
                };
            }
        };
    }
)
