<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("FLXMD_ORDER_NAME"),
	"DESCRIPTION" => GetMessage("FLXMD_ORDER_DESCRIPTION"),
	"ICON" => "/images/news_list.gif",
	"SORT" => 3,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "flxmd",
		"NAME" => GetMessage("FLXMD_ORDER_SECTION_NAME"),
		"SORT" => 12,
	),
);

?>
