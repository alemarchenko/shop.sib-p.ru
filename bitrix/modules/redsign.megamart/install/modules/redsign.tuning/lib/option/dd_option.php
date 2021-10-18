<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset,
	Redsign\Tuning;

class DDOption extends TuningOption
{

	protected $name = 'Dran&Drop';
	protected $description = 'Dran&Drop wrapper';

	public function showOption($options = array())
	{
		$tuning = Tuning\TuningCore::getInstance();
		$instanceOption = Tuning\TuningOption::getInstance();

		$arResult['OPTIONS'] = array();
		$arResult['TABS'] = array();

		$optionList = $tuning->getInstanceOptionMananger()->getOptionsByIds($options['CHILDREN']);

		foreach ($optionList as $id => $arOption)
		{
			$optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);

			if ($optionObj != null)
			{
				$optionObj->onload();
				if (!$optionObj->isSortable())
					continue;

				$arOption['SORTABLE'] = $options;
				$arOption['ID'] = $id;

				$arOption['VALUE'] = $tuning->getOptionValue($id);

				ob_start();
				$optionObj->showOption($arOption);
				$out = ob_get_contents();
				ob_end_clean();

				$arResult['OPTIONS'][$id] = $arOption;
				$arResult['OPTIONS'][$id]['DISPLAY_HTML'] = $out;
			}
		}
?>
<div class="rstuning__option rstuning-col-12 js-rs_option_info" <?
	?>data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>" <?
	?>data-option-id="<?=$options['ID']?>" <?
	?>data-control-name="<?=$options['CONTROL_NAME']?>" <?
	?>>
	<div class="rstuning__option__dd">
		<?php if($options['NAME']): ?>
			<div class="rstuning__option-opname"><?=$options['NAME']?></div>
        <?php endif; ?>
		<div class="rstuning-row js-rstuning-sortable rstuning__option__dd__counter"><?php
		if (!empty($arResult['OPTIONS']) && !empty($options['VALUE'])):
			foreach ($options['VALUE'] as $id):
				if (!empty($arResult['OPTIONS'][$id]))
				echo $arResult['OPTIONS'][$id]['DISPLAY_HTML'];
			endforeach;
		endif;
		?></div>
	</div>
</div>
		<?
	}

	public function onload($options = array())
	{
		$asset = Asset::getInstance();

		$asset->addJs(getLocalPath('css/redsign.tuning/options/dd/script.js'), true);
		$asset->addCss(getLocalPath('css/redsign.tuning/options/dd/style.css'));

		$asset->addJs(getLocalPath('css/redsign.tuning/options/dd/sortable/sortable.js'), true);
		// $asset->addCss(getLocalPath('css/redsign.tuning/options/dd/sortable/sortable.css'));
	}

	public function prepareValueBeforeRestoreDefault($params)
	{
		return implode(',', $params['OPTION']['DEFAULT']);
	}

	public function prepareValueBeforeSave($params)
	{
		return implode(',', $params['VALUE']);
	}

	public function prepareValueAfterGet($params)
	{
		if (is_array($params['VALUE']))
		{
			$params['OPTION']['VALUE'] = $params['VALUE'];
		}
		else
		{
			$params['OPTION']['VALUE'] = explode(',', $params['VALUE']);
		}
	}

    public function getPainting()
    {
        ob_start();
?>
body .rstuning .rstuning__option__dd__fallback .rstuning__option__dd__drag svg {
    stroke: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
<?php
        $css = ob_get_contents();
        ob_end_clean();

		return $css;
	}

}
