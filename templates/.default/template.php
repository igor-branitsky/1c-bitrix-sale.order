<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="wrapper margin-b">
	<div class="content">
		<div class="box">
			<div class="side-wrapper side-wrapper-checkout">

				<div class="side-add">
					<div class="checkout-wrap">

						<div class="checkout-main">

							<div class="checkout-main-head">
								<div class="title h4">
									<?=Loc::getMessage("FLXMD_SALE_ORDER_MY_ORDER");?> <sup class="caption"><?=$arResult['BASKET_COUNT'];?></sup>
								</div>
								<a class="link-icon gray" href="/basket/">
									<i>
										<svg class="edit">
											<use xlink:href="#edit"></use>
										</svg>
									</i>
								</a>
							</div>

							<div class="checkout-scroller">

								<?foreach ($arResult['BASKET_LIST'] as $key => $arItem):?>
									<a class="product-table-card product-table-card-small" href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<div class="product-table-card-inner">
											<?if(!empty($arItem['IMAGE'])):?>
												<div class="product-table-card-img">
													<img src="<?=$arItem['IMAGE']?>" alt="<?=$arItem['NAME']?>" />
												</div>
											<?endif;?>
											<div class="product-table-card-descr">
												<p class="annotation small">
													<?=$arItem['NAME'];?>
												</p>
												<?if($arItem['CAN_BUY']):?>
													<div class="fullpage-modal-accessible yes"><?=Loc::getMessage("FLXMD_SALE_ORDER_IN_STOCK");?></div>
												<?else:?>
													<div class="fullpage-modal-accessible"><?=Loc::getMessage("FLXMD_SALE_ORDER_PREPAYMEND");?></div>
												<?endif;?>
												<?if(!empty($arItem['PROPS'])):?>
													<div class="prod-descr">
														<?foreach ($arItem['PROPS'] as $arProp):?>
															<div class="caption"><?=$arProp['NAME']?>: <?=$arProp['VALUE']?> </div>
														<?endforeach;?>
													</div>
												<?endif;?>
												<div class="product-table-card-vals">
													<div class="text">
														<?=$arItem['QUANTITY'];?>
														<?=$arItem['MEASURE_NAME'];?>
													</div>
													<div class="text">
														<?=$arItem['SUM'];?>
													</div>
												</div>
											</div>
										</div>
									</a>
								<?endforeach;?>

							</div>

						</div>

						<div class="checkout-total">
							<div class="buy-card-descr">
								<div class="buy-card-descr-item">
									<div class="caption"><?=Loc::getMessage("FLXMD_SALE_ORDER_PRICE");?></div>
									<div class="text"><?=$arResult['BASKET_SUM_BASE_PRINT'];?></div>
								</div>
								<div class="buy-card-descr-item">
									<div class="caption"><?=Loc::getMessage("FLXMD_SALE_ORDER_SALE");?></div>
									<div class="text"><?=$arResult['BASKET_SUM_SALE_PRINT'];?></div>
								</div>
							</div>
							<div class="buy-card-total">
								<div class="buy-card-total-name">
									<div class="text"><?=Loc::getMessage("FLXMD_SALE_ORDER_RESULT");?></div>
								</div>
								<div class="buy-card-total-val">
									<div class="text">
										<b><?=$arResult['BASKET_SUM_PRINT'];?></b>
									</div>
									<div class="caption"><?=Loc::getMessage("FLXMD_SALE_ORDER_NOT_DELIVERY");?></div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="side-main">
					<div class="tabs-wrapper <?if(!$arResult['USER_AUTH']):?>js-tabs-wrap<?endif;?>">

						<?if(!$arResult['USER_AUTH']):?>
							<div class="tabs-head">
								<div class="tabs-item js-tab-trigger active">
									<span><?=Loc::getMessage("FLXMD_SALE_ORDER_PAY_ONE");?></span>
								</div>
								<div class="tabs-item js-tab-trigger">
									<span><?=Loc::getMessage("FLXMD_SALE_ORDER_PAY_ABOVE");?></span>
								</div>
							</div>
						<?endif;?>

						<div class="tabs-body">

							<div class="tabs-cont">
								<!-- window.FilterProducts()-->
								<!-- window.initMap()-->
								<!-- window.validateForms()-->
								<div class="has-preloader">

									<div class="preloader-wrap">
										<span class="preloader">
											<span class="preloader-inner"></span>
										</span>
									</div>

									<form class="js-validate" action="/">
										<div class="input-row">

											<div class="title h3"><?=Loc::getMessage("FLXMD_SALE_ORDER_PERSONAL");?></div>

											<div class="input-item w100">
												<div class="input-wrapper">
													<input
														class="input-main"
														id="checkoutName"
														type="text"
														name="USER_FIO"
														value="<?=$arResult['USER_FIO']?>"
														data-validation="length"
														data-validation-error-msg="<?=Loc::getMessage("FLXMD_SALE_ORDER_FIELD_REQUIRED");?>"
														data-validation-length="min1"
													/>
													<label class="input-label req" for="#checkoutName">
														<?=Loc::getMessage("FLXMD_SALE_ORDER_FIO");?>
													</label>
												</div>
											</div>

											<div class="input-item w50">
												<div class="input-wrapper">
													<input
														class="input-main"
														id="checkoutPhone"
														placeholder="+7 495 000-00-00"
														type="tel"
														name="USER_PHONE_NUMBER"
														value="<?=$arResult['USER_PHONE']?>"
														data-validation="custom"
														data-validation-regexp="^[-0-9()+ ]+$"
														data-validation-error-msg="<?=Loc::getMessage("FLXMD_SALE_ORDER_PHONE_NUMBER");?>"
													/>
													<label class="input-label req" for="#checkoutPhone">
														<?=Loc::getMessage("FLXMD_SALE_ORDER_PHONE");?>
													</label>
												</div>
											</div>

											<div class="input-item w50">
												<div class="input-wrapper">
													<input
														class="input-main"
														id="checkoutEmail"
														type="email"
														name="USER_EMAIL"
														value="<?=$arResult['USER_EMAIL']?>"
														data-validation="email"
														data-validation-error-msg="<?=Loc::getMessage("FLXMD_SALE_ORDER_EMAIL_NOT");?>"
													/>
													<label class="input-label req" for="#checkoutEmail">
														E-mail
													</label>
												</div>
											</div>

											<div class="title h3"><?=Loc::getMessage("FLXMD_SALE_ORDER_DELIVERY_LIST");?></div>

											<div class="input-item select-radio-item">

												<?foreach ($arResult['DELIVERY_LIST'] as $key => $arItem):?>
													<div class="select-radio">
														<label class="select-radio-label">
															<input
																class="select-radio-input-real js-changer-delivery"
																type="radio"
																name="delivery"
																value="<?=$arItem['ID'];?>"
																<?if($key == 0):?>checked="checked"<?endif;?>
																data-change-block="block-<?=$arItem['ID'];?>"
																data-id="<?=$arItem['ID'];?>"
															/>
															<div class="select-radio-inner">
																<span class="select-radio-input">
																	<span class="select-radio-input-fake">
																		<svg class="success">
																			<use xlink:href="#success"/>
																		</svg>
																	</span>
																</span>
																<span class="select-radio-text"><?=$arItem['NAME'];?></span>
															</div>
														</label>
													</div>
												<?endforeach;?>

											</div>

											<?foreach ($arResult['DELIVERY_LIST'] as $key => $arItem):?>
												<div class="block-delivery-change" data-delivery-block="block-<?=$arItem['ID'];?>" <?if($key > 0):?>style="display:none;"<?endif;?>>

													<?if($arItem['STORE']):?>
														<?foreach ($arItem['STORE'] as $arStore):?>

															<div class="input-item w50">
																<div class="contact-text">
																	<div class="text accent"><?=Loc::getMessage("FLXMD_SALE_ORDER_PICKUP");?></div>
																	<p class="annotation small"><?=$arStore['ADDRESS'];?></p>
																	<div class="caption"><?=$arStore['SCHEDULE'];?></div>
																	<div class="contact-tels">
																		<?=$arStore['UF_PHONE'];?>
																	</div>
																</div>
															</div>

															<div class="input-item w50">
																<div class="map-wrap">
																	<div
																		class="map-elem"
																		id="map"
																		data-lat="<?=$arStore['GPS_N'];?>"
																		data-lon="<?=$arStore['GPS_S'];?>"
																		data-icon="<?=SITE_TEMPLATE_PATH?>/img/pin.svg"
																	>
																	</div>
																</div>
															</div>
														<?endforeach;?>
													<?else:?>

														<?if($arResult['USER_AUTH'] && !empty($arResult['USER_ADDRESS'])):?>
															<div data-block-form-address="1" class="block-address">
																<div class="input-item w100">
																	<div class="select-check js-select-trigger select-radio input-wrapper">
																		<div class="header-link has-dropdown input-main">
																			<span></span>
																			<div class="input-label req"><?=Loc::getMessage("FLXMD_SALE_ORDER_ADDRESS");?></div>
																			<i>
																				<svg class="arr-bold">
																					<use xlink:href="#arr-bold"/>
																				</svg>
																			</i>
																		</div>
																		<div class="dropdown-target">
																			<div class="dropdown-inner">
																				<div class="dropdown-content">
																					<div class="check-list">
																						<?foreach ($arResult['USER_ADDRESS'] as $sKeyAddress => $arAddress):?>
																							<?if($arAddress['MAIN_ADDRESS'] == 'Y'):?>
																								<div class="caption primary">
																									<?=Loc::getMessage("FLXMD_SALE_ORDER_ADDRESS_BASE");?>
																								</div>
																								<div class="checkbox">
																									<label class="checkbox-label">
																										<input
																											class="checkbox-real"
																											type="radio"
																											value="<?=$sKeyAddress;?>"
																											name="checkoutRegion"
																											checked="checked"
																										/>
																										<span class="checkbox-main">
																											<span class="checkbox-checked">
																												<svg class="success">
																													<use xlink:href="#success"/>
																												</svg>
																											</span>
																										</span>
																										<span class="checkbox-text">
																											<?=$arAddress['REGION'];?> область,
																											г.<?=$arAddress['CITY'];?>,
																											ул.<?=$arAddress['STREET'];?>,
																											д.<?=$arAddress['BUILDING'];?>,
																											кв.<?=$arAddress['ROOM'];?>
																										</span>
																									</label>
																								</div>
																							<?endif;?>
																						<?endforeach;?>
																						<?$bFlag = true;?>
																						<?foreach ($arResult['USER_ADDRESS'] as $sKeyAddress => $arAddress):?>
																							<?if($arAddress['MAIN_ADDRESS'] !== 'Y'):?>
																								<?if($bFlag):?>
																									<div class="caption primary">
																										<?=Loc::getMessage("FLXMD_SALE_ORDER_ADDRESS_DOP");?>
																									</div>
																									<?$bFlag = false;?>
																								<?endif;?>
																								<div class="checkbox">
																									<label class="checkbox-label">
																										<input
																											class="checkbox-real"
																											type="radio"
																											value="<?=$sKeyAddress;?>"
																											name="checkoutRegion"
																										/>
																										<span class="checkbox-main">
																											<span class="checkbox-checked">
																												<svg class="success">
																													<use xlink:href="#success"/>
																												</svg>
																											</span>
																										</span>
																										<span class="checkbox-text">
																											<?=$arAddress['REGION'];?> область,
																											г.<?=$arAddress['CITY'];?>,
																											ул.<?=$arAddress['STREET'];?>,
																											д.<?=$arAddress['BUILDING'];?>,
																											кв.<?=$arAddress['ROOM'];?>
																										</span>
																									</label>
																								</div>
																							<?endif;?>
																						<?endforeach;?>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>

																<div class="link-icon js-change-address" data-address-id="2">
																	<i>
																		<svg class="plus">
																			<use xlink:href="#plus"/>
																		</svg>
																	</i>
																	<span><?=Loc::getMessage("FLXMD_SALE_ORDER_ADD_ADDRESS");?></span>
																</div>
															</div>
														<?endif;?>

														<div
															class="block-address"
															data-block-form-address="2"
															<?if($arResult['USER_AUTH'] && !empty($arResult['USER_ADDRESS'])):?>style="display: none;"<?endif;?>
														>
															<div class="input-item w50">
																<div class="select-check js-select-trigger select-radio input-wrapper">
																	<div class="header-link has-dropdown input-main">
																		<span> </span>
																		<div class="input-label req">
																			<?=Loc::getMessage("FLXMD_SALE_ORDER_REGION");?>
																		</div>
																		<i>
																			<svg class="arr-bold">
																				<use xlink:href="#arr-bold"/>
																			</svg>
																		</i>
																	</div>
																	<div class="dropdown-target">
																		<div class="dropdown-inner">
																			<div class="dropdown-content">
																				<div class="check-list">
																					<?foreach ($arResult['LIST_REGION'] as $sKeyReg => $sValue):?>
																						<div class="checkbox">
																							<label class="checkbox-label">
																								<input
																									class="checkbox-real"
																									type="checkbox"
																									value="<?=$sKeyReg;?>"
																									name="checkoutRegion"
																									data-validation="checkbox_group"
																									data-validation-qty="min1"
																								/>
																								<span class="checkbox-main">
																									<span class="checkbox-checked">
																										<svg class="success">
																											<use xlink:href="#success"/>
																										</svg>
																									</span>
																								</span>
																								<span class="checkbox-text"><?=$sValue;?></span>
																							</label>
																						</div>
																					<?endforeach;?>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>

															<div class="input-item w50">
																<div class="input-wrapper">
																	<input
																		class="input-main"
																		id="checkoutCity"
																		type="text"
																		name="checkoutCity"
																		data-validation="length"
																		data-validation-error-msg="Обязательное поле"
																		data-validation-length="min1"
																	/>
																	<label class="input-label req" for="#checkoutCity">
																		<?=Loc::getMessage("FLXMD_SALE_ORDER_LOCALITY");?>
																	</label>
																</div>
															</div>

															<div class="input-item w50">
																<div class="input-wrapper">
																	<input
																			class="input-main"
																			id="checkoutStreet"
																			type="text"
																			name="checkoutStreet"
																			data-validation="length"
																			data-validation-error-msg="Обязательное поле"
																			data-validation-length="min1"
																	/>
																	<label class="input-label req" for="#checkoutStreet">
																		<?=Loc::getMessage("FLXMD_SALE_ORDER_STREET");?>
																	</label>
																</div>
															</div>

															<div class="input-item w16">
																<div class="input-wrapper">
																	<input
																		class="input-main"
																		id="checkoutBuilding"
																		type="text"
																		name="checkoutBuilding"
																		data-validation="length"
																		data-validation-error-msg="Обязательное поле"
																		data-validation-length="min1"
																	/>
																	<label class="input-label req" for="#checkoutBuilding">
																		<?=Loc::getMessage("FLXMD_SALE_ORDER_ROOM");?>
																	</label>
																</div>
															</div>

															<div class="input-item w16">
																<div class="input-wrapper">
																	<input
																		class="input-main"
																		id="checkoutCorp"
																		type="text"
																		name="checkoutCorp"
																	/>
																	<label class="input-label" for="#checkoutCorp">
																		<?=Loc::getMessage("FLXMD_SALE_ORDER_COOP");?>
																	</label>
																</div>
															</div>

															<div class="input-item w16">
																<div class="input-wrapper">
																	<input
																		class="input-main"
																		id="checkoutOffice"
																		type="text"
																		name="checkoutOffice"
																	/>
																	<label class="input-label" for="#checkoutOffice">
																		<?=Loc::getMessage("FLXMD_SALE_ORDER_APARTMENT");?>
																	</label>
																</div>
															</div>

															<?if($arResult['USER_AUTH'] && !empty($arResult['USER_ADDRESS'])):?>
																<div class="link-icon js-change-address" data-address-id="1">
																	<i>
																		<svg class="marker">
																			<use xlink:href="#marker"></use>
																		</svg>
																	</i>
																	<span><?=Loc::getMessage("FLXMD_SALE_ORDER_CHANGE_ADDRESS");?></span>
																</div>
															<?endif;?>
														</div>

													<?endif;?>

													<?if($arItem['DESCRIPTION']):?>
														<div class="caption-cont caption">
															<div class="caption-cont-icon">
																<svg class="question">
																	<use xlink:href="#question"/>
																</svg>
															</div>

															<div class="caption-cont-text">
																<?=$arItem['DESCRIPTION'];?>
															</div>
														</div>
													<?endif;?>

												</div>
											 <?endforeach;?>

											<div class="title h3"><?=Loc::getMessage("FLXMD_SALE_ORDER_PAYSYSTEM");?></div>

											<div class="text accent"><?=Loc::getMessage("FLXMD_SALE_ORDER_PAYSYSTEM_COURIER");?></div>

											<div class="input-item select-radio-item">
												<?$bFlag = true;?>
												<? foreach ($arResult['PAY_SYSTEM'] as $sKey => $arItem):?>
													<?if($arItem['ACTION_FILE'] === 'cash'):?>
														<div class="select-radio">
															<label class="select-radio-label">
																<input
																	class="select-radio-input-real js-changer"
																	type="radio"
																	name="paysystem"
																	value="<?=$arItem['PAY_SYSTEM_ID'];?>"
																	<?if($bFlag):?>checked="checked"<?$bFlag = false; endif; ?>
																/>
																<div class="select-radio-inner">
																	<span class="select-radio-input">
																		<span class="select-radio-input-fake">
																			<svg class="success">
																				<use xlink:href="#success"/>
																			</svg>
																		</span>
																	</span>
																	<span class="select-radio-text">
																		<?=$arItem['NAME'];?>
																	</span>
																</div>
															</label>
														</div>
													<?endif;?>
												<?endforeach;?>
											</div>

											<?if(!$arResult['NOT_PREPAYMENT']):?>
												<div class="text accent"><?=Loc::getMessage("FLXMD_SALE_ORDER_PAYSYSTEM_MANY");?></div>

												<div class="input-item select-radio-item">
													<? foreach ($arResult['PAY_SYSTEM'] as $sKey => $arItem):?>
														<?if($arItem['ACTION_FILE'] !== 'cash'):?>
															<div class="select-radio">
																<label class="select-radio-label">
																	<input
																		class="select-radio-input-real js-changer"
																		type="radio"
																		name="paysystem"
																		value="<?=$arItem['PAY_SYSTEM_ID'];?>"
																		<?if($bFlag):?>checked="checked"<?$bFlag = false; endif; ?>
																	/>
																	<div class="select-radio-inner">
																		<span class="select-radio-input">
																			<span class="select-radio-input-fake">
																				<svg class="success">
																					<use xlink:href="#success"/>
																				</svg>
																			</span>
																		</span>
																		<span class="select-radio-text">
																			<?=$arItem['NAME'];?>
																		</span>
																	</div>
																</label>
															</div>
														<?endif;?>
													<?endforeach;?>
												</div>

												<div>
													<div class="caption-cont caption">
														<div class="caption-cont-icon">
															<svg class="question">
																<use xlink:href="#question"/>
															</svg>
														</div>
														<div class="caption-cont-text">
															<?=Loc::getMessage("FLXMD_SALE_ORDER_PAYSYSTEM_TEXT");?>
															<div class="payment-wrap">
																<div class="payment-item">
																	<img src="<?=SITE_TEMPLATE_PATH?>/img/payment/visa-paint.svg" alt="">
																</div>
																<div class="payment-item">
																	<img src="<?=SITE_TEMPLATE_PATH?>/img/payment/maestro-paint.svg" alt="">
																</div>
																<div class="payment-item">
																	<img src="<?=SITE_TEMPLATE_PATH?>/img/payment/Mastercard-paint.svg" alt="">
																</div>
																<div class="payment-item">
																	<img src="<?=SITE_TEMPLATE_PATH?>/img/payment/Belcart-paint.svg" alt="">
																</div>
															</div>
														</div>
													</div>
												</div>
											<?endif;?>

											<div class="title h3"><?=Loc::getMessage("FLXMD_SALE_ORDER_COMMENT");?></div>

											<div class="input-item w100">
												<div class="input-wrapper">
													<textarea class="input-main" type="text" name="comment" id="checkoutMoreInfo"></textarea>
													<label class="input-label" for="#checkoutMoreInfo">
														<?=Loc::getMessage("FLXMD_SALE_ORDER_COMMENT_VAL");?>
													</label>
												</div>
											</div>

											<div class="input-footer">
												<div class="input-item w50">
													<div class="checkbox">
														<label class="checkbox-label">
															<input class="checkbox-real" type="checkbox" value="1" name="rassilca">
															<span class="checkbox-main">
																<span class="checkbox-checked">
																	<svg class="success">
																		<use xlink:href="#success"/>
																	</svg>
																</span>
															</span>
															<span class="checkbox-text">
																<?=Loc::getMessage("FLXMD_SALE_ORDER_SUBSCRIBE");?>
															</span>
														</label>
													</div>
												</div>

												<div class="input-item w50">
													<button class="btn primary dont-move" name="save_ajax" type="submit">
														<span><?=Loc::getMessage("FLXMD_SALE_ORDER_SAVE");?></span>
													</button>
												</div>
											</div>

										</div>
									</form>
								</div>
							</div>

							<?if(!$arResult['USER_AUTH']):?>
								<div class="tabs-cont">
									<form class="js-validate login-form" action="" method="post" id="form-auth-sale-order">
										<input type="text" class="qwe" name="qwe" value="" />
										<input type="hidden" name="auth_ajax" value="Y" />
										<div class="block-border">
											<div class="input-row">
												<p class="annotation small">
													<?=Loc::getMessage("FLXMD_SALE_ORDER_AUTH_TEXT");?>
												</p>
												<div class="input-item w100">
													<div class="input-wrapper">
														<input
															class="input-main"
															id="loginEmail"
															type="email"
															name="login"
															data-validation="email"
															data-validation-error-msg="<?=Loc::getMessage("FLXMD_SALE_ORDER_EMAIL_NOT");?>"
														/>
														<label class="input-label req" for="#loginEmail">E-mail</label>
													</div>
												</div>
												<div class="input-item w100">
													<div class="input-wrapper">
														<input class="input-main" id="loginPass" type="password" name="password">
														<label class="input-label req" for="#loginPass"><?=Loc::getMessage("FLXMD_SALE_ORDER_AUTH_PASS");?></label>
														<a href="/login/?forgot_password=yes" class="link-icon">
															<span><?=Loc::getMessage("FLXMD_SALE_ORDER_FORGOT_PASS");?></span>
														</a>
													</div>
												</div>
												<div class="error-auth-form"></div>
												<div class="success-auth-form"></div>
												<div class="input-item w100">
													<button class="btn primary dont-move" type="submit">
														<span><?=Loc::getMessage("FLXMD_SALE_ORDER_AUTH");?></span>
													</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							<?endif;?>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
