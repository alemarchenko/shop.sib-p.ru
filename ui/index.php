<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("hide_section", "Y");
$APPLICATION->SetPageProperty("hide_inner_sidebar", "Y");
$APPLICATION->SetPageProperty("hide_outer_sidebar", "Y");
$APPLICATION->SetTitle("Возможности");
?>


<section class="l-section l-section--outer-spacing"><div class="l-section__container"><div class="l-section__wrap-main"><div class="l-section__main">
<div class="opportunity-list">
	<div class="opportunity-list__item opportunity-item opportunity-item--light-blue">
		<a class="opportunity-item__link" href="base/">
			<h2 class="opportunity-item__name">Базовые элементы дизайна</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--teal">
		<a class="opportunity-item__link" href="wrapper/">
			<h2 class="opportunity-item__name">Обертка</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--red">
		<a class="opportunity-item__link" href="forms/">
			<h2 class="opportunity-item__name">Формы</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--amber">
		<a class="opportunity-item__link" href="sliders/">
			<h2 class="opportunity-item__name">Cлайдеры</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--blue">
		<a class="opportunity-item__link" href="popup/">
			<h2 class="opportunity-item__name">Всплывающие окна</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--indigo">
		<a class="opportunity-item__link" href="snippets/">
			<h2 class="opportunity-item__name">Готовые сниппеты</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--green">
		<a class="opportunity-item__link" href="components/">
			<h2 class="opportunity-item__name">Компоненты</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--pink">
		<a class="opportunity-item__link" href="utils/">
			<h2 class="opportunity-item__name">Утилиты</h2>
		</a>
	</div>
	<div class="opportunity-list__item opportunity-item opportunity-item--cyan">
		<a class="opportunity-item__link" href="icons/">
			<h2 class="opportunity-item__name">Иконки</h2>
		</a>
	</div>
</div>
</div></div></div></section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>