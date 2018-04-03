function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
function show_login(){
    $(".create-ac-box").css("display","none");
    $(".login-ac-box").fadeIn();
}
function show_create(){
    $(".login-ac-box").css("display","none");
    $(".create-ac-box").fadeIn();
}


function back_create(){
    $(".second-step-create").css("display","none");
    $(".first-step-create").fadeIn("slow");    
}
function show_menu(){
    $(".login-page").click(function(){
        $(".login-header .sub-nav ul").stop().fadeOut();
    });
    $(".login-header .sub-nav ul").stop().fadeToggle();

}

//              Password toggle visibility
function passVisibility(inputid,button) {
    var x = document.getElementById(inputid);
    
    if (x.type === "password") {
        x.type = "text";
        $(button).html('<i class="fa fa-eye" aria-hidden="true"></i>');
        
    } else {
        x.type = "password";
        $(button).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    }
    
}
