/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/smart-keyboard-handler',
    'select2',
    'mage/mage',
    'mage/ie-class-fixer',
    'domReady!'
], function ($, keyboardHandler, s2) {
    'use strict';

    $.fn.select2.defaults.set('minimumResultsForSearch', 'Infinity');
    $.fn.select2.defaults.set('cache', false);

    if ($('body').hasClass('checkout-cart-index')) {
        if ($('#co-shipping-method-form .fieldset.rates').length > 0 && $('#co-shipping-method-form .fieldset.rates :checked').length === 0) {
            $('#block-shipping').on('collapsiblecreate', function () {
                $('#block-shipping').collapsible('forceActivate');
            });
        }
    }

    $('.cart-summary').mage('sticky', {
        container: '#maincontent'
    });

    $('.panel.header > .header.links').clone().appendTo('#store\\.links');

    keyboardHandler.apply();

    $('.product-item-photo')
            .on('mouseenter', function() {
                var el = $('[altimg]', this),
                    swatch = $('.swatch-tri-wrap', this),
                    imgSrc = el.attr('srcimg'),
                    altSrc = el.attr('altimg');

                if ( el.length > 0 && altSrc ) {
                    el.attr('src', altSrc);
                    swatch.addClass('hidden');
                }
            })
            .on('mouseleave', function() {
                var el = $('[altimg]', this),
                    swatch = $('.swatch-tri-wrap', this),
                    imgSrc = el.attr('srcimg'),
                    altSrc = el.attr('altimg');

                if ( el.length > 0 && altSrc ) {
                    el.attr('src', imgSrc);
                    swatch.removeClass('hidden');
                }
            });
});