<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $APPLICATION;

$asset = Asset::getInstance();


if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;

	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

if (isset($templateData['JS_OBJ']))
{
	?>
	<script>
		BX.ready(BX.defer(function(){
			if (!!window.<?=$templateData['JS_OBJ']?>)
			{
				window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
			}
		}));
	</script>

	<?
	// check compared state
	if ($arParams['DISPLAY_COMPARE'])
	{
		$compared = false;
		$comparedIds = array();
		$item = $templateData['ITEM'];

		if (!empty($_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]))
		{
			if (!empty($item['JS_OFFERS']))
			{
				foreach ($item['JS_OFFERS'] as $key => $offer)
				{
					if (array_key_exists($offer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
					{
						if ($key == $item['OFFERS_SELECTED'])
						{
							$compared = true;
						}

						$comparedIds[] = $offer['ID'];
					}
				}
			}
			elseif (array_key_exists($item['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
			{
				$compared = true;
			}
		}

		if ($templateData['JS_OBJ'])
		{
			?>
			<script>
				BX.ready(BX.defer(function(){
					if (!!window.<?=$templateData['JS_OBJ']?>)
					{
						window.<?=$templateData['JS_OBJ']?>.setCompared('<?=$compared?>');

						<? if (!empty($comparedIds)): ?>
						window.<?=$templateData['JS_OBJ']?>.setCompareInfo(<?=CUtil::PhpToJSObject($comparedIds, false, true)?>);
						<? endif ?>
					}
				}));
			</script>
			<?
		}
	}

	// select target offer
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$offerNum = false;
	$offerId = (int)$this->request->get('OFFER_ID');
	$offerCode = $this->request->get('OFFER_CODE');

	if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
	{
		$offerNum = array_search($offerId, $templateData['OFFER_IDS']);
	}
	elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
	{
		$offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
	}

	if (!empty($offerNum))
	{
		?>
		<script>
			BX.ready(function(){
				if (!!window.<?=$templateData['JS_OBJ']?>)
				{
					window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
				}
			});
		</script>
		<?
	}
}

if ($arParams['USE_FAVORITE'] == 'Y' && \Bitrix\Main\Loader::includeModule('redsign.favorite'))
{
	CJSCore::Init('rs_favorite');
}

if (!empty($arResult['BACKGROUND_COLOR']))
{
	$APPLICATION->SetPageProperty(
		'backgroundImage',
		'style="background-color:'.$arResult['BACKGROUND_COLOR'].'"'
	);

	if (\Bitrix\Main\Loader::includeModule('redsign.devfunc'))
	{
		$oColor = new RSColor($arResult['BACKGROUND_COLOR']);

		ob_start();

		if ($oColor->yiq())
		{
			echo ' l-main__head--dark';
		}
		else
		{
			echo ' l-main__head--light';
		}

		$sBreadcrumbClass = ob_get_clean();
		$APPLICATION->AddViewContent('backgroundClass', $sBreadcrumbClass, 100);
		unset($sBreadcrumbClass);
	}
}

if ($arParams['USE_SHARE'] == 'Y')
{
	$asset->addString('<script src="https://yastatic.net/share2/share.js" async="async" charset="utf-8"></script>');
}

if (is_array($templateData['PRODUCT_PHOTO']['RESIZE']))
{
	$APPLICATION->SetPageProperty("og:image", CHTTP::URN2URI($templateData['PRODUCT_PHOTO']['RESIZE']['src']));
}
elseif (is_array($templateData['PRODUCT_PHOTO']))
{
	$APPLICATION->SetPageProperty("og:image", CHTTP::URN2URI($templateData['PRODUCT_PHOTO']['SRC']));
}

global $catalogElement;
$catalogElement = [
	'LINES_PROPERTIES' => []
];

// Lines properties
if (!empty($arParams['LINES_PROPERTIES']) && is_array($arParams['LINES_PROPERTIES']))
{
	foreach ($arParams['LINES_PROPERTIES'] as $propCode)
	{
		if (!isset($arResult['DISPLAY_PROPERTIES'][$propCode]))
			continue;

		$catalogElement['LINES_PROPERTIES'][$propCode] = $arResult['DISPLAY_PROPERTIES'][$propCode];
	}
}

foreach ($arResult['DISPLAY_PROPERTIES'] as $displayPropertyCode => $displayProperty)
{
	if (
		$displayProperty['USER_TYPE'] == 'redsign_custom_filter' && 
		!isset($catalogElement['DISPLAY_PROPERTIES'][$displayPropertyCode])
	)
	{
		
		$catalogElement['LINES_PROPERTIES'][$displayPropertyCode] = $displayProperty;
	}
}