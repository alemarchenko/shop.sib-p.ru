<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
/** @global CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;

CJSCore::init(array('popup'));
$this->addExternalCss(SITE_TEMPLATE_PATH.'/assets/styles/catalog-item.css');

$randomString = $this->randString();
$arParams['RS_LIST_SECTION'] = 'l_section';

$APPLICATION->setTitle(Loc::getMessage('CPSL_SUBSCRIBE_TITLE_NEW'));
if(!$arResult['USER_ID'] && !isset($arParams['GUEST_ACCESS'])):?>
	<?

	$layout = new \Redsign\MegaMart\Layouts\Section();
	$layout
		->addModifier('bg-white')
		->addModifier('shadow')
		->addModifier('inner-spacing')
		->addModifier('outer-spacing');

	$layout->start();

	$contactTypeCount = count($arResult['CONTACT_TYPES']);
	$authStyle = 'display: block;';
	$identificationStyle = 'display: none;';
	if(!empty($_GET['result']))
	{
		$authStyle = 'display: none;';
		$identificationStyle = 'display: block;';
	}
	?>

	<div class="row">
		<div class="col-sm-9 col-md-6">
			<div class="alert alert-danger"><?=Loc::getMessage('CPSL_SUBSCRIBE_PAGE_TITLE_AUTHORIZE')?></div>
		</div>
		<? $authListGetParams = array(); ?>
		<div class="col-12" id="catalog-subscriber-auth-form" style="<?=$authStyle?>">
			<?$APPLICATION->authForm('', false, false, 'N', false);?>
		</div>
	</div>

	<?$APPLICATION->setTitle(Loc::getMessage('CPSL_TITLE_PAGE_WHEN_ACCESSING'));?>

	<div class="row" id="catalog-subscriber-identification-form" style="<?=$identificationStyle?>">
		<div class="col-sm-9 col-md-6">

			<div class="row">
				<div class="col my-6">
					<h5><?=Loc::getMessage('CPSL_HEADLINE_FORM_SEND_CODE')?></h5>
					<form method="post">
						<?=bitrix_sessid_post()?>
						<input type="hidden" name="siteId" value="<?=SITE_ID?>">
						<?if($contactTypeCount > 1):?>
							<div class="form-group bmd-form-group">
								<label class="bmd-label-floating" for="contactType"><?=Loc::getMessage('CPSL_CONTACT_TYPE_SELECTION')?></label>
								<select id="contactType" class="bmd-form-control" name="contactType">
									<?foreach($arResult['CONTACT_TYPES'] as $contactTypeData):?>
										<option value="<?=intval($contactTypeData['ID'])?>"><?=htmlspecialcharsbx($contactTypeData['NAME'])?></option>
									<?endforeach;?>
								</select>
							</div>
						<?endif;?>
						<div class="form-group bmd-form-group">
							<?
								$contactLable = Loc::getMessage('CPSL_CONTACT_TYPE_NAME');
								$contactTypeId = 0;
								if($contactTypeCount == 1)
								{
									$contactType = current($arResult['CONTACT_TYPES']);
									$contactLable = $contactType['NAME'];
									$contactTypeId = $contactType['ID'];
								}
							?>
							<label class="bmd-label-floating" for="contactInputOut"><?=htmlspecialcharsbx($contactLable)?></label>
							<input type="text" class="bmd-form-control" name="userContact" id="contactInputOut">
							<input type="hidden" name="subscriberIdentification" value="Y">
							<?if($contactTypeId):?>
								<input type="hidden" name="contactType" value="<?=$contactTypeId?>">
							<?endif;?>
						</div>
						<button type="submit" class="mt-4 btn btn-primary"><?=Loc::getMessage('CPSL_BUTTON_SUBMIT_CODE')?></button>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col my-6">
					<h5><?=Loc::getMessage('CPSL_HEADLINE_FORM_FOR_ACCESSING')?></h5>
					<form method="post">
						<?=bitrix_sessid_post()?>
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating" for="contactInputCheck"><?=htmlspecialcharsbx($contactLable)?></label>
							<input type="text" class="bmd-form-control" name="userContact" id="contactInputCheck" value="<?=!empty($_GET['contact']) ? htmlspecialcharsbx(urldecode($_GET['contact'])): ''?>">
						</div>
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating" for="token"><?=Loc::getMessage('CPSL_CODE_LABLE')?></label>
							<input type="text" class="bmd-form-control" name="subscribeToken" id="token">
							<input type="hidden" name="accessCodeVerification" value="Y">
						</div>
						<button type="submit" class="mt-4 btn btn-primary"><?=Loc::getMessage('CPSL_BUTTON_SUBMIT_ACCESS')?></button>
					</form>
				</div>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		BX.ready(function() {
			if(BX('cpsl-auth'))
			{
				BX.bind(BX('cpsl-auth'), 'click', BX.delegate(showAuthForm, this));
				BX.bind(BX('cpsl-identification'), 'click', BX.delegate(showAuthForm, this));
			}
			function showAuthForm()
			{
				var formType = BX.proxy_context.id.replace('cpsl-', '');
				var authForm = BX('catalog-subscriber-auth-form'),
					codeForm = BX('catalog-subscriber-identification-form');
				if(!authForm || !codeForm || !BX('catalog-subscriber-'+formType+'-form')) return;

				BX.style(authForm, 'display', 'none');
				BX.style(codeForm, 'display', 'none');
				BX.style(BX('catalog-subscriber-'+formType+'-form'), 'display', '');
			}
		});
	</script>
<?
	$layout->end();
endif;

?>
<script type="text/javascript">
	BX.message({
		CPSL_MESS_BTN_DETAIL: '<?=('' != $arParams['MESS_BTN_DETAIL']
			? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',

		CPSL_MESS_NOT_AVAILABLE: '<?=('' != $arParams['MESS_BTN_DETAIL']
			? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('CPSL_TPL_MESS_BTN_DETAIL'));?>',
		CPSL_BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_BASKET_REDIRECT');?>',
		CPSL_BASKET_URL: '<?=$arParams["BASKET_URL"];?>',
		CPSL_TITLE_ERROR: '<?=GetMessageJS('CPSL_CATALOG_TITLE_ERROR') ?>',
		CPSL_TITLE_BASKET_PROPS: '<?=GetMessageJS('CPSL_CATALOG_TITLE_BASKET_PROPS') ?>',
		CPSL_BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CPSL_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
		CPSL_BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_SEND_PROPS');?>',
		CPSL_BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CPSL_CATALOG_BTN_MESSAGE_CLOSE') ?>',
		CPSL_STATUS_SUCCESS: '<?=GetMessageJS('CPSL_STATUS_SUCCESS');?>',
		CPSL_STATUS_ERROR: '<?=GetMessageJS('CPSL_STATUS_ERROR') ?>',
		CPSL_SUBSCRIBE_TITLE_NEW: '<?=GetMessageJS('CPSL_SUBSCRIBE_TITLE_NEW') ?>'
	});
</script>
<?

if(!empty($_GET['result']) && !empty($_GET['message']))
{
	$successNotify = strpos($_GET['result'], 'Ok') ? true : false;
	$postfix = $successNotify ? 'Ok' : 'Fail';
	$popupTitle = Loc::getMessage('CPSL_SUBSCRIBE_POPUP_TITLE_'.strtoupper(str_replace($postfix, '', $_GET['result'])));

	$arJSParams = array(
		'NOTIFY_USER' => true,
		'NOTIFY_POPUP_TITLE' => $popupTitle,
		'NOTIFY_SUCCESS' => $successNotify,
		'NOTIFY_MESSAGE' => urldecode($_GET['message']),
	);
	?>
	<script type="text/javascript">
		var <?='jaClass_'.$randomString;?> = new JCCatalogProductSubscribeList(<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
	</script>
	<?
}


$mainId = $this->GetEditAreaId('section');

$layout = \Redsign\MegaMart\Layouts\Builder::createFromParams($arParams);

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addData('SECTION_ATTRIBUTES', 'id="'.$mainId.'" data-entity="parent-container"');

if (!empty($arResult['ITEMS']))
{
	$layout->start();

	$skuTemplate = array();
	if (!empty($arResult['SKU_PROPS']))
	{
		foreach ($arResult['SKU_PROPS'] as $itemId => $arProp)
		{
			foreach($arProp as $propId => $prop)
			{
				$propId = $prop['ID'];
				$skuTemplate[$itemId][$propId] = array(
					'SCROLL' => array(
						'START' => '',
						'FINISH' => '',
					),
					'FULL' => array(
						'START' => '',
						'FINISH' => '',
					),
					'ITEMS' => array()
				);
				$templateRow = '';
				if ('TEXT' == $prop['SHOW_MODE'])
				{
					$skuTemplate[$itemId][$propId]['SCROLL']['START'] = '<div class="bx_item_detail_size full" id="#ITEM#_prop_'.$propId.'_cont">'.
						'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($prop['NAME']).'</span>'.
						'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';;
					$skuTemplate[$itemId][$propId]['SCROLL']['FINISH'] = '</ul></div>'.
						'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style=""></div>'.
						'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style=""></div>'.
						'</div></div>';

					$skuTemplate[$itemId][$propId]['FULL']['START'] = '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$propId.'_cont">'.
						'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($prop['NAME']).'</span>'.
						'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';;
					$skuTemplate[$itemId][$propId]['FULL']['FINISH'] = '</ul></div>'.
						'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style="display: none;"></div>'.
						'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style="display: none;"></div>'.
						'</div></div>';
					foreach ($prop['VALUES'] as $value)
					{
						$value['NAME'] = htmlspecialcharsbx($value['NAME']);
						$skuTemplate[$itemId][$propId]['ITEMS'][$value['ID']] = '<li data-treevalue="'.$propId.'_'.$value['ID'].
							'" data-onevalue="'.$value['ID'].'" style="width: #WIDTH#;" title="'.$value['NAME'].'"><i></i><span class="cnt">'.$value['NAME'].'</span></li>';
					}
					unset($value);
				}
				elseif ('PICT' == $prop['SHOW_MODE'])
				{
					$skuTemplate[$itemId][$propId]['SCROLL']['START'] = '<div class="bx_item_detail_scu full" id="#ITEM#_prop_'.$propId.'_cont">'.
						'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($prop['NAME']).'</span>'.
						'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';
					$skuTemplate[$itemId][$propId]['SCROLL']['FINISH'] = '</ul></div>'.
						'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style=""></div>'.
						'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style=""></div>'.
						'</div></div>';

					$skuTemplate[$itemId][$propId]['FULL']['START'] = '<div class="bx_item_detail_scu" id="#ITEM#_prop_'.$propId.'_cont">'.
						'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($prop['NAME']).'</span>'.
						'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';
					$skuTemplate[$itemId][$propId]['FULL']['FINISH'] = '</ul></div>'.
						'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style="display: none;"></div>'.
						'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style="display: none;"></div>'.
						'</div></div>';
					foreach ($prop['VALUES'] as $value)
					{
						$value['NAME'] = htmlspecialcharsbx($value['NAME']);
						$skuTemplate[$itemId][$propId]['ITEMS'][$value['ID']] = '<li data-treevalue="'.$propId.'_'.$value['ID'].
							'" data-onevalue="'.$value['ID'].'" style="width: #WIDTH#; padding-top: #WIDTH#;"><i title="'.$value['NAME'].'"></i>'.
							'<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$value['PICT']['SRC'].'\');" title="'.$value['NAME'].'"></span></span></li>';
					}
					unset($value);
				}
			}
		}
		unset($templateRow, $prop);
	}

	switch ($arParams['LINE_ELEMENT_COUNT'])
	{
		case '1':
			$gridClass = 'col-12 col-xs-12 xol-sm-12 col-md-12 col-lg-12 col-xl-12';
			break;
		case '2':
			$gridClass = 'col-6 col-xs-6 xol-sm-6 col-md-6 col-lg-6 col-xl-6';
			break;
		case '3':
			$gridClass = 'col-12 col-xs-12 xol-sm-4 col-md-4 col-lg-4 col-xl-4';
			break;
		case '6':
			$gridClass = 'col-6 col-xs-6 col-sm-4 col-md-2 col-lg-2 col-xl-2';
			break;
		case '4':
		default:
			$gridClass = 'col-6 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3';
			break;
	}
	$labelPositionClass = 'product-cat-label-bottom product-cat-label-right';
	?>
	<div class="row row-borders">
	<? foreach ($arResult['ITEMS'] as $key => $arItem)
	{
		$strMainID = $this->GetEditAreaId($arItem['ID']);

		$arItemIDs = array(
			'ID' => $strMainID,
			'PICT' => $strMainID . '_pict',
			'SECOND_PICT' => $strMainID . '_secondpict',
			'MAIN_PROPS' => $strMainID . '_main_props',

			'QUANTITY' => $strMainID . '_quantity',
			'QUANTITY_DOWN' => $strMainID . '_quant_down',
			'QUANTITY_UP' => $strMainID . '_quant_up',
			'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
			'BUY_LINK' => $strMainID . '_buy_link',
			'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
			'SUBSCRIBE_DELETE_LINK' => $strMainID . '_delete_subscribe',

			'PRICE' => $strMainID . '_price',
			'DSC_PERC' => $strMainID . '_dsc_perc',
			'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',

			'PROP_DIV' => $strMainID . '_sku_tree',
			'PROP' => $strMainID . '_prop_',
			'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
			'BASKET_PROP_DIV' => $strMainID . '_basket_prop'
		);

		$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

		$strTitle = (
		isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
			&& '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
			? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
			: $arItem['NAME']
		);
		// $showImgClass = $arParams['SHOW_IMAGE'] != "Y" ? "no-imgs" : "";

		?>
<div class="<?=$gridClass?>">
	<div class="product-cat-container<?//=($arItem['SECOND_PICT'] && $arParams ? 'bx_catalog_item double' : 'bx_catalog_item');?>" id=
		"<?=$strMainID;?>">
<?/*
<!--		<div class="catalog-product-subscribe-list-close">-->
<!--			<span class="catalog-product-subscribe-list-close-item"></span>-->
<!--		</div>-->
*/?>
	<div class="product-cat product-cat-popup">
		<div class="product-cat-image-wrapper">

	<a href="<?=$arItem['DETAIL_PAGE_URL'];?>" class=
		"product-cat-image-canvas" title="<?=$strTitle;?>"><?
		if ($arParams['RS_LAZY_IMAGES_USE'] == 'Y')
		{
			?>
			<img class="product-cat-image lazy-anim-img" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" src="<?=Redsign\MegaMart\LazyloadUtils::getEmptyImage(1, 1);?>" id="<?=$arItemIDs['PICT']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
			<?php
		}
		else
		{
			?>
			<img class="product-cat-image" id="<?=$arItemIDs['PICT']?>" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
			<?php
		}

		if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
		{
			?>
			<span class="product-cat-label-text <?=$labelPositionClass?>">
				<span class="product-cat-label-text-item" id="<?=$arItemIDs['DSC_PERC']?>"
					style="display:<?=($arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] > 0 ? '' : 'none')?>;">
					<?=-$arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']?>%
				</span>
			</span>
		<?
		}
		if ($arItem['LABEL'])
		{
			?><div class="bx_stick average left top" title="<?=$arItem['LABEL_VALUE'];?>">
			<?=$arItem['LABEL_VALUE'];?></div><?
		}
		?>
	</a>
		</div>
	<?
/*
	if ($arItem['SECOND_PICT'])
	{
		?><a id="<?=$arItemIDs['SECOND_PICT'];?>" href="<?=$arItem['DETAIL_PAGE_URL'];?>" class=
			"bx_catalog_item_images_double"<? if ($arParams['SHOW_IMAGE'] == "Y")
	{
		?> style="background-image: url('<?=(
			!empty($arItem['PREVIEW_PICTURE_SECOND'])
			? $arItem['PREVIEW_PICTURE_SECOND']['SRC']
			: $arItem['PREVIEW_PICTURE']['SRC']
		);?>')"<?
	} ?> title="<?=$strTitle;?>"><?
		if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
		{
			?>
			<div id="<?=$arItemIDs['SECOND_DSC_PERC'];?>" class="bx_stick_disc right bottom" style=
				"display:<?=(0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none');?>;">
				-<?=$arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];?>%
			</div>
		<?
		}
		if ($arItem['LABEL'])
		{
			?><div class="bx_stick average left top" title="<?=$arItem['LABEL_VALUE'];?>">
			<?=$arItem['LABEL_VALUE'];?></div><?
		}
		?>
		</a><?
	}
*/
	?>
	<div class="product-cat-content">

	<? if ($arParams['SHOW_NAME'] == "Y")
	{
		?>
		<h6 class="product-cat-title mb-4 mb-lg-5">
			<a href="<?=$arItem['DETAIL_PAGE_URL'];?>" title="<?=$arItem['NAME'];?>"><?=$arItem['NAME'];?></a>
		</h6>
	<?
	}?>
		<div class="product-cat-price-container mb-0 mb-sm-5">
			<div class="text-nowrap">
				<span class="product-cat-price-current" id="<?=$arItemIDs['PRICE'];?>"></span>
			</div>
		</div>
		<div class="product-cat-buttons d-none d-sm-block mb-0 mb-sm-5">
			<div class="product-cat-button-container">
				<a class="btn btn-outline-secondary" href="<?=$arItem['DETAIL_PAGE_URL'];?>">
					<?php
					echo('' != $arParams['MESS_BTN_DETAIL']
						? $arParams['MESS_BTN_DETAIL'] : GetMessage('CPSL_TPL_MESS_BTN_DETAIL'));
					?>
				</a>
				<a id="<?=$arItemIDs['SUBSCRIBE_DELETE_LINK'];?>" class="btn btn-link" delay="1500" href="javascript:void(0)">
					<?=GetMessage('CPSL_TPL_MESS_BTN_UNSUBSCRIBE');?>
				</a>
			</div>
		</div>
		<div class="product-cat-body">

	<?if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])) // Simple Product
	{

	if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
	{
	?>
		<div class="product-cat-info-container product-cat-hidden small" data-entity="props-block">
			<dl class="product-cat-properties">
			<?
			foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
			{
				?>
				<dt><?=$arOneProp['NAME'];?>:</dt>
				<dd><?
				echo(
				is_array($arOneProp['DISPLAY_VALUE'])
					? implode('/', $arOneProp['DISPLAY_VALUE'])
					: $arOneProp['DISPLAY_VALUE']
				);?></dd><?
			}
			?>
			</dl>
		</div>
	<?
	}
	$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
	?>
		<div id="<?=$arItemIDs['BASKET_PROP_DIV'];?>" style="display: none;">
			<?
			if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
				{
					?>
					<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE'];?>[<?=$propID;?>]" value=
						"<?=htmlspecialcharsbx($propInfo['ID']);?>">
					<?
					if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
						unset($arItem['PRODUCT_PROPERTIES'][$propID]);
				}
			}
			$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);

			if (!$emptyProductProperties)
			{

				?>
				<table>
					<?
					foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
					{
						?>
						<tr>
							<td><?=$arItem['PROPERTIES'][$propID]['NAME'];?></td>
							<td>
								<?
								if (
									'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
									&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
								)
								{
									foreach ($propInfo['VALUES'] as $valueID => $value)
									{
										?><label>
										<input type="radio" name=
											"<?=$arParams['PRODUCT_PROPS_VARIABLE'];?>[<?=$propID;?>]" value=
											"<?=$valueID;?>" <?=($valueID == $propInfo['SELECTED'] ?
												'"checked"' : '');?>><?=$value;?>
										</label><br><?
									}
								}
								else
								{
									?><select name="<?=$arParams['PRODUCT_PROPS_VARIABLE'];?>[<?=$propID;?>]"><?
									foreach ($propInfo['VALUES'] as $valueID => $value)
									{
										?>
										<option value="<?=$valueID;?>" <?=($valueID == $propInfo['SELECTED']
											? '"selected"' : '');?>><?=$value;?></option><?
									}
									?></select><?
								}
								?>
							</td>
						</tr>
					<?
					}
					?>
				</table>
			<?
			}
			?>
		</div>
	<?
	}
	$arJSParams = array(
		'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
		'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
		'SHOW_ADD_BASKET_BTN' => false,
		'SHOW_BUY_BTN' => true,
		'SHOW_ABSENT' => true,
		'PRODUCT' => array(
			'ID' => $arItem['ID'],
			'NAME' => $arItem['~NAME'],
			'PICT' => ('Y' == $arItem['SECOND_PICT']?$arItem['PREVIEW_PICTURE_SECOND']:$arItem['PREVIEW_PICTURE']),
			'CAN_BUY' => $arItem["CAN_BUY"],
			'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
			'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
			'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
			'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
			'ADD_URL' => $arItem['~ADD_URL'],
			'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
			'LIST_SUBSCRIBE_ID' => $arParams['LIST_SUBSCRIPTIONS'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
			'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['PICT'] : $arItemIDs['PICT']),
			'QUANTITY_ID' => $arItemIDs['QUANTITY'],
			'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
			'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
			'PRICE_ID' => $arItemIDs['PRICE'],
			'BUY_ID' => $arItemIDs['BUY_LINK'],
			'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
			'DELETE_SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_DELETE_LINK'],
		),
		'LAST_ELEMENT' => $arItem['LAST_ELEMENT'],
	);
	?>
		<script type="text/javascript">
			var <?=$strObName;?> = new JCCatalogProductSubscribeList(
				<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
		</script><?
	}
	else // Wth Sku
	{

	$boolShowOfferProps = !!$arItem['OFFERS_PROPS_DISPLAY'];
	$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));
	if ($boolShowProductProps || $boolShowOfferProps)
	{
	?>
		<div class="product-cat-info-container product-cat-hidden small" data-entity="props-block">
			<dl class="product-cat-properties">
			<?
			if ($boolShowProductProps)
			{
				foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					?><dt><?=$arOneProp['NAME'];?>:</dt>
					<dd><?
					echo(
					is_array($arOneProp['DISPLAY_VALUE'])
						? implode(' / ', $arOneProp['DISPLAY_VALUE'])
						: $arOneProp['DISPLAY_VALUE']
					);?></dd><?
				}
			}

			?>
			</dl>
			<span id="<?=$arItemIDs['DISPLAY_PROP_DIV'];?>" style="display: none;"></span>
		</div>
	<?
	}

	if (!empty($arItem['OFFERS']) && isset($skuTemplate[$arItem['ID']]))
	{
		$arSkuProps = array();?>
		<div id="<?=$arItemIDs['PROP_DIV']?>">
			<?php
			foreach ($arResult['SKU_PROPS'][$arItem['ID']] as $skuProperty)
			{

				$propertyId = $skuProperty['ID'];
				$skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
				if (!isset($arItem['SKU_TREE_VALUES'][$propertyId]))
					continue;
				?>
				<div class="product-cat-info-container product-cat-scu-container product-cat-hidden" data-entity="sku-block">
					<div data-entity="sku-line-block">
						<div class="product-cat-info-container-title mb-2 small text-extra"><?=htmlspecialcharsEx($skuProperty['NAME'])?>: <span class="text-body" data-entity="sku-current-value"></span></div>
						<div class="product-cat-scu-block">
							<div class="product-cat-scu-list">
							<?php
/*
							if (in_array($skuProperty['CODE'], $arParams['OFFER_TREE_DROPDOWN_PROPS']))
							{
								include(MyTemplate::getTemplatePart($templateFolder.'/include/sku-dropdown.php'));
							}
							else
							{
*/
								?>
								<ul class="product-cat-scu-item-list" id="<?=$arItemIDs['PROP'].$propertyId?>_list">
									<?
									foreach ($skuProperty['VALUES'] as $value)
									{
										if (!isset($arItem['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
											continue;

										$value['NAME'] = htmlspecialcharsbx($value['NAME']);

										if ($skuProperty['SHOW_MODE'] === 'PICT')
										{
											?>
											<li class="product-cat-scu-item-color-container" title="<?=$value['NAME']?>"
												data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
												<div class="product-cat-scu-item-color-block">
													<div class="product-cat-scu-item-color" title="<?=$value['NAME']?>"
														style="background-image: url('<?=$value['PICT']['SRC']?>');">
													</div>
												</div>
											</li>
											<?php
										}
										else
										{
											?>
											<li class="product-cat-scu-item-text-container btn btn-outline-secondary btn-sm" title="<?=$value['NAME']?>"
												data-treevalue="<?=$propertyId?>_<?=$value['ID']?>" data-onevalue="<?=$value['ID']?>">
												<div class="product-cat-scu-item-text-block">
													<div class="product-cat-scu-item-text"><?=$value['NAME']?></div>
												</div>
											</li>
											<?php
										}
									}
									?>
								</ul>
								<?php
/*
							}
*/
							?>
							</div>
						</div>
					</div>
				</div>
				<?
			}
			?>
		<?
/*
			foreach ($skuTemplate[$arItem['ID']] as $propId => $propTemplate)
			{
				$valueCount = count($propTemplate['ITEMS']);
				if ($valueCount > 5)
				{
					$fullWidth = ($valueCount*20).'%';
					$itemWidth = (100/$valueCount).'%';
					$rowTemplate = $propTemplate['SCROLL'];
				}
				else
				{
					$fullWidth = '100%';
					$itemWidth = '20%';
					$rowTemplate = $propTemplate['FULL'];
				}
				unset($valueCount);
				echo '<div>', str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $fullWidth), $rowTemplate['START']);
				foreach ($propTemplate['ITEMS'] as $value => $valueItem)
				{
					echo str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $itemWidth), $valueItem);
				}
				unset($value, $valueItem);
				echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $rowTemplate['FINISH']), '</div>';
			}
			unset($propTemplate);
*/
			foreach ($arResult['SKU_PROPS'][$arItem['ID']] as $arOneProp)
			{
				$arSkuProps[] = array(
					'ID' => $arOneProp['ID'],
					'SHOW_MODE' => $arOneProp['SHOW_MODE'],
					'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
				);
			}?>
		</div>

	<?
	if($arItem['OFFERS_PROPS_DISPLAY'])
	{
		foreach($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer)
		{
			$strProps = '';
			if (!empty($arJSOffer['DISPLAY_PROPERTIES']))
			{
				foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<br>'.$arOneProp['NAME'].' <strong>'.(
						is_array($arOneProp['VALUE'])
							? implode(' / ', $arOneProp['VALUE'])
							: $arOneProp['VALUE']
						).'</strong>';
				}
			}
			$arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
		}
	}
	$arJSParams = array(
		'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
		'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
		'SHOW_ADD_BASKET_BTN' => false,
		'SHOW_BUY_BTN' => true,
		'SHOW_ABSENT' => true,
		'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
		'SECOND_PICT' => ($arParams['SHOW_IMAGE'] == "Y" ? $arItem['SECOND_PICT'] : false),
		'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
		'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
		'DEFAULT_PICTURE' => array(
			'PICTURE' => $arItem['PRODUCT_PREVIEW'],
			'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
			'PICT_ID' => $arItemIDs['PICT'],
			'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
			'QUANTITY_ID' => $arItemIDs['QUANTITY'],
			'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
			'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
			'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
			'PRICE_ID' => $arItemIDs['PRICE'],
			'TREE_ID' => $arItemIDs['PROP_DIV'],
			'TREE_ITEM_ID' => $arItemIDs['PROP'],
			'BUY_ID' => $arItemIDs['BUY_LINK'],
			'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
			'DSC_PERC' => $arItemIDs['DSC_PERC'],
			'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
			'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
			'DELETE_SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_DELETE_LINK'],
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE']
		),
		'PRODUCT' => array(
			'ID' => $arItem['ID'],
			'NAME' => $arItem['~NAME'],
			'LIST_SUBSCRIBE_ID' => $arParams['LIST_SUBSCRIPTIONS'],
		),
		'OFFERS' => $arItem['JS_OFFERS'],
		'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps,
		'LAST_ELEMENT' => $arItem['LAST_ELEMENT'],
	); ?>
		<script type="text/javascript">
			var <?=$strObName;?> = new JCCatalogProductSubscribeList(
				<?=CUtil::PhpToJSObject($arJSParams, false, true);?>);
		</script>
	<?
	}
	}
	?>
				</div><?php // .product-cat-body ?>
			</div><?php // .product-cat-content ?>
		</div><?php // .product-cat ?>
	</div><?php // .product-cat-container ?>
</div><?php // $gridClass ?>
	<?
	}
	?>
	</div>
<?
	// load css
	$APPLICATION->IncludeComponent(
		'bitrix:catalog.item',
		'catalog',
		array(),
		$component,
		array('HIDE_ICONS' => 'Y')
	);

	$layout->end();
}
else
{

	if(isset($arParams['GUEST_ACCESS'])):
		// $layout
			// ->addModifier('inner-spacing');
		// $layout->start();
		echo ShowError(Loc::getMessage('CPSL_SUBSCRIBE_NOT_FOUND'));
		// $layout->end();
	endif;
}
?>
