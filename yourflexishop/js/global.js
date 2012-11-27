(function ($) {
	$(document).ready(function(){
		
		var ck_no_special_chars = /^[A-Za-z0-9' ]{3,250}$/;
		var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; 	
		
		$('.footer-contact-form form').submit(function(){
			if(!ck_email.test($(".footer-contact-form-email input").val())){
				$(".footer-contact-form-email").addClass("my-error-input");
				return false;
			}else{
				$(".footer-contact-form-email").removeClass("my-error-input");
			}
			if($(".footer-contact-form-message textarea").val().length < 3){
				$(".footer-contact-form-message").addClass("my-error-textarea");
				return false;
			}else{
				$(".footer-contact-form-message").removeClass("my-error-textarea");	
			}
			$(".footer-contact-form-email").removeClass("my-error-input");
			$(".footer-contact-form-message").removeClass("my-error-textarea");
			return true;
		})
		$('.footer-newsletter form').submit(function(){
			if(!ck_email.test($(".ml-input input").val())){
				$(".ml-input").addClass("my-error");
				return false;
			}else{
				$(".ml-input").removeClass("my-error");
			}
			
			$(".ml-input").removeClass("my-error");
			return true;
		})
	})
})(jQuery);