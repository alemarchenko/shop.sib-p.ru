<?
/** @global CMain $APPLICATION */

use Bitrix\Main,
	Bitrix\Catalog;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->get('AJAX') == 'Y') {

    $APPLICATION->RestartBuffer();
    header('Content-Type: application/json');
    
    $action = $request->get('action');
    $name = $request->get('NAME');
    $iblockID = $request->get('IBLOCK_ID');
    $items = $request->get('ITEMS');
    
    if (isset($_SESSION[$name][$iblockID]['ITEMS'])) {
        if (
            $action == 'compare-sort'
            && is_array($items) && count($items) > 1
        ) {
            uasort($_SESSION[$name][$iblockID]['ITEMS'], function($a, $b) use ($items) {
                $items = array_flip($items);
                return $items[$a['PARENT_ID']] > $items[$b['PARENT_ID']];
            });

            addmessage2log($items);
            addmessage2log($_SESSION[$name][$iblockID]['ITEMS']);
            echo Main\Web\Json::encode(array("STATUS" => "SUCCESS", "TEXT" => "COMPARE SORTED"));
        }
    }
	die();
}