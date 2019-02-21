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
	//$arFilter = Array("ID"=>'102539');//one battery
	$arFilter = Array("ID"=>$v['PRODUCT_ID']);//one battery
	$arSelect = Array("ID", "NAME", "IBLOCK_ID");

	$rsElements = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

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
			//echo "<pre>";
			//print_r("ar_props['VALUE'] _is:" . $ar_props['VALUE']);
			//echo "</pre>";
			if($ar_props['CODE'] == 'CURRENT')
			{
				$PROPS['PROPERTY_CURRENT_USB_VALUE'] = $ar_props['VALUE'];
				//$PROPS[$ar_props['CODE']] = $ar_props['VALUE'];
				//echo "<pre>";
				//print_r("ar_props['VALUE'] _is:" . $ar_props['VALUE']);
				//echo "</pre>";
			}
		}
	}
	//foreach ($PROPS as $prps_key => $prps_value)
	//{
		//echo "<pre>";
		//print_r("$prps_key _is:" . $prps_value);
		//echo "</pre>";
	//}

	$arResult["GRID"]["ROWS"][$k] += $PROPS;

	//foreach ($arResult["GRID"]["ROWS"][$k] as $prps_key => $prps_value)
	//{
		//echo "<pre>";
		//print_r("$prps_key _is:" . $prps_value);
		//echo "</pre>";
	//}
}




//Проверка на то, универсальные или нет
$products = array();
foreach ($arResult['GRID']['ROWS'] as $BASKET_ID => $arItem) 
{
	//echo "<pre>";
	//print_r("$BASKET_ID _is:" . $arItem);
	//echo "</pre>";

	$products[] = $arItem['PRODUCT_ID'];
	foreach ($arItem as $k_in => $v_in)
	{
		//echo "<pre>";
		//print_r("$k_in _is:" . $v_in);
		//echo "</pre>";
	}

}







$rsGroups = CIBlockElement::GetElementGroups($products, true, array('ID', 'CODE', 'IBLOCK_ELEMENT_ID', 'IBLOCK_ID'));

while ($arGroup = $rsGroups->Fetch()) 
{
	$arGroups[$arGroup['IBLOCK_ELEMENT_ID']] = $arGroup;
	//echo "<pre>";
	//print_r("group _is:" . $group);
	//echo "</pre>";
}


foreach ($arGroups as $group) 
{
	//echo "<pre>";
	//print_r("group _is:" . $group);
	//echo "</pre>";

	$db_sects = CIBlockSection::GetNavChain($group['IBLOCK_ID'], $group['ID'], array('ID', 'CODE'));
	while($ar_nav = $db_sects->Fetch()) 
	{
		$arGroups[$group['IBLOCK_ELEMENT_ID']]['CHAIN'][] = $ar_nav;
	}
}

foreach ($arGroups as $prodId => $group) 
{
	//echo "<pre>";
	//print_r("$prodId _is:" . $group);
	//echo "</pre>";

	$is_craftmann = false;
	foreach ($group['CHAIN'] as $section) 
	{
		//echo "<pre>";
		//print_r("section['CODE'] _is:" . $section['CODE']);
		//echo "</pre>";
		if ($section['CODE'] == 'craftmann') 
		{
			$is_craftmann = true;
			break;
		}
	}
	$arGroups[$prodId]['CRAFTMANN'] = $is_craftmann;
}
$arResult['ELEMENT_GROUPS'] = $arGroups;



//if ($arResult['ELEMENT_GROUPS'][$arItem['PRODUCT_ID']]['CRAFTMANN'])
//{
	//$CRAFTMANN = array('PROPERTY_CURRENT_USB_VALUE' => '���� ���� (A)');
	//
	//$arResult['GRID']['CHARACTS_LIST'] += $CRAFTMANN;
//}


foreach ($arResult['GRID']['CHARACTS_LIST'] as $code => $name) 
{
	//echo "<pre>";
	//print_r("$code _is:" . $name);
	//echo "</pre>";

	foreach ($arItem as $k_in => $v_in)
	{
		//echo "<pre>";
		//print_r("$k_in _is:" . $v_in);
		//echo "</pre>";
	}

}






?>
