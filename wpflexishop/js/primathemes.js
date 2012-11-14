jQuery(document).ready(function($){
    /* PrettyPhoto */
    jQuery("a[rel^='prettyPhoto']").prettyPhoto({social_tools:false});
	
	$('a.emptycart').live('click', function(){
		var cartText = $('#cart-top a span.cartcount').text();
		cartText = cartText.replace (/\d+/g, "0");
		$('#cart-top a span.cartcount').text(cartText);
	});

    /* Checkout Slider */
    $("a#checkout-next").click(function(){
        $("#shopping-cart-form").fadeIn();
        var checkoutWidth = $("#shopping-cart").width() + 30;
        $("#checkout-bar-in").animate({width:'+=50%'});
        $("#checkout-slider").animate({marginLeft:'-=' + checkoutWidth}, 800, function() {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
		});
		return false;		
    });
    $("a#checkout-back").click(function(){
        $("#shopping-cart-form").fadeOut();
        var checkoutWidth = $("#shopping-cart").width() + 30;
        $("#checkout-bar-in").animate({width:'-=50%'});
        $("#checkout-slider").animate({marginLeft:'+=' + checkoutWidth}, 800, function() {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
		});
		return false;		
    });
	
    /* Image Hover Effect */
    $('a.large-blog-image, a.category-thumbnail img').hover(function(){
        $(this).fadeTo('slow', 0.5);
    },
    function(){
        $(this).fadeTo('slow', 1);
    });
    
    /* Dynamic Variation Stock */
	jQuery(".wpsc_select_variation").live('change', function() {
		jQuery('option[value="0"]', this).attr('disabled', 'disabled');
		parent_form = jQuery(this).parents("form.product_form");
		form_values =jQuery("input[name='product_id'], .wpsc_select_variation",parent_form).serialize( );
		jQuery.post( 'index.php?update_variation_stock=true', form_values, function(returned_data) {
			variation_msg = '';
			eval(returned_data);
			if( product_id != null ) {
				if( variation_msg != '' ){
					if(variation_status){
						jQuery("p#stock_display_"+product_id).removeClass('out_of_stock');	
						jQuery("p#stock_display_"+product_id).addClass('in_stock');	
					}else{
						jQuery("p#stock_display_"+product_id).removeClass('in_stock');	
						jQuery("p#stock_display_"+product_id).addClass('out_of_stock');	
					}
					jQuery("p#stock_display_"+product_id).html(variation_msg);
				}
			}
		});
		return false;
	});
    
    /* Dynamic Variation Image */
jQuery(document).ready(function($) {
	jQuery(".wpsc_select_variation").live('change', function() {
		jQuery('option[value="0"]', this).attr('disabled', 'disabled');
		parent_form = jQuery(this).parents("form.product_form");
		form_values =jQuery("input[name='product_id'], .wpsc_select_variation",parent_form).serialize( );
		jQuery.post( 'index.php?update_variation_image=true', form_values, function(returned_data) {
			variation_full_img = '';
			variation_single_img = '';
			variation_img_title = '';
			eval(returned_data);
			if( product_id != null ) {
				if( variation_single_img != '' ){
					jQuery("#main_image_"+product_id+" img").attr("src", variation_single_img);
					jQuery("#main_image_"+product_id+" a").attr("href", variation_full_img);
					jQuery("#main_image_"+product_id+" a").attr("title", variation_img_title);
				}
			}
		});
		return false;
	});
});

    /* Multiple Image */
	jQuery(".single_image_thumb a").live('click', function() {
		image_thumb_id = jQuery(this).attr("id");
		product_full_img = jQuery("#single_"+image_thumb_id).attr("href");
		product_single_img = jQuery("#single_"+image_thumb_id+" img").attr("src");
		product_img_title = jQuery("#single_"+image_thumb_id).attr("title");
		if( product_id != null ) {
			if( product_single_img != '' ){
				jQuery("#main_image_"+product_id+" img").attr("src", product_single_img);
				jQuery("#main_image_"+product_id+" a").attr("href", product_full_img);
				jQuery("#main_image_"+product_id+" a").attr("title", product_img_title);
			}
		}
		return false;
	});

    /* Shortcodes 
	$('div.prima-multislide-box').hover(function(){
		$(this).fadeTo('slow', 0.5);
	},
	function(){
		$(this).fadeTo('slow', 1);
	});*/
	// When page loads...
	jQuery(".ps-tab_content").hide(); //Hide all content
	jQuery("ul.ps-tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".ps-tab_content:first").show(); //Show first tab content
	// On Click Event
	jQuery("ul.ps-tabs li").click(function() {
		jQuery("ul.ps-tabs li").removeClass("active"); //Remove any "active" class
		jQuery(this).addClass("active"); //Add "active" class to selected tab
		jQuery(".ps-tab_content").hide(); //Hide all tab content
		var activeTab = jQuery(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		jQuery(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});
	$(".ps-toggle-content").hide();
	$(".ps-toggle-trigger").click(function(){
		$(this).toggleClass("active").next().slideToggle("slow");
		return false; 
	});

    /* GoldCart product search */
	$('input.wpsc_product_search').val(flexiSearchText);
	jQuery('input.searchbox, input.wpsc_product_search').each(function(){
		var txtval = jQuery(this).val();
		jQuery(this).focus(function(){
			jQuery(this).val('')
		});
		jQuery(this).blur(function(){
			if(jQuery(this).val() == ""){
				jQuery(this).val(txtval);
			}
		});
	});
    
});
