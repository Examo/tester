;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "answerEditorAssoc",
        defaults = {
            type: 'assoc'
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

            $('.content', this.element).find('.options-left [data-id]').each( function() {
                result.push([ $(this).data('id'), null ]);
            } );

            $('.content', this.element).find('.options-right [data-id]').each( function(i) {
                result[i][1] = $(this).data('id');
            } );

            this.onChange.apply(this.owner, [result]);
        },

        renderHtml: function (data) {
            var self = this;

            var result = self.getTemplate('content');

            // left column

            // shuffle options
            var ids = Object.keys(data.options);
            for (var i = ids.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var temp = ids[i];
                ids[i] = ids[j];
                ids[j] = temp;
            }

            for (var i in ids) {
                var item = self.getTemplate('item');

                item.find('.text').text(data.options[ids[i]]);
                item.attr( 'data-id', ids[i] );

                result.find('.options-left').append(item);
            }

            result.find('.options-left').sortable({
                axis: 'y',
                update: function(e, ui) {
                    self.changeAnswer();
                }
            });

            // right column

            // shuffle options
            var ids = Object.keys(data.options);
            for (var i = ids.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var temp = ids[i];
                ids[i] = ids[j];
                ids[j] = temp;
            }

            for (var i in data.associations) {
                var item = self.getTemplate('item');

                item.find('.text').text(data.associations[ids[i]]);
                item.attr( 'data-id', ids[i] );

                result.find('.options-right').append(item);
            }

            result.find('.options-right').sortable({
                axis: 'y',
                update: function(e, ui) {
                    self.changeAnswer();
                }
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