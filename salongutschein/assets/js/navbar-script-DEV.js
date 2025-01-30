$("#myTogglerNav").on("hidden.bs.collapse", function() {
    // do something…
    $("#myTogglerNav").attr(
        "style",
        "display:block;width: 0px !important;right: -100px !important;"
    );
});

$("#myTogglerNav").on("shown.bs.collapse", function() {
    $("#myTogglerNav").attr(
        "style",
        "right: 0 !important;width: 250px !important;"
    );
});

/**
 * Copyright (c) 2007-2015 Ariel Flesler - aflesler<a>gmail<d>com | http://flesler.blogspot.com
 * Licensed under MIT
 * @author Ariel Flesler
 * @version 2.1.2
 */
;
(function(f) { "use strict"; "function" === typeof define && define.amd ? define(["jquery"], f) : "undefined" !== typeof module && module.exports ? module.exports = f(require("jquery")) : f(jQuery) })(function($) {
    "use strict";

    function n(a) { return !a.nodeName || -1 !== $.inArray(a.nodeName.toLowerCase(), ["iframe", "#document", "html", "body"]) }

    function h(a) { return $.isFunction(a) || $.isPlainObject(a) ? a : { top: a, left: a } }
    var p = $.scrollTo = function(a, d, b) { return $(window).scrollTo(a, d, b) };
    p.defaults = { axis: "xy", duration: 0, limit: !0 };
    $.fn.scrollTo = function(a, d, b) {
        "object" === typeof d && (b = d, d = 0);
        "function" === typeof b && (b = { onAfter: b });
        "max" === a && (a = 9E9);
        b = $.extend({}, p.defaults, b);
        d = d || b.duration;
        var u = b.queue && 1 < b.axis.length;
        u && (d /= 2);
        b.offset = h(b.offset);
        b.over = h(b.over);
        return this.each(function() {
            function k(a) {
                var k = $.extend({}, b, { queue: !0, duration: d, complete: a && function() { a.call(q, e, b) } });
                r.animate(f, k)
            }
            if (null !== a) {
                var l = n(this),
                    q = l ? this.contentWindow || window : this,
                    r = $(q),
                    e = a,
                    f = {},
                    t;
                switch (typeof e) {
                    case "number":
                    case "string":
                        if (/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(e)) { e = h(e); break }
                        e = l ? $(e) : $(e, q);
                    case "object":
                        if (e.length === 0) return;
                        if (e.is || e.style) t = (e = $(e)).offset()
                }
                var v = $.isFunction(b.offset) && b.offset(q, e) || b.offset;
                $.each(b.axis.split(""), function(a, c) {
                    var d = "x" === c ? "Left" : "Top",
                        m = d.toLowerCase(),
                        g = "scroll" + d,
                        h = r[g](),
                        n = p.max(q, c);
                    t ? (f[g] = t[m] + (l ? 0 : h - r.offset()[m]), b.margin && (f[g] -= parseInt(e.css("margin" + d), 10) || 0, f[g] -= parseInt(e.css("border" + d + "Width"), 10) || 0), f[g] += v[m] || 0, b.over[m] && (f[g] += e["x" === c ? "width" : "height"]() * b.over[m])) : (d = e[m], f[g] = d.slice && "%" === d.slice(-1) ? parseFloat(d) / 100 * n : d);
                    b.limit && /^\d+$/.test(f[g]) && (f[g] = 0 >= f[g] ? 0 : Math.min(f[g], n));
                    !a && 1 < b.axis.length && (h === f[g] ? f = {} : u && (k(b.onAfterFirst), f = {}))
                });
                k(b.onAfter)
            }
        })
    };
    p.max = function(a, d) {
        var b = "x" === d ? "Width" : "Height",
            h = "scroll" + b;
        if (!n(a)) return a[h] - $(a)[b.toLowerCase()]();
        var b = "client" + b,
            k = a.ownerDocument || a.document,
            l = k.documentElement,
            k = k.body;
        return Math.max(l[h], k[h]) - Math.min(l[b], k[b])
    };
    $.Tween.propHooks.scrollLeft = $.Tween.propHooks.scrollTop = {
        get: function(a) { return $(a.elem)[a.prop]() },
        set: function(a) {
            var d = this.get(a);
            if (a.options.interrupt && a._last && a._last !== d) return $(a.elem).stop();
            var b = Math.round(a.now);
            d !== b && ($(a.elem)[a.prop](b), a._last = this.get(a))
        }
    };
    return p
});

/**
 * TotalStorage
 *
 * Copyright (c) 2012 Jared Novack & Upstatement (upstatement.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Total Storage is the conceptual the love child of jStorage by Andris Reinman,
 * and Cookie by Klaus Hartl -- though this is not connected to either project.
 */

/**
 * Create a local storage parameter
 *
 * @desc Set the value of a key to a string
 * @example $.totalStorage('the_key', 'the_value');
 * @desc Set the value of a key to a number
 * @example $.totalStorage('the_key', 800.2);
 * @desc Set the value of a key to a complex Array
 * @example	var myArray = new Array();
 *			myArray.push({name:'Jared', company:'Upstatement', zip:63124});
			myArray.push({name:'McGruff', company:'Police', zip:60652};
			$.totalStorage('people', myArray);
			//to return:
			$.totalStorage('people');
 *
 * @name $.totalStorage
 * @cat Plugins/Cookie
 * @author Jared Novack/jared@upstatement.com
 * @version 1.1.2
 * @url http://upstatement.com/blog/2012/01/jquery-local-storage-done-right-and-easy/
 */
(function($) {
    var ls = window.localStorage;
    var supported;
    if (typeof ls == 'undefined' || typeof window.JSON == 'undefined') { supported = false; } else { supported = true; }
    $.totalStorage = function(key, value, options) { return $.totalStorage.impl.init(key, value); }
    $.totalStorage.setItem = function(key, value) { return $.totalStorage.impl.setItem(key, value); }
    $.totalStorage.getItem = function(key) { return $.totalStorage.impl.getItem(key); }
    $.totalStorage.getAll = function() { return $.totalStorage.impl.getAll(); }
    $.totalStorage.deleteItem = function(key) { return $.totalStorage.impl.deleteItem(key); }
    $.totalStorage.impl = {
        init: function(key, value) { if (typeof value != 'undefined') { return this.setItem(key, value); } else { return this.getItem(key); } },
        setItem: function(key, value) {
            if (!supported) { try { $.cookie(key, value); return value; } catch (e) { console.log('Local Storage not supported by this browser. Install the cookie plugin on your site to take advantage of the same functionality. You can get it at https://github.com/carhartl/jquery-cookie'); } }
            var saver = JSON.stringify(value);
            ls.setItem(key, saver);
            return this.parseResult(saver);
        },
        getItem: function(key) {
            if (!supported) { try { return this.parseResult($.cookie(key)); } catch (e) { return null; } }
            return this.parseResult(ls.getItem(key));
        },
        deleteItem: function(key) {
            if (!supported) { try { $.cookie(key, null); return true; } catch (e) { return false; } }
            ls.removeItem(key);
            return true;
        },
        getAll: function() {
            var items = new Array();
            if (!supported) {
                try {
                    var pairs = document.cookie.split(";");
                    for (var i = 0; i < pairs.length; i++) {
                        var pair = pairs[i].split('=');
                        var key = pair[0];
                        items.push({ key: key, value: this.parseResult($.cookie(key)) });
                    }
                } catch (e) { return null; }
            } else { for (var i in ls) { if (i.length) { items.push({ key: i, value: this.parseResult(ls.getItem(i)) }); } } }
            return items;
        },
        parseResult: function(res) {
            var ret;
            try {
                ret = JSON.parse(res);
                if (ret == 'true') { ret = true; }
                if (ret == 'false') { ret = false; }
                if (parseFloat(ret) == ret && typeof ret != "object") { ret = parseFloat(ret); }
            } catch (e) {}
            return ret;
        }
    }
})(jQuery);

//https://www.developerdrive.com/jquery-plugin-for-caching-forms-using-html5-local-storage/
(function($) {
    $.fn.FormCache = function(options) {
        var settings = $.extend({}, options);

        function on_change(event) {
            var input = $(event.target);
            var key = input.parents("form:first").attr("name");
            var data = JSON.parse($.totalStorage[key]);

            if (input.attr("type") == "radio" || input.attr("type") == "checkbox") {
                data[input.attr("name")] = input.is(":checked");
            } else {
                data[input.attr("name")] = input.val();
            }

            $.totalStorage[key] = JSON.stringify(data);
        }

        return this.each(function() {
            var element = $(this);

            if (typeof Storage !== "undefined") {

            } else {
                // alert('local storage is not available');
                console.error("local storage is not available.");
            }

            var key = element.attr("name");

            var data = false;
            if ($.totalStorage[key]) {
                data = JSON.parse($.totalStorage[key]);
            }

            if (!data) {
                $.totalStorage[key] = JSON.stringify({});
                data = JSON.parse($.totalStorage[key]);
            }
            element.find("input, select").change(on_change);

            element.find("input, select").each(function() {
                if ($(this).attr("type") != "submit") {
                    var input = $(this);
                    var value = data[input.attr("name")];
                    if (
                        input.attr("type") == "radio" ||
                        input.attr("type") == "checkbox"
                    ) {
                        if (value) {
                            input.attr("checked", input.is(":checked"));
                        } else {
                            input.removeAttr("checked");
                        }
                    } else {
                        input.val(value);
                    }
                }
            });

        });
    };
})(jQuery);

// set text button loading
(function($) {
    $.fn.buttonSalo = function(action) {
        if (action === "loading" && this.data("loading-text")) {
            this.data("original-text", this.html())
                .html(this.data("loading-text"))
                .prop("disabled", true);
        }
        if (action === "reset" && this.data("original-text")) {
            this.html(this.data("original-text")).prop("disabled", false);
        }
    };
})(jQuery);
// check is object
function isAnyObject(value) {
    return (
        value != null && (typeof value === "object" || typeof value === "function")
    );
}
// check is nan
function definitelyNaNop(val, def) {
    var def = def || null;
    // alert(val)
    //https://stackoverflow.com/questions/2652319/how-do-you-check-that-a-number-is-nan-in-javascript
    if (typeof val == "number") {
        return parseFloat(val);
    }
    if (val == "") {
        return null;
    }
    if (typeof val === "undefined") {
        return null;
    }
    val = val && val !== true ? Number(val) : parseFloat(val);

    if (isNaN(val)) val = def;
    return val;
}

// https://stackoverflow.com/questions/12462318/find-a-value-in-an-array-of-objects-in-javascript/33097318
Array.prototype.getIemtByParam = function(paramPair) {
    var key = Object.keys(paramPair)[0];
    return this.find(function(item) {
        return item[key] == paramPair[key] ? true : false;
    });
};

function getIemtByStorage(t_arrr, key) {
    if (t_arrr instanceof Array) {
        let item = t_arrr[0];
        return typeof item[key] !== 'undefined' ? item[key] : null;
    } else {
        console.log('Storage (' + key + ') empty');
    }
    return null;
}

if (!String.prototype.startsWith) {
    console.log('!String.prototype.startsWith');
    String.prototype.startsWith = function(searchString, position) {
        position = position || 0;
        return this.indexOf(searchString, position) === position;
    };
}

// Check if the phone number starts with the defined string
function checkDePhoneNumber(fieldValuePhone) {
    // var fieldValuePhone = $('#mobilenumber').val();
    fieldValuePhone = fieldValuePhone.replace(/\s/g, '');
    if (fieldValuePhone !== '') {
        if (
            fieldValuePhone.startsWith('015') ||
            fieldValuePhone.startsWith('016') ||
            fieldValuePhone.startsWith('017') ||
            fieldValuePhone.startsWith('+4915') ||
            fieldValuePhone.startsWith('+4916') ||
            fieldValuePhone.startsWith('+4917') ||
            fieldValuePhone.startsWith('004915') ||
            fieldValuePhone.startsWith('004916') ||
            fieldValuePhone.startsWith('004917')
        ) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function flatten(arr, values) {
    arr = arr.concat(values);
    return arr;
}