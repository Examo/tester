;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "answerEditorThreeQuestion",
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
            } else if ('plugin_answerEditor' in parent.data()) {
                parent.data('plugin_answerEditor').registerExtension(this.settings.type, this);
                this.owner = parent.data('plugin_answerEditor');
            }
        },

        show: function (data, comment) {
            var content = this.renderHtml(this.parseData(data, comment));
            var answer = this.owner.settings.answer ? JSON.parse(this.owner.settings.answer) : this.owner.settings.answer;
            $('.content', this.element).html('').append(content);

            if (answer !== undefined && answer !== null && answer !== "") {
                $('.hint-button').hide();
                var panel = $('.comment-content').find('.panel.panel-default');
                var inputs = $('.content', this.element).find('input');

                for (var i in answer) {
                    $(inputs[i]).val(answer[i][0]);
                    $(inputs[i]).prop('disabled', true);
                    if (answer[i][1] === 1) {
                        $(panel[i]).css("border", "solid").css("border-color", "#219187");
                    } else {
                        $(panel[i]).css("border", "solid").css("border-color", "#F3565D");
                    }
                }

                $('.comment-content').show();
            } else {
                $('.comment-content').hide();
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

        parseData: function (raw, comment) {
            return {
                questions: raw && 'question' in raw ? raw.question : [],
                comments: comment ? comment : []
            };
        },

        changeAnswer: function () {
            var result = [];
            $('.content', this.element).find('input').each(function () {
               result.push($(this).val());
            });
            this.onChange.apply(this.owner, [result]);
        },

        renderHtml: function (data) {
            var self = this;
            var quest = $.cookie('qst_three_question');
            var result = self.getTemplate('content');
            var inputs = result.find('input');

            var ids = sessionStorage.getItem(quest);
            if (ids === undefined || ids === null || ids === "") {
                ids = Object.keys(data.questions);

                sessionStorage.setItem(this.element.id, ids);

                $.cookie('qst_three_question', this.element.id, {
                    expires: 1
                });
            } else {
                ids = ids.split(',');
            }

            for (var i in ids) {
                var item = self.getTemplate('item');

                item.find('.text').text(data.questions[ids[i]]);
                $(inputs[i]).before(item);
                $(inputs[i]).after('<a href=\"#\" class="btn btn-primary hint-button" data-id="'+i+'">Подсказать</a></br>');
                $(inputs[i]).after(
                '<div class=\"comment-content\">\
                    <div class="panel panel-default">\
                        <div class="panel-heading">\
                            <div class="general-item-list">\
                                <div class="item">\
                                    <div class="item-details"> \
                                        <img class="item-pic" src="/i/hintemoticon.jpg">\
                                        <div class="item-name primary-link">\
                                            <strong>Кошка объясняет:</strong>\
                                        </div>\
                                    </div>\
                                    <span>'+data.comments[i]+'</span>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>');
            }

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