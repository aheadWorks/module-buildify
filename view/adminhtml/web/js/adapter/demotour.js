define([
    'Aheadworks_Buildify/js/adapter',
], function () {
    'use strict';

    return {
        demotourEnd: function () {
            if (typeof BuildifyClient !== 'undefined' && BuildifyClient.options.isInited) {
                BuildifyClient.updateDemotour(window.awBuildifyConfig.demotour);
            }
        }
    };
});
