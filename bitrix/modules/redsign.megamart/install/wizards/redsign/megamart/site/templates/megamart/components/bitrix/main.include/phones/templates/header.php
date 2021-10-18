<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!empty($arResult['PHONES']) && is_array($arResult['PHONES'])):?><div class="d-flex align-items-center">
	<div class="d-none d-xl-block mr-4">
		<svg class="icon-svg text-extra h4 mb-0"><use xlink:href="#svg-phone"></use></svg>
	</div>
	<div class="d-block">
		<div class="dropdown">
			<?php if (count($arResult['PHONES']) > 1): ?>
				<a class="d-block font-weight-bold text-body lh-1" href="#" role="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<span class="d-none d-xl-block">
						<?=$arResult['PHONES'][0]?>
						<svg class="icon-svg text-extra lh-0"><use xlink:href="#svg-chevron-down"></use></svg>
					</span>
					<span class="c-icon-count d-xl-none">
						<svg class="icon-svg text-extra h4 mb-0"><use xlink:href="#svg-phone"></use></svg>
					</span>
				</a>
				<div class="dropdown-menu">
					<?php
					foreach ($arResult['PHONES'] as $sPhone):
						$sPhoneUrl = preg_replace('/[^0-9\+]/', '', $sPhone);
						?>
						<a href="tel:<?=$sPhoneUrl?>" class="dropdown-item" ><?=$sPhone?></a>
					<?php endforeach; ?>
				</div>
			<?php
			elseif (count($arResult['PHONES']) == 1):
				$sPhone = $arResult['PHONES'][0];
				$sPhoneUrl = preg_replace('/[^0-9\+]/', '', $sPhone);
			?>
				<a class="d-block font-weight-bold text-body lh-1" href="tel:<?=$sPhoneUrl?>" role="button">
					<span class="d-none d-xl-block">
						<?=$sPhone?>
					</span>
				</a>
			<?php endif; ?>
		</div>

		<div class="d-none d-xl-block lh-1">
			<?php if ($arParams['ANOTHER_BLOCK'] == 'schedule'): ?>
				<div class="d-block small text-extra">
					<?$APPLICATION->IncludeFile(
						SITE_DIR."include/common/schedule.php",
						Array(),
						Array(
							"MODE"=>"html",
							"NAME" => Loc::getMessage('RS_MI_PHONES_SCHEDULE_EDIT')
						)
					);?>
				</div>
			<?php elseif ($arParams['ANOTHER_BLOCK'] == 'address'): ?>
				<div class="d-block small text-extra">
					<svg class="icon-svg text-primary align-baseline"><use xlink:href="#svg-small-pin "></use></svg>
					<?$APPLICATION->IncludeFile(
						SITE_DIR."include/header/address.php",
						Array(),
						Array(
							"MODE"=>"html",
							"NAME" => Loc::getMessage('RS_MI_PHONES_ADDRESS_EDIT')
						)
					);?>
				</div>
			<?php elseif ($arParams['ANOTHER_BLOCK'] == 'recall'): ?>
				<div class="d-block small text-extra">
					<?$APPLICATION->IncludeFile(
						SITE_DIR."include/common/recall.php",
						Array(),
						Array("MODE"=>"html")
					);?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</div><?php
endif;
