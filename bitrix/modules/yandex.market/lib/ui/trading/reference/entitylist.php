<?php

namespace Yandex\Market\Ui\Trading\Reference;

use Bitrix\Main;
use Yandex\Market;
use Yandex\Market\Trading\Service as TradingService;

abstract class EntityList extends Market\Ui\Reference\Page
{
	use Market\Reference\Concerns\HasMessage;
	use Market\Ui\Trading\Concerns\HasHandleMigration;

	protected $serviceCode;

	protected function getReadRights()
	{
		return Market\Ui\Access::RIGHTS_PROCESS_TRADING;
	}

	/** @return string */
	abstract protected function getTargetEntity();

	public function show()
	{
		$setupCollection = $this->getSetupCollection();
		$setupId = $this->getRequestSetupId() ?: $this->getStoredSetupId();

		try
		{
			$setup = $this->resolveSetup($setupCollection, $setupId);

			$this->showSetupSelector($setupCollection, $setup->getId());
			$this->showGrid($setup);

			$this->setStoredSetupId($setup->getId());
		}
		catch (Main\ObjectException $exception)
		{
			$this->showSetupSelector($setupCollection, $setupId, true);
			$this->showError($exception->getMessage());
		}
		catch (Main\ObjectNotFoundException $exception)
		{
			if ($this->getRequestSetupId() === null && $this->getStoredSetupId() === $setupId)
			{
				$this->resetStoredSetupId();
			}

			$this->showSetupSelector($setupCollection, $setupId, true);
			$this->showError($exception->getMessage());
		}
	}

	public function handleException(\Exception $exception)
	{
		$isHandled = (
			$this->handleMigration($exception)
			|| $this->handleDeprecated($exception)
		);

		if (!$isHandled)
		{
			$this->showError($exception->getMessage());
		}
	}

	protected function showError($message)
	{
		\CAdminMessage::ShowMessage([
			'TYPE' => 'ERROR',
			'MESSAGE' => $message,
		]);
	}

	protected function showSetupSelector(Market\Trading\Setup\Collection $setupCollection, $selectedId = null, $force = false)
	{
		$options = $this->buildRoleOptions($setupCollection);
		$showLimit = $force ? 0 : 1;

		if (count($options) <= $showLimit) { return; }

		$usedBehaviors = array_column($options, 'BEHAVIOR');
		$usedSites = array_unique(array_column($options, 'SITE_ID'));
		$useOnlyGroup = true;

		if (count($usedBehaviors) !== count(array_unique($usedBehaviors)))
		{
			$useOnlyGroup = false;
		}
		else if (count($usedSites) > 1)
		{
			$useOnlyGroup = false;
		}

		$options = array_map(static function(array $option) use ($useOnlyGroup, $selectedId) {
			global $APPLICATION;

			if ($useOnlyGroup) { $option['VALUE'] = $option['GROUP']; }

			return $option + [
				'SELECTED' => $option['ID'] === (int)$selectedId,
				'URL' => $APPLICATION->GetCurPageParam(http_build_query([ 'setup' => $option['ID'] ]), [ 'setup' ]),
			];
		}, $options);

		if (Market\Utils\BitrixTemplate::isBitrix24())
		{
			$this->renderCrmSetupSelector($options);
		}
		else
		{
			$this->renderAdminSetupSelector($options);
		}
	}

	protected function renderCrmSetupSelector(array $options)
	{
		global $APPLICATION;

		$selectedOptions = array_filter($options, static function(array $option) { return $option['SELECTED']; });
		$selectedOption = reset($selectedOptions);
		$dropdownItems = array_map(static function(array $option) {
			return [
				'text' => $option['VALUE'],
				'link' => $option['URL'],
				'selected' => $option['SELECTED'],
			];
		}, $options);
		$dropdownItems = array_filter($dropdownItems, static function(array $item) { return !$item['selected']; });
		$dropdownItems = array_values($dropdownItems);

		$html = sprintf(
			'<div class="crm-interface-toolbar-button-container">
				<button class="ui-btn ui-btn-dropdown ui-btn-light-border" type="button" id="yamarket-setup-selector">
					%s
				</button>
			</div>',
			$selectedOption !== false ? $selectedOption['VALUE'] : 'TRADING BEHAVIOR'
		);
		$html .= sprintf(
			'<script>
				BX.ready(function() {
					const button = BX("yamarket-setup-selector");
					const items = JSON.parse(\'%s\');
					
					if (!button || !items) { return; }
					
					items.forEach(function(item) {
						item.onclick = function() { window.location.href = item.link; };
					});
					
					const menu = new BX.PopupMenuWindow({
						bindElement: button,
						items: items,
					});
			
					button.addEventListener("click", function() { menu.show(); });
				});
			</script>',
			Main\Web\Json::encode($dropdownItems)
		);

		$APPLICATION->AddViewContent('inside_pagetitle', $html);
	}

	protected function renderAdminSetupSelector(array $options)
	{
		global $APPLICATION;

		echo '<div style="margin-bottom: 10px;">';

		foreach ($options as $option)
		{
			if ($option['SELECTED'])
			{
				echo sprintf(
					' <span class="adm-btn adm-btn-active">%s</span>',
					htmlspecialcharsbx($option['VALUE'])
				);
			}
			else
			{
				$url = $APPLICATION->GetCurPageParam(http_build_query([ 'setup' => $option['ID'] ]), [ 'setup' ]);

				echo sprintf(
					' <a class="adm-btn" href="%s">%s</a>',
					htmlspecialcharsbx($url),
					htmlspecialcharsbx($option['VALUE'])
				);
			}
		}

		echo '</div>';
	}

	protected function buildRoleOptions(Market\Trading\Setup\Collection $setupCollection)
	{
		$result = [];
		$usedBehaviors = [];

		/** @var Market\Trading\Setup\Model $setup */
		foreach ($setupCollection as $setup)
		{
			if (!$setup->isActive()) { continue; }

			$siteId = $setup->getSiteId();
			$service = $setup->getService();
			$behaviorCode = $service->getBehaviorCode();
			$behaviorTitle = $setup->getService()->getInfo()->getTitle('BEHAVIOR');
			$title = $setup->getField('NAME');

			$usedBehaviors[$behaviorCode] = true;

			if ($title === $setup->getDefaultName())
			{
				$siteEntity = $setup->getEnvironment()->getSite();
				$title = sprintf('[%s] %s (%s)', $siteId, $siteEntity->getTitle($siteId), $behaviorTitle);
			}

			$result[] = [
				'ID' => (int)$setup->getId(),
				'VALUE' => $title,
				'BEHAVIOR' => $behaviorCode,
				'SITE_ID' => $siteId,
				'GROUP' => $behaviorTitle,
			];
		}

		if (count($usedBehaviors) > 1)
		{
			$result = $this->sortRoleOptionsByBehavior($result);
		}

		return $result;
	}

	protected function sortRoleOptionsByBehavior($options)
	{
		$serviceCode = $this->getServiceCode();
		$behaviors = Market\Trading\Service\Manager::getBehaviors($serviceCode);
		$behaviorsSort = array_flip($behaviors);

		uasort($options, static function($optionA, $optionB) use ($behaviorsSort) {
			$sortA = isset($behaviorsSort[$optionA['BEHAVIOR']]) ? $behaviorsSort[$optionA['BEHAVIOR']] : 500;
			$sortB = isset($behaviorsSort[$optionB['BEHAVIOR']]) ? $behaviorsSort[$optionB['BEHAVIOR']] : 500;

			if ($sortA === $sortB) { return 0; }

			return $sortA < $sortB ? -1 : 1;
		});

		return $options;
	}

	abstract protected function showGrid(Market\Trading\Setup\Model $setup);

	abstract protected function getGridId();

	protected function initializePrintActions(Market\Trading\Setup\Model $setup, $documents)
	{
		if (empty($documents)) { return; }

		self::includeSelfMessages();

		Market\Ui\Library::load('jquery');

		Market\Ui\Assets::loadPluginCore();
		Market\Ui\Assets::loadPlugins([
			'lib.dialog',
			'lib.printdialog',
			'OrderList.DialogAction',
			'OrderList.Print',
		]);

		Market\Ui\Assets::loadMessages([
			'PRINT_DIALOG_SUBMIT',
		]);

		$this->addDialogActionsScript('Print', [
			'url' => Market\Ui\Admin\Path::getModuleUrl('trading_order_print', [
				'view' => 'dialog',
				'setup' => $setup->getId(),
				'alone' => 'Y',
			]),
			'items' => $this->getPrintItems($documents),
			'lang' => [
				'REQUIRE_SELECT_ORDERS' => static::getMessage('PRINT_REQUIRE_SELECT_ORDERS'),
			],
		]);
	}

	protected function initializeActivityActions(Market\Trading\Setup\Model $setup, $activities)
	{
		if (empty($activities)) { return; }

		Market\Ui\Library::load('jquery');

		Market\Ui\Assets::loadPluginCore();
		Market\Ui\Assets::loadPlugins([
			'lib.dialog',
			'Ui.ModalForm',
			'OrderList.DialogAction',
			'OrderList.Activity',
		]);

		$this->addDialogActionsScript('Activity', [
			'url' => Market\Ui\Admin\Path::getModuleUrl('trading_order_activity', [
				'view' => 'dialog',
				'setup' => $setup->getId(),
				'alone' => 'Y',
			]),
			'items' => $this->getActivityItems($activities),
			'lang' => [
				'ACTIVITY_SUBMIT' => static::getMessage('ACTIVITY_SUBMIT'),
			],
		]);
	}

	protected function addDialogActionsScript($type, array $parameters)
	{
		$pageAssets = Main\Page\Asset::getInstance();
		$contents = sprintf(
			'<script>
				BX.YandexMarket.OrderList["%s"] = new BX.YandexMarket.OrderList.%s(null, ' . \CUtil::PhpToJSObject($parameters) . ');
			</script>',
			Market\Data\TextString::toLower($type),
			$type
		);

		$pageAssets->addString($contents, false, Main\Page\AssetLocation::AFTER_JS);
	}

	/**
	 * @param Market\Trading\Service\Reference\Document\AbstractDocument[] $documents
	 *
	 * @return array
	 */
	protected function getPrintItems($documents)
	{
		$result = [];

		foreach ($documents as $type => $document)
		{
			$result[] = [
				'TYPE' => $type,
				'TITLE' => $document->getTitle(),
			];
		}

		return $result;
	}

	protected function getActivityItems($activities)
	{
		$result = [];

		foreach ($activities as $path => $activity)
		{
			$items = $this->makeActivityItems($path, $activity);

			if (empty($items)) { continue; }

			array_push($result, ...$items);
		}

		return $result;
	}

	protected function makeActivityItems($path, Market\Trading\Service\Reference\Action\AbstractActivity $activity, $chain = '')
	{
		$result = [];

		if ($activity instanceof Market\Trading\Service\Reference\Action\ComplexActivity)
		{
			foreach ($activity->getActivities() as $key => $child)
			{
				$childChain = ($chain !== '' ? $chain . '.' . $key : $key);
				$childItems = $this->makeActivityItems($path, $child, $childChain);

				if (empty($childItems)) { continue; }

				array_push($result, ...$childItems);
			}
		}
		else
		{
			$result[] = [
				'TYPE' => $path . ($chain !== '' ? '|' . $chain : ''),
				'TITLE' => $activity->getTitle(),
			];
		}

		return $result;
	}

	/**
	 * @param Market\Trading\Setup\Model $setup
	 * @param Market\Trading\Service\Reference\Document\AbstractDocument[] $documents
	 * @param Market\Trading\Service\Reference\Action\AbstractActivity[] $activities
	 *
	 * @return array
	 */
	protected function getOrderListRowActions(Market\Trading\Setup\Model $setup, $documents, $activities)
	{
		return
			$this->getOrderListRowActivityActions($setup, $activities)
			+ $this->getOrderListRowPrintActions($setup, $documents);
	}

	/**
	 * @param Market\Trading\Setup\Model $setup
	 * @param Market\Trading\Service\Reference\Document\AbstractDocument[] $documents
	 *
	 * @return array
	 */
	protected function getOrderListGroupActions(Market\Trading\Setup\Model $setup, $documents)
	{
		return $this->getOrderListGroupPrintActions($documents);
	}

	/**
	 * @param Market\Trading\Setup\Model $setup
	 * @param Market\Trading\Service\Reference\Action\AbstractActivity[] $activities
	 *
	 * @return array
	 */
	protected function getOrderListRowActivityActions(Market\Trading\Setup\Model $setup, $activities)
	{
		$result = [];

		foreach ($activities as $path => $activity)
		{
			$code = 'ACTIVITY_' . Market\Data\TextString::toUpper(str_replace('/', '_', $path));

			$result[$code] = $this->makeOrderListRowActivityAction($path, $activity);
		}

		return $result;
	}

	protected function makeOrderListRowActivityAction($path, Market\Trading\Service\Reference\Action\AbstractActivity $activity, $chain = '')
	{
		$result = [
			'TEXT' => $activity->getTitle(),
			'FILTER' => $activity->getFilter(),
		];

		if ($activity instanceof Market\Trading\Service\Reference\Action\ComplexActivity)
		{
			$result['MENU'] = [];

			foreach ($activity->getActivities() as $key => $child)
			{
				$childChain = ($chain !== '' ? $chain . '.' . $key : $key);

				$result['MENU'][] = $this->makeOrderListRowActivityAction($path, $child, $childChain);
			}
		}
		else if ($activity instanceof Market\Trading\Service\Reference\Action\CommandActivity)
		{
			$type = $path . ($chain !== '' ? '|' . $chain : '');

			$result['METHOD'] = sprintf(
				'BX.YandexMarket.OrderList.activity.executeCommand("%s", "#ID#", %s)',
				$type,
				$this->getGridId()
			);
			$result += $activity->getParameters(); // confirm and etc
		}
		else if ($activity instanceof Market\Trading\Service\Reference\Action\FormActivity)
		{
			$type = $path . ($chain !== '' ? '|' . $chain : '');

			$result['METHOD'] = sprintf(
				'BX.YandexMarket.OrderList.activity.openDialog("%s", "#ID#")',
				$type
			);
		}

		return $result;
	}

	/**
	 * @param Market\Trading\Setup\Model $setup
	 * @param Market\Trading\Service\Reference\Document\AbstractDocument[] $documents
	 *
	 * @return array
	 */
	protected function getOrderListRowPrintActions(Market\Trading\Setup\Model $setup, $documents)
	{
		$menu = [];

		foreach ($documents as $type => $document)
		{
			$key = 'PRINT_' . Market\Data\TextString::toUpper($type);

			$menu[$key] = [
				'TEXT' => $document->getTitle('PRINT'),
				'METHOD' => 'BX.YandexMarket.OrderList.print.openDialog("' .  $type .  '", "#ID#")',
			];
		}

		return [
			'PRINT' => [
				'TEXT' => self::getMessage('ACTION_PRINT'),
				'MENU' => $menu,
			],
		];
	}

	/**
	 * @param Market\Trading\Service\Reference\Document\AbstractDocument[] $documents
	 *
	 * @return array
	 */
	protected function getOrderListGroupPrintActions($documents)
	{
		$result = [];

		foreach ($documents as $type => $document)
		{
			$key = 'PRINT_' . Market\Data\TextString::toUpper($type);
			$needSelectOrders = $document->getEntityType() !== Market\Trading\Entity\Registry::ENTITY_TYPE_NONE;

			if ($needSelectOrders)
			{
				$action = sprintf(
					'BX.YandexMarket.OrderList.print.openGroupDialog("%s", %s)',
					$type,
					$this->getGridId()
				);
			}
			else
			{
				$action = sprintf('BX.YandexMarket.OrderList.print.openDialog("%s")', $type);
			}

			$result[$key] = [
				'type' => 'button',
				'value' => $key,
				'name' => $document->getTitle('PRINT'),
				'action' => $action,
			];
		}

		return $result;
	}

	protected function getServiceActivities(Market\Trading\Setup\Model $setup)
	{
		$router = $setup->getService()->getRouter();
		$environment = $setup->getEnvironment();
		$pageTargetEntity = $this->getTargetEntity();
		$result = [];

		foreach ($router->getMap() as $path => $actionClass)
		{
			if (!$router->hasDataAction($path)) { continue; }

			$action = $router->getDataAction($path, $environment);

			if (!($action instanceof Market\Trading\Service\Reference\Action\HasActivity)) { continue; }

			$activity = $action->getActivity();

			if ($activity->getSourceType() !== $pageTargetEntity) { continue; }

			$result[$path] = $activity;
		}

		return $result;
	}

	protected function getPrintDocuments(Market\Trading\Setup\Model $setup)
	{
		$printer = $setup->getService()->getPrinter();
		$pageTargetEntity = $this->getTargetEntity();
		$result = [];

		foreach ($printer->getTypes() as $type)
		{
			$document = $printer->getDocument($type);

			if ($document->getSourceType() !== $pageTargetEntity) { continue; }

			$result[$type] = $document;
		}

		return $result;
	}

	protected function getComponentBaseUrl(Market\Trading\Setup\Model $setup)
	{
		global $APPLICATION;

		$queryParameters = array_filter([
			'lang' => LANGUAGE_ID,
			'service' => $setup->getServiceCode(),
			'id' => $setup->getId(),
		]);

		return $APPLICATION->GetCurPage() . '?' . http_build_query($queryParameters);
	}

	protected function getSetupCollection()
	{
		$serviceCode = $this->getServiceCode();

		return Market\Trading\Setup\Collection::loadByFilter([
			'filter' => [
				'=TRADING_SERVICE' => $serviceCode,
			],
		]);
	}

	public function setServiceCode($serviceCode)
	{
		$this->serviceCode = $serviceCode;
	}

	protected function getServiceCode()
	{
		if ($this->serviceCode === null)
		{
			$this->serviceCode = $this->resolveServiceCode();
		}

		return $this->serviceCode;
	}

	protected function resolveServiceCode()
	{
		$result = (string)$this->request->get('service');

		if ($result === '')
		{
			$message = self::getMessage('SERVICE_CODE_NOT_SET');
			throw new Main\ArgumentException($message, 'service');
		}

		if (!Market\Trading\Service\Manager::isExists($result))
		{
			$message = self::getMessage('SERVICE_CODE_INVALID', [ '#SERVICE#' => $result ]);
			throw new Main\SystemException($message);
		}

		return $result;
	}

	protected function getRequestSetupId()
	{
		return $this->request->get('setup');
	}

	protected function getStoredSetupId()
	{
		global $USER;

		$userId = ($USER instanceof \CUser ? (int)$USER->GetID() : 0);
		$category = $this->getUserOptionCategory();
		$option = (string)\CUserOptions::GetOption($category, 'setup_id', null, $userId);

		return $option !== '' ? (int)$option : null;
	}

	protected function setStoredSetupId($setupId)
	{
		global $USER;

		$userId = ($USER instanceof \CUser ? (int)$USER->GetID() : 0);
		$category = $this->getUserOptionCategory();

		if ((string)$this->getStoredSetupId() !== (string)$setupId)
		{
			\CUserOptions::SetOption($category, 'setup_id', $setupId, false, $userId);
		}
	}

	protected function resetStoredSetupId()
	{
		global $USER;

		$userId = ($USER instanceof \CUser ? (int)$USER->GetID() : 0);
		$category = $this->getUserOptionCategory();

		\CUserOptions::DeleteOption($category, 'setup_id', false, $userId);
	}

	/** @return string */
	abstract protected function getUserOptionCategory();

	/**
	 * @param Market\Trading\Setup\Collection $setupCollection
	 * @param int|null $setupId
	 *
	 * @return Market\Trading\Setup\Model
	 * @throws Main\SystemException
	 */
	protected function resolveSetup(Market\Trading\Setup\Collection $setupCollection, $setupId = null)
	{
		if ($setupId !== null)
		{
			$setup = $setupCollection->getItemById($setupId);

			if ($setup === null)
			{
				$message = self::getMessage('SETUP_NOT_FOUND', [ '#ID#' => $setupId ]);
				throw new Main\ObjectNotFoundException($message);
			}

			if (!$setup->isActive())
			{
				$message = self::getMessage('SETUP_INACTIVE', [ '#ID#' => $setupId ]);
				throw new Main\ObjectException($message);
			}
		}
		else
		{
			$setup = $setupCollection->getActive();

			if ($setup === null)
			{
				$message = self::getMessage('SETUP_NOT_EXISTS');
				throw new Main\ObjectNotFoundException($message);
			}
		}

		return $setup;
	}
}