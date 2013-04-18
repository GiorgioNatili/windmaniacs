<div id="spinvariablecontainer"></div>
<form id="wdm-create-ad-<?php print $step; ?>" class="wdm-create-ad" method="post" <?php print $form_extra_attr?>>

<div class="formcarbonbackground">

  <div id="stepnavbar">
      
      <ul>
          <li id="step1" class="<?php print ($step == 1) ? 'active' : 'off'; ?>"><span class="step">Step n&deg;1</span><br />
                              <span class="descriptionstep">Scegli la categoria</span>
          </li>
          <li id="step2" class="<?php print ($step == 2) ? 'active' : 'off'; ?>"><span class="step">Step n&deg;2</span><br />
                              <span class="descriptionstep">Seleziona una o pi&ugrave; sottocategorie e <br />inserisci i dati dell'annuncio</span>
          </li>
          <li id="step3" class="<?php print ($step == 3) ? 'active' : 'off'; ?>"><span class="step">Step n&deg;3</span><br />
                              <span class="descriptionstep">Dichiara lo stato d'uso <br />del tuo prodotto in vendita</span>
          </li>
          <li id="step4" class="<?php print ($step == 4) ? 'active' : 'off'; ?>"><span class="step">Step n&deg;4</span><br />
                              <span class="descriptionstep">Carica al massimo <br />5 immagini</span>
          </li>
          <li id="step5" class="<?php print ($step == 5) ? 'active' : 'off'; ?>"><span class="step">Step n&deg;5</span><br />
                          <span class="descriptionstep">Pubblica l'annuncio</span>
          </li>
      </ul>
  
  </div>

  <?php include("step-$step.php"); ?>

  <?php foreach($hidden_value as $name => $value): ?>
    <input type="hidden" name="<?php print $name; ?>" value="<?php print $value; ?>">
  <?php endforeach; ?>

  <input type="hidden" name="wdm_create_ad_form_step" value="<?php print $step; ?>" />
  
  </div>

  <?php if ($step > 1 && $step  < 5): ?>
    <input type="submit" name="action_prev"   value="<?php print _('Step') . ' ' . ($step - 1); ?>" /></button>
  <?php endif; ?>
  <?php if ($button_current): ?>
    <input type="submit" name="submit"        value="Submit" />
  <?php endif; ?>
  <?php if ($step < 5): ?>
    <input type="submit" name="action_next"   value="<?php print _('Step') . ' ' . ($step + 1); ?>"></button>
  <?php endif; ?>
</form>
