<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset;

class RadioOption extends TuningOption
{

	protected $name = 'radio';
	protected $description = 'Radio buttons';

    public function showOption($options = array())
    {
        if ($options['VIEW'] == 'buttons')
        {
            $col = false;
        }
        else
        {
            $col = (!empty($options['GRID_SIZE_VALUE']) ? $options['GRID_SIZE_VALUE'] : 12);
        }

        $cssStyleClass = '';
        if (empty($options['VIEW']) && $options['SHOW_IMAGES'] == 'Y')
        {
            $options['VIEW'] = 'images';
        }

        switch ($options['VIEW'])
        {
            case 'images':
                $cssStyleClass = ' mod-images';
                break;
            case 'buttons':
                $cssStyleClass = ' mod-buttons';
                break;
        }
		?>
        <?php if (!empty($options['VALUES'])): ?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info" data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>">
    <div class="rstuning__option__radio<?=$cssStyleClass?>">
        <?php if($options['NAME']): ?>
			<div class="rstuning__option-opname"><?=$options['NAME']?></div>
        <?php endif;
        echo ($col ? '<div class="rstuning-row">' : '');
        $count = 0;
        foreach ($options['VALUES'] as $arValue): ?>
            <div class="rstuning__option__radio__alone<?=($col ? ' rstuning-col-12 rstuning-col-sm-'.$col : '')?>">
                <input <?
                    ?>class="with-gap" <?
                    ?>type="radio" <?
                    ?>id="<?=$arValue['CONTROL_ID']?>" <?
                    ?>name="<?=$options['CONTROL_NAME']?>" <?
                    ?>value="<?=$arValue['HTML_VALUE']?>" <?
                    ?><?=($arValue['HTML_VALUE'] == $options['VALUE'] ? 'checked="checked"' : '')?> <?
                    ?><?=$options['ATTR']?> <?
                    if (!empty($options['MACROS']) && $options['MACROS'] != '')
                    {
                        ?>data-macros="<?=$options['MACROS']?>" <?
                    }
                ?>>
                <label for="<?=$arValue['CONTROL_ID']?>"><?
                    if ($options['VIEW'] == 'images')
                    {
                        ?><span class="rstuning__option__radio__div rstuning__option__radio__image" title="<?=$arValue['NAME']?>"><?
                            if ($options['HIDE_LABELS'] != 'Y')
                            {
                                if ($col == 12)
                                {
                                    ?><span class="rstuning__option__radio__name mod-left mod-big"><?
                                        ?><?=($options['NUMBERS'] == 'Y' ? '<span class="rstuning__option__radio__number">'.(++$count).'.</span>' : '')?><?
                                        ?><?=$arValue['NAME']?><?
                                    ?></span><?
                                    ?><span class="rstuning__option__radio__around-image"><img src="<?=$arValue['IMAGE']?>" alt="<?=$arValue['NAME']?>" title="<?=$arValue['NAME']?>"></span><?
                                }
                                else
                                {
                                    ?><span class="rstuning__option__radio__around-image"><img src="<?=$arValue['IMAGE']?>" alt="<?=$arValue['NAME']?>" title="<?=$arValue['NAME']?>"></span><?
                                    ?><span class="rstuning__option__radio__name"><?
                                        ?><?=($options['NUMBERS'] == 'Y' ? '<span class="rstuning__option__radio__number">'.(++$count).'.</span>' : '')?><?
                                        ?><?=$arValue['NAME']?><?
                                    ?></span><?
                                }
                            }
                            else
                            { // HIDE_LABELS == Y
                                if ($col == 12 && $options['NUMBERS'] == 'Y')
                                {
                                    ?><span class="rstuning-row mod-align-items-center"><?
                                        ?><span class="rstuning-col-1 mod-text-left"><?
                                            ?><span class="rstuning__option__radio__name mod-big"><?
                                                ?><?=($options['NUMBERS'] == 'Y' ? '<span class="rstuning__option__radio__number">'.(++$count).'.</span>' : '')?><?
                                            ?></span><?
                                        ?></span><?
                                        ?><span class="rstuning-col-11"><?
                                        ?><span class="rstuning__option__radio__around-image"><img src="<?=$arValue['IMAGE']?>" alt="<?=$arValue['NAME']?>" title="<?=$arValue['NAME']?>"></span><?
                                        ?></span><?
                                    ?></span><?
                                }
                                else
                                {
                                    ?><span class="rstuning__option__radio__around-image"><img src="<?=$arValue['IMAGE']?>" alt="<?=$arValue['NAME']?>" title="<?=$arValue['NAME']?>"></span><?
                                }
                            }
                        ?></span><?
                    }
                    else
                    {
                        ?><span class="rstuning__option__radio__div rstuning__option__radio__overname"><span class="rstuning__option__radio__name"><?=$arValue['NAME']?></span></span><?
                    }
                ?></label>
            </div>
        <?php endforeach; ?>
	    <?=($col ? '</div>' : '')?>
	</div>
</div>
        <?php endif; ?><?php
	}

    public function onload ($options = array())
    {
        $asset = Asset::getInstance();

		$asset->addCss(getLocalPath('css/redsign.tuning/options/radio/style.css'));
    }

    public function getPainting()
    {
        ob_start();
?>
body .rstuning__option__radio.mod-images input:hover + label .rstuning__option__radio__div,
body .rstuning__option__radio.mod-images input:checked + label .rstuning__option__radio__div,
body .rstuning__option__radio.mod-buttons input:hover + label .rstuning__option__radio__div,
body .rstuning__option__radio.mod-buttons input:checked + label .rstuning__option__radio__div {
    border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
<?php
        $css = ob_get_contents();
        ob_end_clean();

		return $css;
	}

}
