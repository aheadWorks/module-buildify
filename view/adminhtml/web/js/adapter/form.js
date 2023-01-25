define([
    'jquery',
    'underscore',
    'Aheadworks_Buildify/js/adapter',
    'Aheadworks_Buildify/js/adapter/form/form-use-native-behaviour-flag',
], function ($, _, buildifyAdapter, useNativeBehaviour) {
    'use strict';

    return {
        _queue: {},

        isAvailableComponent: function (componentType) {
            return window.awBuildifyConfig
                && window.awBuildifyConfig.enabledComponentTypes.indexOf(componentType) !== -1;
        },

        getBuilderObject: function (formObject, callbackName, callbackArgs) {
            var index = 'getBuildifyObject';

            this._queue[index] = {
                formObject: formObject,
                actions: [{method: callbackName, args: callbackArgs}]
            };

            buildifyAdapter.getBuilderObject();
        },

        getBuilderObjectCallback: function (event) {
            var detail = JSON.parse(event.data.detail),
                extensionAttributesKey = event.data.extension_attributes_key,
                queue = this._queue['getBuildifyObject'],
                formObject = queue['formObject'],
                actions = queue['actions'];

            useNativeBehaviour(true);
            this._setBuildifyFormData(formObject, detail, extensionAttributesKey);
            _.each(actions, function (action) {
                formObject[action['method']].apply(formObject, action['args']);
            });
            useNativeBehaviour(false);
        },

        /**
         * @param {Object} formObject
         * @param {Object} data
         * @param {string} extensionAttributesKey
         * @private
         */
        _setBuildifyFormData: function (formObject, data, extensionAttributesKey) {
            var sourcePrefix = 'data.extension_attributes.aw_entity_fields.' + extensionAttributesKey + '.';

            formObject.source.set(sourcePrefix + 'editor_config', data.editor_config);
        }
    };
});
