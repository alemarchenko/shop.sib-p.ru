<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

use \Bitrix\Main\EventManager;


$sFormName = $arResult['FORM_NAME'];
$sFormRequestUri = $arResult['REQUEST_URI'];

EventManager::getInstance()->addEventHandler (
	'main',
	'OnEndBufferContent',
	function (&$content) use ($sFormName, $sFormRequestUri)
	{
		$sTagFormStart = '<form action="'.$sFormRequestUri.'" method="POST" id="'.$sFormName.'" novalidate>';
		$sFormScript = <<<EOT
<script type="text/javascript">
(function() {
	'use strict';

	var form = document.forms['{$sFormName}'];

	if (form)
	{
		form.addEventListener('submit', function (event)
		{
			if (form.checkValidity() === false)
			{
				event.preventDefault();
				event.stopImmediatePropagation();
			}
			BX.closeWait();

			form.classList.add('was-validated');
		});

		if (RS.Init)
		{
			RS.Init(['bmd'], form);
		}

		$(form).find('input[data-mask]').each(function ()
		{
			var maskOptions = {
				mask: this.getAttribute('data-mask')
			};

			var mask = new IMask(this, maskOptions);
		});
	}
})();
</script>
EOT;
		$content = str_replace(
			$sTagFormStart,
			$sTagFormStart.$sFormScript,
			$content
		);
	}
);
