<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__DIR__.'/../detail.php');

global $arArticleTags;

if (!empty($arArticleTags) && count($arArticleTags) > 0):
?>
	<div class="b-article-detail__tags">
		<h5><?=Loc::getMessage('RS_N_TAGS'); ?></h5>
		<?php foreach ($arArticleTags as $arTag): ?>
			<a href="<?=$arTag['LINK']?>" class="article-tag has-ripple" itemprop="keywords" content="<?=$arTag['NAME']?>"><?=$arTag['NAME']?></a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
