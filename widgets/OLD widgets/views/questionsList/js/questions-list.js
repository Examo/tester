;(function ($, window, document, undefined) {
    "use strict";

    // Create the defaults once
    var pluginName = "questionsList",
        defaults = {
            data: {},
            modal: false,
            name: '',
            link: ''
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;

        this.data = {};

        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        // INIT
        init: function () {
            this._initSorting();
            this._initButtons();
            this._initModal();

            this._initData();
        },

        _initSorting: function () {
            var self = this;

            $('.list', this.element).sortable({
                update: function (event, ui) {
                    self._updateInputs();
                }
            });
        },

        _initButtons: function () {
            var self = this;

            // remove
            $('.list', self.element).on('click', '.remove', function () {
                self.remove($(this).parent().data('id'));
            });
        },

        _initModal: function () {
            var self = this;

            // show modal
            self.settings.modal.on('show.bs.modal', function (e) {
                //reset state
                $(this).find('.add').prop('checked', false);
                $(this).find('.add').prop('disabled', false);
                $(this).find('.count').text(0);
                $(this).data('items', []);

                // disable already added items
                updateModalCheckboxes();
            })

            // modal selection
            self.settings.modal.on('click', '.add', function () {
                var id = $(this).data('id');
                var data = self.settings.modal.data('items');

                if ($(this).prop('checked')) {
                    if (!(id in data)) {
                        data[id] = $(this).data('text');
                    }
                } else {
                    if (id in data) {
                        delete data[id];
                    }
                }

                self.settings.modal.data('items', data);
                $('.count', self.settings.modal).text(Object.keys(data).length);
            });

            // close modal
            self.settings.modal.on('click', '.save', function () {
                var data = self.settings.modal.data('items');

                for (var id in data) {
                    self.add(id, data[id]);
                }

                self.settings.modal.modal('hide');
            });

            // update checkboxes
            function updateModalCheckboxes() {
                // previously added items
                for (var id in self.data) {
                    self.settings.modal.find('[data-id=' + id + ']').prop("checked", true).prop("disabled", true);
                }

                // current modal selections
                var data = self.settings.modal.data('items');
                for (var id in data) {
                    self.settings.modal.find('[data-id=' + id + ']').prop("checked", true);
                }
            }

            self.settings.modal.on('pjax:end', updateModalCheckboxes);
            updateModalCheckboxes();
        },

        _initData: function () {
            for (var i in this.settings.data.order) {
                var id = this.settings.data.order[i];
                this.add(id, this.settings.data.items[id]);
            }
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

        add: function (id, name) {
            if (id in this.data) {
                return; // already exists
            }

            this.data[id] = name;

            var block = this.getTemplate('item');

            block.attr('data-id', id);

            block.find('.id').text(id);
            block.find('.text').text(name);
            block.find('.link').attr('href', this.settings.link + id);

            $('.list', this.element).append(block);

            this._updateInputs();
        },

        remove: function (id) {
            if (!(id in this.data)) {
                return; // not found
            }

            delete this.data[id];

            $('.list [data-id="' + id + '"]', this.element).remove();

            this._updateInputs();
        },

        clear: function () {
            if (!Object.keys(this.data).length || confirm('Данное действие сбросит уже добавленные задания. Продолжить?')) {
                for (var id in this.data) {
                    this.remove(id);
                }
            }
        },

        _updateInputs: function () {
            var self = this;

            $('[name="' + self.settings.name + '[question_id][]"]').remove();
            $('[name="' + self.settings.name + '[position][]"]').remove();

            $('.item', self.element).each(function (i) {
                if (!$(this).data('id')) {
                    return;
                }

                var iId = $('<input>');
                iId.attr('type', 'hidden');
                iId.attr('name', self.settings.name + '[question_id][]');
                iId.attr('value', $(this).data('id'));
                $(self.element).append(iId);

                var iPos = $('<input>');
                iPos.attr('type', 'hidden');
                iPos.attr('name', self.settings.name + '[position][]');
                iPos.attr('value', i);
                $(self.element).append(iPos);
            });
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