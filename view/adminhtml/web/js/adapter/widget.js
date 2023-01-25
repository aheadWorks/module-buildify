define([
    'jquery',
    'underscore',
    'uiRegistry',
    'wysiwygAdapter',
    'Aheadworks_Buildify/js/adapter/widget/wysiwyg-exists-disable-flag',
    'Aheadworks_Buildify/js/adapter',
    'mage/adminhtml/wysiwyg/widget'
], function ($, _, registry, wysiwyg, wysiwygExistsDisableFlag, buildifyAdapter) {
    'use strict';

    var wgtObject,
        initiatorId,
        countOn = 0;

    function encodeWidgets (content) {
        var imageData = {};

        content.gsub(/\{\{widget(.*?)\}\}/i, function (match) {
            var config = window.awBuildifyConfig.widget,
                attributes = wysiwyg.parseAttributesString(match[1]);

            if (attributes.type) {
                attributes.type = attributes.type.replace(/\\\\/g, '\\');

                imageData.id = Base64.idEncode(match[0]);
                imageData.imageSrc = config.placeholders[attributes.type];

                if (config.types[attributes.type]) {
                    imageData.type = config.types[attributes.type];
                }
            }
        });

        return imageData;
    }

    registry.get("buildify_form.buildify_form.general.widget", function (element) {
        wgtObject = element;

        element.on('value', function (value) {
            var newValue = {};

            countOn++;
            if (countOn === 1 && value) {
                newValue = {
                    initiator: initiatorId,
                    data: {
                        preview: encodeWidgets(value),
                        content: value
                    }
                };
                buildifyAdapter.insertWidget(newValue);
            } else if (countOn === 2) {
                countOn = 0;
            }
        });
    });

    return {

        widgetModalOpen: function (event) {
            var url = window.awBuildifyConfig.widget.openModalUrl + 'widget_target_id/' /*+ 'buildify_form_widget_wysiwyg/', */+ wgtObject.uid + '/',
                encodedValue = event.data.encodedValue;

            wgtObject.clear();
            if (encodedValue) {
                var element = document.createElement('img');
                element.id = encodedValue;

                widgetTools.setActiveSelectedNode(element);
                widgetTools.setEditMode(true);
                wysiwygExistsDisableFlag(true);
            } else {
                widgetTools.setActiveSelectedNode(null);
                widgetTools.setEditMode(false);
                wysiwygExistsDisableFlag(false);
            }
            widgetTools.openDialog(url);
            initiatorId = event.data.initiator;
        }
    }
});