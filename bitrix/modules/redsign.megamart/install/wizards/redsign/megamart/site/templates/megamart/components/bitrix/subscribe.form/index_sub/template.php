<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<section class="l-section">
	<div class="container">
		<div class="l-section__content l-section__content_sub">
			<div class="subscribe__grani" ></div>
			<div  class= "subscribe" >
				<div class="row align-items-center">			
					<div class="col-12 col-md-12<?=($arParams['VERTICAL'] == 'Y' ? '' : ' col-lg-3')?>">
						<div class="subscribe-message">
							<img src="<?=$templateFolder?>/mail.png">
						</div>
					</div>
					<div class="col-md-12<?=($arParams['VERTICAL'] == 'Y' ? '' : ' col-lg-5')?> subscribe_tops" >
						<div class="row">
							<h4 class="subscribe__text "><?=GetMessage("subscr_form_h")?></h4>
							<p class="subscribe__text "><?=GetMessage("subscr_form_text")?> </p>
						</div>
					</div>
					<div class="col-md-12<?=($arParams['VERTICAL'] == 'Y' ? '' : ' col-lg-4')?> mt-3 mb-3 subscribe_podp">
						<form action="<?=$arResult["FORM_ACTION"]?>">
							<div class= "subscribe__pod-1<?=($arParams['SHORT'] == 'Y' ? ' m-short' : '')?>">
								<input type="text" name="sf_EMAIL" class="form-control" value="<?=$arResult["EMAIL"]?>" placeholder="¬ведите ваш email" />
							</div>
							<div class= "subscribe__pod-2<?=($arParams['SHORT'] == 'Y' ? ' m-short' : '')?>">
								<button type="submit" class="btn btn-primary subscribe__btn_subs" value="<?=GetMessage("subscr_form_button")?>" name="OK" ><?=GetMessage("subscr_form_button")?></button>
							</div>	
							<div class="clear"></div>		
						</form>
					</div>
				</div>
			</div>
			<div class="subscribe__grani"></div>
		</div>
	</div>
</section>
