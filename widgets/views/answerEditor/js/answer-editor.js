;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "answerEditor",
        defaults = {
            input: '',
            types: {},
            type: '',
            data: {}
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;

        this.extensions = {};
        this.type = false;
        this.data = false;

        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        // INIT
        init: function () {
            this.findExtensions();

            this.setType(this.settings.type, this.settings.data);
        },

        // EXTENSIONS
        findExtensions: function () {
            var self = this;

            $('.answer-editor-extension', self.element).each(function () {
                if ('plugin_answerEditor_extension' in $(this).data()) {
                    $(this).data('plugin_answerEditor_extension').registerExtension(self);
                }
            });
        },
        registerExtension: function (type, instance) {
            if (this.hasType(type)) {
                this.extensions[type] = instance;
                this.extensions[type].onChange = this.setAnswer;

                if (type == this.getType()) {
                    instance.show(this.data);
                }
            }
        },

        // TYPE
        getType: function () {
            return this.type;
        },
        setType: function (type, data) {
            if (this.type == type || !this.hasType(type)) {
                return;
            }

            if (this.type in this.extensions) {
                this.extensions[this.type].hide();
            }

            if (type in this.extensions) {
                this.extensions[type].show(data);
            }

            this.type = type;
            this.data = data;
        },
        hasType: function (name) {
            for (var id in this.settings.types) {
                if (this.settings.types[id] == name) {
                    return true;
                }
            }

            return false;
        },

        setAnswer: function (data) {
            $(this.settings.input).val(JSON.stringify(data));
        }

    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" +
                    pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);