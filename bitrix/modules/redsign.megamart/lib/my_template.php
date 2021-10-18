<?php

namespace Redsign\MegaMart;

use \Bitrix\Main;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\ModuleManager;

class MyTemplate
{
    private static $instance;
	private static $navItems = array();

    public static function getTemplatePart($sPath)
	{
        if (empty($sPath))
		{
            return;
        }

        $paths = array($sPath);

        $sFileExt = end(explode('.', $sPath));
        array_unshift($paths, str_replace($sFileExt, '', $sPath).'custom.'.$sFileExt);

        foreach ($paths as $path)
		{
            $filePath = Application::getDocumentRoot().$path;

            if (file_exists($filePath))
			{
                return $filePath;
            }
        }

        return;
    }


	public static function getSiteHead()
	{
		global $APPLICATION;
		$isWidePage = $APPLICATION->GetProperty('wide_page') == 'Y';
		$isHideOuterSidebar = $APPLICATION->GetProperty('hide_outer_sidebar') == 'Y';
		$isHideInnerSidebar = $APPLICATION->GetProperty('hide_inner_sidebar') == 'Y';
		$isHideSection = $APPLICATION->GetProperty('hide_section') == 'Y';
		$isHideTitle = $APPLICATION->GetProperty('hide_title') == 'Y';
		$isHidePageTitle = $APPLICATION->GetProperty('hide_page_title') == 'Y';

		$hasSidebar = !$isWidePage || ($isHideOuterSidebar && $isHideInnerSidebar);

		$sMainClass = 'l-main';
		if (!$isWidePage)
		{
			// $sMainClass .= ' container';
			if (!$isHideOuterSidebar)
			{
				$sMainClass .= ' l-main--has-outer-sidebar';
			}
			if (!$isHideInnerSidebar)
			{
				$sMainClass .= ' l-main--has-inner-sidebar';
			}
		}

		$sHTML = '<div class="'.$sMainClass.'">';

			if (!$isHideTitle)
			{
				$sHTML .= '<div class="l-main__head'.$APPLICATION->GetViewContent('backgroundClass').'" '.$APPLICATION->GetProperty("backgroundImage").'>';

				if (!$isWidePage)
				{
					$sHTML .= '<div class="container">';
				}

				$sHTML .= $APPLICATION->GetNavChain(
					$path = false,
					$iNumFrom = 0,
					$sNavChainPath = SITE_TEMPLATE_PATH.'/components/bitrix/breadcrumb/standart/template.php',
					$bIncludeOnce = true,
					$bShowIcons = false
				);

				if (!$isHidePageTitle)
				{
					$sHTML .= '<h1 class="l-main__title" id="pagetitle">'.$APPLICATION->GetTitle(true).'</h1>';
					$sHTML .= $APPLICATION->GetViewContent('after-title');
				}

				if (!$isWidePage)
				{
					$sHTML .= '</div>';
				}

				$sHTML .= '</div>';
			}

			$sHTML .= '<div class="l-main__container'.(!$isWidePage ? ' container' : '').'">';
				$sHTML .= '<div class="l-main__outer">';
					$sHTML .= '<div class="l-main__outer-content">';
						$sHTML .= '<div class="l-main__inner">';
							$sHTML .= '<div class="l-main__inner-content">';

								if (!$isHideSection)
								{

									$sHTML .= '<div class="l-section l-section--outer-spacing l-section--inner-spacing l-section--bg-white l-section--heighter"><div class="l-section__main box-shadow-1">';
								}
		return $sHTML;
	}

	public static function getSiteFooter()
	{
		global $APPLICATION;
		$isWidePage = $APPLICATION->GetProperty('wide_page') == 'Y';
		$isHideOuterSidebar = $APPLICATION->GetProperty('hide_outer_sidebar') == 'Y';
		$isHideInnerSidebar = $APPLICATION->GetProperty('hide_inner_sidebar') == 'Y';
		$isHideSection = $APPLICATION->GetProperty('hide_section') == 'Y';
		$sInnerSidebarPath = $APPLICATION->GetProperty('inner_sidebar_file');
		$sOuterSidebarPath = $APPLICATION->GetProperty('outer_sidebar_file');

		$sHTML = '';
								if (!$isHideSection)
								{
									echo '</div></div>'; // l-section__main l-section
								}
							echo '</div>'; // l-main__inner-content

							if (!$isHideInnerSidebar)
							{
								$APPLICATION->IncludeFile(
									"include/sidebar.php",
									array('SIDEBAR_PATH' => $sInnerSidebarPath, 'TYPE' => 'inner'),
									array('SHOW_BORDER' => false)
								);
							}

						echo '</div>'; // l-main__inner
					echo '</div>'; // l-main__outer-content

					if (!$isHideOuterSidebar)
					{
						$APPLICATION->IncludeFile(
							"include/sidebar.php",
							array('SIDEBAR_PATH' => $sOuterSidebarPath, 'TYPE' => 'outer'),
							array('SHOW_BORDER' => false)
						);
					}

				echo '</div>'; // l-main__outer+
			echo '</div>'; // l-main__container
		echo '</div>'; // l-main
	}

	public static function addPageNavItem($item)
	{
		global $APPLICATION;

		self::$navItems[] = $item;

		// $APPLICATION->AddViewContent(
			// 'site-page-navigation',
			// '<div class="nav-item">'.
				// '<a class="nav-link js-link-scroll" href="#'.$item['ID'].'">'.
					// $item['NAME'].'<svg class="nav-link-icon icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>'.
				// '</a>'.
			// '</div>'
		// );
	}

	public static function getPageNav()
	{
		global $APPLICATION;

		global $USER;
		$arUserGroups = array(2);
		if (isset($USER) && $USER instanceof \CUser)
		{
			$arUserGroups = $USER->GetUserGroupArray();
			Main\Type\Collection::normalizeArrayValuesByInt($arUserGroups, true);
		}

		$cacheTime = 86400;
		$cacheID = SITE_ID.'|'.LANGUAGE_ID.(SITE_TEMPLATE_ID != '' ? '|'.SITE_TEMPLATE_ID:'').'|'.serialize($arUserGroups);

		$cache = \Bitrix\Main\Data\Cache::createInstance();

		if ($cache->initCache($cacheTime, $cacheId))
		{
			$arCache = $cache->getVars();
			self::$navItems = $arCache['navItems'];
		}
		elseif ($cache->startDataCache())
		{
			$arCache = array();

			$arCache['navItems'] = self::$navItems;

			if (empty($arCache)) {
				$cache->abortDataCache();
			}

			$cache->endDataCache($arCache);
		}

		// $arNavItems = $APPLICATION->GetViewContent('site-page-navigation');

		// if (strlen($arNavItems) > 0)
		// {
			// $sHTML .= '<div class="l-main__nav-wrap">'.
				// '<nav class="l-main__nav nav-scrollspy position-sticky sticky-compact">'.
					// $arNavItems.
				// '</nav>'.
			// '</div>';
		// }


		if (!empty(self::$navItems))
		{
			$sHTML .= '<div class="l-main__nav-wrap">'.
				'<nav class="l-main__nav nav-scrollspy position-sticky sticky-compact">';

					foreach (self::$navItems as $item)
					{
						$sHTML .= '<div class="nav-item">'.
							'<a class="nav-link js-link-scroll" href="#'.$item['ID'].'">'.
								'<span class="text-truncate">'.$item['NAME'].'</span>'.
								'<svg class="nav-link-icon icon-svg"><use xlink:href="#svg-arrow-right"></use></svg>'.
							'</a>'.
						'</div>';
					}
					unset($item);
			$sHTML .= '</nav>'.
			'</div>';
		}

		return $sHTML;
	}

	public static function rsTuningOnBeforeGetReadyMacros(\Bitrix\Main\Event $event) {

		if (!Loader::includeModule('redsign.devfunc'))
			return;

		$arParams = $event->getParameters();
		$macrosManager = $arParams['ENTITY'];

		$macrosList = $macrosManager->getList();

		$color11 = $macrosList['COLOR_1_1'];
		if (strlen($color11) == 6) {
			$rsColor11 = new \RSColor($color11);
			$macrosManager->set('COLOR_1_1_DARKEN_10_PERSENT', $rsColor11->darken(10)->getHex());
			$macrosManager->set('COLOR_1_1_LIGHTEN_25_PERSENT', $rsColor11->lighten(25)->getHex());
            $macrosManager->set('COLOR_1_1_LIGHTEN_15_PERSENT', $rsColor11->lighten(15)->getHex());
			$macrosManager->set('COLOR_1_1_LIGHTEN_25_OPACITY', $rsColor11->getRgba(0.25));
			$macrosManager->set('COLOR_1_1_DARKEN_7_5_PERSENT', $rsColor11->darken(7.5)->getHex());

		} else {
			$macrosManager->set('COLOR_1_1_DARKEN_10_PERSENT', $color11);
		}

		$color12 = $macrosList['COLOR_1_2'];
		if (strlen($color12) == 6) {
			$rsColor12 = new \RSColor($color12);
			$macrosManager->set('COLOR_1_2_DARKEN_10_PERSENT', $rsColor12->darken(10)->getHex());
		} else {
			$macrosManager->set('COLOR_1_2_DARKEN_10_PERSENT', $color12);
		}
	}

    function ShowPanel()
    {
        if ($GLOBALS["USER"]->IsAdmin() && \COption::GetOptionString("main", "wizard_solution", "", SITE_ID) == "redsign.megamart")
        {
            $GLOBALS["APPLICATION"]->SetAdditionalCSS("/bitrix/wizards/redsign/megamart/css/panel.css"); 

            $arMenu = Array(
                Array(        
                    "ACTION" => "jsUtils.Redirect([], '".\CUtil::JSEscape("/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardSiteID=".SITE_ID."&wizardName=redsign:megamart&".\bitrix_sessid_get())."')",
                    "ICON" => "bx-popup-item-wizard-icon",
                    "TITLE" => \GetMessage("STOM_BUTTON_TITLE_W1"),
                    "TEXT" => \GetMessage("STOM_BUTTON_NAME_W1"),
                )
            );

            $GLOBALS["APPLICATION"]->AddPanelButton(array(
                "HREF" => "/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardName=redsign:megamart&wizardSiteID=".SITE_ID."&".bitrix_sessid_get(),
                "ID" => "activelife_wizard",
                "ICON" => "bx-panel-site-wizard-icon",
                "MAIN_SORT" => 2500,
                "TYPE" => "BIG",
                "SORT" => 10,    
                "ALT" => \GetMessage("SCOM_BUTTON_DESCRIPTION"),
                "TEXT" => \GetMessage("SCOM_BUTTON_NAME"),
                "MENU" => $arMenu,
            ));
        }
    }
}
