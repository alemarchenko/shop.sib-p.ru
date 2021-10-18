<?php

use \Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$this->setFrameMode(true);

?>
<?php if (isset($arResult['ITEMS'][0]['BACKGROUND'])): ?>
	<div class="js-preloader rs_banner-preloader preloader" style="width: 100%; display: none;">
		<div class="rs-banner-loader">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
	</div>
	<script data-skip-moving="true">
	var image = new Image();
	image.src = '<?=$arResult['ITEMS'][0]['BACKGROUND']?>';
	if (!image.complete)
	{
		var preloader = document.querySelector('.js-preloader');

		if (preloader)
		{
			preloader.style.display = 'block';
		}
	}

	<?php
	$arItem = $arResult['ITEMS'][0];
	$sTextColor = isset($arItem['PROPERTIES']['TEXT_COLOR']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['TEXT_COLOR']['VALUE_XML_ID'] : 'dark';

	?>

	document.querySelector('.l-head').classList.add('color-<?=$sTextColor?>');
	<?php unset($arItem); ?>
	</script>
<?php endif; ?>
<div class="rs-banners-container js-mainbanners-container <?=$arResult['BANNER_CLASS']?>">

	<div class="rs-banners-sidebanners">
		<?php
		if (!empty($arResult['SIDEBANNERS']['LEFT']))
		{
			?>
			<div class="rs-banners-sidebanner __left js-sidebanners <?php if(in_array("left", $arResult['SELECTED_SIDEBANNERS'])) {echo 'js-sidebanners_selected';} ?>">
				<?php foreach($arResult['SIDEBANNERS']['LEFT'] as $arImage): ?>
					<div class="rs-banners-sidebanner_image">
						<a href="<?=$arImage['link']?>">
							<img src="<?=$arImage['src']?>">
						</a>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		if (!empty($arResult['SIDEBANNERS']['RIGHT']))
		{
			?>
			<div class="rs-banners-sidebanner __right js-sidebanners <?php if(in_array("right", $arResult['SELECTED_SIDEBANNERS'])) {echo 'js-sidebanners_selected';} ?>">
				<?php foreach($arResult['SIDEBANNERS']['RIGHT'] as $arImage): ?>
					<div class="rs-banners-sidebanner_image">
						<a href="<?=$arImage['link']?>">
							<img src="<?=$arImage['src']?>">
						</a>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
		}
		?>
	</div>


	<div
		class="js-banner-options"
		style="display: none;"
		<?php foreach($arResult['BANNER_OPTIONS'] as $optionName => $optionValue): ?>
			<?php if(is_bool($optionValue)): ?>
				data-<?=$optionName?>="<?=$optionValue ? 'true' : 'false'?>"
			<?php elseif(is_array($optionValue)): ?>

			<?php else: ?>
				data-<?=$optionName?>="<?=$optionValue?>"
			<?php endif; ?>
		<?php endforeach; ?>
	></div>

	<div class="rs-banners js-banners owl-theme owl-carousel" style="display: block">

	<?php foreach($arResult['ITEMS'] as $arItem): ?>

		<?php
		 $this->AddEditAction(
			$arItem['ID'],
			$arItem['EDIT_LINK'],
			CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT")
		);
		$this->AddDeleteAction(
			$arItem['ID'],
			$arItem['DELETE_LINK'],
			CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
			array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))
		);

		$sTextColor = isset($arItem['PROPERTIES']['TEXT_COLOR']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['TEXT_COLOR']['VALUE_XML_ID'] : 'dark';
		?>

		<div class="rs-banners_banner" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-text-color="<?=$sTextColor?>">
			<?php if($arItem['VIDEO_TYPE'] == 'frame'): ?>
				<a class="owl-video" href="<?=$arItem['VIDEO_URL']?>"></a>
			<?php elseif($arItem['VIDEO_TYPE'] == 'file'): ?>

				<div class="rs-banners_video" data-play="false">
					<video src="<?=$arItem['VIDEO_URL']?>"></video>
				</div>
				<div class="rs-banners_video-play"></div>
				<div class="rs-banners_wrap">
						<div class="rs-banners_infowrap rs-banners_infovideo">
							<div class="rs-banners_info">

								<?php if(!empty($arItem['PRODUCT_TITLE'])): ?>
									<div class="rs-banners_title rs-banners_video-blockwrap">
										<?=$arItem['PRODUCT_TITLE']?>
									</div>
								<?php endif; ?>

								<?php if(!empty($arItem['PRODUCT_PRICE'])): ?>
									<div class="rs-banners_price rs-banners_video-blockwrap">
										<?=$arItem['PRODUCT_PRICE']?>
									</div>
								<?php endif; ?>

								<?php if(!empty($arItem['PRODUCT_DESC'])): ?>
									<div class="rs-banners_desc rs-banners_video-blockwrap">
										<?=$arItem['PRODUCT_DESC']?>
									</div>
								<?php endif; ?>

							</div>
						</div>
					</div>
			<?php else: ?>


				<?php if(!empty($arItem['BACKGROUND'])): ?>
				<div
					class="rs-banners_background"
					data-img-src="<?=$arItem['BACKGROUND']?>"
					style="background-image:url('<?=$arItem['BACKGROUND']?>')"
				>
					<img src="<?=$arItem['BACKGROUND']?>" style="width: 0px; height: 0px;">
				</div>
				<?php endif; ?>


				<div class="rs-banners_wrap">
					<?php if(!empty($arItem['PRODUCT_IMG'])): ?>

						<div class="rs-banners_product">
							<img
								data-img-src="<?=$arItem['PRODUCT_IMG']?>"
								src="<?=$arItem['PRODUCT_IMG']?>"
								alt="<?=$arItem['NAME']?>"
							>
						</div>

					<?php endif; ?>

						<div class="rs-banners_infowrap" style="opacity: 0; transition: 1s;">
							<div class="rs-banners_info">
								<?php if(!empty($arItem['PRODUCT_TITLE'])): ?>
									<div
										class="rs-banners_title"
										style="<?=!(empty($arItem['PRODUCT_TITLE_BACKGROUND'])) ? 'background: '.htmlspecialcharsbx($arItem['PRODUCT_TITLE_BACKGROUND']).';' : ''?>"
									>
										<?=$arItem['PRODUCT_TITLE'];?>
									</div>
								<?php endif; ?>
								<?php if(!empty($arItem['PRODUCT_PRICE'])): ?>
									<br>
									<div
										class="rs-banners_price"
										style="<?=!(empty($arItem['PRODUCT_PRICE_BACKGROUND'])) ? 'background: '.htmlspecialcharsbx($arItem['PRODUCT_PRICE_BACKGROUND']).';' : ''?>"
									>
										<?=$arItem['PRODUCT_PRICE'];?>
									</div>
								<?php endif; ?>
								<?php if(!empty($arItem['PRODUCT_DESC'])): ?>
									<br>
									<div class="rs-banners_desc">
										<?=$arItem['PRODUCT_DESC'];?>
									</div>
								<?php endif; ?>

								<?php if ($arItem['PROPERTIES']['BUTTONS']['VALUE']): ?>
								<br><div class="rs-banners_buttons">
									<?php
									foreach ($arItem['PROPERTIES']['BUTTONS']['VALUE'] as $nIndex => $sButtonSrc):
										$sButtonText = $arItem['PROPERTIES']['BUTTONS']['DESCRIPTION'][$nIndex];
										$isAjax = false;

										if (preg_match('/^ajax:.+/', $sButtonSrc)) {
											$isAjax = true;
											$sButtonSrc = preg_replace('/^(ajax:)(.+)/', "$2", $sButtonSrc);
										}
									?>
										<a href="<?=$sButtonSrc?>" class="btn<?=($nIndex === 0) ? ' btn-primary' : ' btn-outline-primary';?>"<?php if ($isAjax): ?> data-type="ajax"<?php endif; ?>><?=$sButtonText?></a>
									<?php endforeach; ?>
								</div>
								<?php endif; ?>
							</div>
						</div>

				</div>
				<?php if(!empty($arItem['PRODUCT_LINK'])): ?>
					<a href="<?=$arItem['PRODUCT_LINK']?>" target="_blank" class="rs-banners_link"></a>
				<?php endif; ?>
			<?php endif; ?>

		</div>
	<?php endforeach; ?>
	</div>

	<div class="rs-banners_bottom-line"></div>
</div>

<div class="js-preloader rs_banner-preloader preloader" style="width: 100%; display: none;">
	<div class="rs-banner-loader">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</div>
</div>



<script>
	rsBannersOnReady();
</script>
