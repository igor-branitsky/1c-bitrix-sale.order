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
	Bitrix\Sale;


Loc::loadMessages(__FILE__);

class OrderMake extends \CBitrixComponent {

	public $arRequest = [];

	private $bStopPaymentOnline = false;

	public function executeComponent() {

		Loader::includeModule("sale");
		Loader::includeModule("iblock");

		global $USER;

		$this->arRequest = Application::getInstance()->getContext()->getRequest();

		$this->arResult['USER_ID'] = $USER->GetID();

		$this->arResult['USER_AUTH'] = $USER->IsAuthorized();

		$this->getBasket();

		$this->checkBasket();

		$this->includeComponentTemplate();

	}

	public function getBasket(){

		$this->arResult['BASKET_LIST'] = [];

		$arProductID = [];
		$arBasketMap = [];
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

				if($arProduct['CATALOG_QUANTITY'] <= 0){
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
