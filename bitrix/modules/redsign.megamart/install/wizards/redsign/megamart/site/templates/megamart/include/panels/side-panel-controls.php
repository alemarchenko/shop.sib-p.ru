<?php
use \Bitrix\Main\Localiztion\Loc;

?>
<div class="side-panel-controls">
	<div class="side-panel-controls__item">
		<?php
		$APPLICATION->IncludeComponent(
			'rsmm:ui.widget',
			'cart-icon',
			array(
				'AJAX_LOAD' => 'Y',
			),
			false,
			array(
				'HIDE_ICONS' => 'Y'
			)
		);
		?>
	</div>

	<div class="side-panel-controls__item">
		<?php
		$APPLICATION->IncludeComponent(
			'rsmm:ui.widget',
			'favorite-icon',
			array(
				'AJAX_LOAD' => 'Y',
			),
			false,
			array(
				'HIDE_ICONS' => 'Y'
			)
		);
		?>
	</div>

	<div class="side-panel-controls__item">
		<?php
		$APPLICATION->IncludeComponent(
			'rsmm:ui.widget',
			'compare-icon',
			array(
				'AJAX_LOAD' => 'Y',
			),
			false,
			array(
				'HIDE_ICONS' => 'Y'
			)
		);
		?>
	</div>

	<div class="side-panel-controls__item">
		<?php
		$APPLICATION->IncludeFile(
			SITE_DIR.'include/panels/side/recall.php',
			array()
		);
		?>
	</div>
</div>
