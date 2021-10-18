<?php
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

$rs = new \Bitrix\Main\Type\RandomSequence();
$sId = 'side-panel';
?>
<div class="side-panel" id="<?=$sId?>">

	<div class="side-panel__container" id="<?=$sId?>-container">
		<div class="side-panel__inner" id="<?=$sId?>-inner">

			<button class="side-panel__close" data-panel-close>
				<svg class="icon-svg text-secondary"><use xlink:href="#svg-close"></use></svg>
			</button>
		</div>
	</div>

	<div class="side-panel__controls">
		<?php

		if ($arParams['SHOW_CONTROLS']) {
			$APPLICATION->IncludeFile(
				"include/panels/side-panel-controls.php",
				array(),
				array('SHOW_BORDER' => false)
			);
		}
		?>
	</div>
</div>
