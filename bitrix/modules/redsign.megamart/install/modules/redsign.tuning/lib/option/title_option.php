<?php

namespace Redsign\Tuning;

use Bitrix\Main\Page\Asset;

class TitleOption extends TuningOption
{

		protected $name = 'title';
		protected $description = 'Title block';

		public function showOption($options = array())
		{
?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info">
		<div class="rstuning__option__title"><?=$options['NAME']?></div>
</div>
<?
		}

	public function onload($options = array())
	{
		$asset = Asset::getInstance();
		$asset->addCss(getLocalPath('css/redsign.tuning/options/title/style.css'));
    }

}
