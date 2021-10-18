<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Иконки");
//$APPLICATION->SetPageProperty("hide_section", "Y");

\Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/default.min.css">');
\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>');
?>

<?php

function getIconsFromDirectory(\Bitrix\Main\IO\Directory $directory) 
{
	$arIcons = [];
	
	foreach ($directory->getChildren() as $child) 
	{
		if ($child->isDirectory()) 
		{
			return array_merge($arIcons, getIconsFromDirectory());
		} 
		else if ($child->isFile()) 
		{
			if ($child->getExtension() == 'svg') 
			{
				$sFileName = str_replace('.svg', '', $child->getName());
				$arIcons[] = $sFileName;
			}
		}
	}
	
	return $arIcons;
}

$arIconsPath = [
	'/bitrix/modules/redsign.megamart/assets/svg/',
	'/local/modules/redsign.megamart/assets/svg/',
	SITE_DIR.'assets/icons/'
];

$arIcons = [];

foreach ($arIconsPath as $sPath) {
	$directory = new \Bitrix\Main\IO\Directory($_SERVER['DOCUMENT_ROOT'].$sPath);
	
	if ($directory->isExists()) 
	{
		$arIcons = array_merge($arIcons, getIconsFromDirectory($directory));
	}

}

?>

<?php if (count($arIcons) > 0): ?>
<table class="table">
	<thead>
		<tr>
			<th></th>
			<th>Название</th>
			<th>Использование</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($arIcons as $sIconName): ?>
		<tr>
			<td><svg class="icon-svg"><use xlink:href="#svg-<?=$sIconName?>"></use></svg></th>
			<td><?=$sIconName?></th>
			<td><code class="html">&lt;svg class=&quot;icon-svg&quot;&gt;&lt;use xlink:href=&quot;#svg-<?=$sIconName?>&quot;&gt;&lt;/use&gt;&lt;/svg&gt;</code></th>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<p>Для использования собственных svg иконок, загрузите svg файл в директорию <em><?=SITE_DIR?>assets/icons/</em> и персоберите иконки в настройках модуля <em>Настройки -> Настройки продукта -> Настройки модулей -> MegaMart – интернет магазин для больших продаж -> Иконки</em></p>


<?php endif; ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>