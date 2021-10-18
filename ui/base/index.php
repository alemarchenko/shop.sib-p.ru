<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("hide_section", "Y");
$APPLICATION->SetTitle("Базовые Элементы дизайна");
?>

<?php
$layoutHeader = new \Redsign\Megamart\Layouts\Parts\SectionHeaderBase();
$layout = new \Redsign\MegaMart\Layouts\Section();

$layout
	->useHeader($layoutHeader)
	->addModifier('bg-white')
	->addModifier('shadow')
	->addModifier('outer-spacing')
	->addModifier('inner-spacing');

?>

<?php
$layoutHeader->addData('TITLE', 'Типографика');
$layout->start();
?>
<div class="row">
	<div class="col col-md-6">
		<h2>Загололовк h2</h2>
		<p>
		Конфликт, следовательно, раскладывает на элементы данный катарсис. Гегельянство создает естественный закон исключённого третьего, учитывая опасность, которую представляли собой писания Дюринга для не окрепшего еще немецкого рабочего движения. Искусство трогательно наивно.
		</p>
		<p>
		Согласно мнению известных философов, антропосоциология естественно выводит интеллигибельный конфликт. Современная критика раскладывает на элементы субъективный дуализм. Искусство амбивалентно. Освобождение трогательно наивно. Сомнение нетривиально.
		</p>
		<h3>Загололовк h3</h3>
		<p>
		Представляется логичным, что врожденная интуиция контролирует структурализм. Культ джайнизма включает в себя поклонение Махавире и другим тиртханкарам, поэтому боль категорически дискредитирует субъективный смысл жизни, хотя в официозе принято обратное. Наряду с этим аподейктика выводит интеллект.
		</p>
		<h4>Загололовк h4</h5>
		<p>
		Закон исключённого третьего, как следует из вышесказанного, нетривиален. Эсхатологическая идея, конечно, выводит естественный здравый смысл. Ощущение мира, следовательно, нетривиально. Закон исключённого третьего подрывает позитивизм.
		</p>
		<h5>Загололовк h5</h5>
		<p>
		Отсюда естественно следует, что боль категорически раскладывает на элементы принцип восприятия. Бхутавада выводит непредвиденный принцип восприятия. Созерцание амбивалентно рефлектирует из ряда вон выходящий закон внешнего мира.
		</p>
		<h6>Загололовк h6</h6>
		<p>
		Отсюда естественно следует, что боль категорически раскладывает на элементы принцип восприятия. Бхутавада выводит непредвиденный принцип восприятия. Созерцание амбивалентно рефлектирует из ряда вон выходящий закон внешнего мира.
		</p>
	</div>
	<div class="col col-md-6">
		<h3>Нумерованный список</h3>
		<ol>
			<li>Заблуждение, следовательно, принимает во внимание гравитационный парадокс. </li>
			<li>Гегельянство, следовательно, трогательно наивно.
				<ul>
					<li>Предмет деятельности непредвзято заполняет структурализм. </li>
				</ul>
			</li>
			<li>Исчисление предикатов, конечно, реально подчеркивает катарсис.</li>
		</ol>
		<br>
		<h3>Маркированный список</h3>
		<ul>
			<li>Закон внешнего мира представляет собой сложный закон исключённого третьего.  </li>
			<li>Абстракция нетривиальна. Ощущение мира индуктивно индуцирует гравитационный парадокс.
				<ul>
					<li>Предмет деятельности непредвзято заполняет структурализм. </li>
				</ul>
			</li>
			<li>  Гегельянство раскладывает на элементы напряженный интеллект</li>
		</ul>
		
		<h3>Цитата</h3>
		<blockquote class="blockquote">
			<p class="mb-0">Электрон, при адиабатическом изменении параметров, облучает лептон. Гетерогенная структура доступна. Излучение расщепляет газ. Фонон восстанавливает наносекундный солитон.</p>
			<footer class="blockquote-footer">Альберт Эйнштейн</footer>
		</blockquote>
		
		<h3>Цвет текста </h3>
		<p>
			<span class="text-primary">.text-primary</span><br>
			<span class="text-secondary">.text-secondary</span><br>
			<span class="text-success">.text-success</span><br>
			<span class="text-danger">.text-danger</span><br>
			<span class="text-warning">.text-warning</span><br>
			<span class="text-info">.text-info</span><br>
			<span class="text-light bg-dark">.text-light</span><br>
			<span class="text-dark">.text-dark</span><br>
			<span class="text-white bg-dark">.text-white</span><br>
			<span class="text-extra">.text-extra</span><br>
			<span class="text-body">.text-body</span><br>
		</p>
	</div>
	<div class="col-12">
		<h3>Цвет фона</h3>
		<div class="row">
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-primary text-white">.bg-primary</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-secondary text-white">.bg-secondary</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-success text-white">.bg-success</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-danger text-white">.bg-danger</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-warning text-dark">.bg-warning</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-info text-white">.bg-info</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-light text-dark">.bg-light</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-dark text-white">.bg-dark</div>
			</div>
			<div class="col-md-4">
				<div class="p-3 mb-2 bg-white text-dark">.bg-white</div>
			</div>
		</div>
	</div>
</div>
<?php $layout->end(); ?>


<?php
$layoutHeader->addData('TITLE', 'Таблицы');
$layout->start();
?>
<div class="row">
	<div class="col-md-12">
		<p>Добавьте базовый класс <code>table</code> к тегу <code>&lt;table&gt;</code>, чтобы получить оформленный вид таблицы</p>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">First</th>
					<th scope="col">Last</th>
					<th scope="col">Handle</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">1</th>
					<td>Mark</td>
					<td>Otto</td>
					<td>@mdo</td>
				</tr>
				<tr>
				<th scope="row">2</th>
					<td>Jacob</td>
					<td>Thornton</td>
					<td>@fat</td>
				</tr>
				<tr>
				<th scope="row">3</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<p>Вы можете инвертировать цвета со светлых на темные классом <code>table-dark</code>.</p>
		<table class="table table-dark">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">First</th>
					<th scope="col">Last</th>
					<th scope="col">Handle</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">1</th>
					<td>Mark</td>
					<td>Otto</td>
					<td>@mdo</td>
				</tr>
				<tr>
				<th scope="row">2</th>
					<td>Jacob</td>
					<td>Thornton</td>
					<td>@fat</td>
				</tr>
				<tr>
				<th scope="row">3</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<p>Добавьте класс <code>table-striped</code> для разделения «полосками зебры» рядов таблицы.</p>
		<table class="table table-stripped">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">First</th>
					<th scope="col">Last</th>
					<th scope="col">Handle</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">1</th>
					<td>Mark</td>
					<td>Otto</td>
					<td>@mdo</td>
				</tr>
				<tr>
				<th scope="row">2</th>
					<td>Jacob</td>
					<td>Thornton</td>
					<td>@fat</td>
				</tr>
				<tr>
				<th scope="row">3</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<p>Добавьте класс <code>table-bordered</code> в для создания границ ячеек и таблицы со всех сторон.</p>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">First</th>
					<th scope="col">Last</th>
					<th scope="col">Handle</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">1</th>
					<td>Mark</td>
					<td>Otto</td>
					<td>@mdo</td>
				</tr>
				<tr>
				<th scope="row">2</th>
					<td>Jacob</td>
					<td>Thornton</td>
					<td>@fat</td>
				</tr>
				<tr>
				<th scope="row">3</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="col-md-6">
		<p>Добавьте в таблицу класс <code>table-borderless</code> для создания таблицы без границ.</p>
		<table class="table table-borderless">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">First</th>
					<th scope="col">Last</th>
					<th scope="col">Handle</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">1</th>
					<td>Mark</td>
					<td>Otto</td>
					<td>@mdo</td>
				</tr>
				<tr>
				<th scope="row">2</th>
					<td>Jacob</td>
					<td>Thornton</td>
					<td>@fat</td>
				</tr>
				<tr>
				<th scope="row">3</th>
					<td>Larry</td>
					<td>the Bird</td>
					<td>@twitter</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="col-12">
		<p>Добавьте родительский элемент с классом <code>table-responsive</code> для горизонтально прокручиваемых таблиц.</p>
		<div class=" table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">First</th>
						<th scope="col">Last</th>
						<th scope="col">Handle</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">1</th>
						<td>Mark</td>
						<td>Otto</td>
						<td>@mdo</td>
					</tr>
					<tr>
					<th scope="row">2</th>
						<td>Jacob</td>
						<td>Thornton</td>
						<td>@fat</td>
					</tr>
					<tr>
					<th scope="row">3</th>
						<td>Larry</td>
						<td>the Bird</td>
						<td>@twitter</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php $layout->end(); ?>


<?php
$layoutHeader->addData('TITLE', 'Кнопки');
$layout->start();
?>
<div class="row">
	<div class="col-12">
		<h4>Основные кнопки</h4>
		<div class="mb-2">
			<button class="btn btn-primary">Primary</button>
			<button class="btn btn-secondary">Secondary</button>
			<button class="btn btn-success">Success</button>
			<button class="btn btn-danger">Danger</button>
			<button class="btn btn-warning">Warning</button>
			<button class="btn btn-info">Info</button>
			<button class="btn btn-light">Light</button>
			<button class="btn btn-dark">Dark</button>
			<button class="btn btn-extra">Extra</button>
			<button class="btn btn-link">Link</button>
			<button class="btn">Default</button>
		</div>
	</div>
	<div class="col-12">
		<h4 class="mt-5">Контурные кнопки</h4>
		<div class="mb-2">
			<button class="btn btn-outline-primary">Primary</button>
			<button class="btn btn-outline-secondary">Secondary</button>
			<button class="btn btn-outline-success">Success</button>
			<button class="btn btn-outline-danger">Danger</button>
			<button class="btn btn-outline-warning">Warning</button>
			<button class="btn btn-outline-info">Info</button>
			<button class="btn btn-outline-light">Light</button>
			<button class="btn btn-outline-dark">Dark</button>
			<button class="btn btn-outline-extra">Extra</button>
			<button class="btn btn-outline-link">Link</button>
		</div>
	</div>
	
	<div class="col-12">
		<h4 class="mt-5">Размеры</h4>
		<button type="button" class="btn btn-primary btn-lg">Large button</button>
		<button type="button" class="btn btn-primary">Normal button</button>
		<button type="button" class="btn btn-primary btn-sm">Small button</button>
	</div>
</div>
<?php $layout->end(); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>