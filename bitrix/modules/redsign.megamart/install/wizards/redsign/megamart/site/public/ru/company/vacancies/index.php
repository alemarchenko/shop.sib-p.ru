<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии");

?>
<h3>Добро пожаловать в команду</h3><br>
<p>
ООО «Megamart» начала свою работу в 2004 году. Его основателями являются: Матвеев Дмитрий, руководивший до этого производственным цехом на крупном предприятии в США, и Борисов Станислав – бывший генеральный директор и совладелец «Производственной компании», крупного российского производственного холдинга. Сегодня компания занимает лидирующую позицию на российском рынке и приглашает присоединится к команде профессионалов.
</p><br>
<h4>Выберите свое направление</h4>
 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"accordion", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "accordion",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#CONTENT_VACANCIES_IBLOCK_ID#",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"JOB_LINK" => "#SITE_DIR#forms/job/?element_id=#ELEMENT_ID#",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"NOTE_PROP" => "NOTE",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "TYPE",
			1 => "NOTE",
			2 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SOCIAL_SERVICES" => "vkontakte,facebook,odnoklassniki,twitter",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"RS_VACANCIES_B24_CRM_FORM_USE" => "Y",
		"RS_VACANCIES_B24_CRM_FORM_ID" => "9",
		"RS_VACANCIES_B24_CRM_FORM_SEC" => "2wf3vq",
		"RS_VACANCIES_B24_CRM_FORM_VACANCY_PARAM" => "my_param",
		"MESS_BTN_FILTER_ALL" => "Все вакансии"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>