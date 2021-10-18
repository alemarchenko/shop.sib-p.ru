<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) 
{
    die();
}

$request =  \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->get('collection'))
{
    $nCollectionId = (int) $request->get('collection');

    include $_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder().'/include/collection.php'; 
}
else
{
    include $_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder().'/include/list.php'; 
}
