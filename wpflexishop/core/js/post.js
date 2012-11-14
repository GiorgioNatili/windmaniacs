jQuery(document).ready(function($) {
    $(".hidden-field").css("display","none");
    var field = "#" + $('.con-check:checked').val();
    $(field).show();
    $(".con-check").click(function(){
    	var field = "#" + $(this).val();
    	$(".hidden-field").hide();
		$(field).show();
		var name = $(field).find("select :selected").text();
		$("#slider-name").val(name);
 	}); 	
 	$('div.select select').change(function(){
 		var name = $(this).find(":selected").text();
		$("#slider-name").val(name);
 	});
});