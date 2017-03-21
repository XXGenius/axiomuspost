{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='axiomuspostcarrier'}">{l s='Checkout' mod='axiomuspostcarrier'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Bank-wire payment' mod='axiomuspostcarrier'}
{/capture}

{*{include file="$tpl_dir./breadcrumb.tpl"}*}

<h2>{l s='Доставка:' mod='axiomuspostcarrier'}</h2>
<hr>
{assign var='current_step' value='shipping'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
    <p class="warning">{l s='Your shopping cart is empty.' mod='axiomuspostcarrier'}</p>
{else}

    <h4>{l s='Выберите спсоб доставки:' mod='axiomuspostcarrier'}</h4>

    <form action="{$link->getModuleLink('axiomuspostcarrier', 'validation', [], true)|escape:'html'}" method="post" class="form-inline">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4">
                        <select class="form-control" id="kad_type" name="kad-type">
                            <option value="1">в пределах МКАД</option>
                            <option value="2">в пределах 5км. от МКАД</option>
                            <option value="3">от 5 до 10км. от МКАД</option>
                            <option value="4">от 10 до 25км. от МКАД</option>
                            <option value="5">от 25 до 40км. от МКАД</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <div class="required form-group">
                            <label class="radio-inline"><input type="radio" name="delivery-type" value="0" id="opt-delivery">Доставка до двери</label>
                            {if $city=='mscw' or $city=='ptr'}<label class="radio-inline"><input type="radio" name="delivery-type" value="1" id="opt-carry">Самовывоз</label>{/if}
                        </div>
                    </div>
                </div>
                <br>
                {if $city=='mscw' or $city=='ptr'}
                <div class="row" id="rowDateTime">
                    <div class="col-lg-8">
                        <div class="required form-group">
                            <div class="row">
                                <div class="col-lg-8">
                                    <label for="delivery-date">'Дата доставки:<br> (не раньше сегодняшнего дня) <sup>*</sup></label>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" class="is_required form-control" data-validate="isGenericName" id="delivery_date" name="delivery-date" value="{$tomorrow|date_format:"%d.%m.%Y"}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <select class="form-control" id="time_type" name="time-type">
                            <option value="1">c 10:00 до 14:00</option>
                            <option value="2">c 14:00 до 18:00</option>
                            <option value="3">с 18:00 до 22:00</option>
                            <option value="4">c 23:00 до 03:00</option>
                            <option value="5">c 3:00 до 6:00</option>
                        </select>

                    </div>
                </div>
                <br>
                {/if}
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-lg-8 text-right"><h4><b>Всего товаров на сумму:</b></h4></div>
                    <div class="col-lg-4"><h4 id="productPrice">{$productprice}р.</h4></div>
                </div>
                <div class="row">
                    <div class="col-lg-8 text-right"><h4><b>Стоимость доставки:</b></h4></div>
                    <div class="col-lg-4 post-progress"><h4 id="deliveryPrice">-- р.</h4></div>
                    <div class="col-lg-4 progress-img" style="display: none"><img src="/img/loader.gif"></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-8 text-right"><h3><b>ВСЕГО:</b></h3></div>
                    <div class="col-lg-4 post-progress"><h3 id="sumPrice">{$productprice}р.</h3></div>
                    <div class="col-lg-4 progress-img" style="display: none"><img src="/img/loader.gif"></div>
                </div>
            </div>
        </div>
        <p class="cart_navigation clearfix">
            <input type="hidden" name="step" value="4" />
                    <a href="{$link->getPageLink('order', true, NULL, "step=4{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}" title="{l s='Previous' mod='axiomuspostcarrier'}" class="button-exclusive btn btn-default">
                        <i class="icon-chevron-left"></i>
                        {l s='Вернуться' mod='axiomuspostcarrier'}
                    </a>

                <button type="submit" name="processCarrier" class="button btn btn-default standard-checkout button-medium">
							<span>
								{l s='Продолжить' mod='axiomuspostcarrier'}
                                <i class="icon-chevron-right right"></i>
							</span>
                </button>

        </p>
    </form>
    <script>
        $(document).ready(function () {
            $('#delivery_date').datepicker();
//            $('.customTimePicker').timepicker({
//                'step': 60,
//                'timeFormat': 'H:i',
//                'useSelect': 'true',
////                'disableTimeRanges': [
////                    ['0', '9'],
////                    ['22', '24']
////                ],
//                'minTime': '10',
//                'maxTime': '22',
//                'showDuration': false
//            });
//
//            $('#time_from').timepicker('setTime', new Date(0, 0, 0, 10, 0, 0, 0));
//            $('#time_to').timepicker('setTime', new Date(0, 0, 0, 22, 0, 0, 0));

            var radioInputDelivery = $('#opt-delivery');
            radioInputDelivery.prop('checked', true);

            {if $city=='mscw' or $city=='ptr'}
                $("body").on("change", "input[type=radio]", function () {
                    if(radioInputDelivery.prop('checked')){
                        $('#rowDateTime').show();
                    }else{
                        $('#rowDateTime').hide();
                    }
                });
            {/if}

            $('.radio-inline').click(function () {
                updatePrice();
            });

            $('select').change(function () {
                updatePrice();
            });
//            $('input').change(function () {
//                updatePrice();
//            });

            function updatePrice() {
                carry = $("input[name=delivery-type]").val();
                data = 'carry='+carry+'&city='+'{$city}&weight={$weight}&price={$productprice}';
                if (carry=='0'){
                    kadtype = $("#kad_type").val();
                    deliveryDate = $("#delivery_date").val();
                    timetype = $("#time_type").val();

                    data += '&kad='+kadtype+'&date='+deliveryDate;
                    data += '&time='+timetype;
                    console.log(data);
                }else{
                    //Для самовывоза
                }
                $.ajax({
                    type: 'POST',
                    url: '/index.php?fc=module&module=axiomuspostcarrier&controller=getprice',
                    data: data,
                    beforeSend: function () {
                        $('.post-progress').hide();
                        $('.progress-img').show();
                    },
                    success: function(price){
                        $('.post-progress').show();
                        $('.progress-img').hide();
                        productPrice = {$productprice};
                        $('#deliveryPrice').text(price+'р.');
                        $('#sumPrice').text((parseFloat(productPrice)+parseFloat(price)).toFixed(2)+'р.');
                    }
                });
            }
//            $("body").on("click", "#select", function () {
//                $("input[type=checkbox]").prop("checked", false).change();
//            });
        });
    </script>
{/if}
