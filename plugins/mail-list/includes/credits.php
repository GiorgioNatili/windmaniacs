<?php

//show the credits
function danycode_credits($credits_plugin_name,$credits_plugin_url){
	?>
	
	<div class="danycode-credits-separator"></div>
	<div class="danycode-credits-container clearfix">
		<div class="danycode-credits-container-left">
			<p>I have spent a lot of time researching and developing my software and if you wish to show you appreciation, you can give me a small donation via PayPal through the following Button.</p>
		</div>
		<div class="danycode-credits-container-right">
			<div class="danycode-paypal-button">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="HFBK9ZH4KDAS8">
			<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
			</form>	
			</div>
		</div>
	</div>
	<?php
	echo '<div class="danycode-credits-link"><p>Ask for support at <a target="_blank" href="'.$credits_plugin_url.'">'.$credits_plugin_name.' Official Page</a><br />Know more about my software at <a target="_blank" href="http://www.danycode.com">danycode.com</a></p></div>';
}

?>
