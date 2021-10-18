<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Компоненты");
//$APPLICATION->SetPageProperty("hide_section", "Y");

\Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>');
?>
<div class="d-block">
	<h3>Навигация</h3>
	<ul class="nav nav-slide">
		<li class="nav-item">
			<a class="nav-link active" href="#"><span>Active</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#"><span>Link</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#"><span>Link</span></a>
		</li>
	</ul>
	<a data-toggle="collapse" href="#navCodeExample" class="btn btn-link my-4">Показать код</a>
	
	<div id="navCodeExample" class="collapse">
		<pre><code class="html" style="tab-size: 4;">&lt;ul class=&quot;nav nav-slide&quot;&gt;
	&lt;li class=&quot;nav-item&quot;&gt;
		&lt;a class=&quot;nav-link active&quot; href=&quot;#&quot;&gt;&lt;span&gt;Active&lt;/span&gt;&lt;/a&gt;
	&lt;/li&gt;
	&lt;li class=&quot;nav-item&quot;&gt;
		&lt;a class=&quot;nav-link&quot; href=&quot;#&quot;&gt;&lt;span&gt;Link&lt;/span&gt;&lt;/a&gt;
	&lt;/li&gt;
	&lt;li class=&quot;nav-item&quot;&gt;
		&lt;a class=&quot;nav-link&quot; href=&quot;#&quot;&gt;&lt;span&gt;Link&lt;/span&gt;&lt;/a&gt;
	&lt;/li&gt;
&lt;/ul&gt;
		</code></pre>
	</div>
</div>

<div class="d-block mt-4">
	<h3>Выпадающее меню</h3>
	<div class="dropdown">
		<button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Dropdown button
		</button>
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="#">Action</a>
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
		</div>
	</div>
	
	<a data-toggle="collapse" href="#dropdownExample" class="btn btn-link my-4">Показать код</a>
	
	<div id="dropdownExample" class="collapse">	
		<pre><code class="html" style="tab-size: 4;">&lt;div class=&quot;dropdown&quot;&gt;
	&lt;button class=&quot;btn btn-outline-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton&quot; data-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
		Dropdown button
	&lt;/button&gt;
	&lt;div class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuButton&quot;&gt;
		&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;
		&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;
		&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;
	&lt;/div&gt;
&lt;/div&gt;</code></pre>
	</div>
</div>

<div class="d-block mt-4">
	<h3>Аккордеон</h3>
	<div class="accordion" id="accordionExample">
		<div class="card">
			<div class="card-header" id="headingOne">
				<a class="card-header-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
				Collapsible Group Item #1
				</a>
			</div>
			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
				<div class="card-body">
					Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header" id="headingTwo">
				<a class="card-header-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
				Collapsible Group Item #2
				</a>
			</div>
			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
				<div class="card-body">
					Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header" id="headingThree">
				<a class="card-header-link collapsed"  data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				Collapsible Group Item #3
				</a>
			</div>
			<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
				<div class="card-body">
					Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
				</div>
			</div>
		</div>
	</div>
	
	<a data-toggle="collapse" href="#accordionCode" class="btn btn-link my-4">Показать код</a>
	
	<div id="accordionCode" class="collapse">	
		<pre><code class="html"  style="tab-size: 4;">&lt;div class=&quot;accordion&quot; id=&quot;accordionExample&quot;&gt;
	&lt;div class=&quot;card&quot;&gt;
		&lt;div class=&quot;card-header&quot; id=&quot;headingOne&quot;&gt;
			&lt;a class=&quot;card-header-link collapsed&quot; data-toggle=&quot;collapse&quot; data-target=&quot;#collapseOne&quot; aria-expanded=&quot;true&quot; aria-controls=&quot;collapseOne&quot;&gt;
			Collapsible Group Item #1
			&lt;/a&gt;
		&lt;/div&gt;
		&lt;div id=&quot;collapseOne&quot; class=&quot;collapse show&quot; aria-labelledby=&quot;headingOne&quot; data-parent=&quot;#accordionExample&quot;&gt;
			&lt;div class=&quot;card-body&quot;&gt;
				Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
			&lt;/div&gt;
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;div class=&quot;card&quot;&gt;
		&lt;div class=&quot;card-header&quot; id=&quot;headingTwo&quot;&gt;
			&lt;a class=&quot;card-header-link collapsed&quot; data-toggle=&quot;collapse&quot; data-target=&quot;#collapseTwo&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseTwo&quot;&gt;
			Collapsible Group Item #2
			&lt;/a&gt;
		&lt;/div&gt;
		&lt;div id=&quot;collapseTwo&quot; class=&quot;collapse&quot; aria-labelledby=&quot;headingTwo&quot; data-parent=&quot;#accordionExample&quot;&gt;
			&lt;div class=&quot;card-body&quot;&gt;
				Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
			&lt;/div&gt;
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;div class=&quot;card&quot;&gt;
		&lt;div class=&quot;card-header&quot; id=&quot;headingThree&quot;&gt;
			&lt;a class=&quot;card-header-link collapsed&quot;  data-toggle=&quot;collapse&quot; data-target=&quot;#collapseThree&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseThree&quot;&gt;
			Collapsible Group Item #3
			&lt;/a&gt;
		&lt;/div&gt;
		&lt;div id=&quot;collapseThree&quot; class=&quot;collapse&quot; aria-labelledby=&quot;headingThree&quot; data-parent=&quot;#accordionExample&quot;&gt;
			&lt;div class=&quot;card-body&quot;&gt;
				Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
			&lt;/div&gt;
		&lt;/div&gt;
	&lt;/div&gt;
&lt;/div&gt;</code></pre>
	</div>
</div>


<div class="d-block">
	<h3>Табы</h3>
	<ul class="nav nav-slide" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><span>Home</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><span>Profile</span></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><span>Contact</span></a>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active p-4" id="home" role="tabpanel" aria-labelledby="home-tab">Home content</div>
		<div class="tab-pane fade p-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">Profile content</div>
		<div class="tab-pane fade p-4" id="contact" role="tabpanel" aria-labelledby="contact-tab">Contact content</div>
	</div>
	
	<a data-toggle="collapse" href="#tabCode" class="btn btn-link my-4">Показать код</a>
	
	<div id="tabCode" class="collapse">
		<pre><code class="html" style="tab-size: 4;">&lt;ul class=&quot;nav nav-slide&quot; id=&quot;myTab&quot; role=&quot;tablist&quot;&gt;
	&lt;li class=&quot;nav-item&quot;&gt;
		&lt;a class=&quot;nav-link active&quot; id=&quot;home-tab&quot; data-toggle=&quot;tab&quot; href=&quot;#home&quot; role=&quot;tab&quot; aria-controls=&quot;home&quot; aria-selected=&quot;true&quot;&gt;&lt;span&gt;Home&lt;/span&gt;&lt;/a&gt;
	&lt;/li&gt;
	&lt;li class=&quot;nav-item&quot;&gt;
		&lt;a class=&quot;nav-link&quot; id=&quot;profile-tab&quot; data-toggle=&quot;tab&quot; href=&quot;#profile&quot; role=&quot;tab&quot; aria-controls=&quot;profile&quot; aria-selected=&quot;false&quot;&gt;&lt;span&gt;Profile&lt;/span&gt;&lt;/a&gt;
	&lt;/li&gt;
	&lt;li class=&quot;nav-item&quot;&gt;
		&lt;a class=&quot;nav-link&quot; id=&quot;contact-tab&quot; data-toggle=&quot;tab&quot; href=&quot;#contact&quot; role=&quot;tab&quot; aria-controls=&quot;contact&quot; aria-selected=&quot;false&quot;&gt;&lt;span&gt;Contact&lt;/span&gt;&lt;/a&gt;
	&lt;/li&gt;
&lt;/ul&gt;
&lt;div class=&quot;tab-content&quot; id=&quot;myTabContent&quot;&gt;
	&lt;div class=&quot;tab-pane fade show active p-4&quot; id=&quot;home&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;home-tab&quot;&gt;Home content&lt;/div&gt;
	&lt;div class=&quot;tab-pane fade p-4&quot; id=&quot;profile&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;profile-tab&quot;&gt;Profile content&lt;/div&gt;
	&lt;div class=&quot;tab-pane fade p-4&quot; id=&quot;contact&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;contact-tab&quot;&gt;Contact content&lt;/div&gt;
&lt;/div&gt;</code></pre>
	</div>
</div>

<script>hljs.initHighlightingOnLoad();</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>