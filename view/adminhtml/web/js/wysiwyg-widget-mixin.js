define([
    'Aheadworks_Buildify/js/adapter/widget/wysiwyg-exists-disable-flag',
    'mage/utils/wrapper'
], function (wysiwygExistsDisableFlag, wrapper) {
    'use strict';

    return function () {
        var wywygWdgtPrtype = WysiwygWidget.Widget.prototype;

        wywygWdgtPrtype.loadOptions = wrapper.wrap(wywygWdgtPrtype.loadOptions, function (proceed) {
            wysiwygExistsDisableFlag(false);
            proceed();
        });

        wywygWdgtPrtype.wysiwygExists = wrapper.wrap(wywygWdgtPrtype.wysiwygExists, function (proceed) {
            if (wysiwygExistsDisableFlag()) {
                return true;
            }
            return proceed();
        });
    }
});
