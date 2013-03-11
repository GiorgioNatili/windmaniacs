(function ($) {
  $(document).ready(function(){

    var ck_no_special_chars = /^[A-Za-z0-9' ]{3,250}$/;
    var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

    var opts = {
      lines: 17, // The number of lines to draw
      length: 0, // The length of each line
      width: 3, // The line thickness
      radius: 12, // The radius of the inner circle
      corners: 0.2, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      color: '#0099FF', // #rgb or #rrggbb
      speed: 1, // Rounds per second
      trail: 62, // Afterglow percentage
      shadow: true, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: '20', // Top position relative to parent in px
      left: 'auto' // Left position relative to parent in px
    }

    var target = document.getElementById('spinvariablecontainer');
    //var spinner = new Spinner(opts).spin(target);

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
    });

    //Ajax form select
    $('.wdm-maincat').live('click',function(event){
      event.preventDefault();
      var tab = '.' + $(this).data('wdmmaincat');
      var defaultcat = $(this).data('defaultcat');
      $('input[name="wdm-category"]').val(defaultcat);
      $('.wdm-maincat').removeClass('active');
      $('.wdm-maincat').each(function(){
        $('.menuTabs').hide();
      });
      //$('.wdm-variable-select').fadeTo('fast',0.1);
      $('.wdm-variable-select .select').fadeTo('fast',0.1);
      var spinner = new Spinner(opts).spin(target);
      $(this).toggleClass('active');
      $(tab).show();
      $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
          action: 'wdm_ajax_select',
          wdmcat: defaultcat
        },
        success: function(data){
          $('.wdm-variable-select').empty();
          $('.wdm-variable-select').append(data);
          //$('.wdm-variable-select').fadeTo('fast',1);
          $('.wdm-variable-select .select').fadeTo('fast',1);
          $('.menuTabs li').removeClass('active');
          $('.menuTabs li:first-child').addClass('active');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
          console.log(errorThrown);
        }
      });
    });

    //Ajax form replacement
    $('.wdm-tab').live('click',function(event){
      event.preventDefault();
      var clicked_tab = $(this);
      var wdmcat = $(this).data('wdmcat');
      $('input[name="wdm-category"]').val(wdmcat);
      //$('.wdm-variable-select').fadeTo('fast',0.3);
      $('.wdm-variable-select .select').fadeTo('fast',0.3);
      var spinner = new Spinner(opts).spin(target);
      $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        data: {
          action: 'wdm_ajax_select',
          wdmcat: wdmcat
        },
        success: function(data){
          $('.wdm-variable-select').empty();
          $('.wdm-variable-select').append(data);
          //$('.wdm-variable-select').fadeTo('fast',1);
          $('.wdm-variable-select .select').fadeTo('fast',1);
          $('.menuTabs li').removeClass('active');
          clicked_tab.addClass('active');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
          console.log(errorThrown);
        }
      });
    });
  });
})(jQuery);
