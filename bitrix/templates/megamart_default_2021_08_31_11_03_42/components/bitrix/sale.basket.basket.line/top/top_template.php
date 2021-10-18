<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?><a href="<?=$arParams['PATH_TO_BASKET']?>" class="c-icon-count<?=(!$compositeStub && $arResult['NUM_PRODUCTS'] > 0 ? ' has-items' : '')?>">
<?
/*
if (!$compositeStub && $arParams['SHOW_AUTHOR'] == 'Y'):?>
    <div class="bx-basket-block">
        <i class="fa fa-user"></i>
        <?if ($USER->IsAuthorized()):
            $name = trim($USER->GetFullName());
            if (! $name)
                $name = trim($USER->GetLogin());
            if (strlen($name) > 15)
                $name = substr($name, 0, 12).'...';
            ?>
            <a href="<?=$arParams['PATH_TO_PROFILE']?>"><?=htmlspecialcharsbx($name)?></a>
            &nbsp;
            <a href="?logout=yes"><?=Loc::getMessage('TSB1_LOGOUT')?></a>
        <?else:?>
            <a href="<?=$arParams['PATH_TO_REGISTER']?>?login=yes"><?=Loc::getMessage('TSB1_LOGIN')?></a>
            &nbsp;
            <a href="<?=$arParams['PATH_TO_REGISTER']?>?register=yes"><?=Loc::getMessage('TSB1_REGISTER')?></a>
        <?endif?>
    </div>
<?endif
*/
?>
	<svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg>
	<?
	if (!$compositeStub)
	{
		if ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y')
		{
			if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y')
			{
				?>
				<span class="c-icon-count__quantity"><?=$arResult['NUM_PRODUCTS']?></span>
				<?php
			}
		}
	}
	/*
	if ($arParams['SHOW_PERSONAL_LINK'] == 'Y'):?>
		<br>
		<span class="icon_info"></span>
		<a href="<?=$arParams['PATH_TO_PERSONAL']?>"><?=Loc::getMessage('TSB1_PERSONAL')?></a>
	<?endif
	*/?>
</a>
