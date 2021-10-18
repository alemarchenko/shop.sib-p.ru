<?php
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php');
?>

<footer class="l-footer l-footer--type2 l-footer--<?=(RS_MM_FOOTER_THEME === 'type2_light' ? 'light' : 'dark')?>" data-footer>

	<div class="l-footer__inner">
		<div class="container">
			<div class="row">
				<div class="col flex-fill flex-grow-0 w-auto">

					<div class="d-block mb-4">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/location.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						);
						?>
					</div>

					<div class="d-block my-4">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/phones.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						);
						?>
					</div>

					<div class="d-block my-4">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/address.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						);
						?>
					</div>

					<div class="d-block my-4">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/emails.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						);
						?>
					</div>

				</div>
				<div class="col flex-grow-1 w-auto px-5">
					<div class="row">
						<div class="col-md-12">
							<?php
							$APPLICATION->IncludeFile(
								SITE_DIR.'/include/footer/menu_2.php',
								array(),
								array(
									'SHOW_BORDER' => false
								)
							);
							?>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-3">
					<div class="mb-4">
					<?php if(IsModuleInstalled('sender')):?>
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/sender.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						);
						?>
					<?php endif;?>
					</div>

					<div class="mb-4">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/compact/socnet_links.php',
							array(),
							array(
								'SHOW_BORDER' => false
							)
						);
						?>
					</div>
                    <div class="mb-4" id="grecaptcha-inline-badge">

                    </div>
				</div>
			</div>
		</div>
	</div>

	<?php if (RS_MM_USE_UP_BUTTON): ?>
	<div class="l-footer__up-button">
		<a href="#" class="d-block btn text-center p-4 text-white d-lg-none js-link-up">
			<?=Loc::getMessage('RS_FOOTER_BACK_TO_TOP'); ?>
			<svg class="icon-svg"><use xlink:href="#svg-arrow-up"></use></svg>
		</a>
		<div class="up-float-button<?php if (RS_MM_CONTROL_PANEL == 'bottom'): ?> up-float-button--up<?php endif; ?> js-link-up" data-float-button>
			<svg class="icon-svg"><use xlink:href="#svg-arrow-up"></use></svg>
		</div>
	</div>
	<?php endif; ?>

	<div class="l-footer__copyright">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center">
				<div class="d-block">
					<div class="d-block">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/all_rights.php',
							array(),
							array(
								'NAME' => Loc::getMessage('RS_FOOTER_EDIT_ALL_RIGHTS')
							)
						);
						?>
					</div>
					<div class="d-block b-alfa-copyright">
						<?=Loc::getMessage('RS_FOOTER_COPYRIGHT', array('#CURRENT_YEAR#' => date('Y')))?>
					</div>
				</div>
				<div class="d-flex align-items-center">
					<div class="d-none d-md-block">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/payments.php',
							array(),
							array(
								'NAME' => Loc::getMessage('RS_FOOTER_EDIT_PAYMENTS')
							)
						);
						?>
					</div>
					<div class="d-block mr-5" id="bx-composite-banner"></div>
					<div class="d-block">
						<?php
						$APPLICATION->IncludeFile(
							SITE_DIR.'/include/footer/age_limit.php',
							array(),
							array(
								'NAME' => Loc::getMessage('RS_FOOTER_AGE_LIMIT')
							)
						);
						?>
					</div>

				</div>
			</div>
		</div>
	</div>

</footer>
