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

        renderHtml: function (data) {
            var self = this;

            var result = self.getTemplate('content');

            // left column

            // shuffle options
            var ids = Object.keys(data.options);
            //for (var i = ids.length - 1; i > 0; i--) {
            //    var j = Math.floor(Math.random() * (i + 1));
            //    var temp = ids[i];
            //    ids[i] = ids[j];
            //    ids[j] = temp;
            //}

            var char = "А".charCodeAt(0);
            for (var i in ids) {
                if ( data.options[ids[i]].length == 0 ) {
                    continue;
                }

                var item = self.getTemplate('item');
                item.find('.text').text(data.options[ids[i]]);
                item.find('.number').text( String.fromCharCode(char) );
                result.find('.options-left').append(item);

                var answer = self.getTemplate('answer');
                answer.find('.option').text( String.fromCharCode(char) );
                answer.find('input').attr('data-id', ids[i]);
                result.find('.answers').append(answer);

                char++;
            }


            // right column

            // shuffle options
            var ids = Object.keys(data.associations);
            for (var i = ids.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var temp = ids[i];
                ids[i] = ids[j];
                ids[j] = temp;
            }

            for (var i in data.associations) {
                var item = self.getTemplate('item');

                if ( data.associations[ids[i]].length == 0 ) {
                    continue;
                }

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