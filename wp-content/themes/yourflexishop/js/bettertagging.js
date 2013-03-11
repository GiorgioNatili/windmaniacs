(function($){
  $(document).ready(function(){

    // Custom taxonomy #ids
    var ids = Array(
                'wdm-windsurf-boards',
                'wdm-windsurf-masts',
                'wdm-windsurf-sails',
                'wdm-windsurf-accessories',
                'wdm-windsurf-booms',
                'wdm-kitesurfing-accessories',
                'wdm-kitesurfing-kites',
                'wdm-kitesurfing-boards'
              );

    // Hide all product category divs
    // if not selected
    for (var i = 0; i < ids.length; i++){
      $('#' + ids[i] + 'div').hide();
    }

    // When a product category is selected, show its subset of categories
    var prod_cat = Array('wpsc_product_categorychecklist');
    var prod_cat_id = '#' + prod_cat[0] + ' input';
    $(prod_cat_id).change(function(){
      var value = this.value;
      switch (value) {
        case '91':
        $('#' + ids[2] + 'div').toggle();
          break;

        case '92':
        $('#' + ids[0] + 'div').toggle();
          break;

        case '93':
        $('#' + ids[3] + 'div').toggle();
          break;

        case '101':
        $('#' + ids[1] + 'div').toggle();
          break;

        case '102':
        $('#' + ids[4] + 'div').toggle();
          break;

        case '122':
        $('#' + ids[5] + 'div').toggle();
          break;

        case '123':
        $('#' + ids[6] + 'div').toggle();
          break;

        case '124':
        $('#' + ids[7] + 'div').toggle();
          break;
      }
    });

    // find all second level taxonomies and avoid multiple tagging
    for (var i = 0; i < ids.length; i++) {
      var tax_id = '#' + ids[i] + 'checklist .children input';
      $(tax_id).change(function(){
        // console.log($(this).parentsUntil('.tabs-panel').parent('div'));
        if($(this).parentsUntil('.children').parent().find(':checked').length > 1){
          $(this).parentsUntil('.children').parent().find(':checked').attr('checked',false);
          $(this).attr('checked',true);
        }
      });

      if($(tax_id).length < 1){
        tax_id = ids[i] + ' input';
        $(tax_id).change(function(){
          // console.log($(this).parentsUntil('.tabs-panel').parent('div'));
          if($(this).parentsUntil('.categorychecklist').parent().find(':checked').length > 1){
            $(this).parentsUntil('.categorychecklist').parent().find(':checked').attr('checked',false);
            $(this).attr('checked',true);
          }
        });
      }
    }

  });
})(jQuery);
