

<script src="jquery.js"></script>
/*menu ususario */
$("#seta_dropdown").click(function(e) {
    $(".menu_user").css("display", "block");
})

$(".right ul ul").mouseleave(function(e) {
    $(".menu_user").css("display", "none");
})

