<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\EventManager;
use \Redsign\MegaMart\AdminUtils;

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/options.php');
Loc::loadMessages(__FILE__);

$sModuleId = 'redsign.megamart';

\Bitrix\Main\Loader::includeModule($sModuleId);

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

$siteIterator = \Bitrix\Main\SiteTable::getList();
$arSites = $siteIterator->fetchAll();

$arDataOptions = [
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_PHONE_NUMBER'),
		'TYPE' => 'INPUT_ARRAY',
		'ID' => 'global_phones',
		'CODE' => 'GLOBAL_PHONES'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_EMAIL'),
		'TYPE' => 'INPUT_ARRAY',
		'ID' => 'global_emails',
		'CODE' => 'GLOBAL_EMAILS'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_SCHEDULE'),
		'TYPE' => 'INCLUDE',
		'INCLUDE_FILE' => 'include/common/schedule.php'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_ADDRESS_HEADER'),
		'TYPE' => 'INCLUDE',
		'INCLUDE_FILE' => 'include/header/address.php'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_ADDRESS_MOBILE_MENU'),
		'TYPE' => 'INCLUDE',
		'INCLUDE_FILE' => 'include/compact/address_area.php'
	],
];

$arMainOptions = [
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_LAZYLOAD_IMAGES'),
		'TYPE' => 'CHECKBOX',
		'ID' => 'global_lazyload_images',
		'CODE' => 'GLOBAL_LAZYLOAD_IMAGES'
	],

	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_GOOGLE_FONTS'),
		'TYPE' => 'INPUT',
		'ID' => 'global_google_font_embed_code',
		'CODE' => 'GLOBAL_GOOGLE_FONT_EMBED_CODE'
	],
];

$arSaleOptions = [
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_USE_ORDER_MIN_PRICE'),
		'TYPE' => 'CHECKBOX',
		'ID' => 'sale_use_order_min_price',
		'CODE' => 'SALE_USE_ORDER_MIN_PRICE'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_ORDER_MIN_PRICE'),
		'TYPE' => 'INPUT',
		'ID' => 'sale_order_min_price',
		'CODE' => 'SALE_ORDER_MIN_PRICE'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_ORDER_MIN_PRICE_ERROR_TEXT'),
		'TYPE' => 'INPUT',
		'ID' => 'sale_order_min_price_error_text',
		'CODE' => 'SALE_ORDER_MIN_PRICE_ERROR_TEXT'
	]
];

$arMicroDataOptions = [
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_MICRODATA_ORGANIZATION_NAME'),
		'TYPE' => 'INPUT',
		'ID' => 'microdata_organization_name',
		'CODE' => 'MICRODATA_ORGANIZATION_NAME'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_MICRODATA_ORGANIZATION_IMAGE_URL'),
		'TYPE' => 'INPUT',
		'ID' => 'microdata_organization_image_url',
		'CODE' => 'MICRODATA_ORGANIZATION_IMAGE_URL'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_MICRODATA_ORGANIZATION_ADDRESS'),
		'TYPE' => 'INPUT',
		'ID' => 'microdata_organization_address',
		'CODE' => 'MICRODATA_ORGANIZATION_ADDRESS'
	],
	[
		'NAME' => Loc::getMessage('RS_MM_OPTIONS_MICRODATA_ORGANIZATION_PHONE'),
		'TYPE' => 'INPUT',
		'ID' => 'microdata_organization_phone',
		'CODE' => 'MICRODATA_ORGANIZATION_PHONE'
	],
];

$sCustomIconsPath = '#SITE_DIR#assets/icons/';
$arIconsPath = [
	'/bitrix/modules/'.$sModuleId.'/assets/svg/',
	'/local/modules/'.$sModuleId.'/assets/svg/'
];

foreach ($arSites as $arSite)
{
	$arIconsPath[] = str_replace('#SITE_DIR#', $arSite['DIR'], $sCustomIconsPath);
}

if ($request->isPost() && check_bitrix_sessid())
{
	// Generate Icons
	if ($request->getPost('action') && $request->getPost('action') == 'GENERATE_SVG')
	{
		$APPLICATION->RestartBuffer();

		$returnText = '';

		try
		{
			AdminUtils::generateSvgIcons($arIconsPath, $request->getPost('path'));
			$returnText = '<span style="color: green">'.Application::getDocumentRoot().$request->getPost('path').'</span>';

			Option::set($sModuleId, 'icons_rand', \Bitrix\Main\Security\Random::getString(10), $request->getPost('siteId'));
		}
		catch (Exception $e)
		{
			$returnText = '<span style="color: red">'.Loc::getMessage('RS_MM_OPTION_ICONS_GEN_ERROR').'</span>';
		}

		echo $returnText;
		die();
	}

	$arOptions = array_merge(
		[],
		$arMainOptions,
		$arDataOptions,
		$arSaleOptions,
		$arMicroDataOptions
	);

	$bUseSaleOrderBonus = false;
	$bUseOrderMinPrice = false;

	// Save options
	foreach ($arSites as $arSite)
	{
		foreach ($arOptions as $arOption)
		{
			if (!empty($arOption['CODE']) && !empty($arOption['ID']))
			{
				$currentVal = Option::get($sModuleId, $arOption['ID'], '', $arSite['LID']);
				$val = $request->getPost($arOption['CODE'].'_'.$arSite['LID']);

				if ($arOption['TYPE'] == 'CHECKBOX')
				{
					$val = ($val == 'Y' ? $val : 'N');
				}

				if ($arOption['TYPE'] == 'INPUT_ARRAY')
				{
					if (is_array($val))
					{
						$val = array_filter($val, function ($itemVal) {
							return !empty($itemVal);
						});
					}
					$val = serialize($val);
				}

				/* get font name from embed code */
				if ($arOption['CODE'] == 'GLOBAL_GOOGLE_FONT_EMBED_CODE' && !empty($val))
				{
					$arPatterns = array(
						'`<link href=".+family=([^&:]+).*" .+>$`',
						'`\+`'
					);

					$arReplacements = array(
						'$1',
						' '
					);

					$sFontName = preg_replace($arPatterns, $arReplacements, $val);
					Option::set($sModuleId, 'global_google_font_name', $sFontName, $arSite['LID']);
				}

				Option::set($sModuleId, $arOption['ID'], $val, $arSite['LID']);
			}
		}

		//grecaptcha options
		$sPublicKeyVal = $request->getPost('global_grecaptcha_public_key_'.$arSite['LID']);
		$sPrivateKeyVal = $request->getPost('global_grecaptcha_private_key_'.$arSite['LID']);
		$sRemoveSelectorsVal = $request->getPost('global_grecaptcha_remove_selectors_'.$arSite['LID']);
		$nMinScore = $request->getPost('global_grecaptcha_min_score_'.$arSite['LID']);
		$sEnabled = $request->getPost('global_grecaptcha_enabled_'.$arSite['LID']);

		$bEnabled = $sEnabled == 'Y';

		if ($bEnabled)
		{
			EventManager::getInstance()->registerEventHandler(
				'main',
				'OnPageStart',
				'redsign.megamart',
				'\\Redsign\\MegaMart\\GReCaptcha\\GReCaptchaEvents',
				'onPageStart'
			);
		}

		Option::set($sModuleId, 'global_grecaptcha_public_key', $sPublicKeyVal, $arSite['LID']);
		Option::set($sModuleId, 'global_grecaptcha_private_key', $sPrivateKeyVal, $arSite['LID']);
		Option::set($sModuleId, 'global_grecaptcha_min_score', $nMinScore, $arSite['LID']);
		Option::set($sModuleId, 'global_grecaptcha_remove_selectors', $sRemoveSelectorsVal, $arSite['LID']);
		Option::set($sModuleId, 'global_grecaptcha_enabled', $sEnabled, $arSite['LID']);

		if ($request->getPost('use_sale_order_bonus_'.$arSite['LID']) == 'Y')
		{
			$bUseSaleOrderBonus = true;
		}

		if ($request->getPost('SALE_USE_ORDER_MIN_PRICE_'.$arSite['LID']) == 'Y')
		{
			$bUseOrderMinPrice = true;
		}

		Option::set($sModuleId, 'use_sale_order_bonus', $request->getPost('use_sale_order_bonus_'.$arSite['LID']), $arSite['LID']);
		Option::set($sModuleId, 'sale_order_bonus', $request->getPost('sale_order_bonus_'.$arSite['LID']), $arSite['LID']);
		Option::set($sModuleId, 'sale_order_bonus_type', $request->getPost('sale_order_bonus_type_'.$arSite['LID']), $arSite['LID']);
		// Option::set($sModuleId, 'sale_order_total_bonus', $request->getPost('sale_order_total_bonus_'.$arSite['LID']), $arSite['LID']);
		// Option::set($sModuleId, 'sale_order_total_bonus_type', $request->getPost('sale_order_total_bonus_type_'.$arSite['LID']), $arSite['LID']);

	}

	if ($bUseSaleOrderBonus)
	{
		EventManager::getInstance()->registerEventHandler(
			'sale',
			'OnSaleOrderPaid',
			$sModuleId,
			'\Redsign\MegaMart\Sale\CashBack',
			'addBonus'
		);

		EventManager::getInstance()->registerEventHandler(
			'sale',
			'OnAfterUserAccountUpdate',
			$sModuleId,
			'\Redsign\MegaMart\Sale\CashBack',
			'OnAfterUserAccountUpdate'
		);
	}
	else
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'sale',
			'OnSaleOrderPaid',
			$sModuleId,
			'\Redsign\MegaMart\Sale\CashBack',
			'addBonus'
		);

		EventManager::getInstance()->unRegisterEventHandler(
			'sale',
			'OnAfterUserAccountUpdate',
			$sModuleId,
			'\Redsign\MegaMart\Sale\CashBack',
			'OnAfterUserAccountUpdate'
		);
	}

	if ($bUseOrderMinPrice)
	{
		EventManager::getInstance()->registerEventHandler(
			'sale',
			'OnSaleComponentOrderCreated',
			$sModuleId,
			'\Redsign\Megamart\OrderUtils',
			'OnSaleComponentOrderCreated'
		);
	}
	else
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'sale',
			'OnSaleComponentOrderCreated',
			$sModuleId,
			'\Redsign\Megamart\OrderUtils',
			'OnSaleComponentOrderCreated'
		);
	}

	CAdminMessage::ShowMessage(array(
		"MESSAGE" => Loc::getMessage('RS_MM_OPTIONS_CLEAR_CACHE_NOTE'),
		"HTML" => true,
		"TYPE" => "OK"
	));
}

Bitrix\Main\Page\Asset::getInstance()->addJs('https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js');
Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css">');

Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/redsign.megamart/admin/options.js');
Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="/bitrix/panel/redsign.megamart/options.css">');

$arTabs = array();
$arTabs[] = array(
	'DIV' => 'redsign_mm',
	'TAB' => Loc::getMessage('RS_MM_OPTIONS_TAB_NAME_SETTINGS'),
	'ICON' => '',
	'TITLE' => Loc::getMessage('RS_MM_OPTIONS_TAB_TITLE_SETTINGS')
);
$arTabs[] = array(
	'DIV' => 'redsign_mm_data',
	'TAB' => Loc::getMessage('RS_MM_OPTIONS_TAB_NAME_DATA'),
	'ICON' => '',
	'TITLE' => Loc::getMessage('RS_MM_OPTIONS_TAB_TITLE_DATA')
);
$arTabs[] = array(
	'DIV' => 'redsign_mm_sale',
	'TAB' => Loc::getMessage('RS_MM_OPTIONS_TAB_NAME_SALE_SETTINGS'),
	'ICON' => '',
	'TITLE' => Loc::getMessage('RS_MM_OPTIONS_TAB_TITLE_SALE_SETTINGS')
);
$arTabs[] = array(
	'DIV' => 'redsign_mm_grecaptcha',
	'TAB' => Loc::getMessage('RS_MM_OPTIONS_TAB_NAME_GRECAPTCHA'),
	'ICON' => '',
	'TITLE' => Loc::getMessage('RS_MM_OPTIONS_TAB_TITLE_GRECAPTCHA')
);
$arTabs[] = array(
	'DIV' => 'redsign_mm_icons',
	'TAB' => Loc::getMessage('RS_MM_OPTIONS_TAB_NAME_ICONS'),
	'ICON' => '',
	'TITLE' => Loc::getMessage('RS_MM_OPTIONS_TAB_TITLE_ICONS')
);
$arTabs[] = array(
	'DIV' => 'redsign_mm_microdata',
	'TAB' => Loc::getMessage('RS_MM_OPTIONS_TAB_NAME_MICRODATA'),
	'ICON' => '',
	'TITLE' => Loc::getMessage('RS_MM_OPTIONS_TAB_TITLE_MICRODATA')
);
$tabControl = new CAdminTabControl('tabControl', $arTabs);

$tabControl->Begin();
?>
<form method="post" name="rsmm_option" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
	<?=bitrix_sessid_post();?>

	<?php $tabControl->BeginNextTab(); ?>
	<tr>
		<td valign="top" colspan="2">
		<?php
		$tabs = AdminUtils::getSiteTabs($arSites);
		$tabSiteControl = new CAdminViewTabControl("subTabControlGlobal", $tabs);
		$tabSiteControl->Begin();
		?>
		<?php foreach ($arSites as $arSite):
			$tabSiteControl->BeginNextTab();
		?>
			<table width="75%" align="center">
				<tr>
					<?php AdminUtils::showOptions($arMainOptions, $arSite); ?>
				</tr>
			</table>
		<?php
		endforeach;
		$tabSiteControl->End();
		?>
		</td>
	</tr>

	<?php $tabControl->BeginNextTab(); ?>
	<tr>
		<td valign="top" colspan="2">
		<?php
		$tabs = AdminUtils::getSiteTabs($arSites);
		$tabSiteControl = new CAdminViewTabControl("subTabControlData", $tabs);
		$tabSiteControl->Begin();
		?>
		<?php foreach ($arSites as $arSite):
			$tabSiteControl->BeginNextTab();
		?>
			<table width="75%" align="center">
				<tr>
					<?php AdminUtils::showOptions($arDataOptions, $arSite); ?>
				</tr>
			</table>
		<?php
		endforeach;
		$tabSiteControl->End();
		?>
		</td>
	</tr>

	<?php $tabControl->BeginNextTab(); ?>
	<tr>
		<td valign="top" colspan="2">
		<?php
		$tabs = AdminUtils::getSiteTabs($arSites);
		$tabSiteControl = new CAdminViewTabControl("subTabControlSaleSettings", $tabs);
		$tabSiteControl->Begin();
		?>
		<?php foreach ($arSites as $arSite):
			$tabSiteControl->BeginNextTab();
		?>
			<table width="75%" align="center">
				<tr>
					<?php AdminUtils::showOptions($arSaleOptions, $arSite); ?>
				</tr>

				<tr>
					<td>
						<?=Loc::getMessage('RS_MM_OPTIONS_USE_SALE_ORDER_BONUS')?>:
					</td>
					<td>
						<?php
						$val = Option::get($sModuleId, 'use_sale_order_bonus', 'N', $arSite['LID']);
						?>
						<input type="checkbox"  value="Y" name="use_sale_order_bonus_<?=$arSite['LID']?>" id="use_sale_order_bonus_<?=$arSite['LID']?>" <?php if ($val == 'Y'): ?> checked<?php endif; ?>>
					</td>
				</tr>

				<tr>
					<td>
						<?=Loc::getMessage('RS_MM_OPTIONS_SALE_ORDER_BONUS')?>:
					</td>
					<td>
						<?php
						$val = Option::get($sModuleId, 'sale_order_bonus', 0, $arSite['LID']);
						?>
						<input value="<?=$val?>" name="sale_order_bonus_<?=$arSite['LID']?>" type="text">
						<?php
						$val = Option::get($sModuleId, 'sale_order_bonus_type', 'N', $arSite['LID']);
						?>
						<select name="sale_order_bonus_type_<?=$arSite['LID']?>">
							<option value="P"<?if ($val == 'P') echo ' selected';?>><?=Loc::getMessage('RS_MM_OPTIONS_VALUE_TYPE_PERCENT')?></option>
							<option value="F"<?if ($val == 'F') echo ' selected';?>><?=Loc::getMessage('RS_MM_OPTIONS_VALUE_TYPE_FIXED')?></option>
						</select>
					</td>
				</tr>
<?/*
				<tr>
					<td>
						<?=Loc::getMessage('RS_MM_OPTIONS_SALE_ORDER_TOTAL_BONUS')?>:
					</td>
					<td>
						<?php
						$val = Option::get($sModuleId, 'sale_order_total_bonus', 0, $arSite['LID']);
						?>
						<input value="<?=$val?>" name="sale_order_total_bonus_<?=$arSite['LID']?>" type="text">
						<?php
						$val = Option::get($sModuleId, 'sale_order_total_bonus_type', 'N', $arSite['LID']);
						?>
						<select name="sale_order_total_bonus_type_<?=$arSite['LID']?>">
							<option value="P"<?if ($val == 'P') echo ' selected';?>><?=Loc::getMessage('RS_MM_OPTIONS_VALUE_TYPE_PERCENT')?></option>
							<option value="F"<?if ($val == 'F') echo ' selected';?>><?=Loc::getMessage('RS_MM_OPTIONS_VALUE_TYPE_FIXED')?></option>
						</select>
					</td>
				</tr>
*/?>
			</table>
		<?php
		endforeach;
		$tabSiteControl->End();
		?>
		</td>
	</tr>
	<?php $tabControl->BeginNextTab();	?>
	<tr>
		<td valign="top" colspan="2">
			<?php
			$tabs = AdminUtils::getSiteTabs($arSites);
			$tabSiteControl = new CAdminViewTabControl("subTabControlGrecaptchaSettings", $tabs);
			$tabSiteControl->Begin();
			?>
			<?php foreach ($arSites as $arSite):
				$tabSiteControl->BeginNextTab();
			?>
				<h3><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_STEP_1'); ?></h3>
				<img src="https://static.redsign.ru/demo-content/megamart/parameters/grecaptcha_info.png" />
				<h3><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_STEP_2'); ?></h3>

				<div class="grecaptcha-container">
					<table class="grecaptcha-table">
						<tr>
							<th><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_PUBLIC_KEY');?></th>
							<th><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_PRIVATE_KEY');?></th>
						</tr>
						<tr>
							<td>
								<?php
								$sPublicKeyVal = Option::get($sModuleId, 'global_grecaptcha_public_key', '', $arSite['LID']);
								?>
								<input class="grecaptcha-input" type="text" size="40" value="<?=$sPublicKeyVal?>" name="global_grecaptcha_public_key_<?=$arSite['LID']?>" id="global_grecaptcha_public_key_<?=$arSite['LID']?>">
							</td>
							<td>
								<?php
								$sPrivateKeyVal = Option::get($sModuleId, 'global_grecaptcha_private_key', '', $arSite['LID']);
								?>
								<input class="grecaptcha-input" type="text" size="40" value="<?=$sPrivateKeyVal?>" name="global_grecaptcha_private_key_<?=$arSite['LID']?>" id="global_grecaptcha_private_key_<?=$arSite['LID']?>">
							</td>
						</tr>
					</table>
				</div>

				<h3><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_STEP_3');?></h3>
				<div class="grecaptcha-container">
					<label>
						<?php
						$bEnabled = Option::get($sModuleId, 'global_grecaptcha_enabled', 'N', $arSite['LID']) == 'Y';
						?>
						<input type="checkbox"  value="Y" name="global_grecaptcha_enabled_<?=$arSite['LID']?>" id="global_grecaptcha_enabled_<?=$arSite['LID']?>" class="grecaptcha-checkbox"<?php if ($bEnabled): ?> checked<?php endif; ?>>
						<span class="grecaptcha-checkbox-desc"><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_ENABLED');?> </span>
					</label>

					<details class="grecaptcha-extended-options">
						<summary>
							<?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_SHOW_EXTENDED_PARAMETERS');?>
						</summary>
						<div class="grecaptcha-extended-options-list">
							<div class="grecaptcha-extended-options-list-item">
								<?php
								$sMinScoreValue = Option::get($sModuleId, 'global_grecaptcha_min_score', '', $arSite['LID']);
								?>
								<label>
									<div class="grecaptcha-input-desc"><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_MIN_SCORE'); ?> </div>
									<input class="grecaptcha-input" type="text" size="40" value="<?=$sMinScoreValue?>" name="global_grecaptcha_min_score_<?=$arSite['LID']?>" id="global_grecaptcha_min_score_<?=$arSite['LID']?>">
								</label>
							</div>
							<div class="grecaptcha-extended-options-list-item">
								<?php
								$sBlockIdValue = Option::get($sModuleId, 'global_grecaptcha_block_id', '', $arSite['LID']);
								?>
								<label>
									<div class="grecaptcha-input-desc"><?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_BADGE_ID'); ?></div>
									<input class="grecaptcha-input" type="text" size="40" value="<?=$sBlockIdValue?>" name="global_grecaptcha_block_id" id="global_grecaptcha_block_id_<?=$arSite['LID']?>">
								</label>
							</div>
							<div class="grecaptcha-extended-options-list-item">
								<?php
								$sRemoveSelectorsVal = Option::get($sModuleId, 'global_grecaptcha_remove_selectors', '', $arSite['LID']);


								?>
								<label>
									<div class="grecaptcha-input-desc"> <?=Loc::getMessage('RS_MM_OPTIONS_GRECAPTCHA_REMOVE_SELECTORS');?> </div>
									<textarea  class="grecaptcha-textarea" name="global_grecaptcha_remove_selectors_<?=$arSite['LID']?>"><?=$sRemoveSelectorsVal?></textarea>
								</label>
							</div>
						</div>
					</details>
				</div>
			<?php
			endforeach;
			$tabSiteControl->End();
			?>
		</td>
	</tr>
	<?php $tabControl->BeginNextTab();	?>
	<tr>
		<td colspan="2">
			<?php
			$tabs = AdminUtils::getSiteTabs($arSites);
			$tabSiteControl = new CAdminViewTabControl("subTabControlIcons", $tabs);
			$tabSiteControl->Begin();

			foreach ($arSites as $arSite)
			{
				$tabSiteControl->BeginNextTab();
				?>
				<table width="75%" align="center">
					<tr>
						<td colspan="2">
							<div>
								<?php
								echo Loc::getMessage('RS_MM_OPTIONS_CUSTOM_ICONS_PATH', array(
									'#PATH#' => str_replace('#SITE_DIR#', $arSite['DIR'], $sCustomIconsPath)
								));?>
							</div><br>
							<input style="padding: 1.5px 5px; margin-right: 10px; margin-top: 1px;" type="text" size="15" value="<?=$arSite['DIR']?>include/icons.svg" name="SVG_SPRITES_PATH_<?= $arSite["LID"] ?>" id="SVG_SPRITES_PATH_<?=$arSite['LID']?>">
							<button class="adm-btn" onclick="event.preventDefault(); generateSvgIcons('SVG_SPRITES_PATH_<?= $arSite["LID"] ?>', '<?=$arSite["LID"]?>')"><?=Loc::getMessage('RS_MM_OPTIONS_GENERATE_ICONS');?></button>
							<span style="margin-left: 10px;" id="SVG_SPRITES_PATH_<?= $arSite["LID"] ?>_MESSAGE"></span>
						</td>
					</tr>
				</table>
				<?php
			}
			$tabSiteControl->End();
			?>
		</div>
	</tr>

	<?php $tabControl->BeginNextTab(); ?>
	<tr>
		<td valign="top" colspan="2">
		<?php
		$tabs = AdminUtils::getSiteTabs($arSites);
		$tabSiteControl = new CAdminViewTabControl("subTabControlSaleSettings", $tabs);
		$tabSiteControl->Begin();
		?>
		<?php foreach ($arSites as $arSite):
			$tabSiteControl->BeginNextTab();
		?>
			<table width="75%" align="center">
				<tr>
					<?php AdminUtils::showOptions($arMicroDataOptions, $arSite); ?>
				</tr>
			</table>
		<?php
		endforeach;
		$tabSiteControl->End();
		?>
		</td>
	</tr>

</form>
<?php
$tabControl->Buttons(array());
$tabControl->End();
?>

<script>
function generateSvgIcons(inputId, siteId)
{
	var path = BX(inputId).value;
	var data = {
		path: path,
		siteId: siteId,
		action: 'GENERATE_SVG',
		sessid: BX.bitrix_sessid(),
	}

	BX.ajax({
		url: '<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>',
		method: 'post',
		data: data,
		onsuccess: function (result) {
			BX(inputId + '_MESSAGE').innerHTML = result;
		}
	});
}
</script>
