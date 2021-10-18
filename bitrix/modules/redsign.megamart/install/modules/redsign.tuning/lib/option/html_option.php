<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset;

class HtmlOption extends TuningOption
{

		protected $name = 'html';
		protected $description = 'Html block';

		public function showOption($options = array())
		{
?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info">
		<div class="rstuning__option-opname"><?=$options['HTML_VALUE']?></div>
</div>
<?
		}

	public function onload($options = array())
	{
		$asset = Asset::getInstance();
		$asset->addCss(getLocalPath('css/redsign.tuning/options/html/style.css'));
    }

}
