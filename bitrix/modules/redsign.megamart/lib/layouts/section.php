<?php
namespace Redsign\MegaMart\Layouts;

use \Redsign\MegaMart\Layouts\Parts;

class Section extends Base
{

	public function useHeader(Parts\Base $header = null)
	{
		$this->registerPart('HEADER', $header);

		return $this;
	}

	public function getHeader() {
		return $this->getPart('HEADER');
	}

	public function isUseHeader()
	{
		return $this->isRegisteringPart('HEADER');
	}

	public function start()
	{
		$sModifiersClasses = '';
		foreach ($this->modifiers as $modifier) {
			$sModifiersClasses .= ' l-section--'.$modifier;
		}

		$sAttributes = $this->getData('SECTION_ATTRIBUTES');
		$sMainAttributes = $this->getData('SECTION_MAIN_ATTRIBUTES');
		$sHeaderAttributes = $this->getData('HEADER_ATTRIBUTES');

		echo '<section class="l-section'.$sModifiersClasses.'"'.$sAttributes.'>'; // .l-section
			echo '<div class="l-section__container">'; // .l-section__container
                
                echo '<div class="l-section__wrap-main">';
                    if ($this->isUseHeader()) {
                        echo '<div class="l-section__head" data-entity="header" '.$sHeaderAttributes.'>';
                            $this->getHeader()->show();
                        echo '</div>';
                    }

                    echo '<div class="l-section__main"'.$sMainAttributes.'>';

	}

	public function end() {
                    if (!is_null($this->slider)) {                        
                        ?><div data-slider-nav-sm="<?=$this->slider?>" class="slider-nav-sm bg-light mt-1"></div><?
                    }
                    echo '</div>'; // .l-section__main
                echo '</div>'; // .l-section__wrap-main
			echo '</div>'; // .l-section__container
		echo '</section>'; // .l-section
	}


}
