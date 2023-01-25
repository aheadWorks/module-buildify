define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Aheadworks_Buildify/js/adapter'
], function ($, alert, buildifyAdapter) {
    'use strict';

    return {

        /**
         * Retrieve templates
         * @param {Object} event
         */
        getTemplates: function (event) {
            var action = window.awBuildifyConfig.template.getUrl;

            this._sendAjax(action, {}, this._insertTemplate);
        },

        /**
         * Save template
         * @param {Object} event
         */
        saveTemplate: function (event) {
            var action = window.awBuildifyConfig.template.saveUrl,
                requestData = JSON.parse(event.data.detail) || {};

            requestData['editor'] = JSON.parse(requestData['editor']);

            this._sendAjax(action, requestData, this._insertTemplate, this._insertTemplate);
        },

        /**
         * Export template
         * @param {Object} event
         */
        exportTemplate: function (event) {
            var action = window.awBuildifyConfig.template.exportUrl,
                fullUrl
            ;

            fullUrl = action.replace(/\/$/, "") + '?' + $.param(event.data.detail);
            window.location.href = fullUrl;
        },

        /**
         * Import template
         * @param {Object} event
         */
        importTemplate: function (event) {
            var action = window.awBuildifyConfig.template.importUrl,
                requestData = {}
            ;

            requestData['encoded_content'] = event.data.detail.content;

            this._sendAjax(action, requestData, this._insertTemplates, this._insertTemplates);
        },

        /**
         * Delete template
         * @param {Object} event
         */
        deleteTemplate: function (event) {
            var action = window.awBuildifyConfig.template.deleteUrl,
                requestData = {template_id: event.data.detail.template_id};

            this._sendAjax(action, requestData);
        },

        /**
         * Import template to editor
         * @param {Object} event
         */
        importTemplateToEditor: function (event) {
            var action = window.awBuildifyConfig.template.getContentUrl,
                requestData = {template_id: event.data.detail.template_id, builder_config: event.data.detail.object};

            this._sendAjax(action, requestData, this._importContent);
        },

        _insertTemplate: function (response) {
            buildifyAdapter.insertTemplate(response);
        },

        _insertTemplates: function (response) {
            buildifyAdapter.insertTemplates(response);
        },

        _importContent: function (response) {
            buildifyAdapter.importTemplateToEditorResponse(response.content);
        },

        _sendAjax: function(action, requestData, callbackSuccess, callbackError) {
            requestData['form_key'] = window.FORM_KEY;

            $.ajax({
                url: action,
                type: 'POST',
                dataType: 'json',
                data: requestData,

                /**
                 * Before send callback
                 */
                beforeSend: function() {
                    $('body').trigger('processStart');
                },

                /**
                 * Success callback.
                 * @param {Object} response
                 * @returns {Boolean}
                 */
                success: function(response) {
                    if (response.error) {
                        if (callbackError) {
                            callbackError(response);
                        }
                        alert({ content: response.message });
                    } else {
                        if (callbackSuccess) {
                            callbackSuccess(response);
                        }
                    }
                },

                /**
                 * Complete callback
                 */
                complete: function () {
                    $('body').trigger('processStop');
                }
            });
        }
    }
});