<?php

namespace Yandex\Market\Trading\Entity\SaleCrm;

use Yandex\Market;
use Bitrix\Main;

class Environment extends Market\Trading\Entity\Sale\Environment
{
	public function isSupported()
	{
		return parent::isSupported() && $this->hasCrmModule();
	}

	protected function hasCrmModule()
	{
		return (
			Main\ModuleManager::isModuleInstalled('crm')
			&& Main\ModuleManager::isModuleInstalled('intranet')
		);
	}

	protected function createAdminExtension()
	{
		return new AdminExtension($this);
	}

	protected function createOrderRegistry()
	{
		return new OrderRegistry($this);
	}

	protected function createListener()
	{
		return new Listener($this);
	}
}