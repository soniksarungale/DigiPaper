$(".user-dp").click(function(){
    $(".main-navigation").toggle("fast");
});
$(".menu-icon").click(function(){
    $(".main-navigation").toggle("fast");
});
function spell_check(to){
    if(to=="on"){
        $("#paper-txt").attr("spellcheck",true);
    }
    else{
        $("#paper-txt").attr("spellcheck",false);
    }
}
               function Confirm(title, msg, $true, $false, action) {
        var $content =  "<div class='dialog-ovelay fadeIn'>" +
                        "<div class='dialog zoomIn'><header>" +
                         " <h3> " + title + " </h3> " +
                         "<i class='fas delete-exit fa-times'></i>" +
                     "</header>" +
                     "<div class='dialog-msg'>" +
                         " <p> " + msg + " </p> " +
                     "</div>" +
                     "<footer>" +
                         "<div class='controls'>" +
                             " <button class='button button-primary-flat doAction'>" + $true + "</button> " +
                             " <button class='button button-default-flat cancelAction'>" + $false + "</button> " +
                         "</div>" +
                     "</footer>" +
                  "</div>" +
                "</div>";
        
        $('body').prepend($content);
        
        $('body').off('click', '.doAction');
        $('body').on('click', '.doAction', function () {
            $(this).parents('.dialog-ovelay').find('.dialog').removeClass('zoomIn').addClass('zoomOut');
            $(this).parents('.dialog-ovelay').fadeOut(function () {
                $(this).remove();
            });
            action();
        });
        
        $('.cancelAction, .delete-exit').click(function () {
            $(this).parents('.dialog-ovelay').find('.dialog').removeClass('zoomIn').addClass('zoomOut');
            $(this).parents('.dialog-ovelay').fadeOut(function () {
                $(this).remove();
            });
        });
    }
var excempt = [37,38,39,40,46,8,36,35];
	// Loop through every editiable thing
	$("[contenteditable='true']").each(function(index,elem) {
	    var $elem = $(elem);
	    // Check for a property called data-input-length="value" (<div contenteditiable="true" data-input-length="100">)
	    var length = $elem.data('input-length');
	    // Validation of value
	    if(!isNaN(length)) {
	    	// Register keydown handler
	        $elem.on('keydown',function(evt){
	        	// If the key isn't excempt AND the text is longer than length stop the action.
	            if(excempt.indexOf(evt.which) === -1 && $elem.text().length > length) {
	               evt.preventDefault();
	               return false;
	            }
	        });
	    }
	});