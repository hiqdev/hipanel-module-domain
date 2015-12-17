;(function ($, window, document, undefined) {
    var pluginName = "domainsCheck",
        defaults = {
            domainRowClass: ".check-item",
            finally: function() {
                console.log('all done, sir!');
            }
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.items = {};
        this.settings = $.extend({}, defaults, options);
        this.requests = {};
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            this.items = this.element.find(this.settings.domainRowClass);
            setTimeout(function () {this.startQuerier();}.bind(this), 500);
        },
        startQuerier: function () {
            if (this.items) {
                $.each(this.items, function(k, item) {
                    this.query(item);
                }.bind(this));
            }
        },
        registerRequest: function(domain) {
            this.requests[domain] = {domain: domain, state: 'progress'};
        },
        registerFinish: function(domain) {
            this.requests[domain]['state'] = 'finished';

            if (this.registerCheckAll()) {
                return this.settings.finally();
            } else {
                return false;
            }
        },
        registerCheckAll: function() {
            if (this.requests.length == 0) return false;

            var all_closed = true;
            $.each(this.requests, function(domain, v) {
                if (v.state == 'progress') all_closed = false;
            });

            return all_closed;
        },
        query: function (item) {
            var domain = $(item).data('domain');

            if(!domain) return false;

            $.ajax({
                url: "check",
                dataType: 'html',
                type: 'POST',
                beforeSend: function() {
                    this.registerRequest(domain);
                }.bind(this),
                data: {domain: domain},
                success: function(data) {
                    //this.registerFinish(domain);
                    return this.settings.success(data, domain, this.element);
                }.bind(this)
            });

            return this;
        }
    };

    $.fn[ pluginName ] = function (options) {
        this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
        return this;
    };
})(jQuery, window, document);
