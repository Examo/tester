;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "questionEditorDictation",
        defaults = {
            type: 'dictation'
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
                items: raw && 'items' in raw ? raw.items : [],
                comments: raw && 'comments' in raw ? raw.comments : []
            };
        },

        changeData: function () {
            var result = {
                items: [],
                comments: []
            };

            var nodes = $('.content .editor', this.element).get(0).childNodes;
            for (var i = 0; i < nodes.length; i++) {
                // if TEXT NODE
                if (nodes[i].nodeType == 3) {
                    result.items.push(nodes[i].textContent);
                } else {

                    var node = $(nodes[i]);

                    if (node.hasClass('item')) {
                        var item = [];
                        node.find('.option').each(function () {
                            item.push($(this).find('.value').text());
                        });

                        result.items.push(item);
                        result.comments.push(node.data('comment'));
                    }

                }
            }

            this.onChange.apply(this.owner, [result]);
        },

        renderHtml: function (data) {
            var self = this;

            var result = self.getTemplate('content');
            var editor = result.find('.editor');

            // load data
            var editableItemId = 0;
            for (var i = 0; i < data.items.length; i++) {
                if ($.isArray(data.items[i])) {
                    var item = self.getTemplate('item');
                    self.buildItem( item, data.items[i][0], data.items[i].splice(1), data.comments[editableItemId] );
                    editor.append( item );

                    editableItemId++;
                } else {
                    editor.append( data.items[i] );
                }
            }

            // bind editor events
            editor.on('selectstart', function () {
                $(document).one('mouseup', function () {
                    self.onSelection(this.getSelection());
                });
            }).on('keyup', function () {
                self.onSelection(document.getSelection());
            }).on('input', function () {
                // clear formatting from copypasted text
                $('> *', this).each(function () {
                    if ($(this).hasClass('item') || $(this).hasClass('carret')) {
                        return;
                    }

                    $(this).replaceWith($(this).text());
                });

                self.changeData();
            });

            return result;
        },

        //--------------------------------------------------------------------------------------------------------------

        /**
         * Add tooltip when some text selected
         * @param selection
         */
        onSelection: function (selection) {
            var self = this;

            // remove any active tooltips
            $('.content', self.element).find('.selection-menu, .carret').remove();

            // we are interested only in Range selection
            if (selection.type != "Range") {
                return;
            }

            // avoid selecting other items
            var isOtherItemSelected = false;
            $('.content', self.element).find('.item').each(function () {
                if (selection.containsNode(this, true)) {
                    isOtherItemSelected = true;
                }
            });
            if (isOtherItemSelected) {
                return;
            }

            // insert carret
            var range = selection.getRangeAt(0);
            range.insertNode($("<span class='carret'>&nbsp;</span>")[0]);

            // show tooltip
            var carret = $('.content .carret', self.element);
            var pos = carret.offset();

            var tooltip = self.getTemplate('selection-menu').css({
                top: pos.top + carret.height(),
                left: pos.left
            });

            // replace selected text with options editor
            tooltip.find('.add').click(function () {
                //extract text value
                var contents = range.cloneContents();
                var value = contents.childNodes[contents.childNodes.length - 1].textContent;

                // build element
                var item = self.getTemplate('item').attr('id', 'item' + Math.round(Math.random() * 1e10));
                self.replaceSelection(item.prop('outerHTML'));
                item = $('#' + item.attr('id'));

                self.buildItem(item, value, []);

                self.removeSelection();
                self.changeData();
            });

            $('.content', self.element).append(tooltip);
        },

        /**
         * Replace selected text with html data
         * @param html
         * @param selectInserted
         */
        replaceSelection: function (html, selectInserted) {
            var sel, range, fragment;

            if (typeof window.getSelection != "undefined") {
                // IE 9 and other non-IE browsers
                sel = window.getSelection();

                // Test that the Selection object contains at least one Range
                if (sel.getRangeAt && sel.rangeCount) {
                    // Get the first Range (only Firefox supports more than one)
                    range = window.getSelection().getRangeAt(0);
                    range.deleteContents();

                    // Create a DocumentFragment to insert and populate it with HTML
                    // Need to test for the existence of range.createContextualFragment
                    // because it's non-standard and IE 9 does not support it
                    if (range.createContextualFragment) {
                        fragment = range.createContextualFragment(html);
                    } else {
                        // In IE 9 we need to use innerHTML of a temporary element
                        var div = document.createElement("div"), child;
                        div.innerHTML = html;
                        fragment = document.createDocumentFragment();
                        while ((child = div.firstChild)) {
                            fragment.appendChild(child);
                        }
                    }
                    var firstInsertedNode = fragment.firstChild;
                    var lastInsertedNode = fragment.lastChild;
                    range.insertNode(fragment);
                    if (selectInserted) {
                        if (firstInsertedNode) {
                            range.setStartBefore(firstInsertedNode);
                            range.setEndAfter(lastInsertedNode);
                        }
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                }
            } else if (document.selection && document.selection.type != "Control") {
                // IE 8 and below
                range = document.selection.createRange();
                range.pasteHTML(html);
            }
        },

        /**
         * Remove text selection
         */
        removeSelection: function () {
            if (window.getSelection) {
                if (window.getSelection().empty) {  // Chrome
                    window.getSelection().empty();
                } else if (window.getSelection().removeAllRanges) {  // Firefox
                    window.getSelection().removeAllRanges();
                }
            } else if (document.selection) {  // IE?
                document.selection.empty();
            }

            $('.content', this.element).find('.selection-menu, .carret').remove();
        },

        /**
         * Prepare editor item
         * @param item
         * @param value
         * @param options
         */
        buildItem: function (item, value, options, comment) {
            var self = this;

            // insert default option
            var option = self.getTemplate('option');
            option.find('.value').text(value);
            option.find('button').remove();
            item.find('.dropdown-toggle .value').text(value);
            item.find('.dropdown-menu').append(option);

            // bind delete all options
            item.find('.remove').click(function () {
                if (confirm('Удалить варианты?')) {
                    item.replaceWith(value);
                    self.changeData();
                }

                return false;
            });

            // bind delete one option
            item.find('.dropdown-menu').on('click', '.delete', function () {
                $(this).closest('li').remove();
                self.changeData();

                return false;
            });

            // bind add new option
            item.find('.add').click(function () {
                var val = prompt('Добавить вариант:');

                if (val !== null) {
                    var option = self.getTemplate('option');
                    option.find('.value').text(val);
                    item.find('.dropdown-menu').append(option);
                    self.changeData();
                }

                return false;
            });

            // bind add comment
            item.find('.comment').attr('title', comment).click(function () {
                var val = prompt('Добавить комментарий:', item.data('comment'));

                if (val !== null) {
                    item.data('comment', val).attr('title', val);
                    self.changeData();
                }

                return false;
            });

            // add options
            for (var i in options) {
                var option = self.getTemplate('option');
                option.find('.value').text(options[i]);
                item.find('.dropdown-menu').append(option);
            }

            item.data('comment', comment);
        }

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