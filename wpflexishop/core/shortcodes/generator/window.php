<?php
// Get the path to the root.
$full_path = __FILE__;
$path_bits = explode( 'wp-content', $full_path );
$url = $path_bits[0];
// Require WordPress bootstrap.
require_once( $url . '/wp-load.php' );
if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here", PRIMA_DOMAIN));
    global $wpdb;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php _e("Shortcode Generator", PRIMA_DOMAIN); ?></title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript">
function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function primasc_submit() {
	var primasc_output;
	var primasc_panelid = document.getElementById('primasc_panel');
	if (primasc_panelid.className.indexOf('current') != -1) {
		var primascid = document.getElementById('primasc_tag').value;
		switch(primascid)
		{
			case 0:
				tinyMCEPopup.close();
			break;
			case "column2_1_1":
				primasc_output = "[column]<br/>[twocol_one]<br/> First column content <br/>[/twocol_one]<br/>[twocol_one_last]<br/> Second column content <br/>[/twocol_one_last]<br/>[/column]";
			break;
			case "column2_2_1":
				primasc_output = "[column]<br/>[threecol_two]<br/> First column content <br/>[/threecol_two]<br/>[threecol_one_last]<br/> Second column content <br/>[/threecol_one_last]<br/>[/column]";
			break;
			case "column2_1_2":
				primasc_output = "[column]<br/>[threecol_two]<br/> First column content <br/>[/threecol_two]<br/>[threecol_one_last]<br/> Second column content <br/>[/threecol_one_last]<br/>[/column]";
			break;
			case "column3_1_1_1":
				primasc_output = "[column]<br/>[threecol_one]<br/> First column content <br/>[/threecol_one]<br/>[threecol_one]<br/> Second column content <br/>[/threecol_one]<br/>[threecol_one_last]<br/> Third column content <br/>[/threecol_one_last]<br/>[/column]";
			break;
			case "column3_2_1_1":
				primasc_output = "[column]<br/>[fourcol_two]<br/> First column content <br/>[/fourcol_two]<br/>[fourcol_one]<br/> Second column content <br/>[/fourcol_one]<br/>[fourcol_one_last]<br/> Third column content <br/>[/fourcol_one_last]<br/>[/column]";
			break;
			case "column3_1_2_1":
				primasc_output = "[column]<br/>[fourcol_one]<br/> First column content <br/>[/fourcol_one]<br/>[fourcol_two]<br/> Second column content <br/>[/fourcol_two]<br/>[fourcol_one_last]<br/> Third column content <br/>[/fourcol_one_last]<br/>[/column]";
			break;
			case "column3_1_1_2":
				primasc_output = "[column]<br/>[fourcol_one]<br/> First column content <br/>[/fourcol_one]<br/>[fourcol_one]<br/> Second column content <br/>[/fourcol_one]<br/>[fourcol_two_last]<br/> Third column content <br/>[/fourcol_two_last]<br/>[/column]";
			break;
			case "column4":
				primasc_output = "[column]<br/>[fourcol_one]<br/> First column content <br/>[/fourcol_one]<br/>[fourcol_one]<br/> Second column content <br/>[/fourcol_one]<br/>[fourcol_one]<br/> Third column content <br/>[/fourcol_one]<br/>[fourcol_one_last]<br/> Fourth column content <br/>[/fourcol_one_last]<br/>[/column]";
			break;
			case "column5":
				primasc_output = "[column]<br/>[fivecol_one]<br/> First column content <br/>[/fivecol_one]<br/>[fivecol_one]<br/> Second column content <br/>[/fivecol_one]<br/>[fivecol_one]<br/> Third column content <br/>[/fivecol_one]<br/>[fivecol_one]<br/> Fourth column content <br/>[/fivecol_one]<br/>[fivecol_one_last]<br/> Fifth column content <br/>[/fivecol_one_last]<br/>[/column]";
			break;
			case "column6":
				primasc_output = "[column]<br/>[sixcol_one]<br/> First column content <br/>[/sixcol_one]<br/>[sixcol_one]<br/> Second column content <br/>[/sixcol_one]<br/>[sixcol_one]<br/> Third column content <br/>[/sixcol_one]<br/>[sixcol_one]<br/> Fourth column content <br/>[/sixcol_one]<br/>[sixcol_one]<br/> Fifth column content <br/>[/sixcol_one]<br/>[sixcol_one_last]<br/> Sixth column content <br/>[/sixcol_one_last]<br/>[/column]";
			break;
			case "hr":
				primasc_output = "["+ primascid + "]";
			break;
			case "divider":
				primasc_output = "["+ primascid + "]";
			break;
			case "divider_flat":
				primasc_output = "["+ primascid + "]";
			break;
			case "year":
				primasc_output = "["+ primascid + "]";
			break;
			case "date":
				primasc_output = "["+ primascid + " format=\"l, F j, Y\" ]";
			break;
			case "search_form":
				primasc_output = "["+ primascid + "]";
			break;
			case "feedburner_form":
				primasc_output = "["+ primascid + " id=\"\" ]";
			break;
			case "button":
				primasc_output = "["+ primascid + "  color=\"\" icon=\"\" link=\"#\"] Button Text [/" + primascid + "]";
			break;
			case "quote":
				primasc_output = "["+ primascid + "  style=\"boxed\" float=\"\"]  Please insert you content here [/" + primascid + "]";
			break;
			case "box":
				primasc_output = "["+ primascid + "  color=\"\" icon=\"\" ]  Please insert you content here [/" + primascid + "]";
			break;
			case "toggle":
				primasc_output = "["+ primascid + " title=\"Toggle Title\"] <br/><br/>Please insert you content here <br/><br/>[/" + primascid + "]";
			break;
			case "tabs":
				primasc_output= "[tabs]<br/>[tab title=\"First Tab\"]<br/><br/>First Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Second Tab\"]<br/><br/>Second Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Third Tab\"]<br/><br/>Third Tab content goes here.<br/><br/>[/tab]<br/>[/tabs]";
			break;
			case "prima_categories":
				primasc_output = "["+ primascid + " title=\"Categories\" image_width=\"150\" image_height=\"150\" number=\"5\" slider=\"no\"]";
			break;
			case "prima_products":
				primasc_output = "["+ primascid + " title=\"Latest Products\" image_width=\"150\" image_height=\"150\" number=\"5\" slider=\"no\"]";
			break;
			case "prima_bestsellers":
				primasc_output = "["+ primascid + " title=\"Best Sellers\" image_width=\"150\" image_height=\"150\" number=\"5\" slider=\"no\"]";
			break;
			case "prima_testimonials":
				primasc_output = "["+ primascid + " number=\"3\" slider=\"no\"]";
			break;
			case "prima_audio":
				primasc_output = "["+ primascid + " width=\"640\" mp3=\"replacewithyourmp3url\"]";
			break;
			case "prima_video":
				primasc_output = "["+ primascid + " width=\"640\" height=\"264\" m4v=\"replacewithyourm4vurl\"]";
			break;
			default:
				primasc_output="["+primascid + "] Please insert you content here [/" + primascid + "]";
		}
	}
	if(window.tinyMCE) {
		//TODO: For QTranslate we should use here 'qtrans_textarea_content' instead 'content'
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, primasc_output);
		//Peforms a clean up of the current editor HTML. 
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches. 
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}
</script>
	<base target="_self" />
</head>
<body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';
document.getElementById('primasc_tag').focus();" style="display: none">
	<form name="primasc_tabs" action="#">
	<div class="tabs">
		<ul>
			<li id="primasc_tab" class="current">
				<span>
				<a href="javascript:mcTabs.displayTab('primasc_tab','primasc_panel');" onMouseDown="return false;">
					<?php _e("Shortcodes", PRIMA_DOMAIN); ?>
				</a>
				</span>
			</li>
		</ul>
	</div>
	
	<div class="panel_wrapper">
		<!-- gallery panel -->
		<div id="primasc_panel" class="panel current">
		<br />
		<table border="0" cellpadding="4" cellspacing="0">
         <tr>
            <td nowrap="nowrap"><label for="primasc_tag"><?php _e("Select Shortcodes", PRIMA_DOMAIN); ?></label></td>
            <td>
				<select style="width: 200px" name="primasc_tag" id="primasc_tag">
					<option value="0"><?php _e("-- Please Choose --", PRIMA_DOMAIN); ?></option>
					<option value="prima_categories"><?php _e("Product Categories", PRIMA_DOMAIN); ?></option>
					<option value="prima_products"><?php _e("Products", PRIMA_DOMAIN); ?></option>
					<option value="prima_bestsellers"><?php _e("Best Sellers", PRIMA_DOMAIN); ?></option>
					<option value="prima_testimonials"><?php _e("Testimonials", PRIMA_DOMAIN); ?></option>
					<option value="prima_audio"><?php _e("Audio", PRIMA_DOMAIN); ?></option>
					<option value="prima_video"><?php _e("Video", PRIMA_DOMAIN); ?></option>
					<option value="column2_1_1"><?php _e("2 Columns (1:1)", PRIMA_DOMAIN); ?></option>
					<option value="column2_2_1"><?php _e("2 Columns (2:1)", PRIMA_DOMAIN); ?></option>
					<option value="column2_1_2"><?php _e("2 Columns (1:2)", PRIMA_DOMAIN); ?></option>
					<option value="column3_1_1_1"><?php _e("3 Columns (1:1:1)", PRIMA_DOMAIN); ?></option>
					<option value="column3_2_1_1"><?php _e("3 Columns (2:1:1)", PRIMA_DOMAIN); ?></option>
					<option value="column3_1_2_1"><?php _e("3 Columns (1:2:1)", PRIMA_DOMAIN); ?></option>
					<option value="column3_1_1_2"><?php _e("3 Columns (1:1:2)", PRIMA_DOMAIN); ?></option>
					<option value="column4"><?php _e("4 Columns", PRIMA_DOMAIN); ?></option>
					<option value="column5"><?php _e("5 Columns", PRIMA_DOMAIN); ?></option>
					<option value="column6"><?php _e("6 Columns", PRIMA_DOMAIN); ?></option>
					<?php 
					$shortcodes = prima_shortcodes(); 
					foreach ( $shortcodes as $shortcode ) {
						$shortcode_name = str_replace('_', ' ' , $shortcode);
						$shortcode_name = ucwords($shortcode_name);
						echo '<option value="'.$shortcode.'">'.$shortcode_name.'</option>';
					}
					?>
				</select>
			</td>
          </tr>
         
        </table>
		</div>
		
	</div>
		
	
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", PRIMA_DOMAIN); ?>" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e("Insert", PRIMA_DOMAIN); ?>" onClick="primasc_submit();" />
		</div>
	</div>
</form>
</body>
</html>
