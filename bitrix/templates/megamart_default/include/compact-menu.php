<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

?>
<div class="l-compact-menu">
	<div class="position-relative">
		<div class="d-block d-md-none l-compact-menu__user">
			<div class="container">
				<div class="compact-user-panel">
					<div class="compact-user-panel__user">
						<?=$APPLICATION->ShowViewContent('rs_mm_compact_menu_user'); // system.auth.form ?>
					</div>
					<div class="compact-user-panel__icons">
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
		<div class="l-compact-menu__items">
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"compact",
				array(
					"ROOT_MENU_TYPE" => "compact",
					"MAX_LEVEL" => "4",
					"CHILD_MENU_TYPE" => "mainsub",
					"USE_EXT" => "Y",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
					"MENU_CACHE_TYPE" => "Y",
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(),
					"COMPONENT_TEMPLATE" => "",
                    'CATALOG_PATH' => '/catalog/'
				),
				false
			); ?>
			<div class="l-compact-menu__bottom border-top d-md-none">
				<div class="container">
					<?php
					$sLocationPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/compact/location.php';
					$sPhonesPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/compact/phones.php';
					$sAdressPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/compact/address.php';
					$sEmailsPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/compact/emails.php';
					$sSocnetLinksPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/compact/socnet_links.php';

					if (file_exists($sLocationPath)) {
						?><div class="my-5"><?
							include $sLocationPath;
						?></div><?
					}

					if (file_exists($sPhonesPath)) {
						?><div class="my-5"><?
							include $sPhonesPath;
						?></div><?
					}

					if (file_exists($sAdressPath)) {
						?><div class="my-5"><?
							include $sAdressPath;
						?></div><?
					}

					if (file_exists($sEmailsPath)) {
						?><div class="my-5"><?
							include $sEmailsPath;
						?></div><?
					}

					if (file_exists($sSocnetLinksPath)) {
						?><div class="my-5"><?
							include $sSocnetLinksPath;
						?></div><?
					}

					?>
				</div>
			</div>
		</div>
	</div>
</div>
