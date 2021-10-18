<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)  {
	die();
}

use \Bitrix\Main\Localization\Loc;
?>
<script id="simple-basket-item-template" type="text/html">
	<tr class="simple-basket-item" id="simple-basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">

		{{#SHOW_RESTORE}}
		<td class="simple-basket-item__cell" colspan="7">
			<div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="simple-basket-item-height-aligner-{{ID}}">
				<div class="text-center">
					<?=Loc::getMessage('SBB_GOOD_CAP')?> <strong>{{NAME}}</strong> <?=Loc::getMessage('SBB_BASKET_ITEM_DELETED')?>.
					<div class="d-block">
						<a href="javascript:void(0)" data-entity="basket-item-restore-button">
							<?=Loc::getMessage('SBB_BASKET_ITEM_RESTORE')?>
						</a>
					</div>
				</div>
			</div>
			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>
		{{/SHOW_RESTORE}}

		{{^SHOW_RESTORE}}
		<td class="simple-basket-item__cell simple-basket-item__cell--picture">
			<div class="simple-basket-item__img-block">

				{{#DETAIL_PAGE_URL}}
					<a href="{{DETAIL_PAGE_URL}}" >
				{{/DETAIL_PAGE_URL}}

					<img class="simple-basket-item__img" alt="{{NAME}}"
						src="{{{IMAGE_URL}}}{{^IMAGE_URL}}<?=$templateFolder?>/images/no_photo.png{{/IMAGE_URL}}">
				{{#DETAIL_PAGE_URL}}
					</a>
				{{/DETAIL_PAGE_URL}}
			</div>
			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>

		<td class="simple-basket-item__cell simple-basket-item__cell--info">
			<h2 class="simple-basket-item__name">
				{{#DETAIL_PAGE_URL}}
					<a href="{{DETAIL_PAGE_URL}}" class="basket-item-info-name-link">
				{{/DETAIL_PAGE_URL}}
					<span data-entity="basket-item-name">{{NAME}}</span>
				{{#DETAIL_PAGE_URL}}
					</a>
				{{/DETAIL_PAGE_URL}}
			</h2>
			{{#NOT_AVAILABLE}}
				<div class="alert alert-warning text-center">
					<?=Loc::getMessage('SBB_BASKET_ITEM_NOT_AVAILABLE')?>.
				</div>
			{{/NOT_AVAILABLE}}
			{{#DELAYED}}
				<div class="alert alert-warning text-center">
					<?=Loc::getMessage('SBB_BASKET_ITEM_DELAYED')?>.
					<a href="javascript:void(0)" data-entity="basket-item-remove-delayed">
						<?=Loc::getMessage('SBB_BASKET_ITEM_REMOVE_DELAYED')?>
					</a>
				</div>
			{{/DELAYED}}
			{{#WARNINGS.length}}
				<div class="alert alert-warning alert-dismissable" data-entity="basket-item-warning-node">
					<span class="close" data-entity="basket-item-warning-close">&times;</span>
						{{#WARNINGS}}
							<div data-entity="basket-item-warning-text">{{{.}}}</div>
						{{/WARNINGS}}
				</div>
			{{/WARNINGS.length}}

			<div class="simple-basket-items__props">
				
				<?
				if (in_array('PROPS', $arParams['COLUMNS_LIST']))
				{
					?>
					{{#PROPS}}
					<div class="simple-basket-item-prop">
						<div class="simple-basket-item-prop__name">{{NAME}}: </div>
						<div class="simple-basket-item-prop__val">{{VALUE}}</div>
					</div>
					{{/PROPS}}
					<?
				}
				?>

				{{#SKU_BLOCK_LIST}}
				<div class="simple-basket-item-prop">
					<div class="simple-basket-item-prop__name">{{NAME}}: </div>
					<div class="simple-basket-item-prop__val">{{SELECT_VALUE}}</div>
				</div>
				{{/SKU_BLOCK_LIST}}
			</div>
			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>

		<td class="simple-basket-item__cell simple-basket-item__cell--quantity">

			<div class="simple-basket-item-amount{{#NOT_AVAILABLE}} disabled{{/NOT_AVAILABLE}}" data-entity="basket-item-quantity-block">
				<div class="simple-basket-item-amount__btn-minus" data-entity="basket-item-quantity-minus"></div>
				<div class="input-group d-inline-flex flex-nowrap w-auto">
					<input class="simple-basket-item-amount__field form-control" type="number" name="quantity" value="{{QUANTITY}}"
						{{#NOT_AVAILABLE}} disabled="disabled"{{/NOT_AVAILABLE}}
						data-value="{{QUANTITY}}" data-entity="basket-item-quantity-field"
						id="simple-basket-item-quantity-{{ID}}">
					<span class="simple-basket-item-amount__measure input-group-append">
						<span class="input-group-text">{{MEASURE_TEXT}}</span>
					</span>
				</div>
				<div class="simple-basket-item-amount__btn-plus" data-entity="basket-item-quantity-plus"></div>
			</div>

			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>

		<td class="simple-basket-item__cell simple-basket-item__cell--price text-nowrap">
			<div class="simple-basket-item__price-block">
				<div class="simple-basket-item__price" id="basket-item-price-{{ID}}">{{{FULL_PRICE_FORMATED}}}</div>
				<div class="simple-basket-item__price-desc"><?=Loc::getMessage('SBB_BASKET_ITEM_PRICE_FOR')?> {{MEASURE_RATIO}} {{MEASURE_TEXT}}</div>
			</div>

			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>


		<td class="simple-basket-item__cell simple-basket-item__cell--price text-nowrap">

			{{#SHOW_DISCOUNT_PRICE}}
				<div class="simple-basket-item__price-block">
					<div class="simple-basket-item__price">{{{DISCOUNT_PRICE_FORMATED}}}</div>
				</div>
			{{/SHOW_DISCOUNT_PRICE}}

			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>

		<td class="simple-basket-item__cell simple-basket-item__cell--price text-nowrap">

			<div class="simple-basket-item__price-block">
				<div class="simple-basket-item__price" id="simple-basket-item-sum-price-{{ID}}">{{{SUM_PRICE_FORMATED}}}</div>
			</div>
			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>

		<td class="simple-basket-item__cell simple-basket-item__cell--actions">
			<div class="simple-basket-item__actions">
				<svg class="icon-svg  trash-anim-icon" data-entity="basket-item-delete" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
					<path xmlns="http://www.w3.org/2000/svg" d="M29,13H25V12a3,3,0,0,0-3-3H18a3,3,0,0,0-3,3v1H11a1,1,0,0,0,0,2H29a1,1,0,0,0,0-2ZM17,13V12a1,1,0,0,1,1-1h4a1,1,0,0,1,1,1v1Z"/>
					<path xmlns="http://www.w3.org/2000/svg" d="M25,31H15a3,3,0,0,1-3-3V15a1,1,0,0,1,2,0V28a1,1,0,0,0,1,1H25a1,1,0,0,0,1-1V15a1,1,0,0,1,2,0V28A3,3,0,0,1,25,31Zm-6-6V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Zm4,0V19a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Z"/>
				</svg>
			</div>
			{{#SHOW_LOADING}}
				<div class="simple-basket-item__overlay"></div>
			{{/SHOW_LOADING}}
		</td>
		{{/SHOW_RESTORE}}
	</tr>
</script>
<?php
