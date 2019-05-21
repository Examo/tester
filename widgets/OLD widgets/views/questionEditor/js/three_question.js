;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "questionEditorThreeQuestion",
        defaults = {
            type: 'three_question'
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.owner = false;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function () {
            this.registerExtension();
        },
        registerExtension: function (owner) {
            var parent = $(this.element).parent();
            if (owner) {
                owner.registerExtension(this.settings.type, this);
                this.owner = owner;
            } else if ('plugin_questionEditor' in parent.data()) {
                parent.data('plugin_questionEditor').registerExtension(this.settings.type, this);
                this.owner = parent.data('plugin_questionEditor');
            }
        },

        show: function (data) {
            var content = this.renderHtml(this.parseData(data));
            $('.content', this.element).html('').append(content);
        },

        hide: function () {
            $('.content', this.element).html('');
        },

        getTemplate: function (name) {
            var template = $('.template.' + name + '-template', this.element);

            if (template.length) {
                return template.clone()
                    .removeClass('template')
                    .removeClass(name + '-template');
            } else {
                return $('<div>');
            }
        },

        onChange: function (data) {
        },

        //--------------------------------------------------------------------------------------------------------------
        // Current extension implementation
        //--------------------------------------------------------------------------------------------------------------

        parseData: function (raw) {
            return {
                question: raw && 'question' in raw ? raw.question : [],
                answer: raw && 'answer' in raw ? raw.answer : []
            };
        },

        changeData: function () {
            var result = {
                question: [],
                answer: []
            };

            $('.content', this.element).find('.question').each(function () {
                result.question.push($(this).val());
            });

            $('.content', this.element).find('.answer').each(function () {
                result.answer.push($(this).val());
            });

            this.onChange.apply(this.owner, [result]);
        },

        renderHtml: function (data) {
            var self = this;

            var result = self.getTemplate('content');

            for (var i in data.question) {
                var question = $(result).find('.question')[i];
                $(question).val(data.question[i]);
            }

            for (var i in data.answer) {
                var answer = $(result).find('.answer')[i];
                $(answer).val(data.answer[i]);
            }

            result.on('change', 'input', function () {
                self.changeData();
            });

            result.on('change', 'textarea', function () {
                self.changeData();
            });

            return result;
        }

        //--------------------------------------------------------------------------------------------------------------

    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_questionEditor_extension")) {
                $.data(this, "plugin_questionEditor_extension", new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);