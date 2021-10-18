<?php

if (!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

$areaId = $arParams['TARGET_ID'].'_sorter';
    
$itemIds = array(
    'ID' => $areaId,
);
$obName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);
?>
<nav class="d-flex align-items-center" id="<?=$itemIds['ID']?>">
    <?php
    $this->SetViewTarget($itemIds['ID']);

    $frame = $this->createFrame($itemIds['ID'], false)->begin();
    $frame->setBrowserStorage(true);
    ?>

	<?php
    if (
        $arParams['ALFA_SORT_BY_SHOW'] == 'Y'
        && is_array($arResult['CSORTING']) && count($arResult['CSORTING']) > 1
    )
	{
		?>
		<form class="d-sm-none" name="" action="" method="POST">
			<input type="hidden" name="<?=$arParams['ALFA_ACTION_PARAM_NAME']?>" value="<?=$arParams['ACTION_CHANGE_SORT']?>">

			<select class="custom-select" name="<?=$arParams['ALFA_ACTION_PARAM_VALUE']?>">
				<?php
				foreach ($arResult['CSORTING'] as $key => $sort)
				{
					if(ToUpper($sort['VALUE']) == 'SORT_DESC')
					{
						continue;
					}

					$sortName = '';
					if (strpos(ToUpper($sort['VALUE']), 'PRICE') === false)
					{
						$sortName = ToUpper($sort['VALUE']);
					}
					elseif ($sort['DIRECTION'] == 'asc')
					{
						$sortName = 'PRICE_ASC';
					}
					else
					{
						$sortName = 'PRICE_DESC';
					}
					?>
					<option value="<?=$sort['VALUE']?>"<?=($arResult['USING']['CSORTING']['ARRAY']['VALUE'] == $sort['VALUE'] ? ' selected' : '')?>>
						<?=Loc::getMessage('RS_MM_RCR_CATALOG_SORT_VARIABLE_'.$sortName)?>
					</option>
					<?php
				}
				unset($key, $sort);
				?>
			</select>
		</form>

		<div class="d-none d-sm-block mr-3">
			<?php
			$usingSortName = '';
			if (strpos(ToUpper($arResult['USING']['CSORTING']['ARRAY']['VALUE']), 'PRICE') === false)
			{
				$usingSortName = ToUpper($arResult['USING']['CSORTING']['ARRAY']['VALUE']);
			}
			elseif ($arResult['USING']['CSORTING']['ARRAY']['DIRECTION'] == 'asc')
			{
				$usingSortName = 'PRICE_ASC';
			}
			else
			{
				$usingSortName = 'PRICE_DESC';
			}
			$dropdownId = $this->getEditAreaId('sortby');
			?>
			<div class="dropdown">
				<div class="btn btn-sm btn-link-extra-primary decoration-none dropdown-toggle" id="<?=$dropdownId?>" data-toggle="dropdown" aria-expanded="true">
					<?=Loc::getMessage('RS_MM_RCR_CATALOG_SORT_VARIABLE_'.$usingSortName)?>
				</div>
				<nav class="dropdown-menu" role="menu" aria-labelledby="<?=$dropdownId?>">
					<?php
					foreach ($arResult['CSORTING'] as $key => $sort)
					{
						if (ToUpper($sort['VALUE']) == 'SORT_DESC')
						{
							continue;
						}

						$sortName = '';
						if (strpos(ToUpper($sort['VALUE']), 'PRICE') === false)
						{
							$sortName = ToUpper($sort['VALUE']);
						}
						elseif ($sort['DIRECTION'] == 'asc')
						{
							$sortName = 'PRICE_ASC';
						}
						else
						{
							$sortName = 'PRICE_DESC';
						}
						?>
						<a class="dropdown-item" href="<?=$sort['URL']?>" rel="nofollow">
							<?=Loc::getMessage('RS_MM_RCR_CATALOG_SORT_VARIABLE_'.$sortName)?>
							<?/*<svg class="icon-svg"><use xlink:href="#svg-chevron-<?=($sort['DIRECTION'] == 'asc' ? 'up' : 'down')?>"></use></svg>*/?>
						</a>
						<?php
					}
					unset($key, $sort);
					?>
				</nav>
			</div>
		</div>
<?
/*
		<div class="d-none d-sm-block mr-3">
			<?php
			$arrUsed = array();
			$arrUsed[] = $arResult['USING']['CSORTING']['ARRAY']['GROUP'];
			
			foreach ($arResult['CSORTING'] as $sort)
			{
				if ($sort['GROUP'] != '')
				{
					$sortName = '';
					if (strpos(ToUpper($sort['VALUE']), 'PRICE') === false)
					{
						$sortName = ToUpper($sort['VALUE']);
					}
					elseif ($sort['DIRECTION'] == 'asc')
					{
						$sortName = 'PRICE_ASC';
					}
					else
					{
						$sortName = 'PRICE_DESC';
					}

					if (!in_array($sort['GROUP'], $arrUsed))
					{
						$arrUsed[] = $sort['GROUP'];
						?>
						<a class="btn btn-sm text-extra" href="<?=$sort['URL']?>">
							<?=Loc::getMessage('RS_MM_RCR_CATALOG_SORT_VARIABLE_'.$sortName)?>
							<svg class="icon-svg"><use xlink:href="#svg-chevron-<?=($sort['DIRECTION'] == 'asc' ? 'up' : 'down')?>"></use></svg>
						</a>
						<?php
					}
					elseif ($arResult['USING']['CSORTING']['ARRAY']['VALUE'] == $sort['VALUE'])
					{
						?>
						<a class="btn btn-sm text-primary" href="<?=$sort['URL2']?>">
							<?=Loc::getMessage('RS_MM_RCR_CATALOG_SORT_VARIABLE_'.$sortName)?>
							<svg class="icon-svg"><use xlink:href="#svg-chevron-<?=($sort['DIRECTION'] == 'asc' ? 'up' : 'down')?>"></use></svg>
						</a>
						<?php
					}
				}
			}
			unset($sort);
			?>
		</div>
		<?php
*/
	}
	?>
	

	<?php
    if (
        $arParams['ALFA_OUTPUT_OF_SHOW'] == 'Y'
        && is_array($arResult['COUTPUT']) && count($arResult['COUTPUT']) > 1
    ):
        $dropdownId = $this->getEditAreaId('output');
    ?>
        <div class="d-none d-sm-block">
			<div class="dropdown d-inline-block">
				<div class="btn btn-sm btn-link-extra-primary decoration-none dropdown-toggle" id="<?=$dropdownId?>" data-toggle="dropdown" aria-expanded="true">
					<?=Loc::getMessage('RS_MM_RCR_CATALOG_OUTPUT_TITLE').' '.$arResult['USING']['COUTPUT']['ARRAY']['VALUE']?>
				</div>
				<nav class="dropdown-menu" role="menu" aria-labelledby="<?=$dropdownId?>">
					<?php
					foreach ($arResult['COUTPUT'] as $output)
					{
						?>
						<a class="dropdown-item" href="<?=$output['URL']?>" rel="nofollow">
							<?=$output['VALUE']?>
						</a>
						<?php
					}
					unset($output);
					?>
				</nav>
			</div>
		</div>

		<form class="d-none" name="" action="" method="POST">
			<input type="hidden" name="<?=$arParams['ALFA_ACTION_PARAM_NAME']?>" value="<?=$arParams['ACTION_CHANGE_SORT']?>">
			<select class="custom-select" name="<?=$arParams['ALFA_ACTION_PARAM_VALUE']?>">
				<?php
				foreach ($arResult['COUTPUT'] as $output)
				{
					?>
					<option class="dropdown-item" href="<?=$output['URL']?>" rel="nofollow">
						<?=$output['VALUE']?>
					</option>
					<?php
				}
				unset($output);
				?>
			</select>
		</form>
    <?php endif; ?>


	<?php
    if (
        $arParams['ALFA_CHOSE_TEMPLATES_SHOW'] == 'Y'
        && is_array($arResult['CTEMPLATE']) && count($arResult['CTEMPLATE']) > 1
    ):
    ?>
        <div class="ml-auto">
			<?php
			foreach ($arResult['CTEMPLATE'] as $key => $template)
			{
				$itemClass = 'c-icon';
				if ($template['USING'] == 'Y')
				{
					$itemClass .= ' active';
				}

				if (
					$key != $arResult['USING']['CTEMPLATE']['KEY'] + 1 && $arResult['USING']['CTEMPLATE']['KEY'] + 1 != count($arResult['CTEMPLATE'])
					|| $key != 0 && $arResult['USING']['CTEMPLATE']['KEY'] + 1 == count($arResult['CTEMPLATE'])
				)
				{
					$itemClass .= ' d-none d-lg-inline-flex';
				}
				?>
				<a class="<?=$itemClass?>" href="<?=$template['URL']?>" title="<?=($template['NAME_LANG'] !=  '' ? $template['NAME_LANG'] : $template['VALUE'])?>" rel="nofollow">
					<svg class="icon icon-svg"><use xlink:href="#svg-<?=$template['VALUE']?>"></use></svg>
				</a>
				<?php
			}
			unset($key, $template);
			?>
        </div>
    <?php endif; ?>
    
	<?php if ($request->isAjaxRequest()): ?>
		<script>$('#<?=$itemIds['ID']?>').find('[data-toggle="dropdown"]').dropdown();</script>
	<?php endif; ?>
	
	<?php
    $frame->end();
    $this->EndViewTarget();
    
    echo $APPLICATION->GetViewContent($itemIds['ID']);
    ?>
</nav>
<?php
$jsParams = array(

    'VISUAL' => array(
        'ID' => $itemIds['ID'],
        'TARGET_ID' => $arParams['TARGET_ID'],
    ),
    
);
?>
<script>
  var <?=$obName?> = new RSCatalogSorter(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>

