<?php

namespace Redsign\DevFunc\Controller;

use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\Error;
use Bitrix\Main\Web\Uri;

class Location extends Controller
{
	protected function init()
	{
		parent::init();
	}

	public function configureActions()
	{
		return [
			'setLocation' => [
				'prefilters' => [
					new Csrf()
				]
			]
		];
	}

	public function setLocationAction(string $locationId)
	{
		\Redsign\DevFunc\Sale\Location\Location::setMyCity($locationId);
		$result['id'] = $locationId;

		$arRegions = \Redsign\DevFunc\Sale\Location\Region::getRegions();
		$arRegionCurrent = [];

		if (is_array($arRegions) && count($arRegions) > 0) 
		{
			$context = Context::getCurrent();
			$server = $context->getServer();
			
			foreach ($arRegions as $arRegion)
			{
				if ($locationId == $arRegion['LOCATION_ID'])
				{
					$arRegionCurrent = $arRegion;
					break;
				}
			}
			unset($arRegion);

			if (empty($arRegionCurrent))
				$arRegionCurrent = \Redsign\DevFunc\Sale\Location\Region::getDefaultRegion();

			if (!empty($arRegionCurrent)) 
			{
				\Redsign\DevFunc\Sale\Location\Region::set($arRegionCurrent);
				if (
					is_array($arRegionCurrent['LIST_DOMAINS']) && count($arRegionCurrent['LIST_DOMAINS']) > 0
					&& !in_array($server->getServerName(), $arRegionCurrent['LIST_DOMAINS'])
				)
				{
					if (strlen($this->request->get('backurl')) > 0)
						$uri = new Uri($this->request->get('backurl'));
					else
						$uri = new Uri('/');
					$uri->setHost(reset($arRegionCurrent['LIST_DOMAINS']));
					$result['redirect'] = $uri->getUri();
				}
            }
        }
		
		return $result;
	}
}