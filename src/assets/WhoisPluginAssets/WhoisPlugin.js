/**
 * Created by tofid on 28.09.16.
 */
;(function ($, window, document, undefined) {
    var pluginName = "whois";
    var defaults = {
        domain: null,
        lookupUrl: 'lookup',
        finally: function (data) {
            console.log('Done!');
        },
        beforeQueryStart: function (item) {
            return true;
        }
    };

    function Plugin(element, options) {
        var _this = this;
        this.element = $(element);
        this.items = {};
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();

        return {
            startQuerier: function () {
                return _this.startQuerier();
            }
        };
    }

    Plugin.prototype = {
        init: function () {
            this.startQuerier();
        },
        startQuerier: function () {
            if (this.settings.beforeQueryStart()) {
                this.query();
            }
        },
        query: function () {
            var _this = this;
            var domain = this.settings.domain;

            if (!domain) return false;

            $.ajax({
                url: _this.settings.lookupUrl,
                dataType: 'html',
                type: 'POST',
                data: {
                    domain: domain
                },
                success: function (data) {
                    _this.element.html(data);
                    _this.settings.finally(data);
                }
            });

            return this;
        },
        finally: function (data) {
            
        }
    };

    $.fn[pluginName] = function (options) {
        if (!$(this).data("plugin_" + pluginName)) {
            $(this).data("plugin_" + pluginName, new Plugin(this, options));
        }

        return $(this).data("plugin_" + pluginName);
    };
})(jQuery, window, document);
