<div id="dynamic-upload-images">
    Caricamento immagini (Max 5, Max 1Mb, jpg gif png format) <br />
    <?php for($i = 0; $i < 5; $i++): ?>
	<input type="file" name="uploadfiles_<?php print $i; ?>" value="SELEZIONA FILE DAL DISPOSITIVO" class="uploadfiles" />  
    <?php endfor; ?>
</div>
