$(document).ready(function() {
    $(".navShowHide").click(function() {
        var main = $("#mainSectionContainer");
        var nav = $("#sideNavContainer");

        if(main.hasClass("leftPadding")) {
            // nav.hide();
            nav.toggle();
        } else {
            // nav.show();
            nav.toggle();
        }

        main.toggleClass("leftPadding");
    })
});

function notSignedIn() {
    alert("You must be signed in to perform this action");
}