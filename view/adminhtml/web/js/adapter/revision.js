define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Aheadworks_Buildify/js/adapter'
], function ($, alert, buildifyAdapter) {
    'use strict';

    return {
        /**
         * Import template to editor
         * @param {Object} event
         */
        importRevisionToEditor: function (event) {
            var action = window.awBuildifyConfig.revision.getContentUrl,
                requestData = {revision_id: event.data.detail.revision_id, builder_config: event.data.detail.object};

            this._sendAjax(action, requestData, this._importContent);
        },

        /**
         * Delete template
         * @param {Object} event
         */
        deleteRevision: function (event) {
            var action = window.awBuildifyConfig.revision.deleteUrl,
                requestData = {revision_id: event.data.detail.revision_id};

            this._sendAjax(action, requestData);
        },

        _importContent: function (response) {
            buildifyAdapter.importRevisionToEditorResponse(response.content);
        },

        _sendAjax: function(action, requestData, callback) {
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
                        alert({ content: response.message });
                    } else {
                        if (callback) {
                            callback(response);
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
