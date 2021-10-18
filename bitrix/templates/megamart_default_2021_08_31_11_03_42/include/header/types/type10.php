<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

$documentRoot = Application::getDocumentRoot();
$request = Application::getInstance()->getContext()->getRequest();
$curPage = $APPLICATION->GetCurPage(true);

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php');

$hasBanner = $curPage == SITE_DIR.'index.php';

$sMenuTheme = RS_MM_MENU_THEME;
define('RS_MM_HEAD_TYPE', 'type10');

$sHeaderClass = 'l-head l-head--type10 l-head--'.$sMenuTheme;
$sHeaderBackgroundClass = 'position-relative';

if ($hasBanner)
{
	$sHeaderClass .= ' has-banner';

	if (RS_MM_BANNER_TYPE == 'underlay')
	{
		$sHeaderClass .= ' is-underlay';
	} else if (RS_MM_BANNER_TYPE == 'center')
	{
		$sHeaderClass .= ' has-shift';
	}

	if (RS_MM_BANNER_TYPE == 'center_sidebanners')
	{
		$sHeaderClass .= ' has-sidebanners';
		define('RS_MM_BANNER_SIDEBANNERS', 'both');
	}
}

if (!$hasBanner || RS_MM_BANNER_TYPE != 'underlay')
{
	$sHeaderBackgroundClass .= ' bg-light';
}

?>
<div class="l-topline d-none d-md-block">
	<div class="container l-topline__container">
		<div class="l-topline__left">
			<div class="l-topline__menu">
				<?php
				$APPLICATION->IncludeFile(
					SITE_DIR.'/include/header/menu_topline.php',
					array(),
					array(
						'SHOW_BORDER' => false
					)
				);
				?>
			</div>
		</div>
		<div class="l-topline__right">

			<div class="l-topline__location mx-4">
				<?php
				$APPLICATION->IncludeFile(
					SITE_DIR.'/include/header/location_topline.php',
					array(),
					array(
						'SHOW_BORDER' => false
					)
				);
				?>
			</div>

			<div class="l-topline__personal mx-4">
				<?php
				$APPLICATION->IncludeFile(
					SITE_DIR.'/include/header/personal_topline.php',
					array(),
					array(
						'SHOW_BORDER' => false
					)
				);
				?>
			</div>

			<div class="ml-4 l-topline__icons">
				<?php
				$APPLICATION->IncludeComponent(
					'rsmm:ui.widget',
					'favorite-icon',
					array(
					),
					false,
					array(
						'HIDE_ICONS' => 'Y'
					)
				);

				$APPLICATION->IncludeComponent(
					'rsmm:ui.widget',
					'compare-icon',
					array(
					),
					false,
					array(
						'HIDE_ICONS' => 'Y'
					)
				);

				$APPLICATION->IncludeComponent(
					'rsmm:ui.widget',
					'cart-icon',
					array(
					),
					false,
					array(
						'HIDE_ICONS' => 'Y'
					)
				);
				?>
			</div>

		</div>
	</div>

</div>
<header class="<?=$sHeaderClass?>">
	<div class="l-head__main">
		<div class="<?=$sHeaderBackgroundClass?>">
			<div class="container">
				<div class="d-flex align-items-center justify-content-between py-5">

					<div class="l-head__logo-slogan d-flex align-items-center mr-3 order-1">
						<div class="d-block l-head__logo">
							<a class="b-header-logo" href="<?=SITE_DIR?>">
								<?php
								$APPLICATION->IncludeFile(
									SITE_DIR.'/include/header/logo.php',
									array(),
									array(
										'NAME' => Loc::getMessage('RS_HEADER_EDIT_LOGO')
									)
								);
								?>
							</a>
						</div>
					</div>

					<div class="js-menu-container d-flex flex-grow-1 align-items-center order-2">

						<div class="l-head__inner-menu flex-grow-1 d-block mx-3">
							<?php $APPLICATION->IncludeFile(
								SITE_DIR.'include/header/menu_inheader_2.php',
								array(),
								array(
									'SHOW_BORDER' => false
								)
							); ?>
						</div>

						<div class="l-head__inner-search d-block ml-3 order-3 position-relative">
							<div class="position-absolute w-100">
								<?php
								$APPLICATION->IncludeFile(
									SITE_DIR.'/include/header/search_popup.php',
									array(),
									array(
										'SHOW_BORDER' => false
									)
								);
								?>
							</div>
							<a class="menu-search-button menu-search-button--<?=RS_MM_MENU_THEME?>" href="#" data-open-search-popup>
								<svg class="icon-svg"><use xlink:href="#svg-search"></use></svg>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($hasBanner): ?>
	<div class="l-head__banner">
		<?php

		if (RS_MM_BANNER_TYPE == 'center') {
			echo '<div class="l-head__banner-center">';
		}

		$APPLICATION->IncludeFile(
			SITE_DIR.'include/header/banner.php',
			array(),
			array(
				'SHOW_BORDER' => false
			)
		);

		if (RS_MM_BANNER_TYPE == 'center') {
			echo '</div>';
		}
		?>
	</div>
	<?php endif; ?>

</header>
