/**
 * Created by tofid on 23.11.15.
 */

;(function ($, window, document, undefined) {
    var pluginName = "NSync";

    var defaults = {
        inlineFieldSelector: '.inline-form-selector',
        dynamicFieldSelector: '.dynamic-form-selector',

        dynamicFormWidgetContainer: '.dynamicform_wrapper',
        dynamicFormWidgetItem: '.item',
        dynamicFormWidgetInsertButton: '.add-item',
        dynamicFormWidgetDeleteButton: '.remove-item',

        state: []
    };

    function Plugin(element, options) {
        this.init();
    }

    Plugin.prototype = {
        // Common
        init: function (event) {
            this.addInlineFormListener(event);
            //this.addDynamicFormListener();
        },
        stateIsChanged: function () {
            return this.getDynamicElementsCount() !== this.getInlineElementsCount();
        },

        // Inline Processing
        addInlineFormListener: function (event) {
            var _ = this;
            $(defaults.inlineFieldSelector).find('input').eq(0).on('keyup change', function (event) {
                _.updateInlineForm(event);
            });
        },
        updateInlineForm: function (event) {
            this.getStateFromInlineForm();
            this.drawDynamicForm(event);
        },
        drawInlineForm: function () {

        },
        getInlineElementsCount: function () {
            return this.parseInlineForm().length;
        },
        getStateFromInlineForm: function () {
            defaults.state = this.parseInlineForm();
        },
        parseInlineForm: function () {
            var items = [],
                wholeStrSplitRe = /,\s*/,
                ipStrSplitRe = /\/\s*/,
                splits = $(defaults.inlineFieldSelector).find('input').eq(0).val().split(wholeStrSplitRe);
            splits.forEach(function(element, index, array) {
                var split = element.split(ipStrSplitRe);
                items.push({
                    name: split[0],
                    ip: split[1]
                });
            });
            return items;
        },

        // Dynamic Processing
        addDynamicFormListener: function (event) {

        },
        updateDynamicForm: function () {

        },
        drawDynamicForm: function (event) {
            if (this.stateIsChanged()) {
                var diff = 0,
                    dynamicElems = this.getDynamicElementsCount(),
                    inlineElems = this.getInlineElementsCount();
                // Draw items
                if (dynamicElems < inlineElems) {
                    diff = inlineElems - dynamicElems;
                    for (var i = 0; i < diff; i++) {
                        this.addDynamicItem(event);
                    }
                } else {
                    diff = dynamicElems - inlineElems;
                    for (var i = 0; i < diff; i++) {
                        this.deleteDynamicItem(event);
                    }
                }
            }
            // Fill the items
            defaults.state.forEach(function(element, index, array) {
                var DWContainer = $(defaults.dynamicFormWidgetContainer);
                var state = defaults.state;
                DWContainer.find(defaults.dynamicFormWidgetItem).each(function(index, element) {
                    var stateItem = state.shift(),
                        containerFields = $(element).find('input');
                    containerFields.eq(0).val(stateItem.name);
                    containerFields.eq(1).val(stateItem.ip);
                });
            });

        },
        getDynamicElementsCount: function () {
            return $('.item').eq(0).closest(defaults.dynamicFormWidgetContainer).find(defaults.dynamicFormWidgetItem).length;
        },
        getStateFromDynamicForm: function () {

        },
        addDynamicItem: function (event) {
            var df = $(defaults.dynamicFormWidgetContainer);
            var df_options = window[df.data('dynamicform')];
            df.yiiDynamicForm("addItem", df_options, event, $(defaults.dynamicFormWidgetInsertButton).eq(-1));
        },
        deleteDynamicItem: function (event) {
            var df = $(defaults.dynamicFormWidgetContainer);
            var df_options = window[df.data('dynamicform')];
            df.yiiDynamicForm("deleteItem", df_options, event, $(defaults.dynamicFormWidgetDeleteButton).eq(-1));
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