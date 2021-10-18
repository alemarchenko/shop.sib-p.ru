<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
if(strlen($arResult["NAV_STRING"]) > 0)
{
	?>
	<div class="row mb-3">
		<div class="col"><?=$arResult["NAV_STRING"]?></div>
	</div>
	<?
}

if (count($arResult["PROFILES"]))
{
	?>
	<div class="row mb-3">
		<div class="col">
			<div class="table-responsive">
				<table class="table sale-personal-profile-table">
					<thead>
						<tr>
							<?
							$dataColumns = array(
								"ID", "DATE_UPDATE", "NAME", "PERSON_TYPE_ID"
							);
							foreach ($dataColumns as $column)
							{
								?>
								<th scope="col">
									<?=Loc::getMessage("P_".$column)?>
									<a class="sale-personal-profile-list-arrow-up" href="<?=$arResult['URL']?>by=<?=$column?>&order=asc#nav_start"><i class="fa fa-chevron-up"></i></a>
									<a class="sale-personal-profile-list-arrow-down" href="<?=$arResult['URL']?>by=<?=$column?>&order=desc#nav_start"><i class="fa fa-chevron-down"></i></a>
								</th>
								<?
							}
							?>
							<th><?=Loc::getMessage("SALE_ACTION")?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?foreach($arResult["PROFILES"] as $val)
					{
						?>
						<tr>
							<td scope="row" class="align-middle"><a title="<?= Loc::getMessage("SALE_DETAIL_DESCR") ?>" href="<?= $val["URL_TO_DETAIL"] ?>"><?= $val["ID"] ?></a></td>
							<td class="align-middle"><?= $val["DATE_UPDATE"] ?></td>
							<td class="align-middle"><?= $val["NAME"] ?></td>
							<td class="align-middle"><?= $val["PERSON_TYPE"]["NAME"] ?></td>
							<td class="align-middle">
								<a title="<?= Loc::getMessage("SALE_DETAIL_DESCR") ?>" href="<?= $val["URL_TO_DETAIL"] ?>"><?= GetMessage("SALE_DETAIL") ?></a>
							</td>
							<td  class="text-right align-middle">
								<a class="sale-personal-profile-list-close-button text-secondary" title="<?= Loc::getMessage("SALE_DELETE_DESCR") ?>"
									href="javascript:if(confirm('<?= Loc::getMessage("STPPL_DELETE_CONFIRM") ?>')) window.location='<?= $val["URL_TO_DETELE"] ?>'">
									<svg class="icon-svg basket-item-actions-remove trash-anim-icon" data-entity="basket-item-delete" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
										<path xmlns="http://www.w3.org/2000/svg" d="M29,13H25V12a3,3,0,0,0-3-3H18a3,3,0,0,0-3,3v1H11a1,1,0,0,0,0,2H29a1,1,0,0,0,0-2ZM17,13V12a1,1,0,0,1,1-1h4a1,1,0,0,1,1,1v1Z"></path>
										<path xmlns="http://www.w3.org/2000/svg" d="M25,31H15a3,3,0,0,1-3-3V15a1,1,0,0,1,2,0V28a1,1,0,0,0,1,1H25a1,1,0,0,0,1-1V15a1,1,0,0,1,2,0V28A3,3,0,0,1,25,31Zm-6-6V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Zm4,0V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Z"></path>
									</svg>
								</a>
							</td>
						</tr>
						<?
					}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?
	if(strlen($arResult["NAV_STRING"]) > 0)
	{
		?>
		<div class="row">
			<div class="col"><?=$arResult["NAV_STRING"]?></div>
		</div>
		<?
	}
}
else
{
	?>
	<h3><?=Loc::getMessage("STPPL_EMPTY_PROFILE_LIST") ?></h3>
	<?
}
?>
