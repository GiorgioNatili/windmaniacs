;(function ($) {

  // Spinner options
  var spinner_opts = {
    lines: 13, // The number of lines to draw
    length: 700, // The length of each line
    width: 4, // The line thickness
    radius: 10, // The radius of the inner circle
    corners: 1, // Corner roundness (0..1)
    rotate: 0, // The rotation offset
    color: '#000', // #rgb or #rrggbb
    speed: 1, // Rounds per second
    trail: 60, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: false, // Whether to use hardware acceleration
    className: 'spinner', // The CSS class to assign to the spinner
    zIndex: 2e9, // The z-index (defaults to 2000000000)
    top: 'auto', // Top position relative to parent in px
    left: 'auto' // Left position relative to parent in px
  };

  // Form hider target
  var target = $('#spinvariablecontainer').hide();

  // Spinner
  var spinner = new Spinner(spinner_opts);

  // Start preloader
  var startPreloader = function () {
    $(target).fadeIn();
    spinner.spin(target);
  };

  // Stop preloader
  var stopPreloader = function () {
    spinner.stop();
    $(target).fadeOut();
  };

  var ajaxForm = function (form_name) {
    $(form_name).find(":submit").click(function(event) {
      var form = $(this).parents("form");
      var data = $(form).serialize();
      var button = event.target;
      data = data + "&" + button.name + "=" + button.value;

      $.ajax({
        type       :'post',
        url        : '',
        data       : data,
        beforeSend : startPreloader,
        complete   : stopPreloader,
        success    : function (result) {
          // Find form in response..
          var form = $(form_name, result);
          // ..replace form in DOM..
          $(form_name).replaceWith(form[0]);
          // ..bind events on injected code.
          ajaxForm(form_name);

          // Add TABS
          var selectTab = function (item) {
            var classname = 'tab-selected';

            $('#tabscontainer ul li a', form_name).removeClass(classname);
            $('#tabscontainer ul li a[href="' + item + '"]').addClass(classname);

            $('#tabscontainer div', form_name).hide();
            $('#tabscontainer div' + item, form_name).show();
          }

          $('#tabscontainer div', form_name).hide();
          selectTab($('#tabscontainer ul li a:first').attr('href'));

          $('#tabscontainer ul li a', form_name).click(function (e) {
            var selector = $(this).attr('href');
            selectTab(selector);
            e.preventDefault();
          });
        }
      });

      // stop form from submitting normally
      if (event.preventDefault) {
        event.preventDefault();
      } else {
        event.returnValue = false;
      }
    });

    // To autosubmit form on "main category" change
    $('#wdm-ad-main-category', form_name).change(function (value) {
      $('[name="submit"]', form_name).click();
    });

    // Remove binded event on submit images
    $('#wdm-create-ad-4 input[name="action_next"]').unbind('click');
  }

  // Init
  ajaxForm('form.wdm-create-ad');

})(jQuery);
