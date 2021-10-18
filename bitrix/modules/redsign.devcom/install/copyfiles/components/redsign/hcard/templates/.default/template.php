<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

?>
<div class="vcard">
    <?php if (!empty($arParams['ORGANIZATION'])): ?>
        <h2 class="fn org">
            <?=$arParams['ORGANIZATION'] ?>
        </h2>
    <?php endif; ?>

    <div class="contacts-view">
        <?php
        if (is_array($arResult['ADR']) && count($arResult['ADR']) > 0)
        {
            ?>
            <div class="contacts-view__address">
                <span class="contacts-view__title"><?=Loc::getMessage('ALFA_MSG_HCARD_ADDRESS');?>:</span>
                    <span class="adr"><?php
                        foreach ($arResult['ADR'] as $adr)
                        {
                            ?><span class="<?=$adr['CLASS']?>"><?=$adr['VALUE']?></span><?
                            if ($adr != end($arResult['ADR'])) echo ', ';
                        }
                    ?></span>
                </span>
            </div>
            <?php
        }
        ?>

        <?php if (!empty($arParams['WORKHOURS'])): ?>
            <div class="contacts-view__workhouse">
                <span class="contacts-view__title"><?=Loc::getMessage('ALFA_MSG_HCARD_WORKHOURS');?>:</span>
                    <span class="workhours"><?=$arParams['WORKHOURS']?></span>
                </span>
            </div>
        <?php endif; ?>

        <?php  if (!empty($arParams['PHONE'])):
            $sPhoneUrl = preg_replace('/[^0-9\+]/', '', $arParams['PHONE']);
        ?>
        <div class="contacts-view__phone">
            <span class="contacts-view__title"><?=Loc::getMessage('ALFA_MSG_HCARD_PHONE')?>:</span>
                <span class="workhours">
                    <a href="tel: <?=$sPhoneUrl?>" class="tel"><?=$arParams['PHONE']?></a>
                </span>
            </span>
        </div>
        <?php endif; ?>

        <?php if(!empty($arParams['EMAIL'])): ?>
        <div class="contacts-view__email">
            <span class="contacts-view__title"><?=Loc::getMessage('ALFA_MSG_HCARD_EMAIL')?>:</span>
            <span class="workhours">
                <a href="mailto: <?=$arParams['EMAIL']?>" class="email"><?=$arParams['EMAIL']?></a></td>
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>
