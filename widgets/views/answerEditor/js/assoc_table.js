;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "answerEditorAssocTable",
        defaults = {
            type: 'assoc_table'
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
            $('.content', this.element).html('').append(content);
            var answer = this.owner.settings.answer;
            var immediate_result = this.owner.settings.immediate_result;
            answer = answer.replace(/\[/g, '').replace(/\]/g, '');
            answer = answer.split(',');
            var self = this;
            var $selfContent = $('.content', self.element);
            var items = $selfContent.find('.answers input');

            if (answer[0] === undefined || answer[0] === null && answer[0] === "") {
                return;
            }

            var answ_r = [];
            var answ_l = [];
            for (var i = 1; i < answer.length+1; i++) {
                if (i % 2 === 0) {
                    answ_r.push(answer[i-1]);
                } else {
                    answ_l.push(answer[i-1]);
                }
            }

            var rights = $selfContent.find('.options-right [data-id]');
            // оборачиваем в рамки выводим коментарии
            $selfContent.find('.options-left .item').each( function(y) {
                var right = rights[y];
                var rightId = $(right).data('id');
                var comment = '';

                if ('comments' in data) {
                    comment = data.comments[y];
                }

                if (immediate_result === '1') {

                    if (answ_r[y] === answ_l[y]) {
                        $(this).wrap('<div class="row success"></div>');
                        $(this).after(right);
                        $(this).wrap('<div class="col-md-3 col-xs-3"></div>');
                        $(right).wrap('<div class="col-md-3 col-xs-3"></div>');
                    } else {
                        $(this).wrap('<div class="row error"></div>');
                        $(this).after(right);
                        $(this).wrap('<div class="col-md-3 col-xs-3"></div>');
                        $(right).wrap('<div class="col-md-3 col-xs-3"></div>');
                        $(this).find('span').last().after(
                            "</br><b>Комментарий:</b><div class='item'><b>"+comment+"</b></div>"
                        );
                        $(right).find('span').last().after(
                            "</br><b>Правильный ответ:</b><div class='item success' style='padding: 0px'>"+data.associations[y]+"</div></div>"
                        );
                    }

                    $selfContent.find('.col-xs-6').each (function() {
                        if ($(this).find('.options-left').length > 0) {
                            $(this).css("width", "100%");
                        }
                        if ($(this).find('.options-right').length > 0) {
                            $(this).hide();
                        }
                    });

                    $selfContent.find('.col-xs-3').each (function() {
                        $(this).css("width", "50%");
                    });

                }
                //заполняем поля ответов
                for (let z = 0; z < answ_r.length; z++) {
                    if (rightId === parseInt(answ_r[z])) {
                        var item = items[z];
                        var num = $(right).find('.number').text();
                        $(item).val(num);
                        $(item).prop("disabled", true);
                    }
                }
            });
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
                options: raw && 'options' in raw ? raw.options : [],
                associations: raw && 'associations' in raw ? raw.associations : [],
            };
        },

        changeAnswer: function () {
            var result = [];

            var associations = [];
            $('.content', this.element).find('.options-right [data-id]').each( function(i) {
                associations[ parseInt($(this).data('num')) ] = parseInt($(this).data('id'));
            } );

            $('.content', this.element).find('.answers input').each( function() {
                var val = parseInt( $(this).val() ) - 1;
                var assoc = val in associations ? associations[val] : null;

                result.push([ parseInt($(this).data('id')), assoc ]);
            } );

            this.onChange.apply(this.owner, [result]);
        },

        isNewQuestion: function(rightIds, shuffle)
        {
            return (
                (rightIds === undefined || rightIds === null || shuffle !== this.owner.settings.current) &&
                this.owner.settings.with_shuffle !== 0
            );
        },

        getRightIds: function(data)
        {
            let ids = Object.keys(data.options);
            for (let i = ids.length - 1; i > 0; i--) {
                let j = Math.floor(Math.random() * (i + 1));
                let temp = ids[i];
                ids[i] = ids[j];
                ids[j] = temp;
            }
            return ids;
        },

        renderHtml: function (data) {
            let self = this;
            let shuffle = $.cookie('sfl');
            let quest_r = $.cookie('qstr');
            let result = self.getTemplate('content');
            // left column
            let leftIds = Object.keys(data.options);
            let char = "А".charCodeAt(0);
            let ids = [];

            for (let i in leftIds) {
                if ( data.options[leftIds[i]].length === 0 ) {
                    continue;
                }

                let item = self.getTemplate('item');
                item.find('.text').text(data.options[leftIds[i]]);
                item.find('.number').text( String.fromCharCode(char) );
                result.find('.options-left').append(item);

                let answer = self.getTemplate('answer');
                answer.find('.option').text( String.fromCharCode(char) );
                answer.find('input').attr('data-id', leftIds[i]);
                result.find('.answers').append(answer);

                char++;
            }

            // right column
            // shuffle options
            let rightIds = sessionStorage.getItem(quest_r);

            if (self.isNewQuestion(rightIds, shuffle)) {
                ids = self.getRightIds(data);
                sessionStorage.setItem(this.element.id + 'r', ids);

                $.cookie('qstr', this.element.id + 'r', {
                    expires: 1
                });

                $.cookie('sfl', this.owner.settings.current, {
                    expires: 1
                });
            } else {
                var answer = this.owner.settings.answer;
                var answ_r = [];
                answer = answer.replace(/\[/g, '').replace(/\]/g, '');
                answer = answer.split(',');
                if (rightIds === null) {
                    ids = self.getRightIds(data);
                } else {
                    ids = rightIds.split(',');
                }

                if (answer[0] !== undefined && answer[0] !== null && answer[0] !== "") {
                    for (var i = 1; i < answer.length + 1; i++) {
                        if (i % 2 === 0) {
                            answ_r.push(answer[i - 1]);
                        }
                    }

                    ids.forEach( function (e) {
                       if (answ_r.indexOf(e) === -1) {
                           answ_r.push(e);
                       }
                    });
                    ids = answ_r;
                }
            }

            for (let i in data.associations) {
                if (data.associations[ids[i]] === undefined ||
                    data.associations[ids[i]] === null ||
                    data.associations[ids[i]].length === 0
                ) {
                    continue;
                }

                let item = self.getTemplate('item');

                item.find('.text').text(data.associations[ids[i]]);
                item.find('.number').text( parseInt(i) + 1 );
                item.attr( 'data-id', ids[i] );
                item.attr( 'data-num', i );

                result.find('.options-right').append(item);
            }

            result.on('change', '.answers input', function () {
                self.changeAnswer();
            });

            return result;
        },

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