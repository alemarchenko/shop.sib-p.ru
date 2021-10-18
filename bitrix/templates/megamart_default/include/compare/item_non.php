<?
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>

<section class="l-section l-section--bg-white l-section--shadow l-section--outer-spacing">
	<div class="l-section__container">
		<div class="l-section__wrap-main">
			<div class="l-section__main">
				<div class="compare-non">
					<svg ><use xlink:href="#svg-copy"></use></svg>
					<h2><?=Loc::getMessage('RS_INCLUDE_COMPARE_NON')?></h2>
					<p><?=Loc::getMessage('RS_INCLUDE_COMPARE_NON_TEXT')?></p>
					<a href="/catalog/" class="btn btn-primary"><?=Loc::getMessage('RS_LINK_CATALOG')?></a>
					
				</div>
			</div>
		</div>	
	</div>
</section>










