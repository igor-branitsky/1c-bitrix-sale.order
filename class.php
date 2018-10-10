<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */

/** @global CMain $APPLICATION */


use Bitrix\Main,
	Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Application,
	Bitrix\Sale,
	Bitrix\Sale\Internals,
	Bitrix\Sale\Delivery\ExtraServices,
	Bitrix\Sale\PaySystem,
	Bitrix\Sale\Payment,
	Bitrix\Sale\Shipment;


Loc::loadMessages(__FILE__);

class OrderMake extends \CBitrixComponent {

	public $arRequest = [];

	private $bStopPaymentOnline = false;

	private $arJsonResponse = [];

	private $USER;

	public function executeComponent() {

		Loader::includeModule("sale");
		Loader::includeModule("iblock");
		Loader::includeModule("catalog");

		global $USER;

		$this->USER = $USER;

		$this->arRequest = Application::getInstance()->getContext()->getRequest();

		$this->getUserFields();

		$this->AuthUser();

		$this->getBasket();

		$this->checkBasket();

		$this->getDeliveryOrder();

		$this->getOrderPaySystem();

		$this->includeComponentTemplate();

	}

	public function getDeliveryOrder(){

		$this->arResult['LIST_REGION'] = [
			'brest' => Loc::getMessage("FLXMD_ORDER_SALE_REGION_BREST"),
			'vitebsk' => Loc::getMessage("FLXMD_ORDER_SALE_REGION_VITEBSK"),
			'gomel' => Loc::getMessage("FLXMD_ORDER_SALE_REGION_GOMEL"),
			'grodno' => Loc::getMessage("FLXMD_ORDER_SALE_REGION_GRODNO"),
			'minsk' => Loc::getMessage("FLXMD_ORDER_SALE_REGION_MINSK"),
			'mogilev' => Loc::getMessage("FLXMD_ORDER_SALE_REGION_MOGILEV"),
		];

		$arOrderDelivery = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

		foreach ($arOrderDelivery as $arItem){

			$arStores = ExtraServices\Manager::getStoresList($arItem['ID']);

			if(is_array($arStores) && count($arStores) > 0){
				$dbStore = \Bitrix\Catalog\StoreTable::getList([
					'filter' => [
						'ID' => $arStores,
						'ACTIVE' => 'Y',
					],
					'select' => [
						'ADDRESS',
						'SCHEDULE',
						'UF_PHONE',
						'GPS_N',
						'GPS_S'
					]
				]);
				while($arStore = $dbStore->Fetch()){
					$arItem['STORE'][] = $arStore;
				}
			}

			$this->arResult['DELIVERY_LIST'][] = array(
				'ID' => $arItem['ID'],
				'CODE' => $arItem['CODE'],
				'NAME' => $arItem['NAME'],
				'DESCRIPTION' => $arItem['DESCRIPTION'],
				'STORE' => $arItem['STORE'],
			);
		}
	}

	public function getOrderPaySystem(){

		$dbPaySystem = PaySystem\Manager::getList([
			'select' => [
				'PAY_SYSTEM_ID',
				'NAME',
				'ACTION_FILE',
			],
			'filter' => [
				'ACTIVE' => 'Y',
			]
		]);

		while ($arPaySystem = $dbPaySystem->Fetch()){
			if($this->bStopPaymentOnline && $arPaySystem['ACTION_FILE'] !== 'cash') continue;
			$this->arResult['PAY_SYSTEM'][] = $arPaySystem;
		}

		$this->arResult['NOT_PREPAYMENT'] = $this->bStopPaymentOnline;
	}

	public function getUserFields(){

		$this->arResult['USER_AUTH'] = $this->USER->IsAuthorized();

		if($this->arResult['USER_AUTH']){
			$this->arResult['USER_ID'] = $this->USER->GetID();
			$dbUser = CUser::GetList(
				$by,
				$sort,
				array(
					'ID' => $this->arResult['USER_ID']
				),
				array(
					'SELECT' => array (
						'UF_USER_ADDRESS'
					),
					'FIELDS' => array (
						'EMAIL',
						'PERSONAL_PHONE',
						'NAME',
						'LAST_NAME',
						'SECOND_NAME'
					)
				)
			);
			$arUser = $dbUser->Fetch();

			if(!empty($arUser['UF_USER_ADDRESS']) && is_array($arUser['UF_USER_ADDRESS'])){
				foreach ($arUser['UF_USER_ADDRESS'] as $sAddress){
					$this->arResult['USER_ADDRESS'][] = unserialize($sAddress);
				}
			}

			$this->arResult['USER_EMAIL'] = $arUser['EMAIL'];
			$this->arResult['USER_PHONE'] = $arUser['PERSONAL_PHONE'];
			$this->arResult['USER_FIO'] = $arUser['LAST_NAME'].' '.$arUser['NAME'].' '.$arUser['SECOND_NAME'];
		}
	}

	public function getOrderProps(){

		$dbOrderProps = Internals\OrderPropsTable::getList();

		while($arOrderProp = $dbOrderProps->Fetch()){
			$this->arResult['ORDER_PERSONAL_FIELDS'][$arOrderProp['CODE']] = $arOrderProp['NAME'];
		}

	}

	public function AuthUser(){
		if(
			$this->request->isAjaxRequest() &&
			$this->request->getPost('auth_ajax') === 'Y' &&
			empty($this->request->getPost('qwe'))
		){
			if($this->arResult['USER_AUTH']) {
				$arJsonResponse['AUTH'] = 'Y';
				$arJsonResponse['AUTH_MESSAGE'] = Loc::getMessage("FLXMD_ORDER_SALE_AUTH_ERROR");
			} else {
				$sLogin = htmlspecialchars($this->request->getPost('login'));
				$sPassword = htmlspecialchars($this->request->getPost('password'));
				if(empty($sLogin) || empty($sPassword)) {
					$arJsonResponse['AUTH'] = 'N';
					$arJsonResponse['AUTH_MESSAGE'] = Loc::getMessage("FLXMD_ORDER_SALE_AUTH_NOT_FIELD");
				} else {
					if($this->USER->Login($sLogin, $sPassword, "Y") === true){
						$arJsonResponse['AUTH'] = 'Y';
						$arJsonResponse['AUTH_MESSAGE'] = Loc::getMessage("FLXMD_ORDER_SALE_AUTH_SUCCESS");
					} else {
						$arJsonResponse['AUTH'] = 'N';
						$arJsonResponse['AUTH_MESSAGE'] = Loc::getMessage("FLXMD_ORDER_SALE_AUTH_ERROR_LOGIN_OR_PASS");
					}
				}
			}

			$this->getAjaxResult($arJsonResponse, true);
		}
	}

	public function getAjaxResult($mixContent, $isJson = false){
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		echo $isJson ? json_encode($mixContent) : $mixContent;
		die();
	}

	public function getBasket(){

		$this->arResult['BASKET_LIST'] = [];

		$arProductID = [];
		$arBasketMap = [];
		$arProductSet = [];
		$nBaseSumBasket = 0;
		$nSumBasket = 0;

		$objBasket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
		$this->arResult['BASKET_COUNT'] = count($objBasket->getQuantityList());

		$objBasketCollection = $objBasket->getBasketItems();

		foreach ($objBasketCollection as $arItemCollection) {

			$nBaseSumBasket  += $arItemCollection->getField('QUANTITY') * $arItemCollection->getField('BASE_PRICE');
			$nSumBasket  += $arItemCollection->getFinalPrice();

			$nParentID = null;
			$nProductID = $arItemCollection->getProductId();
			$arProductSet[] = $nProductID;

			$arOfferLink = explode('#', $arItemCollection->getField('PRODUCT_XML_ID'));

			if(in_array($nProductID, $arOfferLink)){
				$nParentID = $arOfferLink['0'];
			} else {
				$nParentID = $arOfferLink['1'];
				$arProductID[] = $nProductID;
			}

			if(!empty($arOfferLink['0']) && !in_array($arOfferLink['0'], $arProductID)){
				$arProductID[] = $arOfferLink['0'];
			}

			if(!empty($arOfferLink['1']) && !in_array($arOfferLink['1'], $arProductID)){
				$arProductID[] = $arOfferLink['1'];
			}

			$arProps = [];
			$objItemProperty = $arItemCollection->getPropertyCollection();
			$arItemProperty = $objItemProperty->getPropertyValues();

			foreach ($arItemProperty as $key => $arProp){
				if(!preg_match('/\.XML_ID/', $key)){
					$arProps[$key] = $arProp;
				}
			}

			$arBasketMap[$nProductID] = array(
				'PARENT_ID' => $nParentID,
				'NAME' => $arItemCollection->getField('NAME'),
				'DETAIL_PAGE_URL' => $arItemCollection->getField('DETAIL_PAGE_URL'),
				'QUANTITY' => $arItemCollection->getQuantity(),
				'SUM' => CCurrencyLang::CurrencyFormat($arItemCollection->getFinalPrice(), "BYN", true),
				'MEASURE_NAME' => $arItemCollection->getField('MEASURE_NAME'),
				'PROPS' => $arProps,
			);
		}

		if(!empty($arProductID)){

			$dbProduct = CIBlockElement::GetList(
				array(),
				array(
					'ID' => $arProductID,
				),
				false,
				false,
				array(
					'ID',
					'IBLOCK_CODE',
					'PREVIEW_PICTURE',
					'CATALOG_QUANTITY',
				)
			);

			while($arProduct = $dbProduct->Fetch()){

				$arImage[$arProduct['ID']] = CFIle::GetFileArray($arProduct['PREVIEW_PICTURE']);

				if($arProduct['CATALOG_QUANTITY'] <= 0 && in_array($arProduct['ID'], $arProductSet)){
					$this->bStopPaymentOnline = true;
				}

				if(isset($arBasketMap[$arProduct['ID']])){
					$arBasketMap[$arProduct['ID']]['CAN_BUY'] = $arProduct['CATALOG_QUANTITY'] > 0;
					$arBasketMap[$arProduct['ID']]['IBLOCK_CODE'] = $arProduct['IBLOCK_CODE'];
				}

			}

		}

		if(!empty($arImage)){
			foreach ($arBasketMap as $key => $arItem){
				if(!empty($arImage[$key]['SRC'])){
					$arBasketMap[$key]['IMAGE'] = $arImage[$key]['SRC'];
				} elseif(!empty($arImage[$arItem['PARENT_ID']]['SRC'])) {
					$arBasketMap[$key]['IMAGE'] = $arImage[$arItem['PARENT_ID']]['SRC'];
				}
			}
		}

		$this->arResult['BASKET_SUM_BASE_PRINT'] = CCurrencyLang::CurrencyFormat($nBaseSumBasket, "BYN", true);
		$this->arResult['BASKET_SUM_PRINT'] = CCurrencyLang::CurrencyFormat($nSumBasket, "BYN", true);
		$this->arResult['BASKET_SUM_SALE_PRINT'] = CCurrencyLang::CurrencyFormat($nBaseSumBasket - $nSumBasket, "BYN", true);

		$this->arResult['BASKET_SUM_BASE'] = $nBaseSumBasket;
		$this->arResult['BASKET_SUM'] = $nSumBasket;
		$this->arResult['BASKET_SUM_SALE'] = $nBaseSumBasket - $nSumBasket;

		$this->arResult['BASKET_LIST'] = $arBasketMap;
	}

	public function checkBasket(){
		if(empty($this->arResult['BASKET_LIST'])){
			LocalRedirect('/basket/', false,'301 moved permanently');
			exit();
		}
	}
}

?>
