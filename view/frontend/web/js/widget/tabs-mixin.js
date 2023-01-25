define([
    'jquery'
], function ($) {

    return function (widget) {
        $.widget('mage.tabs', widget, {
            _create: function () {
                $('.product.data.items #description #buildify').show();
                this._super();
            }
        });

        return $.mage.tabs;
    };
});