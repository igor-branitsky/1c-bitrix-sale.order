<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arCurrentValues */

use Bitrix\Iblock;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;

if (!Loader::includeModule('iblock')) return;


$arComponentParameters = [
	'GROUPS'     => [],
	'PARAMETERS' => [
	]
];

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
