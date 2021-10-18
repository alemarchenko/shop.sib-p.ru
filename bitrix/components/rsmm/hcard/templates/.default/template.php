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
    <table class="contacts-view-table">
        <tbody>
            <tr>
                <td><b><?=Loc::getMessage('RS_HCARD_ADDRESS');?></b></td>
                <td class="adr"><?php
                    
                    if (!empty($arParams['ADR_POSTAL_CODE']))
                    {
                        ?><span class="postal-code"><?=$arParams['ADR_POSTAL_CODE']?></span><?
                    }

                    if (!empty($arParams['COUNTRY_NAME']))
                    {
                        ?><span class="country-name"><?=$arParams['COUNTRY_NAME']?></span><?
                    }

                    if (!empty($arParams['ADR_REGION']))
                    {
                        ?><span class="region"><?=$arParams['ADR_REGION']?></span><?
					}
					
                    if (!empty($arParams['ADR_LOCALITY']))
                    {
                        ?><span class="locality"><?=$arParams['ADR_LOCALITY']?></span><?
                    }

                    if (!empty($arParams['ADR_STREET_ADDRESS']))
                    {
                        ?><span class="street-adress"><?=$arParams['ADR_STREET_ADDRESS']?></span><?
                    }

                    if (!empty($arParams['ADR_EXT_ADDRESS']))
                    {
                        ?><span class="extended-address"><?=$arParams['ADR_EXT_ADDRESS']?></span><?
                    }

                ?></td>
            </tr>  

            <?php if (!empty($arParams['WORKHOURS'])): ?>
            <tr>
                <td><b><?=Loc::getMessage('RS_HCARD_WORKHOURS'); ?></b></td>
                <td><span class="workhours"><?=$arParams['WORKHOURS']?></span></td>
            </tr>
            <?php endif; ?>

            <?php 
            if (!empty($arParams['PHONE'])): 
                $sPhoneUrl = preg_replace('/[^0-9\+]/', '', $arParams['PHONE']);
            ?>
            <tr>
                <td><b><?=Loc::getMessage('RS_HCARD_PHONE')?></b></td>
                <td>
                    <a href="tel: <?=$sPhoneUrl?>" class="tel"><?=$arParams['PHONE']?></a>
                </td>
            </tr>
            <?php endif; ?>

            <?php if(!empty($arParams['EMAIL'])): ?>
            <tr>
                <td><b><?=Loc::getMessage('RS_HCARD_EMAIL')?></b></td>
                <td><a href="mailto: <?=$arParams['EMAIL']?>" class="email"><?=$arParams['EMAIL']?></a></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>    
</div>
