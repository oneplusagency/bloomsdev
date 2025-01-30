/**
 *    Chained Selects for jQuery
 *    Copyright (C) 2008 Ziadin Givan www.CodeAssembly.com
 *
 *   settings = { usePost : true, before:function() {}, after: function() {}, default: null, parameters : { parameter1 : 'value1', parameter2 : 'value2'} }
 *   if usePost is true, then the form will use POST to pass the parameters to the target, otherwise will use GET
 *   "before" function is called before the ajax request and "after" function is called after the ajax request.
 *   If defaultValue is not null then the specified option will be selected.
 *   You can specify additional parameters to be sent to the the server in settings.parameters.
 *
 */
(function($) {
    $.fn.chainSelect = function(target, url, settings) {
        return this.each(function() {
            $(this).change(function() {
                settings = jQuery.extend({
                    after: null,
                    before: null,
                    usePost: false,
                    change: true,
                    defaultValue: null,
                    parameters: {
                        '_id': $(this).attr('id'),
                        '_name': $(this).attr('name')
                    }
                }, settings);

                settings.parameters._value = $(this).val();

                if (settings.before != null) {
                    settings.before(target);
                }

                ajaxCallback = function(data, textStatus) {
                    // console.log(JSON.stringify(data));
                    $(target).html(""); //clear old options
                    // data = eval(data); //get json array
                    // data = JSON.stringify(data); //get json array
                    // console.log(JSON.stringify(data));

                    $.each(data, function(key, value) {
                        if (data.hasOwnProperty(key)) {
                            var option = $("<option />").val(key).append(value);
                            $(target).append(option);
                        }
                    });

                    if (settings.defaultValue != null) {
                        $(target).val(settings.defaultValue); //select default value
                    } else {
                        $("option:first", target).attr("selected", "selected"); //select first option
                    }

                    let self_sel = $('option:selected', $(target)).val();
                    //console.log('self_sel: ' + self_sel);
                    if (!self_sel) {
                        $("option:first", target).attr("selected", "selected"); //select first option
                    }

                    // $(target).val(1090);

                    if (settings.after != null) {
                        settings.after(target);
                    }
                    if (settings.change == true) {
                        $(target).change(); //call next chain
                    }
                };

                if (settings.usePost == true) {
                    $.post(url, settings.parameters, ajaxCallback, 'json');
                } else {
                    $.get(url, settings.parameters, ajaxCallback, 'json');
                }
            });
        });
    };
})(jQuery);