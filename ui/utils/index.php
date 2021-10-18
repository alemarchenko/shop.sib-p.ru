<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Утилиты");
//$APPLICATION->SetPageProperty("hide_section", "Y");

\Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>');
?>

<h3>Обводки</h3>
<div class="d-block my-4">
	<div class="d-inline-block p-7 border bg-extra-gray mr-3"></div>
	<div class="d-inline-block p-7 border-top  bg-extra-gray mr-3"></div>
	<div class="d-inline-block p-7 border-bottom  bg-extra-gray mr-3"></div>
	<div class="d-inline-block p-7 border-left  bg-extra-gray mr-3"></div>
	<div class="d-inline-block p-7 border-right bg-extra-gray mr-3"></div>
</div>
<pre><code class="html" style="tab-size: 4;">&lt;div class=&quot;border&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;border-top&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;border-right&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;border-bottom&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;border-left&quot;&gt;&lt;/div&gt;
</code>
</pre>

<h3 class="mt-4">Загругление</h3>
<div class="d-block my-4">
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded"></div>
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded-top"></div>
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded-right"></div>
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded-bottom"></div>
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded-left"></div>
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded-circle"></div>
	<div class="d-inline-block p-7 bg-extra-gray border mr-3 rounded-0"></div>
</div>
<pre><code class="html" style="tab-size: 4;">&lt;div class=&quot;rounded&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;rounded-top&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;rounded-right&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;rounded-bottom&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;rounded-left&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;rounded-circle&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;rounded-0&quot;&gt;&lt;/div&gt;
</code>
</pre>

<h3 class="mt-4">Адаптивные видео</h3>
<p>Оберните вставленный объект, например <code>&lt;iframe&gt;</code>, родительским элементом класса <code>.embed-responsive</code> с заданным соотношением сторон экрана. </p>

<pre><code class="html" style="tab-size: 4;">&lt;!-- Соотношение сторон 21:9 --&gt;
&lt;div class=&quot;embed-responsive embed-responsive-21by9&quot;&gt;
  &lt;iframe class=&quot;embed-responsive-item&quot; src=&quot;...&quot;&gt;&lt;/iframe&gt;
&lt;/div&gt;

&lt;!-- Соотношение сторон 16:9 --&gt;
&lt;div class=&quot;embed-responsive embed-responsive-16by9&quot;&gt;
  &lt;iframe class=&quot;embed-responsive-item&quot; src=&quot;...&quot;&gt;&lt;/iframe&gt;
&lt;/div&gt;

&lt;!-- Соотношение сторон 4:3 --&gt;
&lt;div class=&quot;embed-responsive embed-responsive-4by3&quot;&gt;
  &lt;iframe class=&quot;embed-responsive-item&quot; src=&quot;...&quot;&gt;&lt;/iframe&gt;
&lt;/div&gt;

&lt;!-- Соотношение сторон 1:1 --&gt;
&lt;div class=&quot;embed-responsive embed-responsive-1by1&quot;&gt;
  &lt;iframe class=&quot;embed-responsive-item&quot; src=&quot;...&quot;&gt;&lt;/iframe&gt;
&lt;/div&gt;
</code></pre>

<h3 class="mt-4">Отступы</h3>
<p>Классы отступов названы по формату <code>{property}{sides}-{size}</code> для и <code>{property}{sides}-{breakpoint}-{size}</code> для sm, md, lg и xl.</p>

<p>Где <em>property</em> – это одно из:</p>
<ul>
  <li><code>m</code> - для классов, которые задают <code>margin</code></li>
  <li><code>p</code> - для классов, которые задают <code>padding</code></li>
</ul>
<p>Где <em>sides</em> – это одно из:</p>
<ul>
  <li><code>t</code> - для классов, которые задают <code>margin-top</code> или <code>padding-top</code></li>
  <li><code>b</code> - для классов, которые задают <code>margin-bottom</code> или <code>padding-bottom</code></li>
  <li><code>l</code> - для классов, которые задают <code>margin-left</code> или <code>padding-left</code></li>
  <li><code>r</code> - для классов, которые задают <code>margin-right</code> или <code>padding-right</code></li>
  <li><code>x</code> - для классов, которые задают и <code>*-left</code> и <code>*-right</code></li>
  <li><code>y</code> - для классов, которые задают и <code>*-top</code> и <code>*-bottom</code></li>
  <li>blank - для классов, которые задают <code>margin</code> или <code>padding</code> для всех 4-х сторон элемента</li>
</ul>
<p>Где <em>размер</em> – это один из:</p>
<ul>
  <li><code>0</code> - для классов, которые удаляют <code>margin</code> или <code>padding</code> назначая его равны <code>0</code></li>
  <li><code>1</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>1px</code></li>
  <li><code>2</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>0.25rem</code></li>
  <li><code>3</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>0.5rem</code></li>
  <li><code>4</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>1rem</code></li>
  <li><code>5</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>1.5rem</code></li>
  <li><code>6</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>2rem</code></li>
  <li><code>7</code> - для классов, которые  устанавливают <code>margin</code> или <code>padding</code> к значению, равному <code>3rem</code></li>
  <li><code>auto</code> - для классов, которые  устанавливают <code>margin</code> как auto</li>
</ul>

<p> Примеры использования</p>

<pre><code class="html" style="tab-size: 4;">&lt;div class=&quot;m-4&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;my-2&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;mx-5&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;p-4&quot;&gt;&lt;/div&gt;
&lt;div class=&quot;pt-5&quot;&gt;&lt;/div&gt;</code></pre>

<script>hljs.initHighlightingOnLoad();</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>