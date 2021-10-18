<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Слайдеры");
$APPLICATION->SetPageProperty("hide_section", "Y");

\Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>');
?>

<?php
$layoutHeader = new \Redsign\Megamart\Layouts\Parts\SectionHeaderBase();
$layout = new \Redsign\MegaMart\Layouts\Section();

$layout
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addModifier('inner-spacing');

?>

<?php $layout->start(); ?>
<p>Для создания слайдера добавьте аттрибут <code>data-slider="{ID слайдера}"</code></p>
<p>Аттрибут <code>data-slider-options</code> принимает json массив с параметрами слайдера. Список параметров доступен в <a href="https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html" target="__blank">документации библиотеки owl.carousel.js</a></p>
<pre><code class="php" style="tab-size: 4;">&lt;?php
$arSliderOptions = [
	'items' => 2, // Количество отображаемых элементов
];
?&gt;
&lt;div data-slider=&quot;images&quot; data-slider-options='&lt;?=\Bitrix\Main\Web\Json::encode($arSliderOptions)?&gt;'&gt;
	&lt;img src=&quot;img_1.png&quot; alt=&quot;img 1&quot;&gt;
	&lt;img src=&quot;img_2.png&quot; alt=&quot;img 2&quot;&gt;
	&lt;img src=&quot;img_3.png&quot; alt=&quot;img 3&quot;&gt;
	&lt;img src=&quot;img_4.png&quot; alt=&quot;img 4&quot;&gt;
&lt;/div&gt;
	
</code>
</pre>

<p>Для размещения элементов навигации слайдера разместите на странице следующий код</p>
<pre><code>&lt;!-- Кнопки навигации --&gt;
&lt;div data-slider-nav=&quot;{ID слайдера}&quot; class=&quot;slider-nav&quot; id=&quot;slider-nav-cards&quot;&gt;&lt;/div&gt;
&lt;!-- Навигационные точки навигации --&gt;
&lt;div data-slider-dots=&quot;{ID слайдера}&quot; class=&quot;slider-dots&quot; id=&quot;slider-dots-cards&quot;&gt;&lt;/div&gt;
&lt;!-- Мобильная навигация --&gt;
&lt;div data-slider-nav-sm=&quot;{ID слайдера}&quot; class=&quot;slider-nav-sm&quot;&gt;&lt;/div&gt;
</code></pre>

Метод <code>Redsign\MegaMart\Layouts\Section:useSlider('{ID слайдера}')</code> автоматически размещает элементы навигации внутри обертки
<pre><code class="php" style="tab-size: 4;">&lt;?php
$layout->useSlider('cards');
?&gt;</code>
</pre>

<?php $layout->end(); ?>

<?php
$layoutHeader->addData('TITLE', 'Пример слайдера');

$layout
	->useHeader($layoutHeader) 
	->useSlider('cards');

$layout->start();

$arSliderOptions = [];
?>
<div data-slider="cards" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arSliderOptions)?>' class="row">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
	<img src="http://static.redsign.ru/demo-content/megamart/ui_pages/260x150.png">
</div>
<?php $layout->end(); ?>

<script>hljs.initHighlightingOnLoad();</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>