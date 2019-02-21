<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix;
use Bitrix\Main\Loader;
Loader::includeModule("iblock");

$headers = $arResult['GRID']['HEADERS'];
$newHeaders1 = $newHeaders2 = $charactsList = array();

foreach ($headers as $i => $header) {
	if ($header['id'] == 'PROPERTY_ARTICLE_VALUE') {
		array_unshift($newHeaders1, $header);
	} elseif ($header['id'] == 'NAME') {
		$newHeaders1[] = $header;
	} elseif (strpos($header['id'], 'PROPERTY_') !== false) {
		$charactsList[$header['id']] = $header['name'];
	} elseif (in_array($header['id'], array('PRICE', 'QUANTITY', 'SUM'))) {
		$newHeaders2[] = $header;
	}
}
$newHeaders2[] = array('id' => 'DELETE', 'name' => '');

$arResult['GRID']['HEADERS'] = array_merge($newHeaders1, $newHeaders2);

//Cортировка характеристик
$propsToSort = array('CAPACITY', 'WARRANTY', 'CERTIFICATION');
foreach ($propsToSort as $code) {
	$arResult['GRID']['CHARACTS_LIST']['PROPERTY_' . $code . '_VALUE'] = $charactsList['PROPERTY_' . $code . '_VALUE'];
	unset($charactsList['PROPERTY_' . $code . '_VALUE']);
}
$arResult['GRID']['CHARACTS_LIST'] = array_merge($arResult['GRID']['CHARACTS_LIST'], $charactsList);



//Adding CURRENT to properties
foreach ($arResult["GRID"]["ROWS"] as $k => $v)
{
	echo "<pre>";
	//print_r("$k _is:" . $v['PRODUCT_ID']);
	print_r("$k _is:" . $v);
	echo "</pre>";
	foreach ($v as $k_in => $v_in)
	{
		//echo "<pre>";
		//print_r("$k_in _is:" . $v_in);
		//echo "</pre>";
	}
	//$arFilter = Array("ID"=>'102539');//one battery
	$arFilter = Array("ID"=>$v['PRODUCT_ID']);//one battery
	$arSelect = Array("ID", "NAME", "IBLOCK_ID");

	$rsElements = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

	//echo "<pre>";
	//print_r("rsElements _is:" . $rsElements);
	//echo "</pre>";

	while($arElement = $rsElements->GetNextElement())
	{
		$arFields = $arElement->GetFields();
		$arFields_res = array();
		$arFields_res["ID"] = $arFields["ID"];
		$arFields_res["NAME"] = $arFields["NAME"];
		$arFields_res["IBLOCK_ID"] = $arFields["IBLOCK_ID"];
		$element_props = \CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array());

		$PROPS = array();
		while ($ar_props = $element_props->Fetch())
		{
			if($ar_props['CODE'] == 'CURRENT')
			{
 				$PROPS[$ar_props['CODE']] = $ar_props['VALUE'];
			}
		}
	}

	$arResult["GRID"]["ROWS"][$k] += $PROPS;

	//echo "<pre>";
	//print_r("PROPS _is:" . $PROPS);
	//echo "</pre>";

	//foreach($PROPS as $key => $value)
		//foreach($PROPS as $item)
		//{
		//echo "<pre>";
		//print_r("PROPS _is:" . $item);
		//print_r("$PROPS_kk _is:" . $PROPS_kk);
		//echo "</pre>";
		//}

		//foreach($arFields_res as $kk => $vv)
		//{
		//echo "<pre>";
		//print_r(" _is:" . $arFields_res["CURRENT"]);
		//print_r(" _is:" . $arFields_res);
		//print_r(" _is:" . $vv);
		//echo "</pre>";
		//}


	//foreach ($v as $k_in => $v_in)
	//{
		//echo "<pre>";
		//print_r("$k_in _is:" . $v_in);
		//echo "</pre>";
	//}
}




//Проверка на то, универсальные или нет
$products = array();
foreach ($arResult['GRID']['ROWS'] as $BASKET_ID => $arItem) 
{
	$products[] = $arItem['PRODUCT_ID'];
	foreach ($arItem as $k_in => $v_in)
	{
		echo "<pre>";
		print_r("$k_in _is:" . $v_in);
		echo "</pre>";
	}

}


$rsGroups = CIBlockElement::GetElementGroups($products, true, array('ID', 'CODE', 'IBLOCK_ELEMENT_ID', 'IBLOCK_ID'));

while ($arGroup = $rsGroups->Fetch()) 
{
	$arGroups[$arGroup['IBLOCK_ELEMENT_ID']] = $arGroup;
}


foreach ($arGroups as $group) 
{
	$db_sects = CIBlockSection::GetNavChain($group['IBLOCK_ID'], $group['ID'], array('ID', 'CODE'));
	while($ar_nav = $db_sects->Fetch()) 
	{
		$arGroups[$group['IBLOCK_ELEMENT_ID']]['CHAIN'][] = $ar_nav;
	}
}

foreach ($arGroups as $prodId => $group) 
{
	$is_craftmann = false;
	foreach ($group['CHAIN'] as $section) {
		if ($section['CODE'] == 'craftmann') {
			$is_craftmann = true;
			break;
		}
	}
	$arGroups[$prodId]['CRAFTMANN'] = $is_craftmann;
}
$arResult['ELEMENT_GROUPS'] = $arGroups;
?>
