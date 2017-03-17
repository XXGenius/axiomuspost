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

{assign var='current_step' value='shipping'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
    <p class="warning">{l s='Your shopping cart is empty.' mod='axiomuspostcarrier'}</p>
{else}

    <h3>{l s='Уточнения по поводу доставки' mod='axiomuspostcarrier'}</h3>
    <form action="{$link->getModuleLink('axiomuspostcarrier', 'validation', [], true)|escape:'html'}" method="post">
        <div class="required form-group">
            <label for="date_delivery">{l s='Дата доставки: (не раньше сегодняшнего дня)'} <sup>*</sup></label>
            <input type="text" class="is_required form-control" data-validate="isGenericName" id="date_delivery" name="date_delivery" value="{$tomorrow|date_format:"%d.%m.%Y"}" />
        </div>
        <div class="required form-group">
            <label for="time_from">{l s='с: '} <sup>*</sup></label>
            <input type="text" class="is_required form-control customTimePicker" data-validate="isGenericName" id="time_from" name="time_from" value="{if isset($smarty.post.guest_email)}{$smarty.post.guest_email}{/if}" />
        </div>
        <div class="required form-group">
            <label for="time_to">{l s='по: '} <sup>*</sup></label>
            <input type="text" class="is_required form-control customTimePicker" data-validate="isGenericName" id="time_to" name="time_to" value="{if isset($smarty.post.guest_email)}{$smarty.post.guest_email}{/if}" />
        </div>
        <p class="cart_navigation clearfix">
            <input type="hidden" name="step" value="4" />
                    <a href="{$link->getPageLink('order', true, NULL, "step=4{if $multi_shipping}&multi-shipping={$multi_shipping}{/if}")|escape:'html':'UTF-8'}" title="{l s='Previous' mod='axiomuspostcarrier'}" class="button-exclusive btn btn-default">
                        <i class="icon-chevron-left"></i>
                        {l s='Continue shopping' mod='axiomuspostcarrier'}
                    </a>

                <button type="submit" name="processCarrier" class="button btn btn-default standard-checkout button-medium">
							<span>
								{l s='Proceed to checkout' mod='axiomuspostcarrier'}
                                <i class="icon-chevron-right right"></i>
							</span>
                </button>

        </p>
    </form>
    <script>
        $(document).ready(function () {
            $('#date_delivery').datepicker();
            $('.customTimePicker').timepicker({
                'step': 60,
                'timeFormat': 'H:i',
                'useSelect': 'true',
//                'disableTimeRanges': [
//                    ['0', '9'],
//                    ['22', '24']
//                ],
                'minTime': '10',
                'maxTime': '22',
                'showDuration': false
            });

            $('#time_from').timepicker('setTime', new Date(0, 0, 0, 10, 0, 0, 0));
            $('#time_to').timepicker('setTime', new Date(0, 0, 0, 22, 0, 0, 0));
        });
    </script>
{/if}
