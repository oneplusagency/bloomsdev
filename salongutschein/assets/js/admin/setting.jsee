jQuery.validator.setDefaults({
    ignore: ':hidden',
    errorClass: 'validation-error-label',
    validClass: 'has-success',
    // debug: true,
    lang: 'de', // or whatever language option you have.
    highlight: function(element, errorClass, validClass) {
        $(element)
            .addClass(errorClass)
            .removeClass(validClass);
        $(element.form)
            .closest('.tt-form-group')
            .find('label[for=' + element.id + ']')
            .addClass(errorClass);
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element)
            .removeClass(errorClass)
            .addClass(validClass);
        $(element.form)
            .closest('.tt-form-group')
            .find('label[for=' + element.id + ']')
            .removeClass(errorClass);
    }
});


jQuery(document).ready(function($) {
    // validate the comment form when it is submitted
    $("#setting-form").validate({});
});