<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$prefix = $arParams['PREFIX'];

$arJSONResult = array();

?>
<div class="card font-size-sm card-lazyload" id="<?=$prefix?>delivery_block">
	<?php
	if ($arParams['AJAX_CALL'] != 'Y')
	{
		?>
		<div class="card-header py-3 px-4 mb--1"><?=Loc::getMessage('RSDC_TEMPLATE_DELIVERY')?></div>
		<div class="card-body py-3 px-4 text-extra">
			<ul class="list-unstyled mb-0">
				<li class="my-2 d-inline-block w-100"><?=Loc::getMessage('RSDC_TEMPLATE_LOADING')?></li>
			</ul>
		</div>
		<?php
		$arAjaxParams = array(
			'templateName' => $this->GetName(),
			'SITE_ID' => SITE_ID,
			'arParams' => $arResult['ORIGINAL_PARAMS'],
		);
		$ajaxPath = $templateFolder.'/ajax.php';
		?>
		<script>

		BX.addCustomEvent('rs_delivery_update', function(productId, quantity, beforeFn, afterFn) {
		  var params = <?=CUtil::PhpToJSObject($arAjaxParams)?>;
		  params.arParams.ELEMENT_ID = productId || params.arParams.ELEMENT_ID;
		  params.arParams.QUANTITY = quantity || params.arParams.QUANTITY;
		  beforeFn = beforeFn || function() {};
		  afterFn = afterFn || function() {};

		  beforeFn();

		  BX.ajax.post('<?=$ajaxPath?>', params, function(result) {

			afterFn();

			var deliveryBlock = BX("<?=$prefix?>delivery_block");
			if (deliveryBlock && result)
			{
			  deliveryBlock.innerHTML = result;

			  deliveryList = BX("<?=$prefix?>delivery_block_list");

			  deliveryList.style.left = '0px';
			  setTimeout(function() {
				  deliveryList.style.position = 'static';
			  }, 600);
			  window.deliveryList = deliveryList;
			}
		  });
		});
		BX.onCustomEvent('rs_delivery_update');

		</script>
		<?php
	}
	elseif ($arParams['AJAX_CALL'] == 'Y')
	{
		$APPLICATION->RestartBuffer();
		?>
		<div class="card-header py-3 px-4 mb--1">
			<?php
			echo Loc::getMessage('RSDC_TEMPLATE_DELIVERY');
			if (isset($arResult['LOCATION_TO']['LOCATION_NAME']))
			{
				echo Loc::getMessage('RSDC_TEMPLATE_DELIVERY_IN_CITY').' '.$arResult['LOCATION_TO']['LOCATION_NAME'].': ';
			}
			?>
		</div>
		<div class="card-body py-3 px-4 text-extra">
			<div class="position-relative">
				<ul class="list-unstyled mb-0" id="<?=$prefix?>delivery_block_list" style="position: absolute; left: -9999999px;">
				<?php
				if (count($arResult['DELIVERIES']) > 0)
				{
					foreach ($arResult['DELIVERIES'] as $arDelivery)
					{
						if ($arDelivery['CALCULATION']['IS_SUCCESS'])
						{
							?>
							<li class="my-2 d-inline-block w-100">
							<?php
							if ($arDelivery['CALCULATION']['PRICE'] == 0)
							{
								$arDelivery['CALCULATION']['FORMAT_PRICE'] = Loc::getMessage('RSDC_TEMPLATE_FREE_SHIPPING');
							}

							echo $arDelivery['NAME'].' - '.$arDelivery['CALCULATION']['FORMAT_PRICE'];
							if ($arDelivery['CALCULATION']['PERIOD'])
							{
								?> (<?=$arDelivery['CALCULATION']['PERIOD']?>) <?php
							}
							?>
							</li>
							<?php
						}
					}

					if (isset($arParams['SHOW_DELIVERY_PAYMENT_INFO']) && $arParams['SHOW_DELIVERY_PAYMENT_INFO'] == 'Y')
					{
						$deliveryData = Loc::getMessage('RSDC_TEMPLATE_DELIVERY_DATA');
						$deliveryData = str_replace('#DELIVERY_LINK#', $arParams['DELIVERY_LINK'], $deliveryData);
						$deliveryData = str_replace('#PAYMENT_LINK#', $arParams['PAYMENT_LINK'], $deliveryData);
						?>
						<li class="my-2 d-inline-block w-100"><br><?=$deliveryData?></li>
						<?php
					}
					
				}
				else
				{
					?><li class="my-2 d-inline-block w-100"><?=Loc::getMessage('RSDC_TEMPLATE_DELIVERY_NOT_FOUND')?></li><?php
				}
				?>
				</ul>
			</div>
		</div>
	<?php
	die();
	}
	?>
</div>
