define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Aheadworks_Buildify/js/adapter',
], function ($, _, registry, buildifyAdapter) {
    'use strict';

    var imgObject,
        initiatorId,
        countOn = 0;

    registry.get("buildify_form.buildify_form.general.image", function (element) {
        imgObject = element;

        element.on('value', function (value) {
            var newValue = {};

            value = _.isArray(value) ? value[0] : value;

            countOn++;
            if (countOn === 1) {
                newValue = {
                    initiator: initiatorId,
                    file: {
                        path: value.url,
                        filename: value.name
                    }
                };
                buildifyAdapter.insertImage(newValue);
            } else if (countOn === 2) {
                countOn = 0;
            }
        });
    });

    return {

        /**
         * File manager open
         *
         * @param event
         */
        mediaFileManagerOpen: function (event) {
            $('#' + imgObject.mediaGalleryUid).click();
            initiatorId = event.data.initiator;
        }
    }
});