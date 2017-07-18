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
    {l s='Доставка' mod='axiomuspostcarrier'}
{/capture}

{*{include file="$tpl_dir./breadcrumb.tpl"}*}

<h2>{l s='Доставка:' mod='axiomuspostcarrier'}</h2>
<hr>
{assign var='current_step' value='shipping'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
    <p class="warning">{l s='Your shopping cart is empty.' mod='axiomuspostcarrier'}</p>
{else}
    {if $city == 'Москва' || $city == 'Санкт-Петербург' || $AxiomusPost->getActiveCarry($city)|@count != 0}
        <h4>{l s='Выберите спсоб доставки:' mod='axiomuspostcarrier'}</h4>

        <form action="{$link->getModuleLink('axiomuspostcarrier', 'validation', [], true)|escape:'html'}" method="post" class="form-inline">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="required form-group">
                                <label class="radio-inline delivery-type" id="opt-delivery-parent"><input type="radio" name="delivery-type" value="0" id="opt-delivery" )>Доставка до двери</label>
                                <label class="radio-inline delivery-type"><input type="radio" name="delivery-type" value="1" id="opt-carry">Самовывоз</label>
                                <label class="radio-inline delivery-type" id="pecom-button" ><input type="radio" name="delivery-type" value="2" id="opt-pecom">Доставка в регионы</label>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row" id="rowDelivery">
                        <div class="col-lg-3">
                            <select class="form-control" id="kad_type" name="kad-type">
                                {foreach from=$AxiomusPost->getAllKadType('Москва') key=k item=line}
                                    <option value="{$line.id}">{$line.name}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-lg-6">
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

                        <div class="col-lg-3">
                            <select class="form-control" id="time_type" name="time-type">
                                {foreach from=$AxiomusPost->getAllTimeType('Москва') key=k item=line}
                                    <option value="{$line.id}">{$line.name}</option>
                                {/foreach}
                            </select>

                        </div>
                    </div>

                    <div class="row" id="rowCarry" style="display: none">
                        <div class="col-lg-2">
                            <div class="form-group" id="carry-type-group">
                                {foreach from=$AxiomusPost->getActiveCarry($city) key=k item=line}
                                <label class="radio-inline"><input class="carry-type" type="radio" name="carry-name" value="{$k}" id="carry_{$k}">{$line}</label><br>
                                {/foreach}
                            </div>
                        </div>
                        <div class="col-lg-1" id="progress_img_carry_address" style="display: none"><img src="/img/loader.gif"></div>
                        <div class="col-lg-9" id="carry_address_block">
                            <select class="input-large" id="carry_address" name="carry-address">
                            </select>
                            <div class="">
                                <div class="row">
                                    <table id="carry-address-description">
                                        <tr>
                                            <td>Адрес:</td>
                                            <td id="address"></td>
                                        </tr>
                                        <tr>
                                            <td id="work-schedule-title">Время работы:</td>
                                            <td id="work-schedule"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td id="path"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <input type="hidden" name="cgv" value="1"><!-- Соглашение на условия -->
            </div>

            <p class="cart_navigation clearfix">
                <input type="hidden" name="step" value="4" />
                        <a href="{$link->getPageLink('order', true, NULL, "step=4")|escape:'html':'UTF-8'}" title="{l s='Previous' mod='axiomuspostcarrier'}" class="button-exclusive btn btn-default">
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



                $('#delivery_date').datepicker({ minDate: 0});

                city = '{$city}';
                 if (city == 'Москва' || city == 'Санкт-Петербург') {

                     $('#opt-delivery').prop('checked', true);
                     $('#carry_address_block').hide();
                     $('#pecom-button').hide();
                     updatePrice(0);
                 }else{

                     $('#opt-delivery-parent').hide();
                     $('#opt-carry').prop('checked', true);
                     $('#rowDelivery').hide();
                     $('#rowCarry').show();
                     $('#carry-type-group').find('input:first').prop('checked', true);
                     updateCarry();
                     updatePrice(1);
                 }

                $('.carry-type').click(function () {
                    updateCarry();
                    updatePrice(1);
                });

                $('.delivery-type').click(function () {
                    updatePrice();
                });



                $('.delivery-type').change(function () {
                    radioInputDelivery = $("input[name='delivery-type']");
                    if(radioInputDelivery.prop('checked')) {
                        $('#rowDelivery').show();
                        $('#rowCarry').hide();
                    }else{
                        $('#rowDelivery').hide();
                        $('#rowCarry').show();
                    }
                });

                $('#time_type').change(function () {
                    updatePrice(0);
                });

                $('#kad_type').change(function () {
                    updatePrice(0);
                });

                var jsonArray;

                $('#carry_address').change(function () {
                    selectedcarryaddress = $('#carry_address option:selected').val();

                    console.log(selectedcarryaddress);
                    for (var i in jsonArray) {
                        if(jsonArray[i].id == selectedcarryaddress){
                            $('#address').text(jsonArray[i].address);
                            if (jsonArray[i].work_schedule != null) {
                                $('#work-schedule').text(jsonArray[i].work_schedule);
                                $('#work-schedule-title').show();
                            }else{
                                $('#work-schedule').text('');
                                $('#work-schedule-title').hide();
                            }
                            if (jsonArray[i].path != null) {
                                $('#path').text(jsonArray[i].path);
                            }else{
                                $('#path').text('');
                            }
                        }
                    }
                });

    //            $('select').change(function () {
    //                updatePrice();
    //            });
    //            $('input').change(function () {
    //                updatePrice();
    //            });

                function updateCarry() {
                    carry = $('.carry-type:checked').val();
                    data = 'carry='+carry+'&city='+'{$city}';
                    $.ajax({
                        type: 'POST',
                        url: '/index.php?fc=module&module=axiomuspostcarrier&controller=getcarry',
                        data: data,
                        beforeSend: function () {
                            $('#progress_img_carry_address').show();
                            $('#carry_address_block').hide();
                            console.log('get carry...');
                        },
                        success: function(data) {
                            if (data!='false') {
                                $('#progress_img_carry_address').hide();

                                console.log('get carry... end.');

                                console.log(data);
                                jsonArray = JSON.parse(data);
                                console.log(jsonArray);
                                $('#carry_address').text('');
                                for (var i in jsonArray) {
                                    $('#carry_address').append('<option value="' + jsonArray[i].id + '">' + jsonArray[i].address + '</option>');
                                }
                                $('#carry_address_block').show();

                                $('#address').text(jsonArray[0].address);
                                console.log(jsonArray[0].work_schedule);
                                if (jsonArray[0].work_schedule != null) {
                                    $('#work-schedule').text(jsonArray[0].work_schedule);
                                    $('#work-schedule-title').show();
                                }else{
                                    $('#work-schedule').text('');
                                    $('#work-schedule-title').hide();
                                }
                                if (jsonArray[0].path != null) {
                                    $('#path').text(jsonArray[0].path);
                                }else{
                                    $('#path').text('');
                                }
                            }
                        }
                    });
                }

                function updatePrice(carry) {

                    data = 'cart_id='+'{$cart_id}'+'&carry='+carry+'&city='+'{$city}&weight={$weight}&price={$productprice}';
                    if (carry=='0'){
                        kadtype = $("#kad_type").val();
                        deliveryDate = $("#delivery_date").val();
                        timetype = $("#time_type").val();

                        data += '&kad='+kadtype+'&date='+deliveryDate;
                        data += '&time='+timetype;

                    }else{
                        //Для самовывоза
                        carrytype = $('input[name=carry-name]:checked').val();
                        console.log(carrytype);
                        data += '&carrytype='+carrytype;
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
                            if(price==0){
                                $('#deliveryPrice').text('бесплатно');
                            }else{
                                $('#deliveryPrice').text(price+'р.');
                            }
                            $('#sumPrice').text((parseFloat(productPrice)+parseFloat(price)).toFixed(2)+'р.');
                        }
                    });
                }
    //            $("body").on("click", "#select", function () {
    //                $("input[type=checkbox]").prop("checked", false).change();
    //            });
            });
        </script>
    {else}
        <p class="warning text-error"><h3>Доставка в город: <strong>"{$city}"</strong> в данный момент не производится. Приносим свои извинения.</h3></p>
    {/if}
{/if}
