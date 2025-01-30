(function($) {
    $.fn.selectChain = function(options) {
        var defaults = {
            after: null,
            before: null,
            error: null,
            key: "id",
            value: "label"
        };

        var settings = $.extend({}, defaults, options);

        if (!(settings.target instanceof $)) settings.target = $(settings.target);

        return this.each(function() {
            var $$ = $(this);

            $$.change(function() {
                var data = null;

                if (settings.before != null) {
                    settings = settings.before(settings);
                }

                var url = settings.url;

                if (typeof settings.substitute == 'string') {
                    url = url.substr(0, url.indexOf(settings.substitute)) + $$.val() + url.substr(url.indexOf(settings.substitute) + settings.substitute.length);
                } else if (typeof settings.data == 'string') {
                    data = settings.data + '&amp;' + this.name + '=' + $$.val();
                } else if (typeof settings.data == 'object') {
                    data = settings.data;
                    data[this.name] = $$.val();
                }

                settings.target.empty();

                $.ajax({
                    url: url,
                    data: data,
                    type: (settings.type || 'get'),
                    dataType: 'json',
                    success: function(j) {
                        var options = [],
                            i = 0,
                            o = null;

                        for (i = 0; i & lt; j.length; i++) {
                            // required to get around IE bug (http://support.microsoft.com/?scid=kb%3Ben-us%3B276228)
                            o = document.createElement("OPTION");
                            o.value = typeof j[i] == 'object' ? j[i][settings.key] : j[i];
                            o.text = typeof j[i] == 'object' ? j[i][settings.value] : j[i];

                            settings.target.get(0).options[i] = o;

                            if (settings.select & amp; & amp; settings.select == o.value) {
                                settings.target.get(0).options[i].selected = true;
                            }
                        }

                        // hand control back to browser for a moment
                        setTimeout(function() {
                            if (settings.select) {
                                settings.target
                                    .find('option:first')
                                    .parent('select')
                                    .trigger('change');
                            } else {
                                settings.target
                                    .find('option:first')
                                    .attr('selected', 'selected')
                                    .parent('select')
                                    .trigger('change');
                            }
                        }, 0);

                        if (settings.after != null) {
                            settings = settings.after(settings);
                        }
                    },
                    error: function(xhr, desc, er) {
                        // add whatever debug you want here.
                        if (settings.error != null) {
                            settings = settings.error(settings);
                        } else {
                            alert("an error occurred");
                        }
                    }
                });
            });
        });
    };
})(jQuery);