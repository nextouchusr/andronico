var config = {
    deps: [
        'Magento_Theme/js/theme',
    ],
    map: {
        "*": {
            'negativeSlider': 'Magento_Theme/js/negative-slider',
            'initSlick': 'Magento_Theme/js/init-slick',
            'scrollDecisionTree': "Magento_Theme/js/scroll-decision-tree"
        }
    },
    paths: {
        'negativeSlider': 'Magento_Theme/js/negative-slider',
        'initSlick': 'Magento_Theme/js/init-slick'
    },
    shim: {
        'negativeSlider': {
            'deps': ['jquery']
        }
    },
    config: {
        mixins: {
            'mage/collapsible': {
                'Magento_Theme/js/mage/collapsible-mixin': true
            }
        }
    }
};
