;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "answerEditorSelectOne",
        defaults = {
            type: 'select_one'
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
            } else if ('plugin_answerEditor' in parent.data()) {
                parent.data('plugin_answerEditor').registerExtension(this.settings.type, this);
                this.owner = parent.data('plugin_answerEditor');
            }
        },

        show: function (data) {
            var content = this.renderHtml(this.parseData(data));
            var answer = [this.owner.settings.answer.replace("[",'').replace("]",'')];

            $('.content', this.element).html('').append(content);

            if (answer[0] !== undefined && answer[0] !== null && answer[0] !== "") {
                $('.content', this.element).find('input[type=radio]').each(function (i) {
                    for (var y = 0; y < answer.length; y++) {
                        if ($(this).val() === answer[y]) {
                            $(this).prop('checked', true);
                        }
                        $(this).prop('disabled', true);
                    }
                });
            }
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
                options: raw && 'options' in raw ? raw.options : []
            };
        },

        changeAnswer: function () {
            var result = [];

            $('.content', this.element).find('input[type=radio]').each(function (i) {
                if ($(this).prop('checked')) {
                    result.push(parseInt($(this).val()));
                }
            });

            this.onChange.apply(this.owner, [result]);
        },

        renderHtml: function (data) {
            var self = this;
            var shuffle = $.cookie('sfl');
            var quest = $.cookie('qst');
            var result = self.getTemplate('content');

            // shuffle options

            var ids = sessionStorage.getItem(quest);

            if (ids === undefined || ids === null || shuffle !== this.owner.settings.current) {
                ids = Object.keys(data.options);
                for (var i = ids.length - 1; i > 0; i--) {
                    var j = Math.floor(Math.random() * (i + 1));
                    var temp = ids[i];
                    ids[i] = ids[j];
                    ids[j] = temp;
                }
                sessionStorage.setItem(this.element.id, ids);

                $.cookie('sfl', this.owner.settings.current, {
                    expires: 1
                });

                $.cookie('qst', this.element.id, {
                    expires: 1
                });
            } else {
                ids = ids.split(',');
            }

            // render options
            for (var i in ids) {
                var item = self.getTemplate('item');

                item.find('input').val(ids[i]);
                item.find('.text').text(data.options[ids[i]]);

                result.find('.items').append(item);
            }

            // bind select
            result.on('change', 'input', function () {
                self.changeAnswer();
            });

            return result;
        }

        //--------------------------------------------------------------------------------------------------------------

    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_answerEditor_extension")) {
                $.data(this, "plugin_answerEditor_extension", new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);