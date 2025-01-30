// function togglePasswordVisibility($pw, on, $eye) {
//     $pw.attr('type', on ? 'password' : 'text');
//     $eye.toggleClass('fa-eye-slash fa-eye');
// }

// $("[type=password]").each(function() {
//     var $pw = $(this);
//     $pw.css({ position: 'relative' });
//     var $eye = $("<i>").addClass("fa fas fa-eye-slash").click(function() {
//         togglePasswordVisibility($pw, false, $eye);
//         setTimeout(function() {
//             togglePasswordVisibility($pw, true, $eye);
//         }, 800);
//     });
//     $pw.parent().append(
//         $("<div>").addClass("input-group-append").append(
//             $("<span>").addClass("password-button password-button-main").append($eye)
//         )
//     );
// });

$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});