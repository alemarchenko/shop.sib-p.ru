<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ColorPickerOption extends TuningOption
{

	protected $name = 'colorpicker';
	protected $description = 'Color picker';

	public function showOption($options = array())
	{
		if (!is_array($options['VALUES']) || empty($options['VALUES']))
			return;
?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info" data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>">
	<div class="rstuning__option__colorpicker rstuning__option__colorpicker_<?=$options['CONTROL_ID']?>">

		<?php if (is_array($options['SETS']) && !empty($options['SETS'])):
			$arTmpValue = array();
			foreach ($options['VALUES'] as $valKey => $arValue) {
				$arTmpValue[$valKey] = ($options['VALUE'][$valKey] != '' ? $options['VALUE'][$valKey] : $arValue['HTML_VALUE']);
			}
			$arTmpValue = \Bitrix\Main\Web\Json::encode($arTmpValue);
		?>
		<!-- row --><div class="rstuning-row"><div class="rstuning-col-12">
			<?php if ($options['SETS']['NAME']): ?><div class="rstuning__option-opname"><?=$options['SETS']['NAME']?></div><?php endif; ?>
			<div class="rstuning-row">
				<div class="rstuning-col-12">
					<div class="rstuning__option__colorpicker__sets">
						<?php foreach ($options['SETS']['VALUES'] as $setKey => $arSetValue):
							if (!is_array($arSetValue['VALUES']) || empty($arSetValue['VALUES'])) {
								continue;
							}
							$dataValue = \Bitrix\Main\Web\Json::encode($arSetValue['VALUES']);
						?>
						<div class="rstuning__option__colorpicker__alone-color mod-sets<?=($arTmpValue == $dataValue ? ' active' : '')?>" data-colorpicker-id="rstuning__option__colorpicker_<?=$options['CONTROL_ID']?>">
							<a class="rstuning__option__colorpicker__alone-color__a mod-sets js-colorpicker-set" href="#<?=$arSetValue['CONTROL_ID']?>" data-setkey="<?=$setKey?>" data-inputid="<?=$arSetValue['CONTROL_ID']?>" title="" data-value='<?=$dataValue?>'>
								<div class="rstuning__option__colorpicker__before-paint mod-sets">
									<span class="rstuning__option__colorpicker__incircle mod-sets js-colorpicker-set-paint" style="background: <?=$arSetValue['BACKGROUND']?>"></span>
								</div>
							</a>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div></div><!-- .row -->
		<?php endif; ?>

		<!-- row --><div class="rstuning-row"><div class="rstuning-col-12">
			<?php if ($options['NAME']): ?><div class="rstuning__option-opname"><?=$options['NAME']?></div><?php endif; ?>
			<?php
			$count = 0;
			$arFirstValue = array();
			?>
			<div class="rstuning-row">
				<div class="rstuning-col-12">
					<div class="rstuning__option__colorpicker__values">
					<?php if (!empty($options['VALUES'])): ?>
						<div class="rstuning-row">
						<?php foreach ($options['VALUES'] as $valKey => $arValue): ?>
							<?php
							$value = $options['VALUE'][$valKey] != '' ? $options['VALUE'][$valKey] : $arValue['HTML_VALUE'];
							?>
							<div class="rstuning-col-12 rstuning-col-md-4">
								<div class="rstuning__option__colorpicker__alone-color<?=($count < 1 ? ' active' : '')?> js-rstuning__option__colorpicker__alone-color" data-colorpicker-id="rstuning__option__colorpicker_<?=$options['CONTROL_ID']?>">
									<input <?
										?>type="text" <?
										?>class="rstuning__option__colorpicker__hide-me js-rstuning__option__colorpicker__input" <?
										?>name="<?=$options['CONTROL_NAME']?>[<?=$arValue['CONTROL_NAME']?>]" <?
										?>id="<?=$arValue['CONTROL_ID']?>" <?
										?>data-valkey="<?=$valKey?>" <?
										?>value="<?=$value?>" <?
										?><?=$options['ATTR']?> <?
										if (!empty($arValue['MACROS']) && $arValue['MACROS'] != '')
										{
											?>data-macros="<?=$arValue['MACROS']?>" <?
											if ($arValue['TUNING_COLOR'])
											{
												?>data-tuning-color-macros="<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>" <?
											}
										}
									?>>
									<a class="rstuning__option__colorpicker__alone-color__a js-colorpicker-val" href="#<?=$arValue['CONTROL_ID']?>" data-inputid="<?=$arValue['CONTROL_ID']?>">
										<div class="rstuning__option__colorpicker__before-paint">
											<span class="rstuning__option__colorpicker__incircle js-colorpicker-paint" style="background-color: #<?=$value?>"></span>
										</div>
										<div class="rstuning__option__colorpicker__name"><?=$arValue['NAME']?></div>
									</a>
								</div>
							</div>
							<?php
							if ($count < 1)
							{
								$arFirstValue = $arValue;
							}
							$count++;
							?>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
			$value = $options['VALUE'][$valKey] != '' ? $options['VALUE'][$valKey]  : $arFirstValue['HTML_VALUE'];
			?>
			<input <?
				?>class="colorpickerHolder" <?
				?>id="rstuning__option__colorpicker_<?=$options['CONTROL_ID']?>" <?
				?>type="text" <?
				?>data-dcolor="<?=$value?>" <?
			?>/>
			<div class="colorPickerValues">
				<div class="field r mod-dnone-sm">
					<span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.R')?></span>
					<span class="val"><input readonly="" type="text" value=""></span>
				</div>
				<div class="field g mod-dnone-sm">
					<span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.G')?></span>
					<span class="val"><input readonly="" type="text" value=""></span>
				</div>
				<div class="field b mod-dnone-sm">
					<span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.B')?></span>
					<span class="val"><input readonly="" type="text" value=""></span>
				</div>
				<div class="field hex">
					<span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.HEX')?></span>
					<span class="val"><input class="rstuning__colopicker__input-hex js-colorpicker-hex" type="text" value=""></span>
				</div>
			</div>
		</div></div><!-- .row --><?
		?><script>rsTuningSpectrumInit('rstuning__option__colorpicker_<?=$options['CONTROL_ID']?>');</script>
	</div>
</div>
		<?
	}

	public function onload($options = array())
	{
		$asset = Asset::getInstance();

		$asset->addJs(getLocalPath('css/redsign.tuning/options/colorpicker/script.js'), true);
		$asset->addCss(getLocalPath('css/redsign.tuning/options/colorpicker/style.css'));

		$asset->addJs(getLocalPath('css/redsign.tuning/options/colorpicker/spectrum/spectrum.js'), true);
		$asset->addCss(getLocalPath('css/redsign.tuning/options/colorpicker/spectrum/spectrum.css'));
    }

    public function getPainting()
    {
        ob_start();
?>
body .rstuning__option__colorpicker__alone-color:hover .rstuning__option__colorpicker__before-paint,
body .rstuning__option__colorpicker__alone-color.active .rstuning__option__colorpicker__before-paint {
	border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
<?php
        $css = ob_get_contents();
        ob_end_clean();

		return $css;
	}

}
