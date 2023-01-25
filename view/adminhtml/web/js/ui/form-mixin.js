define([
    'jquery',
    'underscore',
    'Aheadworks_Buildify/js/adapter/form',
    'Aheadworks_Buildify/js/adapter/form/form-use-native-behaviour-flag'
], function ($, _, formAdapter, useNativeBehaviour) {
    'use strict';

    return function (renderer) {

        return renderer.extend({
            /**
             * Get shipping address
             * @returns {Object}
             */
            save: function (redirect, data) {
                if (useNativeBehaviour()
                    || !formAdapter.isAvailableComponent(this.componentType)
                    || typeof BuildifyClient === 'undefined'
                    || !BuildifyClient.options.isInited
                ) {
                    return this._super(redirect, data);
                } else {
                    if (!data && this.source.data.action) {
                        data = this.source.data.action;
                    }
                    formAdapter.getBuilderObject(this, 'save', [redirect, data]);
                }
            },
        });
    }
});
