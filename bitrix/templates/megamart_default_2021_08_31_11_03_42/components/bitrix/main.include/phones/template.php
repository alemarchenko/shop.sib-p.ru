<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$sTemplatePath = $arParams['GET_FROM'] == 'file'
					? $_SERVER['DOCUMENT_ROOT'].$arParams['PATH']
					: $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/templates/'.$arParams['RS_TEMPLATE'].'.php';

if (file_exists($sTemplatePath)) {
	include($sTemplatePath);
} else {
	include($_SERVER['DOCUMENT_ROOT'].$arParams['PATH']);
}
