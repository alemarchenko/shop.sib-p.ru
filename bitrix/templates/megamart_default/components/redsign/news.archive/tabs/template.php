<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Localization\Loc;

$sSelectValue = false;

if ($arResult['HAS_SELECTED']) {
	$sSelectValue = Loc::getMessage('RS_NA_ALL');
} else {
	foreach ($arResult['YEARS'] as $arYear) {
		if ($arYear['SELECTED']) {
			$sSelectValue =  $arYear['NAME'];

			break;
		}
	}
}


$sBlockId = $this->randString(5);

?>
<?php if (is_array($arResult['YEARS']) && count($arResult['YEARS']) > 0): ?>

<div class="block-spacing-x py-5 bg-white box-shadow-1">
	<div class="b-news-archive">
		<ul class="nav nav-slide d-none d-sm-flex d-md-none d-lg-flex">
			<li class="nav-item">
				<a class="nav-link<?php if (!$arResult['HAS_SELECTED']) { echo ' active'; }?>" href="<?=$arParams['SEF_FOLDER']?>"><span><?=Loc::getMessage('RS_NA_ALL')?></span></a>
			</li>
			<?php foreach ($arResult['YEARS'] as $iYear => $arYear): ?>
				<?php $sYearId = $this->getEditAreaId($iYear); ?>
				<li class="nav-item">
					<?php if ($arParams['SHOW_YEARS']): ?>
						<a class="nav-link<?php if ($arYear['SELECTED']) { echo ' active'; } ?>" href="<?=$arYear['ARCHIVE_URL']?>"><span><?=$arYear['NAME']?> (<?=$arYear['COUNT']?>)</span></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="d-sm-none d-md-block d-lg-none">
			<div class="dropdown">
				<a class="btn btn-dropdown dropdown-toggle" href="#" role="button" id="<?=$sBlockId?>_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?=Loc::getMessage('RS_NA_FILTER'); ?>
				</a>

				<div class="dropdown-menu" aria-labelledby="<?=$sBlockId?>_dropdown" x-placement="bottom-start">
					<a class="dropdown-item" href="#"><?=Loc::getMessage('RS_NA_FILTER_ALL_TIME'); ?></a>
					<?php foreach ($arResult['YEARS'] as $iYear => $arYear): ?>
						<a class="dropdown-item" href="<?=$arYear['ARCHIVE_URL']?>">
							<?=Loc::getMessage('RS_NA_FILTER_YEAR', array(
								'#YEAR#' => $arYear['NAME'],
								'#COUNT#' => $arYear['COUNT']
							)); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
