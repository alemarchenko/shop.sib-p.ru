<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

$documentRoot = Application::getDocumentRoot();
$request = Application::getInstance()->getContext()->getRequest();
$curPage = $APPLICATION->GetCurPage(true);

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php');

$hasBanner = $curPage == SITE_DIR.'index.php';

$sMenuTheme = RS_MM_MENU_THEME;
define('RS_MM_HEAD_TYPE', 'type7');

$sHeaderClass = 'l-head l-head--type7 l-head--'.$sMenuTheme;
$sHeaderBackgroundClass = 'position-relative';

if ($hasBanner)
{
	$sHeaderClass .= ' has-banner';

	if (RS_MM_BANNER_TYPE == 'underlay')
	{
		$sHeaderClass .= ' is-underlay';
	}
	else if(RS_MM_BANNER_TYPE == 'center' || RS_MM_BANNER_TYPE == 'center_sidebanners')
	{
		$sHeaderClass .= ' has-shift';
	}

	if (RS_MM_BANNER_TYPE == 'center_sidebanners')
	{
		$sHeaderClass .= ' has-sidebanners';
		define('RS_MM_BANNER_SIDEBANNERS', 'right');
	}
}

if (!$hasBanner || RS_MM_BANNER_TYPE != 'underlay')
{
	$sHeaderBackgroundClass .= ' bg-light';
}

?>

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

					<div class="l-head__controls d-flex align-items-center mx-3 order-3">
						<div class="mr-3 mr-lg-5 d-block">
							<?php
							$APPLICATION->IncludeFile(
								SITE_DIR.'/include/header/personal.php',
								array(),
								array(
									'SHOW_BORDER' => false
								)
							);
							?>
						</div>

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

					<div class="l-head__inner-menu d-block mx-3 flex-grow-1 order-2">
						<?php $APPLICATION->IncludeFile(
							SITE_DIR.'include/header/menu_inheader_1.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						); ?>
					</div>

				</div>
			</div>

			<div class="l-head__line bg-<?=RS_MM_MENU_THEME?>">
				<div class="container js-menu-container">
					<div class="l-head__line-inner d-flex">
						<div class="d-block flex-grow-0 flex-shrink-1 position-relative order-2">
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
						<div class="d-flex flex-grow-1 flex-shrink-1 order-1 l-head__menu">
							<?php $APPLICATION->IncludeFile(
								SITE_DIR.'include/header/menu_vertical.php',
								array(),
								array(
									'SHOW_BORDER' => false
								)
							); ?>
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
