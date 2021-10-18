<?php

include $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";
?>
<div>
	<div class="rsform">
	
		<img class="mt-4 mb-6" src="/include/added_bonus_card.png">

		<p>Бонусная программа MegaMart - это система поощрения постоянных покупателей интернет-магазина MegaMart.<br>
		Вы получаете клубные цены — и приобретаете товары выгоднее. Вы накапливаете бонусы и приобретаете следующие товары еще выгоднее!</p>
	
		<?
		global $USER;
		if ($USER->IsAuthorized() && CBXFeatures::IsFeatureEnabled('SaleAccounts'))
		{
			?>
				<div class="mt-5">
					<? $APPLICATION->IncludeComponent("bitrix:sale.personal.account","bonus",Array(
						"SET_TITLE" => "N"
					)); ?>
				</div>
			<?php
		}
		?>
	</div>
</div>