;
if (typeof String.prototype.endsWith !== 'function') {
    String.prototype.endsWith = function(suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };
}
(function ($, window, document, undefined) {
    var pluginName = "NSync";

    var defaults = {
        inlineFieldSelector: '.inline-form-selector input',
        dynamicFieldSelector: '.dynamic-form-selector',

        dynamicFormWidgetContainer: '.dynamicform_wrapper',
        dynamicFormWidgetItem: '.item',
        dynamicFormWidgetInsertButton: '.add-item',
        dynamicFormWidgetDeleteButton: '.remove-item',
        dynamicFormWidgetLimit: 15
    };

    function Plugin(element, options) {
        this.element = element;
        this.state = [];
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {
        // Common
        init: function (event) {
            this.addInlineFormListener(event);
            this.addDynamicFormListener(event);
            this.fieldslistener(event);
        },
        isStateChanged: function () {
            return this.getDynamicElementsCount() !== this.getInlineElementsCount();
        },
        fieldslistener: function (event) {
            var that = this;
            $(this.element).on('change keyup input click', function (event) {
                var DWContainer = $(that.element).find(that.options.dynamicFormWidgetContainer);
                DWContainer.find(that.options.dynamicFormWidgetItem).each(function (i, elem) {
                    var isChildDomain = that.isChildDomain(that.getNameInput(elem).val());
                    that.getIpInput(elem).attr('disabled', !isChildDomain);
                });
                that.updateInlineForm(event);
            });
        },
        isChildDomain: function (nsName) {
            var mainDomain = '.' + $(this.element).find('.domain_name').eq(0).val();
            return nsName.endsWith(mainDomain);
        },

        // Inline Processing
        addInlineFormListener: function (event) {
            var that = this;

            $(this.element).find(this.options.inlineFieldSelector).on('change keyup input', function (event) {
                $(this).data('touched', 1);
                that.updateDynamicForm(event);
                $(this).data('touched', 0);
            });
        },
        updateInlineForm: function (event) {
            this.getStateFromDynamicForm();
            this.drawInlineForm(event);
        },
        drawInlineForm: function (event) {
            var nsInlineField = '';
            this.state.forEach(function (elem, i) {
                nsInlineField += elem.name;
                if (elem.ip !== '') {
                    nsInlineField +=  '/' + elem.ip;
                }
                nsInlineField += ',';
            });

            $(this.element).find(this.options.inlineFieldSelector).val(nsInlineField.replace(/,\s*$/g, ''));
        },
        getInlineElementsCount: function () {
            return this.parseInlineForm().length;
        },
        getStateFromInlineForm: function () {
            this.state = this.parseInlineForm();
        },
        parseInlineForm: function () {
            var items = [],
                wholeStrSplitRe = /,\s*/,
                ipStrSplitRe = /\/\s*/,
                splits = $(this.element).find(this.options.inlineFieldSelector).val().split(wholeStrSplitRe);

            splits.forEach(function (elem, i) {
                var split = elem.split(ipStrSplitRe);
                items.push({
                    name: split[0],
                    ip: split[1]
                });
            });

            return items;
        },

        // Dynamic Processing
        addDynamicFormListener: function (event) {
            var that = this;
            $(this.element).find(this.options.dynamicFormWidgetContainer).on('afterDelete afterInsert', function (event) {
                if (!$(that.element).find(that.options.inlineFieldSelector).data('touched')) {
                    that.updateInlineForm(event);
                }
            });
            $(this.element).find(this.options.dynamicFormWidgetContainer).on('change', 'select', function (event) {
                that.updateInlineForm(event);
            });
        },
        updateDynamicForm: function () {
            this.getStateFromInlineForm();
            this.drawDynamicForm(event);
        },
        drawDynamicForm: function (event) {
            var that = this;
            // Draw dynamic form containers
            if (this.isStateChanged()) {
                var diff = this.getInlineElementsCount() - this.getDynamicElementsCount();

                for (var i = 0; i < Math.abs(diff); i++) {
                    if (diff > 0) {
                        this.addDynamicItem(event);
                    } else {
                        this.deleteDynamicItem(event);
                    }
                }
            }
            // Fill dynamic form widget fields
            var state = $.extend(true, [], this.state);
            var DWContainer = $(this.element).find(that.options.dynamicFormWidgetContainer);
            DWContainer.find(that.options.dynamicFormWidgetItem).each(function (index, element) {
                var stateItem = state.shift();
                that.getNameInput(element).val(stateItem.name);
                if (that.isChildDomain(stateItem.name) && stateItem.ip) {
                    var ips = stateItem.ip.split(';').filter(function (val) {
                            return val.length;
                        }),
                        data = $.map(ips, function (value) {
                            return {
                                'id': value,
                                'text': value
                            }
                        });

                    that.getIpInput(element).data('field').setData(data);
                }
            });
        },
        getDynamicElementsCount: function () {
            return $(this.element).find(this.options.dynamicFormWidgetContainer).find(this.options.dynamicFormWidgetItem).length;
        },
        getStateFromDynamicForm: function () {
            this.state = this.parseDynamicForm();
        },
        parseDynamicForm: function () {
            var items = [];
            var DWContainer = $(this.element).find(this.options.dynamicFormWidgetContainer);
            DWContainer.find(this.options.dynamicFormWidgetItem).each(function (i, elem) {
                var ipInput = this.getIpInput(elem);
                items.push({
                    name: this.getNameInput(elem).val(),
                    ip: ipInput.val() ? ipInput.val().join(';') : ''
                });
            }.bind(this));

            return items;
        },
        addDynamicItem: function (event) {
            var df = $(this.element).find(this.options.dynamicFormWidgetContainer);
            var df_options = window[df.data('dynamicform')];
            df.yiiDynamicForm("addItem", df_options, event, $(df).find(this.options.dynamicFormWidgetInsertButton).eq(-1));
        },
        deleteDynamicItem: function (event) {
            var df = $(this.element).find(this.options.dynamicFormWidgetContainer);
            var df_options = window[df.data('dynamicform')];
            df.yiiDynamicForm("deleteItem", df_options, event, $(df).find(this.options.dynamicFormWidgetDeleteButton).eq(-1));
        },
        getNameInput: function (row) {
            return $(row).find('input[data-attribute="name"]');
        },
        getIpInput: function (row) {
            return $(row).find('select[data-attribute="ip"]');
        }
    };

    $.fn[pluginName] = function (options) {
        this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });

        return this;
    };

})(jQuery, window, document);
