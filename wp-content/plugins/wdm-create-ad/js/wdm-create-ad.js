;(function ($) {
  var form_name = 'form.wdm-create-ad';

  var startPreloader = function () {
    console.log('Preloader START');
  };

  var stopPreloader = function () {
    console.log('Preloader STOP');
  };

  $(form_name).submit( function () {
    $.ajax({
      type       :'post',
      url        : '',
      data       : $(form_name).serialize(),
      beforeSend : startPreloader,
      complete   : stopPreloader,
      success    : function (result) {
        var form = $(form_name, result);
        console.log(form[0]);
        $(form_name).replaceWith(form[0]);
      }
    });
    return false;
  });
})(jQuery);
