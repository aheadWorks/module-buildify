define([
    'jquery',
    'underscore'
], function ($, _) {
    'use strict';

    return {

        /**
         * Setup buildify client
         */
        setup: function (config) {
            require(['buildifyClient'], function () {
                if (typeof BuildifyClient !== 'undefined') {
                    BuildifyClient.init(config);
                }
            }, function () {
                console.log('There was an error during loading client file');
            });
        },

        /**
         * Insert image
         *
         * @param {Object} imgDetails
         */
        insertImage: function (imgDetails) {
            BuildifyClient.insertImage(imgDetails);
        },

        /**
         * Insert template
         *
         * @param {Object} templateDetails
         */
        insertTemplate: function (templateDetails) {
            BuildifyClient.insertTemplate(templateDetails);
        },

        /**
         * Insert templates
         */
        insertTemplates: function (templateDetails) {
            BuildifyClient.insertTemplates(templateDetails);
        },

        /**
         * Insert template to editor response
         *
         * @param {Object} templateDetails
         */
        importTemplateToEditorResponse: function (templateDetails) {
            BuildifyClient.importTemplateToEditorResponse(templateDetails);
        },

        /**
         * Insert revision to editor response
         *
         * @param {Object} revisionDetails
         */
        importRevisionToEditorResponse: function (revisionDetails) {
            BuildifyClient.importRevisionToEditorResponse(revisionDetails);
        },

        /**
         * Retrieve builder object
         */
        getBuilderObject: function () {
            BuildifyClient.getBuilderObject();
        },

        /**
         * Insert widget
         *
         * @param {Object} wgtDetails
         */
        insertWidget: function (wgtDetails) {
            BuildifyClient.insertWidget(wgtDetails);
        },

        changeDesign: function (themeCode) {
            if (typeof BuildifyClient === 'undefined') {
                return;
            }

            if (!BuildifyClient.options.isInited) {
                BuildifyClient.options.preInitedbuilderFrameParams.theme = themeCode;
                return;
            }

            var designDetails = {
                themeCode: themeCode
            };
            BuildifyClient.changeDesign(designDetails);
        },
    };
});
