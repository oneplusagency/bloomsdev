(function($) {
    $.fn.selectChain = function(options) {
        var defaults = {
            key: "id",
            value: "label",
            after: null,
            before: null,
            usePost: false,
            defaultValue: null,
        };

        var settings = $.extend({}, options, defaults);

        if (!(settings.target instanceof $)) settings.target = $(settings.target);

        return this.each(function() {
            var $$ = $(this);

            $$.change(function() {
                var data = null;
                if (typeof settings.data == 'string') {
                    data = settings.data + '&' + this.name + '=' + $$.val();
                } else if (typeof settings.data == 'object') {
                    data = settings.data;
                    data[this.name] = $$.val();
                }

                settings.target.empty();

                $.ajax({
                    url: settings.url,
                    data: data,
                    type: (settings.type || 'get'),
                    dataType: 'json',
                    success: function(j) {
                        var options = [],
                            i = 0,
                            o = null;

                        for (i = 0; i < j.length; i++) {
                            // required to get around IE bug (http://support.microsoft.com/?scid=kb%3Ben-us%3B276228)
                            o = document.createElement("OPTION");
                            o.value = typeof j[i] == 'object' ? j[i][settings.key] : j[i];
                            o.text = typeof j[i] == 'object' ? j[i][settings.value] : j[i];
                            settings.target.get(0).options[i] = o;
                        }

                        // hand control back to browser for a moment
                        setTimeout(function() {
                            settings.target
                                .find('option:first')
                                .attr('selected', 'selected')
                                .parent('select')
                                .trigger('change');
                        }, 0);
                    },
                    error: function(xhr, desc, er) {
                        // add whatever debug you want here.
                        // alert("an error occurred");
                        if (xhr.responseText != "") {
                            var jsonResponseText = $.parseJSON(xhr.responseText);
                            var jsonResponseStatus = '';
                            var message = '';
                            $.each(jsonResponseText, function(name, val) {
                                if (name == "ResponseStatus") {
                                    jsonResponseStatus = $.parseJSON(JSON.stringify(val));
                                    $.each(jsonResponseStatus, function(name2, val2) {
                                        if (name2 == "Message") {
                                            message = val2;
                                        }
                                    });
                                }
                            });
                            console.log(message);
                        }
                    }
                });
            });
        });
    };
})(jQuery);