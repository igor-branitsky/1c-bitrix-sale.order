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
	Bitrix\Main\Application;


Loc::loadMessages(__FILE__);

class OrderMake extends \CBitrixComponent {

	public $arRequest = [];

	public $nUserID;

	public $isAuth;

	public function executeComponent() {

		global $USER;

		$this->arRequest = Application::getInstance()->getContext()->getRequest();

		$this->nUserID = $USER->GetID();

		$this->isAuth = $USER->IsAuthorized();

		$this->includeComponentTemplate();

	}

	public function getBasket(){

		$dbBasket = CSaleBasket::GetList(
			array(),
			array(
				"FUSER_ID" => CSaleBasket::GetBasketUserID(),
				"LID" => SITE_ID,
				"ORDER_ID" => "NULL"
			),
			array()
		);

		while($arBasket = $dbBasket->Fetch()){
			$this->arResult['BASKET_LIST'][] = $arBasket;
		}

	}
}

?>
