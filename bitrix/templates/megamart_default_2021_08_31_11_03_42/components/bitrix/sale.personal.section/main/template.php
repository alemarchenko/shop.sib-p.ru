<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$APPLICATION->SetPageProperty("hide_inner_sidebar", "Y");
$APPLICATION->SetPageProperty("hide_outer_sidebar", "Y");
$APPLICATION->SetPageProperty("hide_section", "Y");


if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$availablePages = array();

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'],
		"name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
		"icon" => 'lk-orders'
	);
}

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ACCOUNT'],
		"name" => Loc::getMessage("SPS_ACCOUNT_PAGE_NAME"),
		"icon" => 'lk-account'
	);
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PRIVATE'],
		"name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
		"icon" => 'lk-private'
	);
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{

	$delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'].$delimeter."filter_history=Y",
		"name" => Loc::getMessage("SPS_ORDER_PAGE_HISTORY"),
		"icon" => 'lk-order-history'
	);
}

if ($arParams['SHOW_PROFILE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PROFILE'],
		"name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
		"icon" => 'lk-profiles'
	);
}

if ($arParams['SHOW_BASKET_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_BASKET'],
		"name" => Loc::getMessage("SPS_BASKET_PAGE_NAME"),
		"icon" => 'lk-cart'
	);
}

if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_SUBSCRIBE'],
		"name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
		"icon" => 'lk-subscribe'
	);
}

if ($arParams['SHOW_CONTACT_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arParams['PATH_TO_CONTACT'],
		"name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
		"icon" => 'lk-contacts'
	);
}

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList)
{
	foreach ($customPagesList as $page)
	{
		$availablePages[] = array(
			"path" => $page[0],
			"name" => $page[1],
			"icon" => (strlen($page[2])) ? htmlspecialcharsbx($page[2]) : ''
		);
	}
}

global $USER;

if ($USER->IsAuthorized())
{
	$availablePages[] = array(
		"path" => SITE_DIR.'?logout=yes&'.bitrix_sessid_get(),
		"name" => Loc::getMessage("SPS_EXIT_NAME"),
		"icon" => 'exit'
	);
}

if (empty($availablePages))
{
	ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
}
else
{
	$layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('outer-spacing');

	$layout->start();
	?>
		<div class="sale-personal-section-list">
			<?
			foreach ($availablePages as $blockElement)
			{
				?>
					<div class="sale-personal-section-list__item sale-personal-section-item">
						<a class="sale-personal-section-item__link" href="<?=htmlspecialcharsbx($blockElement['path'])?>">
							<span class="sale-personal-section-item__icon">
								<svg class="icon-svg"><use xlink:href="#svg-<?=$blockElement['icon']?>"></use></svg>

							</span>
							<h2 class="sale-personal-section-item__name">
								<?=htmlspecialcharsbx($blockElement['name'])?>
							</h2>
						</a>
					</div>
				<?
			}
			?>
		</div>
	<?
	$layout->end();
}
?>
