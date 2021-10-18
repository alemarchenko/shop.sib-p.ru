<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
$APPLICATION->IncludeComponent(
	"bitrix:main.post.form",
	"",
	Array(
		"FORM_ID" => $component->createPostFormId(),
		"SHOW_MORE" => "Y",
		"PARSER" => blogTextParser::GetEditorToolbar(array('blog' => $arResult['Blog'])),
		"LHE" => array(
			'id' => $component->createEditorId(),
			'bSetDefaultCodeView' => $arParams['EDITOR_CODE_DEFAULT'],
			'bResizable' => true,
			'bAutoResize' => true,
			"documentCSS" => "body {color:#434343; font-size: 14px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 20px;}",
			'setFocusAfterShow' => false,
			'ctrlEnterHandler' => 'blogCommentCtrlEnterHandler',
			'normalBodyWidth' => 400	//if width of all button more than editor size - not need set normalBodyWidth
		),
		
		"ADDITIONAL" => array(),

		"TEXT" => Array(
			"ID" => "POST_MESSAGE",
			"NAME" => "comment",
			"VALUE" => "",
			"SHOW" => "Y",
			"HEIGHT" => "200px"
		),
		"SMILES" => COption::GetOptionInt("blog", "smile_gallery_id", 0),
		"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
	),
	$component,
	array("HIDE_ICONS" => "Y")
);
*/

//generate ID for file dialog switcher
if(is_array($arResult["COMMENT_PROPERTIES"]["DATA"][CBlogComment::UF_NAME]))
{
	$switcerhId = 'file-selectdialogswitcher-'
		.$arResult["COMMENT_PROPERTIES"]["DATA"][CBlogComment::UF_NAME]["ENTITY_ID"]
		."-".$arResult["COMMENT_PROPERTIES"]["DATA"][CBlogComment::UF_NAME]["ID"]
		."-".$arResult["COMMENT_PROPERTIES"]["DATA"][CBlogComment::UF_NAME]["FIELD_NAME"];
}

?>
<div class="row">
	<div class="col-12">
		<div class="form-group bmd-form-group" id="<?=$component->createEditorId()?>">
			<label for="comment" class="bmd-label-floating"><?=GetMessage('RS_MM_BBPC_ADAPT_REVIEW_TEXT')?><span>*</span></label>
			<textarea id="comment" name="comment" class="bmd-form-control" tabindex="5" required><?=$arField['CURRENT_VALUE']?></textarea>
		</div>
	</div>
</div>

<script>
BX.ready(function() {
	//	init EDITOR form
	window["UC"] = (!!window["UC"] ? window["UC"] : {});
	window["UC"]["f<?=$component->createPostFormId()?>"] = new FCForm({
		entitiesId : {},
		formId : '<?=$component->createPostFormId()?>',
		editorId : '<?=$component->createEditorId()?>',
		<?if(isset($switcerhId) && strlen($switcerhId) > 0) :?>
		fileDialogSwitcherId : '<?=$switcerhId?>',
		<?endif;?>
		editorName : ''
	});
		if (!!window["UC"]["f<?=$component->createPostFormId()?>"].eventNode)
		{
			BX.addCustomEvent(window["UC"]["f<?=$component->createPostFormId()?>"].eventNode, 'OnUCFormClear', __blogOnUCFormClear);
			BX.addCustomEvent(window["UC"]["f<?=$component->createPostFormId()?>"].eventNode, 'OnUCFormAfterShow', __blogOnUCFormAfterShow);
			BX.addCustomEvent(window["UC"]["f<?=$component->createPostFormId()?>"].eventNode, 'OnUCFormSubmit', __blogOnUCFormSubmit);
		}
});
</script>