<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
echo ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;

if ($normalCount > 0):
?>
<div id="basket_items_list">
	<div class="bx_ordercart_order_table_container">
		<table id="basket_items">
			<thead>
				<tr>
					<td class="margin"></td>
					<?

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//print_r($arResult["GRID"]["HEADERS"]);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

list($arResult["GRID"]["HEADERS"][3], $arResult["GRID"]["HEADERS"][4]) = array($arResult["GRID"]["HEADERS"][4], $arResult["GRID"]["HEADERS"][3]);
					foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

						$arHeaders[] = $arHeader["id"];

						if (in_array($arHeader["id"], array("TYPE"))) // some header columns are shown differently
						{
							continue;
						}
						elseif ($arHeader["id"] == "PROPS")
						{
							$bPropsColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "DELAY")
						{
							$bDelayColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "DELETE")
						{
							$bDeleteColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "WEIGHT")
						{
							$bWeightColumn = true;
						}

						if ($arHeader["id"] == "NAME"):
						?>
							<td class="item" colspan="2" id="col_<?=getColumnId($arHeader)?>">
						<?
						elseif ($arHeader["id"] == "PRICE"):
						?>
							<td class="price" id="col_<?=getColumnId($arHeader)?>">
						<?
						else:
						?>
							<td class="custom" id="col_<?=getColumnId($arHeader)?>">
						<?
						endif;
						?>
							<?=getColumnName($arHeader)?>
							</td>
					<?
					endforeach;

					if ($bDeleteColumn || $bDelayColumn):
					?>
						<td class="custom"></td>
					<?
					endif;
					?>
						<td class="margin"></td>
				</tr>
			</thead>

			<tbody>











				<?
				foreach ($arResult["GRID"]["ROWS"] as $k => $v)
				{

					echo "<pre>";
					//print_r("$k _is:" . $v);
					echo "</pre>";

					foreach ($v as $k_in => $v_in)
					{
						echo "<pre>";
						//print_r("$k_in _is:" . $v_in);
						echo "</pre>";
					}
				}








				foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):

					if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
				?>
					<tr id="<?=$arItem["ID"]?>">
						<td class="margin"></td>
						<?
						foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

							if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE"))) // some values are not shown in columns in this template
								continue;

								if ($arHeader["id"] == "NAME"):
							?>
								<td class="itemphoto" style="width: 135px; height: 120px;">
									<div class="bx_ordercart_photo_container">
										<?
										if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
											$url = $arItem["PREVIEW_PICTURE_SRC"];
										elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
											$url = $arItem["DETAIL_PICTURE_SRC"];
										else:
											$url = $templateFolder."/images/no_photo.png";
										endif;
										?>

										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><!--<a href="<?=$arItem["DETAIL_PAGE_URL"] ?>">--><?endif;?>
											<!-- <div class="bx_ordercart_photo" style="background-image:url('<?=$url?>')"></div> -->
											<div class="bx_ordercart_photo" style="text-align: center;"><img style="margin: 0px;" title="Аккумулятор <?=$arItem["NAME"]?>" alt="Аккумулятор <?=$arItem["NAME"]?> производства CRAFTMANN" src="<?=$url?>"></div>
										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><!--</a>--><?endif;?>
									</div>

									<?
									if (!empty($arItem["BRAND"])):
									?>
									<div class="bx_ordercart_brand">
										<img alt="" src="<?=$arItem["BRAND"]?>" />
									</div>
									<?
									endif;
									?>
								</td>

								<td class="item">
									<h2 class="bx_ordercart_itemtitle">
<? 
if (0)//($_GET['id'])
{
	print_r('id');

	print_r($_GET['id']);
	$ids = explode(";", $_GET['id']);
	//$temp_my_get_name_param = substr($_GET['name'], 16, (strlen($_GET['name'])-1));
	$temp_my_get_name_param = $_GET['name'];
	$temp_my_get_name_param = str_replace('plus', '+', $temp_my_get_name_param);
	//print_r($temp_my_get_name_param);
	$My_detail_page_url = "/akkumulyator/".strtolower(str_replace(' ', '_', trim($temp_my_get_name_param)))."/";
	$My_new_arItemName = "Аккумулятор для ".$temp_my_get_name_param;

	foreach ($ids as $id)
		{
			if($id == $arItem['PRODUCT_ID'])

				{
					$My_temp_arItems = CSaleBasket::GetByID($arItem['ID']);
					$My_update_arFields = array
					(
						"PRODUCT_PROVIDER_CLASS" => $My_new_arItemName,
						"DETAIL_PAGE_URL" => $My_detail_page_url
					);

					CSaleBasket::Update($arItem['ID'], $My_update_arFields);
				}
		}
}


$arBasketItems = array();

$dbBasketItems = CSaleBasket::GetList(
        array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
        array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
        false,
        false,
        array("ID", "NAME", "PRODUCT_PROVIDER_CLASS", "DETAIL_PAGE_URL", "CALLBACK_FUNC", "MODULE", 
              "PRODUCT_ID", "QUANTITY", "DELAY", 
              "CAN_BUY", "PRICE", "WEIGHT")
    );
while ($arItems = $dbBasketItems->Fetch())
{
    $arBasketItems[] = $arItems;
}

// Печатаем массив, содержащий актуальную на текущий момент корзину
//echo "<pre>";
//print_r($arBasketItems);
//echo "</pre>";

foreach ($arBasketItems as $arBasketItem)
{

	//if(($arBasketItem['NAME'] == $arItem['NAME']) &&($arBasketItem['BASKET_ID'] == $arItem['ID']))
	if($arBasketItem['NAME'] == $arItem['NAME']) 
	{
		$db_res = CSaleBasket::GetPropsList
		(
        	array(),
        	array("BASKET_ID" => $arBasketItem['ID'])
    	);

		while ($ar_res = $db_res->Fetch())
		{
			/*
			echo "<pre>";
			print_r($ar_res["NAME"]);
			echo "</pre>";
			echo "<pre>";
			print_r($ar_res["VALUE"]);
			echo "</pre>";
			*/

			//echo "<pre>";
			//print_r($ar_res);
			//echo "</pre>";

			//echo "<pre>";
			//print_r($arBasketItem);
			//echo "</pre>";

			//echo "<pre>";
			//print_r($arItem['PROPS'][0]['VALUE']);
			//echo "</pre>";




			//if($ar_res["NAME"] == 'DEVICE')
				//{
				//print_r($ar_res["VALUE"]);
				//if ($arItem['PROPS'][0]['VALUE'] == $ar_res["VALUE"])
				//{
					//$temp_my_get_name_param = $ar_res["VALUE"];
					$temp_my_get_name_param = $arItem['PROPS'][0]['VALUE'];
					$temp_my_get_name_param = str_replace('plus', '+', $temp_my_get_name_param);
					$My_detail_page_url = "/akkumulyator/".strtolower(str_replace(' ', '_', trim($temp_my_get_name_param)))."/";
					$My_new_arItemName = "Аккумулятор ".$temp_my_get_name_param;
				//}
			//}




			//print_r($arItem['ID']);
			//print_r($My_new_arItemName);
			//print_r($My_detail_page_url);

				$My_temp_arItems = CSaleBasket::GetByID($arItem['ID']);

			//echo "<pre>";
			//print_r($My_temp_arItems);
			//echo "</pre>";

				$My_update_arFields = array
				(
					"PRODUCT_PROVIDER_CLASS" => $My_new_arItemName,
					"DETAIL_PAGE_URL" => $My_detail_page_url
				);

				CSaleBasket::Update($arItem['ID'], $My_update_arFields);



			/*if($ar_res["NAME"] == 'MASS_ID')
			{
				//print_r($ar_res["VALUE"]);
				$ids = explode(";", $ar_res["VALUE"]);

				foreach ($ids as $id)
				{
					if($id == $arItem['PRODUCT_ID'])

					{


						$My_temp_arItems = CSaleBasket::GetByID($arItem['ID']);

						//print_r($id);
						//print_r($arItem['PRODUCT_ID']);
						//print_r($My_temp_arItems);
						//print_r($My_new_arItemName);


						$My_update_arFields = array
						(
						"PRODUCT_PROVIDER_CLASS" => $My_new_arItemName,
						"DETAIL_PAGE_URL" => $My_detail_page_url
						);

						CSaleBasket::Update($arItem['ID'], $My_update_arFields);
					}
				}
			}*/

			//print_r($arBasketItem['PRODUCT_PROVIDER_CLASS']);

			$arItem['PRODUCT_PROVIDER_CLASS'] = $My_new_arItemName;
			$arItem['DETAIL_PAGE_URL'] = $My_detail_page_url;
			$arItem['DETAIL_PAGE_URL'] = str_replace('+', 'plus', $arItem['DETAIL_PAGE_URL']);



		}

		//print_r($arBasketItem['PRODUCT_PROVIDER_CLASS']);

		//$arItem['PRODUCT_PROVIDER_CLASS'] = $arBasketItem['PRODUCT_PROVIDER_CLASS'];
		//$arItem['DETAIL_PAGE_URL'] = $arBasketItem['DETAIL_PAGE_URL'];
		//$arItem['DETAIL_PAGE_URL'] = str_replace('+', 'plus', $arItem['DETAIL_PAGE_URL']);
	}
}


$arBasketItems = array();

$dbBasketItems = CSaleBasket::GetList(
        array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
        array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"
            ),
        false,
        false,
        array("ID", "NAME", "PRODUCT_PROVIDER_CLASS", "DETAIL_PAGE_URL", "CALLBACK_FUNC", "MODULE", 
              "PRODUCT_ID", "QUANTITY", "DELAY", 
              "CAN_BUY", "PRICE", "WEIGHT")
    );
while ($arItems = $dbBasketItems->Fetch())
{
    $arBasketItems[] = $arItems;
}

// Печатаем массив, содержащий актуальную на текущий момент корзину
//echo "<pre>";
//print_r($arBasketItems);
//echo "</pre>";


foreach ($arBasketItems as $arBasketItem)
{

	if($arBasketItem['NAME'] == $arItem['NAME'])
	{



		//print_r($arBasketItem['PRODUCT_PROVIDER_CLASS']);

		//$arItem['PRODUCT_PROVIDER_CLASS'] = $arBasketItem['PRODUCT_PROVIDER_CLASS'];
		//$arItem['DETAIL_PAGE_URL'] = $arBasketItem['DETAIL_PAGE_URL'];
		//$arItem['DETAIL_PAGE_URL'] = str_replace('+', 'plus', $arItem['DETAIL_PAGE_URL']);
	}
}

/*print_r($arItem);*/
?>

										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="#"><?endif;?>

<?if ($arResult['ELEMENT_GROUPS'][$arItem['PRODUCT_ID']]['CRAFTMANN']):?>

<?php $arItem['PRODUCT_PROVIDER_CLASS'] = $arItem['NAME'];  ?>

<?else:?>

<?endif;?> <?=$arItem["PRODUCT_PROVIDER_CLASS"]?>

										<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
									</h2>
									<dl class="characts-list">
										<?foreach ($arResult['GRID']['CHARACTS_LIST']  as $code => $name): ?>
										<?



if($code == 'PROPERTY_CURRENT_USB_VALUE' )
{
	//echo "<pre>";
	//print_r($arResult['GRID']['CHARACTS_LIST']['PROPERTY_CURRENT_USB_VALUE']);
	//echo "<pre>";

	//echo "<pre>";
	//print_r($name);
	//echo "<pre>";

	//print_r($code);
	//print_r($name);

}

//echo "<pre>";
//print_r($arResult['GRID']['CHARACTS_LIST']['PROPERTY_CURRENT_USB_VALUE']);
//echo "<pre>";

//echo "<pre>";
//print_r($arResult['GRID']['CHARACTS_LIST']['PROPERTY_CURRENT_OUT_VALUE']);
//echo "<pre>";




if(count($arItem[$code]) > 0)
{
	$outArray = explode(';',$arItem[$code]);
	//echo "<pre>";
	//print_r($arItem[$code]);
	print_r($code);
	print_r($arItem['CURRENT']);
	//echo "<pre>";
	foreach($outArray as $k => $v)
	{
		//echo "<pre>";
		//print_r("$k is:" . $v);
		//echo "<pre>";

	}
}


 ?>

											<?if(count($arItem[$code]) > 0):?>
											<dt><?=$name?></dt>
										<dd style="text-align: right;/*margin-right: 50px;*/"><?=explode(';',$arItem[$code])[0] . 'fffffffffffff'

?></dd>
											<?endif;?> 
										<?endforeach;?>
									</dl>
									<?/*<div class="bx_ordercart_itemart">
										<?
										if ($bPropsColumn):
											foreach ($arItem["PROPS"] as $val):

												if (is_array($arItem["SKU_DATA"]))
												{
													$bSkip = false;
													foreach ($arItem["SKU_DATA"] as $propId => $arProp)
													{
														if ($arProp["CODE"] == $val["CODE"])
														{
															$bSkip = true;
															break;
														}
													}
													if ($bSkip)
														continue;
												}

												echo $val["NAME"].":&nbsp;<span>".$val["VALUE"]."<span><br/>";
											endforeach;
										endif;
										?>
									</div>
									<?
									/*if (is_array($arItem["SKU_DATA"])):
										foreach ($arItem["SKU_DATA"] as $propId => $arProp):

											// is image property
											$isImgProperty = false;
											foreach ($arProp["VALUES"] as $id => $arVal)
											{
												if (isset($arVal["PICT"]) && !empty($arVal["PICT"]))
												{
													$isImgProperty = true;
													break;
												}
											}

											$full = (count($arProp["VALUES"]) > 5) ? "full" : "";

											if ($isImgProperty): // iblock element relation property
											?>
												<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">

													<span class="bx_item_section_name_gray">
														<?=$arProp["NAME"]?>:
													</span>

													<div class="bx_scu_scroller_container">

														<div class="bx_scu">
															<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>"
																style="width: 200%; margin-left:0%;"
																class="sku_prop_list"
																>
																<?
																foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

																	$selected = "";
																	foreach ($arItem["PROPS"] as $arItemProp):
																		if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																		{
																			if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"])
																				$selected = "bx_active";
																		}
																	endforeach;
																?>
																	<li style="width:10%;"
																		class="sku_prop <?=$selected?>"
																		data-value-id="<?=$arSkuValue["XML_ID"]?>"
																		data-element="<?=$arItem["ID"]?>"
																		data-property="<?=$arProp["CODE"]?>"
																		>
																		<a href="javascript:void(0);">
																			<span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span>
																		</a>
																	</li>
																<?
																endforeach;
																?>
															</ul>
														</div>

														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
													</div>

												</div>
											<?
											else:
											?>
												<div class="bx_item_detail_size_small_noadaptive <?=$full?>">

													<span class="bx_item_section_name_gray">
														<?=$arProp["NAME"]?>:
													</span>

													<div class="bx_size_scroller_container">
														<div class="bx_size">
															<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>"
																style="width: 200%; margin-left:0%;"
																class="sku_prop_list"
																>
																<?
																foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

																	$selected = "";
																	foreach ($arItem["PROPS"] as $arItemProp):
																		if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																		{
																			if ($arItemProp["VALUE"] == $arSkuValue["NAME"])
																				$selected = "bx_active";
																		}
																	endforeach;
																?>
																	<li style="width:10%;"
																		class="sku_prop <?=$selected?>"
																		data-value-id="<?=$arSkuValue["NAME"]?>"
																		data-element="<?=$arItem["ID"]?>"
																		data-property="<?=$arProp["CODE"]?>"
																		>
																		<a href="javascript:void(0);"><?=$arSkuValue["NAME"]?></a>
																	</li>
																<?
																endforeach;
																?>
															</ul>
														</div>
														<div class="bx_slide_left" onclick="leftScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
														<div class="bx_slide_right" onclick="rightScroll('<?=$arProp["CODE"]?>', <?=$arItem["ID"]?>);"></div>
													</div>

												</div>
											<?
											endif;
										endforeach;
									endif;
									*/?>
								</td>
							<?
							elseif ($arHeader["id"] == "QUANTITY"):
							?>
								<td class="custom">
									<span><?=getColumnName($arHeader)?>:</span>
									<div class="centered">
										<table cellspacing="0" cellpadding="0" class="counter">
											<tr>
												<td>
													<?
													$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
													$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
													$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
													?>
													<input
														type="text"
														size="1"
														id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														size="1"
														maxlength="2"
														min="0"
														<?=$max?>
														step="<?=$ratio?>"
														style="max-width: 25px; font-family:"ArialNarrowRegular", Arial, sans-serif; font-stretch: ultra-condensed;"
														value="<?=$arItem["QUANTITY"]?>"
														class="basket-item-quantity"
														readonly
														onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>')"
													>
												</td>
												<td id="quantity_control">
													<div class="quantity_control">
														<a href="javascript:void(0);" class="plus" onclick="quanPlus(<?=$arItem['ID']?>)"></a>
														<a href="javascript:void(0);" class="minus" onclick="quanMinus(<?=$arItem['ID']?>)"></a>
													</div>
												</td>
												<?
												if (isset($arItem["MEASURE_TEXT"])):
												?>
													<td style="text-align: left"><?=$arItem["MEASURE_TEXT"]?></td>
												<?
												endif;
												?>
											</tr>
										</table>
									</div>
									<!-- quantity selector for mobile -->
									<?
									echo getQuantitySelectControl(
										"QUANTITY_SELECT_".$arItem["ID"],
										"QUANTITY_SELECT_".$arItem["ID"],
										$arItem["QUANTITY"],
										$arItem["AVAILABLE_QUANTITY"],
										$useFloatQuantity,
										$arItem["MEASURE_RATIO"],
										$arItem["MEASURE_TEXT"]
									);
									?>
									<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
									<input type="hidden" id="PRICE_<?=$arItem['ID']?>" name="PRICE_<?=$arItem['ID']?>" value="<?=$arItem["PRICE"]?>" />
								</td>
							<?
							elseif ($arHeader["id"] == "PRICE"): 
							?>
								<td class="price">
									<?if (doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0): ?>
										<div class="current_price"><?=$arItem["PRICE_FORMATED"]?></div>
										<div class="old_price"><?=$arItem["FULL_PRICE_FORMATED"]?></div>
									<?else:?>
										<div class="current_price"><?=$arItem["PRICE_FORMATED"];?></div>
									<?endif?>

									<?if (strlen($arItem["NOTES"]) > 0 && 1 == 2):?>
										<div class="type_price"><?=GetMessage("SALE_TYPE")?></div>
										<div class="type_price_value"><?=$arItem["NOTES"]?></div>
									<?endif;?>
								</td>
							<?
							elseif ($arHeader["id"] == "DISCOUNT"):
							?>
								<td class="custom">
									<span><?=getColumnName($arHeader)?>:</span>
									<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"]?>
								</td>
							<?
							elseif ($arHeader["id"] == "WEIGHT"):
							?>
								<td class="custom">
									<span><?=getColumnName($arHeader)?>:</span>
									<?=$arItem["WEIGHT_FORMATED"]?>
								</td>
							<?
							elseif ($arHeader["id"] == "SUM"):
?>
								<td class="custom" width="70">
									<span><?=getColumnName($arHeader)?>:</span>
									<span class="prod-summ" style="font-weight: bold;"><?=$arItem[$arHeader["id"]]?></span>
								</td>
<?
							else: 
							?>
								<td class="custom">
									<span><?=getColumnName($arHeader)?>:</span>
									<?=$arItem[$arHeader["id"]]?>
								</td>
							<?
							endif;
						endforeach;

						if ($bDelayColumn || $bDeleteColumn):
						?>
							<td class="control">
								<?
								if ($bDeleteColumn):
								?>
									<a class="basket-item-delete" href="<?=$APPLICATION->GetCurDir()?>?action=delete&id=<?=$arItem['ID']?>" id="delete-item-<?=$arItem['ID']?>"><?=GetMessage("SALE_DELETE")?></a><br />
								<?
								endif;
								if ($bDelayColumn && 1 == 2):
								?>
									<a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>"><?=GetMessage("SALE_DELAY")?></a>
								<?
								endif;
								?>
							</td>
						<?
						endif;
						?>
							<td class="margin"></td>
					</tr>
					<?
					endif;
				endforeach;
				?>
			</tbody>
		</table>
	</div>
	<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
	<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
	<input type="hidden" id="QUANTITY_FLOAT" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
	<input type="hidden" id="COUNT_DISCOUNT_4_ALL_QUANTITY" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="PRICE_VAT_SHOW_VALUE" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="HIDE_COUPON" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="USE_PREPAYMENT" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />

	<div class="bx_ordercart_order_pay">

		<?if ($arParams["HIDE_COUPON"] != "Y"):?>
			<div class="bx_ordercart_order_pay_left">
				<div class="bx_ordercart_coupon">
					<span><?=GetMessage("STB_COUPON_PROMT")?></span>
					<input type="text" id="COUPON" name="COUPON" value="<?=$arResult["COUPON"]?>" size="21" class="good"> <!-- "bad" if coupon is not valid -->
				</div>
			</div>
		<?endif;?>

		<div class="bx_ordercart_order_pay_right">
			<table class="bx_ordercart_order_sum">
				<?if ($bWeightColumn):?>
					<tr>
						<td class="custom_t1"><?=GetMessage("SALE_TOTAL_WEIGHT")?></td>
						<td class="custom_t2" id="allWeight_FORMATED"><?=$arResult["allWeight_FORMATED"]?></td>
					</tr>
				<?endif;?>
				<?if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"):?>
					<tr>
						<td><?echo GetMessage('SALE_VAT_EXCLUDED')?></td>
						<td id="allSum_wVAT_FORMATED"><?=$arResult["allSum_wVAT_FORMATED"]?></td>
					</tr>
					<tr>
						<td><?echo GetMessage('SALE_VAT_INCLUDED')?></td>
						<td id="allVATSum_FORMATED"><?=$arResult["allVATSum_FORMATED"]?></td>
					</tr>
				<?endif;?>

				<?if (doubleval($arResult["DISCOUNT_PRICE_ALL"]) > 0):?>
					<tr>
						<td class="fwb"><?=GetMessage("SALE_TOTAL")?></td>
						<td class="fwb" id="allSum_FORMATED"><?=str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])?></td>
					</tr>
					<tr>
						<td class="custom_t1"></td>
						<td class="custom_t2" style="text-decoration:line-through; color:#828282;" id="PRICE_WITHOUT_DISCOUNT"><?=$arResult["PRICE_WITHOUT_DISCOUNT"]?></td>
					</tr>
				<?else:?>
					<tr>
						<td class="custom_t1 fwb"><?=GetMessage("SALE_TOTAL")?></td>
						<td class="custom_t2 fwb" id="allSum_FORMATED"><?=$arResult["allSum_FORMATED"]?></td>
					</tr>
				<?endif;?>

			</table>
			<div style="clear:both;"></div><br />
			<a href="javascript:void(0)" id='startCraftmannFormOrder' class="checkout Btn" style="float: right;"><div style="position: absolute; z-index: 10; margin-top: 35px; margin-left: 85px; font-weight: normal; color: #000000;">займет 2-3 минуты</div>ОФОРМИТЬ ЗАКАЗ<span style="position: absolute; margin-top: -1px;"><img src="/images/rightArr.png" alt="" /></span></a>
			<div style="clear:both;"></div>
		</div>
		<div style="clear:both;"></div>

		<div class="bx_ordercart_order_pay_center">
<?if(1 == 2):?>
			<div style="float:left">
				<input type="submit" class="bt2" name="BasketRefresh" value="<?=GetMessage('SALE_REFRESH')?>">
			</div>
<?endif;?>

			<?if ($arParams["USE_PREPAYMENT"] == "Y"):?>
				<?=$arResult["PREPAY_BUTTON"]?>
				<span><?=GetMessage("SALE_OR")?></span>
			<?endif;?>
<?if(1 == 2):?>
			<a href="javascript:void(0)" onclick="checkOut();" class="checkout"><?=GetMessage("SALE_ORDER")?></a>
<?endif;?>
		<div style="clear:both;"></div><br />
		</div>
	</div>
</div>
<?
else:
?>
<div id="basket_items_list">
	<table>
		<tbody>
			<tr>
				<td colspan="<?=$numCells?>" style="text-align:center">
					<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?
endif;
?>