<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обертка");
//$APPLICATION->SetPageProperty("hide_section", "Y");

\Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>');
?>

<p>Для создания обертки используйте следующий код</p>
<pre><code class="html" style="tab-size: 4;">&lt;section class=&quot;l-section l-section--inner-spacing&quot;&gt;
	&lt;div class=&quot;l-section__container&quot;&gt;
		&lt;div class=&quot;l-section__wrap-main&quot;&gt;
			&lt;div class=&quot;l-section__head&quot; data-entity=&quot;header&quot;&gt;
				&lt;div class=&quot;section-head&quot;&gt;
					&lt;div class=&quot;section-head__title&quot;&gt;
						&lt;h2 class=&quot;h4&quot;&gt;Заголовок&lt;/h2&gt;
					&lt;/div&gt;
					&lt;div class=&quot;section-head__controls&quot;&gt;&lt;/div&gt;
				&lt;/div&gt;
			&lt;/div&gt;
			&lt;div class=&quot;l-section__main&quot;&gt;
				Содержимое
			&lt;/div&gt;
		&lt;/div&gt;
	&lt;/div&gt;
&lt;/section&gt;
</code>
</pre>

<p>Либо с помощью php класса</p>
<pre><code class="php" style="tab-size: 4;">&lt;?php
$layout = new \Redsign\MegaMart\Layouts\Section(); // Создаем объект обертки

$layoutHeader = new \Redsign\Megamart\Layouts\Parts\SectionHeaderBase(); // Создаем объект шапки обертки
$layoutHeader->addData('TITLE', 'Заголовок'); // Устанавливаем заголовок

$layout
	->useHeader($layoutHeader) // Указываем используемую шапку
	->addModifier('bg-white') // Добавляем белый фон
	->addModifier('outer-spacing') // Внешний отступ
	->addModifier('inner-spacing'); // Внутренний отступ
	
$layout->start();
	// Содержимое
$layout->end();
unset($layout);
?&gt;</code>
</pre>

<a data-toggle="collapse" href="#sectionExample" class="btn btn-link my-4">Показать пример</a>

<div id="sectionExample" class="collapse">
	<section class="l-section l-section--inner-spacing">
		<div class="l-section__container">
			<div class="l-section__wrap-main">
				<div class="l-section__head" data-entity="header">
					<div class="section-head">
						<div class="section-head__title">
							<h2 class="h4">Заголовок</h2>
						</div>
						<div class="section-head__controls"></div>
					</div>
				</div>
				<div class="l-section__main">
					Содержимое
				</div>
			</div>
		</div>
	</section>
</div>

<p class="mt-4">Для изменения вида обертки предусмотренны следующие модификаторы</p>
<ul>
	<li><code>inner-spacing</code> - добавить внутренний отступ</li>
	<li><code>outer-spacing</code> - добавить внешний отступ</li>
	<li><code>bg-white</code> - добавить белый фон</li>
	<li><code>bg-lg</code> - добавить градиентный фон</li>
	<li><code>shadow</code> - добавить тень</li>
</ul>
<script>hljs.initHighlightingOnLoad();</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>