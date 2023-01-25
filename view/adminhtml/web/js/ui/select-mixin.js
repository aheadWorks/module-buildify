define([
    'Aheadworks_Buildify/js/adapter',
], function (buildifyAdapter) {
    'use strict';

    var availableIndexes = ['custom_theme', 'custom_design'];

    return function (renderer) {
        return renderer.extend({
            /**
             * Update preview if theme changes
             */
            onUpdate: function (value) {
                if (availableIndexes.indexOf(this.index) !== -1) {
                    buildifyAdapter.changeDesign(value);
                }

                return this._super(value);
            },
        });
    }
});
