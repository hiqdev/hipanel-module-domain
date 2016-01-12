;(function ($, window, document, undefined) {
    var pluginName = "domainsCheck";
    var defaults = {
        domainRowClass: ".check-item",
        finally: function () {
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
        this.requests = {};
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
            this.items = this.element.find(this.settings.domainRowClass);
            setTimeout(function () {
                this.startQuerier();
            }.bind(this), 500);
        },
        startQuerier: function () {
            var _this = this;
            if (this.items) {
                $.each(this.items, function (k, item) {
                    if (_this.settings.beforeQueryStart(item)) {
                        this.query(item);
                    }
                }.bind(this));
            }
        },
        registerRequest: function (domain, item) {
            this.requests[domain] = {domain: domain, state: 'progress', item: item};
        },
        registerFinish: function (domain) {
            this.requests[domain]['state'] = 'finished';

            if (this.registerCheckAll()) {
                return this.settings.finally();
            } else {
                return false;
            }
        },
        registerCheckAll: function () {
            if (this.requests.length == 0) return false;

            var all_closed = true;
            $.each(this.requests, function (domain, v) {
                if (v.state == 'progress') all_closed = false;
            });

            return all_closed;
        },
        query: function (item) {
            var domain = $(item).data('domain');

            if (!domain) return false;

            $.ajax({
                url: "check",
                dataType: 'html',
                type: 'POST',
                beforeSend: function () {
                    this.registerRequest(domain);
                }.bind(this),
                data: {domain: domain},
                success: function (data) {
                    this.registerFinish(domain);
                    return this.settings.success(data, domain, this.element);
                }.bind(this)
            });

            return this;
        }
    };

    $.fn[pluginName] = function (options) {
        if (!$(this).data("plugin_" + pluginName)) {
            $(this).data("plugin_" + pluginName, new Plugin(this, options));
        }

        return $(this).data("plugin_" + pluginName);
    };
})(jQuery, window, document);
