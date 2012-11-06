(function ($) {
	$(document).ready(function(){
		
		var ck_no_special_chars = /^[A-Za-z0-9' ]{3,250}$/;
		var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; 	
		
		$('.footer-contact-form form').submit(function(){
			if(!ck_email.test($(".footer-contact-form-email input").val())){
				$(".footer-contact-form-email").addClass("my-error");
				return false;
			}else{
				$(".footer-contact-form-email").removeClass("my-error");
			}
			if($(".footer-contact-form-message textarea").val().length < 3){
				$(".footer-contact-form-message").addClass("my-error");
				return false;
			}else{
				$(".footer-contact-form-message").removeClass("my-error");	
			}
			$(".footer-contact-form-email").removeClass("my-error");
			$(".footer-contact-form-message").removeClass("my-error");
			return true;
		})
	})
})(jQuery);