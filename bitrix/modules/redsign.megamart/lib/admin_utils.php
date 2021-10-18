<?php

namespace Redsign\MegaMart;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Redsign\MegaMart\SVGIconsManager;
use \Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class AdminUtils {

	const MODULE_ID = "redsign.megamart";

	public static function getEditPublicFileLink($file, $siteId) {
		return "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$siteId."&bxpublic=Y&from=includefile&path=".$file."&lang=".LANGUAGE_ID."','width':'1200','height':'500'}).Show();";
	}

	public static function showOptions($arOptions, $arSite) {
		foreach ($arOptions as $arOption) {
			self::showOptionRow($arOption, $arSite);
		}
	}

	public static function getSiteTabs($arSites) {
		$arTabs = array();

		$rand = \Bitrix\Main\Security\Random::getString(3);

		foreach ($arSites as $arSite) {
			$arTabs[] = array(
				'DIV' => 'redsign_mm_sub_'.$arSite['LID'].'_'.$rand,
				'TAB' => '('.$arSite['LID'].') '.$arSite["NAME"],
				'TITLE' => ''
			);
		}

		return $arTabs;
	}

	public static function generateSvgIcons($arIconsPath, $sFilePath) {
		foreach ($arIconsPath as $sPath) {
			$directory = new \Bitrix\Main\IO\Directory($_SERVER['DOCUMENT_ROOT'].$sPath);

			if ($directory->isExists()) {
				self::pushSVGIconsFromDirectory($directory);
			}
		}
		$svg = SVGIconsManager::releaseSVG();
		$svg = SVGIconsManager::minify($svg);

		file_put_contents(Application::getDocumentRoot().$sFilePath, $svg);
	}

	private static function pushSVGIconsFromDirectory(\Bitrix\Main\IO\Directory $directory)  {
		SVGIconsManager::addPath($directory->getPath());
		foreach ($directory->getChildren() as $child) {
			if ($child->isDirectory()) {
				self::pushSVGIconsFromDirectory($child);
			} else if ($child->isFile()) {
				if ($child->getExtension() == 'svg') {
					$sFileName = str_replace('.svg', '', $child->getName());
					SVGIconsManager::pushIcon($sFileName);
				}
			}
		}
	}

	public static function showOptionRow($arOption, $arSite) {
		if (isset($arOption['SHOW']) && !$arOption['IS_SHOW']) {
			return;
		}

		switch ($arOption['TYPE']) {
			case 'HEADER':
				?><tr class="heading">
					<td colspan="2" style="background: transparent;"><?=$arOption['NAME']?></td>
				</tr><?
				break;

			case 'SELECT':
				$currentVal = (isset($arOption['ID']) && $arOption['ID'] <> '' ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
				?><tr><td><?=$arOption['NAME']?></td>
				<td><select name="<?=$arOption['CODE']?>_<?=$arSite['LID']?>">
					<?php
					if (isset($arOption['VALUES']) && is_array($arOption['VALUES'])):
						foreach ($arOption['VALUES'] as $value):
					?>
					<option value="<?=$value?>" <?php if ($value == $currentVal) echo 'selected'; ?>><?=htmlspecialcharsbx($value)?></option>
					<?php
						endforeach;
					endif;
					?>
				</select></td></tr><?php
				break;

			case 'CHECKBOX':
				$currentVal = (isset($arOption['ID']) && $arOption['ID'] <> '' ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : 'N');
				?><tr>
					<td style="width: 50%;"><label for="<?=$arOption['CODE']?>_<?=$arSite['LID']?>"><?=$arOption['NAME']?></label></td>
					<td>
						<input type="checkbox" value="Y" name="<?=$arOption['CODE']?>_<?=$arSite['LID']?>" id="<?=$arOption['CODE']?>_<?=$arSite['LID']?>" <?php if ($currentVal == "Y") echo "checked";?>>
						<?php if (isset($arOption['INCLUDE_FILE'])): ?>
						&nbsp;
						<a class="adm-btn" href="<?=self::getEditPublicFileLink($arSite['DIR'].$arOption['INCLUDE_FILE'], $siteId)?>" title="<?=Loc::getMessage('RS_EDIT_FILE')?>"><?=Loc::getMessage('RS_EDIT_FILE')?></a>
						<?php endif; ?>
					</td>
				</tr>
				<?php
				break;

			case 'INPUT':
				$currentVal = (isset($arOption['ID']) && $arOption['ID'] <> '' ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
				?><tr>
					<td align="right" width="50%"><?=$arOption['NAME']?></td>
					<td width="50%"><input type="text" size="40" value="<?=htmlspecialcharsbx($currentVal)?>" name="<?=$arOption['CODE']?>_<?= $arSite["LID"] ?>" id="<?=$arOption['CODE']?>_<?=$arSite['LID']?>"></td>
				</tr>
				<?php
				break;

			case 'INPUT_ARRAY':
				$currentVal = (isset($arOption['ID']) && $arOption['ID'] <> '' ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
				$arCurrentValues = unserialize($currentVal);
				?>
				<tr>
					<td align="right" width="50%" style="vertical-align: top; padding-top: 20px;"><?=$arOption['NAME']?></td>
					<td width="50%">
						<div class="rs-adm-options-drag-container" data-rs-options-container="<?=$arOption['CODE']?>_<?= $arSite["LID"] ?>">
							<?php foreach ($arCurrentValues as $sVal): ?>
								<div class="rs-adm-options-input-container">
									<input type="text" size="40" value="<?=$sVal?>" name="<?=$arOption['CODE']?>_<?= $arSite["LID"] ?>[]">
								</div>
							<?php endforeach; ?>
							<div class="rs-adm-options-input-container">
								<input type="text" size="40" value="" name="<?=$arOption['CODE']?>_<?= $arSite["LID"] ?>[]">
							</div>
						</div>
						<div class="rs-adm-options-button-container">
							<a href="javascript:;" class="adm-btn adm-btn-save" title="" style="" data-rs-options-add-input="<?=$arOption['CODE']?>_<?= $arSite["LID"] ?>"><?=Loc::getMessage('RS_ADD_INPUT')?></a>
						</div>
					</td>
				</tr>
				<?php
				break;

			case 'LHE':
				\Bitrix\Main\Loader::includeModule('fileman');

				$currentVal = (isset($arOption['ID']) && $arOption['ID'] <> '' ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');

				$params = [
					'id' => $arOption['CODE']."_".$arSite['LID'],
					'inputName' => $arOption['CODE']."_".$arSite['LID'],
					'height' => '160',
					'width' => '330',
					'content' => $currentVal,
					'bResizable' => true,
					'bManualResize' => true,
					'bUseFileDialogs' => false,
					'bFloatingToolbar' => false,
					'bArisingToolbar' => false,
					'bAutoResize' => true,
					'bSaveOnBlur' => true,
					'toolbarConfig' => [
						'Bold', 'Italic', 'Underline', 'Strike',
						'CreateLink', 'DeleteLink',
						'Source', 'BackColor', 'ForeColor'
					]
				];

				$LHE = new \CLightHTMLEditor;

				?><tr>
					<td align="right" width="50%"><?=$arOption['NAME']?></td>
					<td width="50%">
						<?=$LHE->Show($params);?>
					</td>
				</tr><?php
				break;

			case 'LOCATION':
				$currentVal = (isset($arOption['ID']) && $arOption['ID'] <> '' ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
				global $APPLICATION;
				?><tr>
				  <td align="right" width="50%"><?=$arOption['NAME']?></td>
				  <td width="50%">
					  <?php $APPLICATION->IncludeComponent(
						  "bitrix:sale.location.selector.search",
						  "",
						  Array(
							  "COMPONENT_TEMPLATE" => ".default",
							  "ID" => '',
							  "CODE" => $currentVal,
							  "INPUT_NAME" => $arOption['CODE']."_".$arSite['LID'],
							  "PROVIDE_LINK_BY" => "code",
							  "JS_CONTROL_GLOBAL_ID" => "",
							  "FILTER_BY_SITE" => "Y",
							  "SHOW_DEFAULT_LOCATIONS" => "Y",
							  "CACHE_TYPE" => "A",
							  "CACHE_TIME" => "36000000",
							  "FILTER_SITE_ID" => $arSite['LID'],
							  "INITIALIZE_BY_GLOBAL_EVENT" => "",
							  "SUPPRESS_ERRORS" => "N"
						  )
					  ); ?>
				  </td>
				</tr>
				<?php
				break;

			case 'INCLUDE':
				?><tr>
					<td><?=$arOption['NAME']?></td>
					<td><a class="adm-btn" href="<?=self::getEditPublicFileLink($arSite['DIR'].$arOption['INCLUDE_FILE'], $siteId)?>" title="<?=Loc::getMessage('RS_EDIT_FILE')?>"><?=Loc::getMessage('RS_EDIT_FILE')?></a></td>
				</tr><?
				break;

			default:
				break;
		}
	}

}
