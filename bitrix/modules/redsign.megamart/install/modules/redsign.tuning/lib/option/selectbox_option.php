<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset;

class SelectboxOption extends TuningOption
{

	protected $name = 'selectbox';
	protected $description = 'Selectbox control';

	public function showOption($options = array())
	{
		$svgArrow = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129"><path d="m40.4,121.3c-0.8,0.8-1.8,1.2-2.9,1.2s-2.1-0.4-2.9-1.2c-1.6-1.6-1.6-4.2 0-5.8l51-51-51-51c-1.6-1.6-1.6-4.2 0-5.8 1.6-1.6 4.2-1.6 5.8,0l53.9,53.9c1.6,1.6 1.6,4.2 0,5.8l-53.9,53.9z"></path></svg>';
		?>
		<?php if (empty($options['VALUES']))
			return;
		?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info" data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>">
	<?php if ($options['NAME']): ?>
		<div class="rstuning__option-opname"><?=$options['NAME']?></div>
	<?php endif; ?>
	<div class="rstuning__selectbox js-rstuning__selectbox">
		<input <?
			?>type="text" <?
			?>id="<?=$options['CONTROL_ID']?>" <?
			?>name="<?=$options['CONTROL_NAME']?>" <?
			?>value="<?=$options['VALUE']?>" <?
			?><?=$options['ATTR']?> <?
			if (!empty($options['MACROS']) && $options['MACROS'] != '') {
				?>data-macros="<?=$options['MACROS']?>" <?
			}
		?>>
		<div class="rstuning__selectbox__select js-rstuning__selectbox__select">
		<?php
		$currentValue = array();
		foreach ($options['VALUES'] as $arValue):
			if ($arValue['HTML_VALUE'] == $options['VALUE'])
				$currentValue = $arValue;
		?>
			<div <?
				?>class="rstuning__selectbox__option js-rstuning__selectbox__option <?=($arValue['HTML_VALUE'] == $options['VALUE'] ? ' active' : '')?>" <?
				?>id="<?=$arValue['CONTROL_ID']?>" <?
				?>data-value="<?=$arValue['HTML_VALUE']?>" <?
				?>><?=$arValue['NAME']?></div>
		<?php endforeach; ?>
		</div>
		<div class="rstuning__selectbox__value js-rstuning__selectbox__opener closed"><?
			?><span class="js-rstuning__selectbox__value"><?=$currentValue['NAME']?></span><?
			?><?=$svgArrow?><?
		?></div>
	</div>
</div>
		<?
	}

	public function onload($options = array())
	{
		$asset = Asset::getInstance();

		$asset->addCss(getLocalPath('css/redsign.tuning/options/selectbox/style.css'));
		$asset->addJs(getLocalPath('css/redsign.tuning/options/selectbox/script.js'));
    }

    public function getPainting()
    {
        ob_start();
?>
body .rstuning__selectbox.open .rstuning__selectbox__value {
	border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning__selectbox__value:hover {
	border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning__selectbox__value > svg {
	fill: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#;}
<?php
        $css = ob_get_contents();
        ob_end_clean();

		return $css;
	}

}
