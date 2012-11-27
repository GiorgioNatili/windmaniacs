(function($){
  $(document).ready(function(){
    var ids = Array();
    $('#advanced-sortables .categorydiv').each(function(){
      ids.push('#' + $(this).attr('id'));
    });
    for (var i = 0; i < ids.length; i++) {
      //find all second level taxonomies and avoid multiple tagging
      var tax_id = ids[i] + ' ul.children input';
      $(tax_id).change(function(){
        //console.log($(this).parentsUntil('.tabs-panel').parent('div'));
        if($(this).parentsUntil('.children').parent().find(':checked').length > 1){
          $(this).parentsUntil('.children').parent().find(':checked').attr('checked',false);
          $(this).attr('checked',true);
        }
      });

      if($(tax_id).length < 1){
        tax_id = ids[i] + ' input';
        $(tax_id).change(function(){console.log(this);
          //console.log($(this).parentsUntil('.tabs-panel').parent('div'));
          if($(this).parentsUntil('.categorychecklist').parent().find(':checked').length > 1){
            $(this).parentsUntil('.categorychecklist').parent().find(':checked').attr('checked',false);
            $(this).attr('checked',true);
          }
        });
      }
    }

  });
})(jQuery);
