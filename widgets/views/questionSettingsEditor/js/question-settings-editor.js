;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "questionSettingsEditor",
        defaults = {
            input: '',
            switcher: '',
            types: {}
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

            this.bindData();
            this.bindType();
        },

        // EXTENSIONS
        findExtensions: function () {
            var self = this;

            $('.question-settings-editor-extension', self.element).each(function () {
                if ('plugin_questionSettingsEditor_extension' in $(this).data()) {
                    $(this).data('plugin_questionSettingsEditor_extension').registerExtension(self);
                }
            });
        },
        registerExtension: function (type, instance) {
            if (this.hasType(type)) {
                this.extensions[type] = instance;
                this.extensions[type].onChange = this.setData;

                if (type == this.getType()) {
                    instance.show(this.getData());
                }
            }
        },

        // TYPE
        bindType: function () {
            var self = this;

            function onSwitcherChange() {
                var id = $(self.settings.switcher).val();
                if (id in self.settings.types) {
                    self.setType(self.settings.types[id]);
                }
            }

            $(self.settings.switcher).change(onSwitcherChange);
            onSwitcherChange();
        },
        getType: function () {
            return this.type;
        },
        setType: function (type) {
            if (this.type == type || !this.hasType(type)) {
                return;
            }

            if (this.type in this.extensions) {
                this.extensions[this.type].hide();
            }

            if (type in this.extensions) {
                this.extensions[type].show(this.getData());
            }

            this.type = type;
        },
        hasType: function (name) {
            for (var id in this.settings.types) {
                if (this.settings.types[id] == name) {
                    return true;
                }
            }

            return false;
        },

        // DATA
        bindData: function () {
            var self = this;

            function onDataChange() {
                self.setData($(self.settings.input).val());
            }

            $(self.settings.input).change(onDataChange);
            onDataChange();
        },
        getData: function () {
            return JSON.parse(this.data);
        },
        setData: function (data) {
            if (typeof data === "object") {
                data = JSON.stringify(data);
            }

            if (data == this.data) {
                return;
            }

            $(this.settings.input).val(data);

            this.data = data;
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