<?php
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}
?>
<div class="sale-personal-account-wallet-list">
	<?
	foreach($arResult["ACCOUNT_LIST"] as $accountValue)
	{
		?>
		<div class="product-detail-cashback mt-3">
			<img class="product-detail-cashback-icon mr-2 mb-1" src="<?=$this->GetFolder().'/images/coins.png?v3'?>" alt="">
			<span class="text-nowrap"><?=Loc::getMessage('RS_MM_SPA_BONUS_ACCOUNT_SUM');?> <span class="text-primary font-weight-bold"><?=$accountValue['SUM']?></span></span>
		</div>
		<?
	}
	?>
</div>