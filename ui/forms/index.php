<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Формы");
$APPLICATION->SetPageProperty("hide_section", "Y");
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
$layoutHeader->addData('TITLE', 'Базовые формы');
$layout->start();
?>
<div class="">
	<form>
		<div class="form-group">
			<label for="exampleInputEmail1">Email address</label>
			<input type="email" name="asdasd" class="form-control" id="exampleInputEmail1" autocomplete="off">
			<span class="form-text text-muted">We'll never share your email with anyone else.</small>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" name="axsx" class="form-control" id="exampleInputPassword1" autocomplete="off">
		</div>
		<div class="form-group">
			<label for="exampleSelect1">Example select</label>
			<select class="form-control" id="exampleSelect1">
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			</select>
		</div>
		<div class="form-group">
			<label for="exampleSelect2">Example multiple select</label>
			<select multiple class="form-control" id="exampleSelect2">
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			</select>
		</div>
		 <div class="form-group">
			<label for="exampleTextarea">Example textarea</label>
			<textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
		</div>
		<div class="form-group">
				<label for="exampleInputFile">File input</label>
				<input type="file" class="form-control-file" id="exampleInputFile">
			<small class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
		</div>
		
		<div class="radio">
			<label>
				<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
				Option one is this and that&mdash;be sure to include why it's great
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
				Option two can be something else and selecting it will deselect option one
			</label>
		</div>
		<div class="radio disabled">
			<label>
				<input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
				Option three is disabled
			</label>
		</div>
		
		<label class="radio-inline">
			<input type="radio" name="inlineRadioOptions" value="option1"> 1
		</label>
		<label class="radio-inline">
			<input type="radio" name="inlineRadioOptions" value="option2"> 2
		</label>
		<label class="radio-inline">
			<input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> 3
		</label>   
		
		<div class="checkbox">
			<label>
				<input type="checkbox"> Check me out
			</label>
		</div>   
		<div class="checkbox">
			<label>
				<input type="checkbox" value="" checked>
				I'm selected because I seem like the safe choice
			</label>
		</div>
		<div class="checkbox disabled">
			<label>
				<input type="checkbox" value="" disabled>
				Option is disabled because it is scary
			</label>
		</div>  
	</form>
</div>
<?php $layout->end(); ?>

<?php
$layoutHeader->addData('TITLE', 'Megamart формы');
$layout->start();
?>
<div>
	<form>
		<div class="form-group bmd-form-group">
			<label for="exampleInputEmail11" class="bmd-label-floating">Email address</label>
			<input type="email" class="bmd-form-control" name="test" autocomplete="off">
			<span class="bmd-help">We'll never share your email with anyone else.</small>
		</div>
		<div class="form-group bmd-form-group">
			<label for="exampleInputPassword11" class="bmd-label-floating">Password</label>
			<input type="password" class="bmd-form-control" autocomplete="off">
		</div>
		<div class="form-group bmd-form-group">
			<label for="exampleSelect11">Example select</label>
			<select class="bmd-form-control" id="exampleSelect1">
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			</select>
		</div>
		<div class="form-group bmd-form-group">
			<label for="example2Select2">Example multiple select</label>
			<select multiple class="bmd-form-control" id="example2Select2">
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			</select>
		</div>
		 <div class="form-group bmd-form-group">
			<label for="example2Textarea"class="bmd-label-floating">Example textarea</label>
			<textarea class="bmd-form-control" id="example2Textarea" rows="3"></textarea>
		</div>
		
		<div class="bmd-custom-radio radio">
			<label>
				<input type="radio" name="optionsRadios" id="options2Radios1" value="option1" checked>
				Option one is this and that&mdash;be sure to include why it's great
			</label>
		</div>
		<div class="bmd-custom-radio radio">
			<label>
				<input type="radio" name="optionsRadios" id="options2Radios2" value="option2">
				Option two can be something else and selecting it will deselect option one
			</label>
		</div>
		<div class="bmd-custom-radio radio disabled">
			<label>
				<input type="radio" name="optionsRadios" id="options2Radios3" value="option3" disabled>
				Option three is disabled
			</label>
		</div>
		
		<label class="radio-inline bmd-custom-radio-inline">
			<input type="radio" name="inlineRadioOptions" value="option1"> 1
		</label>
		<label class="radio-inline bmd-custom-radio-inline">
			<input type="radio" name="inlineRadioOptions" value="option2"> 2
		</label>
		<label class="radio-inline bmd-custom-radio-inline">
			<input type="radio" name="inlineRadioOptions" value="option3"> 3
		</label>  
		
		<div class="checkbox bmd-custom-checkbox">
			<label>
				<input type="checkbox"> Check me out
			</label>
		</div>   
		<div class="checkbox bmd-custom-checkbox">
			<label>
				<input type="checkbox" value="" checked>
				I'm selected because I seem like the safe choice
			</label>
		</div>
		<div class="checkbox bmd-custom-checkbox disabled">
			<label>
				<input type="checkbox" value="" disabled>
				Option is disabled because it is scary
			</label>
		</div>  
		
		  <label class="checkbox-inline bmd-custom-checkbox-inline">
			<input type="checkbox"  value="option1"> 1
		  </label>
		  <label class="checkbox-inline bmd-custom-checkbox-inline">
			<input type="checkbox"  value="option2"> 2
		  </label>
		  <label class="checkbox-inline bmd-custom-checkbox-inline">
			<input type="checkbox" value="option3"> 3
		  </label>  
	</form>
</div>

<?php $layout->end(); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>