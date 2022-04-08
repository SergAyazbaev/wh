<?php

	$script = <<<JS

window.opener.location.href('sklad/in');
window.close();


JS;
	$this->registerJs( $script );
?>



