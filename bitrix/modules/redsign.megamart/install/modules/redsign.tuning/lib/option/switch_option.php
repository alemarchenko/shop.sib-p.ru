<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

class SwitchOption extends TuningOption
{

	protected $name = 'switch';
	protected $description = 'Switch button';
	protected $isSortable = true;

	public function echoSwitch($options = array(), $params = array())
	{
		?><input <?
			?>class="rstuning__option__switch-unchecked" <?
			?>type="checkbox" <?
			?>id="<?=$options['CONTROL_ID']?>_unchecked" <?
			?>name="<?=$options['CONTROL_NAME']?>" <?
			?>value="N" <?
			?>checked="checked" <?
		?>><?
		?><label for="<?=$options['CONTROL_ID']?>"><?
			?><input <?
				?>type="checkbox" <?
				?>id="<?=$options['CONTROL_ID']?>" <?
				?>name="<?=$options['CONTROL_NAME']?>" <?
				?>value="Y" <?
				?><?=($options['HTML_VALUE'] == $options['VALUE'] ? 'checked="checked"' : '')?> <?
				?><?=$options['ATTR']?> <?
				if (!empty($options['MACROS']) && '' != $options['MACROS'])
				{
					?>data-macros="<?=$options['MACROS']?>" <?
				}
			?>><?php
			?><span class="lever<?=(!empty($params['LEVER_CLASS']) ? ' '.$params['LEVER_CLASS'] : '')?>"></span><?php
		?></label><?
	}

	public function echoOptionSortable($options = array())
	{
		$svgSortable = '<div class="rstuning__option__dd__drag js-rstuning-sortable-handle">
	<svg xmlns="http://www.w3.org/2000/svg" width="20.5" height="20.5" viewBox="0 0 20.5 20.5">
		<path class="cls-1" d="M21.274,12.219L25,8.493l3.651,3.651M30.8,14.3l3.7,3.7-3.693,3.693m-2.15,2.15L25,27.507l-3.8-3.8m-2.022-2.022L15.493,18l3.7-3.7" transform="translate(-15 -8)"></path>
		<path class="cls-1" d="M21.274,12.219L25,8.493l3.651,3.651M30.8,14.3l3.7,3.7-3.693,3.693m-2.15,2.15L25,27.507l-3.8-3.8m-2.022-2.022L15.493,18l3.7-3.7" transform="translate(-15 -8)"></path>
		<rect class="cls-2" x="10" y="1" width="1" height="7"></rect>
		<rect class="cls-2" x="10" y="13" width="1" height="7"></rect>
		<rect class="cls-2" x="1" y="10" width="7" height="1"></rect>
		<rect class="cls-2" x="13" y="10" width="7" height="1"></rect>
	</svg>
</div>';

		?><div class="rstuning-row mod-align-items-center" data-view="Sortable"><?

			?><div class="rstuning-col-1 mod-text-left mod-dnone-sm mod-dnone-md"><?
				?><div class="rstuning__option__dd__counter-increment"></div><?
			?></div><?

			?><div class="rstuning-col-9 rstuning-col-md-10 rstuning-col-lg-7"><?
				?><span class="rstuning__option__switch-name mod-small"><?=$options['NAME']?></span><?
			?></div><?

			?><div class="rstuning-col-2 mod-text-center mod-dnone-sm mod-dnone-md"><?=$svgSortable?></div><?

			?><div class="rstuning-col-3 rstuning-col-md-2 mod-text-center"><?
				self::echoSwitch($options);
			?></div><?

		?></div><?
	}

	public function echoOptionImage($options = array())
	{
		$imageSrc = (!empty($options['IMAGE']) && file_exists(Application::getDocumentRoot().$options['IMAGE']) ? $options['IMAGE'] : false );

		?><div class="rstuning__option__switch-borders" data-view="Image"><?
			?><div class="rstuning__option__switch-image mod-mt-11 mod-mb-11 mod-text-center"><img src="<?=$imageSrc?>" alt="" title=""></div><?php
		?></div><?

		?><div class="mod-text-center"><?
			self::echoSwitch($options, array('LEVER_CLASS' => 'mod-mt-15 mod-mb-7'));
		?></div><?
	}

	public function echoOptionImageLabel($options = array())
	{
		$imageSrc = (!empty($options['IMAGE']) && file_exists(Application::getDocumentRoot().$options['IMAGE']) ? $options['IMAGE'] : false );

		?><div class="rstuning__option__switch-borders" data-view="ImageLabel"><div><?
			?><div class="rstuning-row mod-align-items-center"><?

				?><div class="rstuning-col-9 rstuning-col-md-10"><?
					?><span class="rstuning__option__switch-name mod-small"><?=$options['NAME']?></span><?
				?></div><?

				?><div class="rstuning-col-12 rstuning-col-md-2 mod-text-center"><?
					self::echoSwitch($options);
				?></div><?

				?><div class="rstuning-col-12 mod-text-center"><?
					?><div class="rstuning__option__switch-image mod-mt-16 mod-mb-11"><img src="<?=$imageSrc?>" alt="" title=""></div><?php
				?></div><?

			?></div><?
		?></div></div><?
	}

	public function echoOptionImageLabelGrid($options = array())
	{
		$imageSrc = (!empty($options['IMAGE']) && file_exists(Application::getDocumentRoot().$options['IMAGE']) ? $options['IMAGE'] : false );

		?><div class="rstuning__option__switch-borders" data-view="ImageLabelGrid"><?
			?><div class="rstuning__option__switch-image mod-text-center"><img src="<?=$imageSrc?>" alt="" title=""></div><?php

			?><div class="rstuning__option__switch-name mod-extra-small mod-text-center"><?=$options['NAME']?></div><?
		?></div><?

		?><div class="mod-text-center"><?
			self::echoSwitch($options, array('LEVER_CLASS' => 'mod-mt-15 mod-mb-7'));
		?></div><?
	}

	public function showOption($options = array())
	{
		$col = (!empty($options['GRID_SIZE']) ? $options['GRID_SIZE'] : 12);
		$imageSrc = (!empty($options['IMAGE']) && file_exists(Application::getDocumentRoot().$options['IMAGE']) ? $options['IMAGE'] : false );
?>
<div <?
	?>class="rstuning__option rstuning-col-12<?=($col ? ' rstuning-col-sm-'.$col : '')?> <?=$options['CSS_CLASS']?> js-rs_option_info" <?
	?>data-reload="<?=('Y' == $options['RELOAD'] ? 'Y' : 'N')?>" <?
	?>data-sortable="<?=($options['SORTABLE'] ? 'Y' : 'N')?>" <?
	?>>
	<?php if ($options['SORTABLE']): ?><?
		?><input <?
			?>type="hidden" <?
			?>name="<?=$options['SORTABLE']['CONTROL_NAME']?>[]" <?
			?>value="<?=$options['ID']?>" <?
		?>><?
	?><?php endif; ?>
	<div class="rstuning__option__switch <?
		?>switch<?
		?><?=($options['HTML_VALUE'] == $options['VALUE'] ? ' active' : '')?><?
		?>">
		<div class="label"><?php

			if (!empty($options['SORTABLE']) && empty($imageSrc) && $col == 12)
			{
				self::echoOptionSortable($options);
			}
			elseif (empty($options['SORTABLE']) && !empty($imageSrc) && empty($options['NAME']) && $col == 12)
			{
				self::echoOptionImage($options);
			}
			elseif (empty($options['SORTABLE']) && !empty($imageSrc) && !empty($options['NAME']) && $col == 12)
			{
				self::echoOptionImageLabel($options);
			}
			elseif (empty($options['SORTABLE']) && !empty($imageSrc) && !empty($options['NAME']) && $col != 12)
			{
				self::echoOptionImageLabelGrid($options);
			}
			else
			{
				if (!empty($options['NAME']))
				{
					?><span class="rstuning__option__switch-name"><?=$options['NAME']?></span><?
				}
				self::echoSwitch($options, array('LEVER_CLASS' => 'mod-bottom-2'));
			}

		?></div>
	</div>
</div>
		<?
	}

	public function onload($options = array())
	{
		$asset = Asset::getInstance();

		$asset->addJs(getLocalPath('css/redsign.tuning/options/switch/script.js'), true);
		$asset->addCss(getLocalPath('css/redsign.tuning/options/switch/style.css'));
    }

    public function getPainting()
    {
        ob_start();
?>
body .rstuning .switch label input[type=checkbox]:checked + .lever {
	background-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
body .rstuning .rstuning__option__switch.active .rstuning__option__switch-borders {
	border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
<?php
        $css = ob_get_contents();
        ob_end_clean();

		return $css;
	}

}
