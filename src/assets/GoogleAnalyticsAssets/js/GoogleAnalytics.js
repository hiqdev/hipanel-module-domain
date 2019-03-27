;(function ($, window, document, undefined) {
    'use strict';

    let pluginName = "googleAnalytics";

    function Plugin(element, options) {
        console.log('plugin works');

        element.on('click', () => {
            ga('send', 'event', options.category, options.action);
        })
    }

    $.fn[pluginName] = function (options) {
        if (!$(this).data('plugin_' + pluginName)) {
            $(this).data('plugin_' + pluginName, new Plugin(this, options));
        }

        return $(this).data('plugin_' + pluginName);
    };
})(jQuery, window, document);
