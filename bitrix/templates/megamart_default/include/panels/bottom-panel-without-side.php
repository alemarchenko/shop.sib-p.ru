<?php
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

if ($arParams['SHOW_CONTROLS']):

	$rs = new \Bitrix\Main\Type\RandomSequence();
	$sId = 'bottom-panel';
	?>
	<div class="bottom-panel js-fix-scroll" id="<?=$sId?>">
		<div class="bottom-panel__container" id="<?=$sId?>-container">
			<div class="bottom-panel__close" onclick="event.preventDefault(); RS.Panel.close();"><svg class="icon-svg"><use xlink:href="#svg-close"></use></svg></div>
			<div class="bottom-panel__inner-drag" id="<?=$sId?>-drag-area">
				<div class="hamburger hamburger--resize text-primary">
					<div class="hamburger__box">
						<div class="hamburger__inner"></div>
					</div>
				</div>
			</div>
			<div class="bottom-panel__inner" id="<?=$sId?>-inner"></div>
		</div>

		<div class="bottom-panel__controls">
			<?php
			$APPLICATION->IncludeFile(
				"include/panels/bottom-panel-controls.php",
				array(),
				array('SHOW_BORDER' => false)
			);
			?>
		</div>

	</div>
<?php endif;
