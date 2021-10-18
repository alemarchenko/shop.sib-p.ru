<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

if (isset($arResult['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));

	$injectId = 'sale_gift_product_'.rand();

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.sale.prediction.product.detail'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.sale.prediction.product.detail');

	$frame = $this->createFrame()->begin("");
	?>

	<span id="<?=$injectId?>" class="sale_prediction_product_detail_container"></span>

	<script type="text/javascript">
		BX.ready(function(){

			var giftAjaxData = {
				'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
				'template': '<?=CUtil::JSEscape($signedTemplate)?>',
				'site_id': '<?=CUtil::JSEscape($component->getSiteId())?>'
			};

			bx_sale_prediction_product_detail_load(
				'<?=CUtil::JSEscape($injectId)?>',
				giftAjaxData
			);

			BX.addCustomEvent('onHasNewPrediction', function(html){
				var $button = $('#<?=$arParams['BUTTON_ID'] ?>');
				if (html.length > 0)
				{
					var popover = $button.data('bs.popover');
					if (popover == undefined)
					{
						$button.popover({
							placement : 'top',
							trigger : 'manual',
							sanitize: false,
							html : true,
							content : html + '<svg class="popover-close icon-svg" onclick="$(\'#<?=$arParams['BUTTON_ID']?>\').popover(\'hide\');"><use xlink:href="#svg-close"></use></svg>',
							delay: { show: 300, hide: 600 }
						});
					}
					else
					{
						popover.config.content = html + '<svg class="popover-close icon-svg" onclick="$(\'#<?=$arParams['BUTTON_ID']?>\').popover(\'hide\');"><use xlink:href="#svg-close"></use></svg>';
					}
					
					$button.popover('show');
				}
				else
				{
					$button.popover('hide');
				}
			});

			BX.addCustomEvent('OnBasketChange', function(){
				bx_sale_prediction_product_detail_load(
					'<?=CUtil::JSEscape($injectId)?>',
					giftAjaxData
				);
			});
		});
	</script>

	<?
	$frame->end();
	return;
}
else
{
		?>
		<script type="text/javascript">
			BX.ready(function () {
				BX.onCustomEvent('onHasNewPrediction', ['<?= \CUtil::JSEscape($arResult['PREDICTION_TEXT']) ?>']);
			});
		</script>

		<?

	
}



