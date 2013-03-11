jQuery(document).ready(function($) {
	
    $theme_settings = $('#prima-theme-settings');
    // Add open button functionality.
    $('<button id="open-meta-boxes" class="button button-secondary">' + primathemes.openAll + '</button>"')
    .appendTo($('h2', $theme_settings))
    .click(function() {
        $('div.postbox').removeClass('closed');
        postboxes.save_state(pagenow);
        return false;
    });
    // Add close button functionality.
    $('<button id="close-meta-boxes" class="button button-secondary">' + primathemes.closeAll + '</button>"')
    .appendTo($('h2', $theme_settings))
    .click(function() {
        $('div.postbox').addClass('closed');
        postboxes.save_state(pagenow);
        return false;
    });
/**
 * COLOR INPUT BOX
 * credits: Gary Jones
 */
    // Only show the background color input when the background color option type is Color (Hex)
    $('.background-option-types', $theme_settings).each(function() {
        showHideHexColor($(this));
        $(this).change( function() {
            showHideHexColor( $(this) ) 
        });
    });
    // Add color picker to color input boxes.
    $('input:text.color-picker', $theme_settings).each(function (i) {
        $(this).after('<div id="picker-' + i + '" style="z-index: 100; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
        $('#picker-' + i).hide().farbtastic($(this));
    })
    .focus(function() {
        $(this).next().show();
        if ($(this).val() == '') {
            $(this).val('#');
        }
    })
    .blur(function() {
        $(this).next().hide();
        if ($(this).val() == '#') {
            $(this).val('');
        }
    });
    // Show or hide the hex color input.
    function showHideHexColor($selectElement) {
        // Use of hide() and show() look bad, as it makes it display:block before display:none / inline.
        $selectElement.next().css('display','none');
        if ($selectElement.val() == 'hex') {
            $selectElement.next().css('display', 'inline');
        }
    }
});

function prima_confirm( text ) {
	var answer = confirm( text );
	
	if( answer ) { return true; }
	else { return false; }
}

/**
 * Upload Option
 * Allows window.send_to_editor to function properly using a private post_id
 * Dependencies: jQuery, Media Upload, Thickbox
 * Credits: OptionTree
 */
(function ($) {
  uploadOption = {
    init: function () {
      var formfield,
          formID,
          btnContent = true;
      // On Click
      $('.upload_button').live("click", function () {
        formfield = $(this).prev('input').attr('id');
        formID = $(this).attr('rel');
        // Display a custom title for each Thickbox popup.
        var prima_title = '';
        prima_title = $(this).prev().prev('.upload_title').text();
        tb_show( prima_title, 'media-upload.php?post_id='+formID+'&type=image&amp;TB_iframe=1');
        return false;
      });
            
      window.original_send_to_editor = window.send_to_editor;
      window.send_to_editor = function(html) {
        if (formfield) {
          if ( $(html).html(html).find('img').length > 0 ) {
          	itemurl = $(html).html(html).find('img').attr('src');
          } 
		  else {
          	var htmlBits = html.split("'");
          	itemurl = htmlBits[1];
          	var itemtitle = htmlBits[2];
          	itemtitle = itemtitle.replace( '>', '' );
          	itemtitle = itemtitle.replace( '</a>', '' );
          }
          var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
          var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
          var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
          var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
          if (itemurl.match(image)) {
            btnContent = '<img src="'+itemurl+'" alt="" /><a href="#" class="remove">Remove Image</a>';
          } else {
            btnContent = '<div class="no_image">'+html+'<a href="#" class="remove">Remove</a></div>';
          }
          $('#' + formfield).val(itemurl);
          $('#' + formfield).next().next('div').slideDown().html(btnContent);
          tb_remove();
        } else {
          window.original_send_to_editor(html);
        }
      }
    }
  };
  $(document).ready(function () {
	  uploadOption.init();
      // Remove Uploaded Image
      $('.remove').live('click', function(event) { 
        $(this).hide();
        $(this).parents().prev().prev('.upload').attr('value', '');
        $(this).parents('.screenshot').slideUp();
      });
  })
})(jQuery);