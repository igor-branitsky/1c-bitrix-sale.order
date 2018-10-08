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
									Мой заказ <sup class="caption"><?=$arResult['BASKET_COUNT'];?></sup>
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
													<div class="fullpage-modal-accessible yes">В наличии</div>
												<?else:?>
													<div class="fullpage-modal-accessible">Под азказ</div>
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
									<div class="caption">Стоимость</div>
									<div class="text"><?=$arResult['BASKET_SUM_BASE_PRINT'];?></div>
								</div>
								<div class="buy-card-descr-item">
									<div class="caption">Cкидка</div>
									<div class="text"><?=$arResult['BASKET_SUM_SALE_PRINT'];?></div>
								</div>
							</div>
							<div class="buy-card-total">
								<div class="buy-card-total-name">
									<div class="text">Итого</div>
								</div>
								<div class="buy-card-total-val">
									<div class="text">
										<b><?=$arResult['BASKET_SUM_PRINT'];?></b>
									</div>
									<div class="caption">Без учёта доставки</div>
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
									<span>Покупаю впервые</span>
								</div>
								<div class="tabs-item js-tab-trigger">
									<span>Покупал(а) ранее</span>
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
											<div class="title h3">Контактные данные</div>
											<div class="input-item w100">
												<div class="input-wrapper">
													<input class="input-main" id="checkoutName" type="text" name="checkoutName" data-validation="length" data-validation-error-msg="Обязательное поле" data-validation-length="min1">
													<label class="input-label req" for="#checkoutName">ФИО</label>
												</div>
											</div>
											<div class="input-item w50">
												<div class="input-wrapper">
													<input class="input-main" id="checkoutPhone" placeholder="+7 495 000-00-00" type="tel" name="checkoutPhone" data-validation="custom" data-validation-regexp="^[-0-9()+ ]+$" data-validation-error-msg="Введите корректный номер телефона">
													<label class="input-label req" for="#checkoutPhone">Телефон</label>
												</div>
											</div>
											<div class="input-item w50">
												<div class="input-wrapper">
													<input class="input-main" id="checkoutEmail" type="email" name="checkoutEmail" data-validation="email" data-validation-error-msg="Некорректный E-mail">
													<label class="input-label req" for="#checkoutEmail">E-mail</label>
												</div>
											</div>
											<div class="title h3">Способ доставки</div>
											<div class="input-item select-radio-item">
												<div class="select-radio">
													<label class="select-radio-label">
														<input class="select-radio-input-real js-changer" type="radio" name="type1" value="1" checked="checked" data-change-block="block-1" data-id="1">
														<div class="select-radio-inner">
                                    <span class="select-radio-input">
                                      <span class="select-radio-input-fake">
                                        <svg class="success">
                                          <use xlink:href="#success"/>
                                        </svg>
                                      </span>
                                    </span>
															<span class="select-radio-text">Самовывоз</span>
														</div>
													</label>
												</div>
												<div class="select-radio">
													<label class="select-radio-label">
														<input class="select-radio-input-real js-changer" type="radio" name="type1" value="2" data-change-block="block-1" data-id="2">
														<div class="select-radio-inner">
                                    <span class="select-radio-input">
                                      <span class="select-radio-input-fake">
                                        <svg class="success">
                                          <use xlink:href="#success"/>
                                        </svg>
                                      </span>
                                    </span>
															<span class="select-radio-text">Курьер</span>
														</div>
													</label>
												</div>
											</div>
											<div>
												<div class="input-item w50">
													<div class="select-check js-select-trigger select-radio input-wrapper">
														<div class="header-link has-dropdown input-main">
															<span> </span>
															<div class="input-label req">Область </div>
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
																		<!-- можно заменить на radio-->
																		<div class="checkbox">
																			<label class="checkbox-label">
																				<input class="checkbox-real" type="checkbox" value="1" name="checkoutRegion" data-validation="checkbox_group" data-validation-qty="min1">
																				<span class="checkbox-main">
                                                <span class="checkbox-checked">
                                                  <svg class="success">
                                                    <use xlink:href="#success"/>
                                                  </svg>
                                                </span>
                                              </span>
																				<span class="checkbox-text">Брестская</span>
																			</label>
																		</div>
																		<div class="checkbox">
																			<label class="checkbox-label">
																				<input class="checkbox-real" type="checkbox" value="2" name="checkoutRegion" data-validation="checkbox_group" data-validation-qty="min1">
																				<span class="checkbox-main">
                                                <span class="checkbox-checked">
                                                  <svg class="success">
                                                    <use xlink:href="#success"/>
                                                  </svg>
                                                </span>
                                              </span>
																				<span class="checkbox-text">Витебская</span>
																			</label>
																		</div>
																		<div class="checkbox">
																			<label class="checkbox-label">
																				<input class="checkbox-real" type="checkbox" value="3" name="checkoutRegion" data-validation="checkbox_group" data-validation-qty="min1">
																				<span class="checkbox-main">
                                                <span class="checkbox-checked">
                                                  <svg class="success">
                                                    <use xlink:href="#success"/>
                                                  </svg>
                                                </span>
                                              </span>
																				<span class="checkbox-text">Гомельская</span>
																			</label>
																		</div>
																		<div class="checkbox">
																			<label class="checkbox-label">
																				<input class="checkbox-real" type="checkbox" value="3" name="checkoutRegion" data-validation="checkbox_group" data-validation-qty="min1">
																				<span class="checkbox-main">
                                                <span class="checkbox-checked">
                                                  <svg class="success">
                                                    <use xlink:href="#success"/>
                                                  </svg>
                                                </span>
                                              </span>
																				<span class="checkbox-text">Гродненская</span>
																			</label>
																		</div>
																		<div class="checkbox">
																			<label class="checkbox-label">
																				<input class="checkbox-real" type="checkbox" value="3" name="checkoutRegion" data-validation="checkbox_group" data-validation-qty="min1">
																				<span class="checkbox-main">
                                                <span class="checkbox-checked">
                                                  <svg class="success">
                                                    <use xlink:href="#success"/>
                                                  </svg>
                                                </span>
                                              </span>
																				<span class="checkbox-text">Минская</span>
																			</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="input-item w50">
													<div class="input-wrapper">
														<input class="input-main" id="checkoutCity" type="text" name="checkoutCity" data-validation="length" data-validation-error-msg="Обязательное поле" data-validation-length="min1">
														<label class="input-label req" for="#checkoutCity">Населенный пункт</label>
													</div>
												</div>
												<div class="input-item w50">
													<div class="input-wrapper">
														<input class="input-main" id="checkoutStreet" type="text" name="checkoutStreet" data-validation="length" data-validation-error-msg="Обязательное поле" data-validation-length="min1">
														<label class="input-label req" for="#checkoutStreet">Улица</label>
													</div>
												</div>
												<div class="input-item w16">
													<div class="input-wrapper">
														<input class="input-main" id="checkoutBuilding" type="text" name="checkoutBuilding" data-validation="length" data-validation-error-msg="Обязательное поле" data-validation-length="min1">
														<label class="input-label req" for="#checkoutBuilding">Дом</label>
													</div>
												</div>
												<div class="input-item w16">
													<div class="input-wrapper">
														<input class="input-main" id="checkoutCorp" type="text" name="checkoutCorp">
														<label class="input-label" for="#checkoutCorp">Корпус</label>
													</div>
												</div>
												<div class="input-item w16">
													<div class="input-wrapper">
														<input class="input-main" id="checkoutOffice" type="text" name="checkoutOffice">
														<label class="input-label" for="#checkoutOffice">Кв./оф</label>
													</div>
												</div>
												<div class="caption-cont caption">
													<div class="caption-cont-icon">
														<svg class="question">
															<use xlink:href="#question"/>
														</svg>
													</div>
													<div class="caption-cont-text">Доставка заказа по Минску стоимостью от 100 руб осуществляется бесплатно.
														<br>По Республике Беларусь доставка осуществляется бесплатно, если стоимость заказа составляет более 2 000 руб.</div>
												</div>
											</div>
											<div class="title h3">Способ оплаты</div>
											<div class="text accent">При получении курьеру</div>
											<div class="input-item select-radio-item">
												<div class="select-radio">
													<label class="select-radio-label">
														<input class="select-radio-input-real js-changer" type="radio" name="type2" data-change-block="block-2" value="1">
														<div class="select-radio-inner">
                                    <span class="select-radio-input">
                                      <span class="select-radio-input-fake">
                                        <svg class="success">
                                          <use xlink:href="#success"/>
                                        </svg>
                                      </span>
                                    </span>
															<span class="select-radio-text">Наличными</span>
														</div>
													</label>
												</div>
												<div class="select-radio">
													<label class="select-radio-label">
														<input class="select-radio-input-real js-changer" type="radio" name="type2" value="2" data-change-block="block-2" data-id="1">
														<div class="select-radio-inner">
                                    <span class="select-radio-input">
                                      <span class="select-radio-input-fake">
                                        <svg class="success">
                                          <use xlink:href="#success"/>
                                        </svg>
                                      </span>
                                    </span>
															<span class="select-radio-text">Картой</span>
														</div>
													</label>
												</div>
											</div>
											<div class="text accent">Денежный перевод</div>
											<div class="input-item select-radio-item">
												<div class="select-radio">
													<label class="select-radio-label">
														<input class="select-radio-input-real js-changer" type="radio" name="type2" value="3" data-change-block="block-2" data-id="1">
														<div class="select-radio-inner">
                                    <span class="select-radio-input">
                                      <span class="select-radio-input-fake">
                                        <svg class="success">
                                          <use xlink:href="#success"/>
                                        </svg>
                                      </span>
                                    </span>
															<span class="select-radio-text">Online банковской картой</span>
														</div>
													</label>
												</div>
												<div class="select-radio">
													<label class="select-radio-label">
														<input class="select-radio-input-real js-changer" type="radio" name="type2" value="4" checked="checked" data-change-block="block-2" data-id="1">
														<div class="select-radio-inner">
                                    <span class="select-radio-input">
                                      <span class="select-radio-input-fake">
                                        <svg class="success">
                                          <use xlink:href="#success"/>
                                        </svg>
                                      </span>
                                    </span>
															<span class="select-radio-text">Безналичный расчёт</span>
														</div>
													</label>
												</div>
											</div>
											<div>
												<div class="caption-cont caption">
													<div class="caption-cont-icon">
														<svg class="question">
															<use xlink:href="#question"/>
														</svg>
													</div>
													<div class="caption-cont-text">Электронный платёж с помощью банковких карт: Visa, Maestro, Mastercard, Белкарт
														<div class="payment-wrap">
															<div class="payment-item">
																<img src="img/payment/visa-paint.svg" alt="">
															</div>
															<div class="payment-item">
																<img src="img/payment/maestro-paint.svg" alt="">
															</div>
															<div class="payment-item">
																<img src="img/payment/Mastercard-paint.svg" alt="">
															</div>
															<div class="payment-item">
																<img src="img/payment/Belcart-paint.svg" alt="">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="title h3">Комментарий к заказу</div>
											<div class="input-item w100">
												<div class="input-wrapper">
													<textarea class="input-main" type="text" name="checkoutMoreInfo" id="checkoutMoreInfo"></textarea>
													<label class="input-label" for="#checkoutMoreInfo">Ваши пожелания к заказу</label>
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
															<span class="checkbox-text">Подписаться на рассылку</span>
														</label>
													</div>
												</div>
												<div class="input-item w50">
													<button class="btn primary dont-move" type="submit">
														<span>Подтвердить заказ</span>
													</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>

							<?if(!$arResult['USER_AUTH']):?>
								<style>
									.qwe{
										opacity: 0;
										width: 0px;
										height: 0px;
										position: absolute;
									}
								</style>
								<div class="tabs-cont">
									<form class="js-validate login-form" action="" method="post">
										<input type="text" class="qwe" name="qwe" value="" />
										<div class="block-border">
											<div class="input-row">
												<p class="annotation small">
													Уже делали заказ на нашем сайте?
													Авторизуйтесь, чтобы упростить процедуру
													оформления заказа
												</p>
												<div class="input-item w100">
													<div class="input-wrapper">
														<input
															class="input-main"
															id="loginEmail"
															type="email"
															name="login"
															data-validation="email"
															data-validation-error-msg="Некорректный E-mail"
														/>
														<label class="input-label req" for="#loginEmail">E-mail</label>
													</div>
												</div>
												<div class="input-item w100">
													<div class="input-wrapper">
														<input class="input-main" id="loginPass" type="password" name="password">
														<label class="input-label req" for="#loginPass">Пароль</label>
														<div class="link-icon js-popup-button" data-modal="email">
															<span>Забыли пароль?</span>
														</div>
													</div>
												</div>
												<div class="input-item w100">
													<button class="btn primary dont-move" type="submit">
														<span>Войти</span>
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


