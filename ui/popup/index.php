<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Всплывающие окна");
//$APPLICATION->SetPageProperty("hide_section", "Y");

\Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>');
?>

<p>Чтобы открыть ссылку в всплывающем окне добавьте к ссылке атрибут <code>data-type="ajax" </code></p>
<pre><code class="html" style="tab-size: 4;">&lt;a href=&quot;/forms/recall&quot; data-type=&quot;ajax&quot;&gt;Задать вопрос&lt;/a&gt;</code></pre>
<p><a href="<?=SITE_DIR?>forms/recall/" data-type="ajax">Задать вопрос</a></p>

<p>Параметр <code>data-popup-type</code> позволяет указать тип всплывающего окна. Если тип не указан, всплывающее окно будет открываться в соответсвии настройкам в виджете.</p>
<p> Доступны следующие значения параметра data-popup-type</p>
<ul>
	<li><code>side</code> - в боковой панели</li>
	<li><code>window</code> - в отдельном окне</li>
</ul>

<p>Атрибут <code>title</code> позволяет установить заголовок окна</p>

<pre><code class="html" style="tab-size: 4;">&lt;a href=&quot;/forms/recall/&quot; data-type=&quot;ajax&quot; data-popup-type=&quot;window&quot; title=&quot;Задать вопрос&quot;&gt;Задать вопрос(в отдельном окне)&lt;/a&gt;
&lt;a href=&quot;/forms/recall/&quot; data-type=&quot;ajax&quot; data-popup-type=&quot;side&quot; title=&quot;Задать вопрос&quot;&gt;Задать вопрос (в боковой панели)&lt;/a&gt;
</code></pre>
<p><a href="<?=SITE_DIR?>forms/recall/" data-type="ajax" data-popup-type="window" title="Задать вопрос">Задать вопрос(в отдельном окне)</a><br>
<a href="<?=SITE_DIR?>forms/recall/" data-type="ajax" data-popup-type="side" title="Задать вопрос">Задать вопрос (в боковой панели)</a></p>

<script>hljs.initHighlightingOnLoad();</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>