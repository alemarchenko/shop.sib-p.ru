<?php
namespace Redsign\MegaMart\Layouts\Parts;

class SectionHeaderBase extends Base
{
	public function show()
	{
		?><div class="section-head"><?
			$sTitleId = $this->getData('TITLE_ID');
			?><div class="section-head__title"<?php if (!empty($sTitleId)) echo ' '.$sTitleId; ?>><?
				?><h2 class="h4"><?
					$sTitleLink = $this->getData('TITLE_LINK');
					if (!empty($sTitleLink)) {
						?><a href="<?=$sTitleLink?>"><?=$this->getData('TITLE')?></a><?
					} else {
						?><?=$this->getData('TITLE')?><?
					}
				?></h2><?
			?></div><?

            if (!is_null($this->layout->slider)) {
                ?><div class="section-head__controls"><?
                    
                    ?><div data-slider-dots="<?=$this->layout->slider?>" class="slider-dots"></div><?
                    ?><div data-slider-nav="<?=$this->layout->slider?>" class="slider-nav"></div><?
                ?></div><?
            } else {                
                $arButtons = $this->getData('BUTTONS');
                if (!empty($arButtons) && is_array($arButtons)) {
                    ?><div class="section-head__controls"><?
                    foreach ($arButtons as $arButton) {
                        ?><a href="<?=$arButton['LINK']?>" class="btn btn-primary"><?=$arButton['NAME']?></a><?
                    }
                    ?></div><?
                }
            }

		?></div><?
	}
}
