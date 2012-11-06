<div class="custom-search-widget">
<form role="search" method="post" class="searchform" action="<?php echo add_query_arg('ctid',$ctid,get_permalink(245)); ?>">
    <div class="menuTabs">
      <ul>
      <?php foreach ($custom_taxonomies as $taxonomy):?>
      <?php  if(strpos($taxonomy->slug,'windsurf')) :?>
        <li class="<?php ($ctid == $taxonomy->id) ? print 'active' : FALSE; ?>"><a href="<?php echo add_query_arg('ctid',$taxonomy->id); ?>"><?php print $taxonomy->label; ?></a></li>
      <?php endif ?>
      <?php endforeach?>
      </ul>
    </div>
    <div class="b4k-select">
      <div class="b4k-variable-select">
        <?php
          foreach ($variable_select as $term){
            echo b4k_form_select($term->taxonomy,$ctid,$term->name,'b4k_'.$term->slug,$term->term_id);
          }
        ?>
    </div>
    <div class="b4k-separator"></div>
    <div class="b4k-static-select">
      <?php
        $taxonomy = b4ktm_get_custom_taxonomy('b4ktm-condition');
         echo b4k_form_select($taxonomy->slug,$taxonomy->id,$taxonomy->label,'b4k_'.$taxonomy->slug, FALSE);
      ?>
      <?php
        $taxonomy = b4ktm_get_custom_taxonomy('b4ktm-year');
         echo b4k_form_select($taxonomy->slug,$taxonomy->id,$taxonomy->label,'b4k_'.$taxonomy->slug.'min', FALSE);
      ?>
      <?php
        $taxonomy = b4ktm_get_custom_taxonomy('b4ktm-year');
         echo b4k_form_select($taxonomy->slug,$taxonomy->id,$taxonomy->label,'b4k_'.$taxonomy->slug.'max', FALSE);
      ?>
      <?php
        $taxonomy = b4ktm_get_custom_taxonomy('b4ktm-style');
         echo b4k_form_select($taxonomy->slug,$taxonomy->id,$taxonomy->label,'b4k_'.$taxonomy->slug, FALSE);
      ?>
    </div>
    <div class="select">
      <label for="b4k_pmin">Prezzo</label>
      <select name="b4k_pmin">
          <option value="">0</option>
          <option value="10">10€</option>
          <option value="50">50€</option>
          <option value="100">100€</option>
          <option value="150">150€</option>
          <option value="200">200€</option>
          <option value="250">250€</option>
          <option value="300">300€</option>
          <option value="400">400€</option>
          <option value="500">500€</option>
          <option value="600">600€</option>
          <option value="700">700€</option>
          <option value="800">800€</option>
      </select>
    </div>
    </div>
    <div>
        <input type="submit" value="<?php echo $b4k_settings['b4k-search-text']; ?>">
    </div>
    </form>
</div>
