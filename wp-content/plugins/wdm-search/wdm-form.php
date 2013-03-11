<div class="custom-search-widget">

    <form role="search" method="get" id="wdm-search" class="searchform" action="<?php print get_permalink(356); ?>">
        <div class="mainTabs">
          <ul>
            <li class="wdm-maincat" data-wdmmaincat="wdm-windsurf" data-defaultcat="wdm-windsurf-boards"><a href="?wdm-category=wdm-windsurf-boards">Windsurf</a></li>
            <li class="wdm-maincat" data-wdmmaincat="wdm-kitesurf" data-defaultcat="wdm-kitesurfing-boards"><a href="?wdm-category=wdm-kitesurfing-boards">Kitesurf</a></li>
          </ul>
        </div>
        
        
        <div id="containerUnderCat">
            
            
            <div class="menuTabs wdm-windsurf" <?php  isset($hide_menutab_surf) ? print 'style="display:none"' : NULL; ?>>
              <ul>
              <?php foreach ($custom_taxonomies as $taxonomy):?>
              <?php  if(strpos($taxonomy->slug,'windsurf')) :?>
                <li data-wdmcat="<?php print $taxonomy->slug; ?>" class="wdm-tab <?php ($wdmcat == $taxonomy->slug) ? print 'active' : FALSE; ?>"><a href="<?php echo /*add_query_arg('wdm-category',$taxonomy->slug);*/ '?wdm-category=' . $taxonomy->slug ?>"><?php print $taxonomy->label; ?></a></li>
              <?php endif ?>
              <?php endforeach?>
              </ul>
            </div>
        
        
            <div class="menuTabs wdm-kitesurf" <?php isset($hide_menutab_kite) ? print 'style="display:none"' : NULL; ?>>
              <ul>
              <?php foreach ($custom_taxonomies as $taxonomy):?>
              <?php  if(strpos($taxonomy->slug,'kitesurf')) :?>
                <li data-wdmcat="<?php print $taxonomy->slug; ?>" class="wdm-tab <?php ($wdmcat == $taxonomy->slug) ? print 'active' : FALSE; ?>"><a href="<?php echo /*add_query_arg('wdm-category',$taxonomy->slug);*/ '?wdm-category=' . $taxonomy->slug ?>"><?php print $taxonomy->label; ?></a></li>
              <?php endif ?>
              <?php endforeach?>
              </ul>
            </div>
            
            
            <div class="clear"></div>
            
            
            <div class="wdm-select">
              <div class="wrapper">
                <div class="wdm-variable-select" id="spinvariablecontainer">
                  <?php
                    foreach ($variable_select as $term){
                      echo wdm_form_select($term->taxonomy,$term->id,$term->name,$term->taxonomy,$term->term_id,FALSE,TRUE);
                    }
                  ?>
                </div>
                <div class="wdm-separator"></div>
                <div class="wdm-static-select">
                  <?php
                    $taxonomy = wdm_get_custom_taxonomy('wdm-condition');
                     echo wdm_form_select($taxonomy->slug,$taxonomy->id,$taxonomy->label,$taxonomy->slug, FALSE);
                  ?>
                  <?php
                    $taxonomy = wdm_get_custom_taxonomy('wdm-year');
                     echo wdm_form_select($taxonomy->slug,$taxonomy->id,'Year min',$taxonomy->slug, FALSE,'ASC', TRUE, 'min');
                  ?>
                  <?php
                    $taxonomy = wdm_get_custom_taxonomy('wdm-year');
                     echo wdm_form_select($taxonomy->slug,$taxonomy->id,'Year Max',$taxonomy->slug, FALSE,'DESC', TRUE, 'max');
                  ?>
                  <?php
                    $taxonomy = wdm_get_custom_taxonomy('wdm-style');
                     echo wdm_form_select($taxonomy->slug,$taxonomy->id,$taxonomy->label,$taxonomy->slug, FALSE);
                  ?>
                </div>
                <?php
                  $prices = array(
                    '10' => '10€',
                    '50' => '50€',
                    '100' => '100€',
                    '150' => '150€',
                    '200' => '200€',
                    '250' => '250€',
                    '300' => '300€',
                    '400' => '400€',
                    '500' => '500€',
                    '600' => '600€',
                    '700' => '700€',
                    '800' => '800€'
                  );
        
                    echo wdm_form_select_range('wdm-price','Prezzo',$prices,'ASC','min');
                    echo wdm_form_select_range('wdm-price','Prezzo',$prices,'DESC', 'max');
                ?>
                <div>
                  <input type="hidden" name="wdm-category" value="<?php print $wdmcat ?>">
                  <input type="submit" value="<?php echo $wdm_settings['wdm-search-text']; ?>">
                </div>
                <div class="clear"></div>
              </div>
            </div>
        
        </div>
    
    </form>
</div>
