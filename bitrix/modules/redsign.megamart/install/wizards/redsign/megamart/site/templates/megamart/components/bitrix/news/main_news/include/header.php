<div class="b-article-detail__head">
	<div class="b-article-detail__breadcrumb">
		<?$APPLICATION->IncludeComponent(
			"bitrix:breadcrumb",
			"standart",
			array(
				"START_FROM" => "0",
				"PATH" => "",
				"SITE_ID" => SITE_ID
			)
		);?>
	</div>
	<?php $APPLICATION->ShowViewContent('rs_megamart_article_head'); // news.detail ?>
</div>
