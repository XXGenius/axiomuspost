
{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}

    <!-- Tab nav -->
    <ul class="nav nav-tabs" id="tabDelivery">

        <li class="active">
            <a href="#moscow">
                <i class="icon-truck "></i>
                {l s='Москва'} {*<span class="badge"></span>*}
            </a>
        </li>
        <li>
            <a href="#piter">
                <i class="icon-truck"></i>
                {l s='Санкт-Петербург'}
            </a>
        </li>
        <li>
            <a href="#settings">
                <i class="icon-AdminAdmin"></i>
                {l s='Настройки'}
            </a>
        </li>
    </ul>
    <!-- Tab content -->
    <div class="tab-content panel">
        <!-- Tab shipping -->
        <div class="tab-pane active" id="moscow">
            <!-- Tab nav -->
            <!-- Виды доставок в москве-->
            <ul class="nav nav-tabs" id="tabMoscow">
                <li class="active">
                    <a href="#mscw-settings">
                        <i class="icon-AdminAdmin"></i>
                        {l s='Настройки'} <span class="badge"></span>
                    </a>
                </li>
                {if $use_mscw_axiomus}
                    <li>
                        <a href="#mscw-axiomus">
                            {l s='Axiomus'} <span class="badge"></span>
                        </a>
                    </li>
                {/if}
                {if $use_mscw_topdelivery}
                    <li>
                        <a href="#mscw-topdelivery">
                            {l s='TopDelivery'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_dpd}
                    <li>
                        <a href="#mscw-dpd">
                            {l s='DPD'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_boxberry}
                    <li>
                        <a href="#mscw-boxberry">
                            {l s='BoxBerry'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_axiomus_carry}
                    <li>
                        <a href="#mscw-axiomus-carry">
                            {l s='axiomus(самовывоз)'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_topdelivery_carry}
                    <li>
                        <a href="#mscw-topdelivery-carry">
                            {l s='TopDelivery(самовывоз)'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_dpd_carry}
                    <li>
                        <a href="#mscw-dpd-carry">
                            {l s='DPD(самовывоз)'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_boxberry_carry}
                    <li>
                        <a href="#mscw-boxberry-carry">
                            {l s='BoxBerry(самовывоз)'}
                        </a>
                    </li>
                {/if}
                {if $use_mscw_russianpost_carry}
                    <li>
                        <a href="#mscw-russianpost-carry">
                            {l s='RussianPost(самовывоp)'}
                        </a>
                    </li>
                {/if}
            </ul>
            <!-- Tab Moscow content -->
            <div class="tab-content panel">

                <!-- Tab mscw-setting -->
                <div class="tab-pane active" id="mscw-settings">
                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="mscw-setting-form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-lg-12 alert alert-info">
                                    <strong>Внимание!</strong> При изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий перевозчик
                                </div>
                            </div>
                            <!-- Доставки -->
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать доставку Axiomus'}</label>
                                 <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-axiomus" id="use-mscw-axiomus-on" value="1" {if $use_mscw_axiomus} checked="checked"{/if}>
                                        <label for="use-mscw-axiomus-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-axiomus" id="use-mscw-axiomus-off" value="0" {if !$use_mscw_axiomus} checked="checked"{/if} />
                                        <label for="use-mscw-axiomus-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать доставку TopDelivery'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-topdelivery" id="use-mscw-topdelivery-on" value="1" {if $use_mscw_topdelivery} checked="checked"{/if}>
                                        <label for="use-mscw-topdelivery-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-topdelivery" id="use-mscw-topdelivery-off" value="0" {if !$use_mscw_topdelivery} checked="checked"{/if}/>
                                        <label for="use-mscw-topdelivery-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать доставку DPD'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-dpd" id="use-mscw-dpd-on" value="1" {if $use_mscw_dpd} checked="checked"{/if}>
                                        <label for="use-mscw-dpd-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-dpd" id="use-mscw-dpd-off" value="0" {if !$use_mscw_dpd} checked="checked"{/if}/>
                                        <label for="use-mscw-dpd-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать доставку Boxberry'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-boxberry" id="use-mscw-boxberry-on" value="1" {if $use_mscw_boxberry} checked="checked"{/if}>
                                        <label for="use-mscw-boxberry-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-boxberry" id="use-mscw-boxberry-off" value="0" {if !$use_mscw_boxberry} checked="checked"{/if} />
                                        <label for="use-mscw-boxberry-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <!-- Самовывоз -->
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать самовывоз Axiomus'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-axiomus-carry" id="use-mscw-axiomus-carry-on" value="1" {if $use_mscw_axiomus_carry} checked="checked"{/if}>
                                        <label for="use-mscw-axiomus-carry-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-axiomus-carry" id="use-mscw-axiomus-carry-off" value="0" {if !$use_mscw_axiomus_carry} checked="checked"{/if} />
                                        <label for="use-mscw-axiomus-carry-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать самовывоз TopDelivery'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-topdelivery-carry" id="use-mscw-topdelivery-carry-on" value="1" {if $use_mscw_topdelivery_carry} checked="checked"{/if}>
                                        <label for="use-mscw-topdelivery-carry-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-topdelivery-carry" id="use-mscw-topdelivery-carry-off" value="0" {if !$use_mscw_topdelivery_carry} checked="checked"{/if} />
                                        <label for="use-mscw-topdelivery-carry-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать самовывоз DPD'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-dpd-carry" id="use-mscw-dpd-carry-on" value="1" {if $use_mscw_dpd_carry} checked="checked"{/if}>
                                        <label for="use-mscw-dpd-carry-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-dpd-carry" id="use-mscw-dpd-carry-off" value="0" {if !$use_mscw_dpd_carry} checked="checked"{/if} />
                                        <label for="use-mscw-dpd-carry-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать самовывоз Boxberry'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-boxberry-carry" id="use-mscw-boxberry-carry-on" value="1" {if $use_mscw_boxberry_carry} checked="checked"{/if}>
                                        <label for="use-mscw-boxberry-carry-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-boxberry-carry" id="use-mscw-boxberry-carry-off" value="0" {if !$use_mscw_boxberry_carry} checked="checked"{/if} />
                                        <label for="use-mscw-boxberry-carry-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3">{l s='Использовать самовывоз RussianPost'}</label>
                                <div class="col-lg-1">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="use-mscw-russianpost-carry" id="use-mscw-russianpost-carry-on" value="1" {if $use_mscw_russianpost_carry} checked="checked"{/if}>
                                        <label for="use-mscw-russianpost-carry-on">{l s='Да'}</label>
                                        <input type="radio" name="use-mscw-russianpost-carry" id="use-mscw-russianpost-carry-off" value="0" {if !$use_mscw_russianpost_carry} checked="checked"{/if}" />
                                        <label for="use-mscw-russianpost-carry-off">{l s='Нет'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-5">
                                    <button type="submit" id="submitUseDelivery" class="btn btn-primary pull-right" name="submitUseDelivery">
                                        {l s='Сохранить'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Tab mscw-axiomus -->
                <div class="tab-pane" id="mscw-axiomus">
                    <h4>{l s='Axiomus'} </h4>
                </div>
                <!-- Tab mscw-topdelivery -->
                <div class="tab-pane" id="mscw-topdelivery">
                    <h4>{l s='TopDelivery'} </h4>
                </div>
                <!-- Tab mscw-dpd -->
                <div class="tab-pane" id="mscw-dpd">
                    <h4>{l s='DPD'} </h4>
                </div>
                <!-- Tab mscw-boxberry -->
                <div class="tab-pane" id="mscw-boxberry">
                    <h4>{l s='Boxberry'} </h4>
                </div>
                <!-- Tab mscw-axiomus-carry -->
                <div class="tab-pane" id="mscw-axiomus-carry">
                    <h4>{l s='Axiomus carry'} </h4>
                </div>
                <!-- Tab mscw-topdelivery-carry -->
                <div class="tab-pane" id="mscw-topdelivery-carry">
                    <h4>{l s='TopDelivery carry'} </h4>
                </div>
                <!-- Tab mscw-dpd-carry -->
                <div class="tab-pane" id="mscw-dpd-carry">
                    <h4>{l s='DPD carry'} </h4>
                </div>
                <!-- Tab mscw-boxberry-carry -->
                <div class="tab-pane" id="mscw-boxberry-carry">
                    <h4>{l s='boxberry carry'} </h4>
                </div>
                <!-- Tab mscw-russianpost-carry -->
                <div class="tab-pane" id="mscw-russianpost-carry">
                    <h4>{l s='russian post'} </h4>
                </div>
            </div>



            <!-- Shipping block -->
            {*{if !$order->isVirtual()}*}
                {*<div class="form-horizontal">*}
                    {*{if $order->gift_message}*}
                        {*<div class="form-group">*}
                            {*<label class="control-label col-lg-3">{l s='Message'}</label>*}
                            {*<div class="col-lg-9">*}
                                {*<p class="form-control-static">{$order->gift_message|nl2br}</p>*}
                            {*</div>*}
                        {*</div>*}
                    {*{/if}*}
                    {*{include file='controllers/orders/_shipping.tpl'}*}
                    {*{if $carrierModuleCall}*}
                        {*{$carrierModuleCall}*}
                    {*{/if}*}
                    {*<hr />*}
                    {*{if $order->recyclable}*}
                        {*<span class="label label-success"><i class="icon-check"></i> {l s='Recycled packaging'}</span>*}
                    {*{else}*}
                        {*<span class="label label-inactive"><i class="icon-remove"></i> {l s='Recycled packaging'}</span>*}
                    {*{/if}*}

                    {*{if $order->gift}*}
                        {*<span class="label label-success"><i class="icon-check"></i> {l s='Gift wrapping'}</span>*}
                    {*{else}*}
                        {*<span class="label label-inactive"><i class="icon-remove"></i> {l s='Gift wrapping'}</span>*}
                    {*{/if}*}
                {*</div>*}
            {*{/if}*}
        </div>
        <!-- Tab returns -->
        <div class="tab-pane" id="piter">
            <h4>{l s='Санкт-Петербург'}</h4>
            <!-- Tab nav -->
            <!-- Виды доставок в москве-->
            <ul class="nav nav-tabs" id="tabPiter">
                <li class="active">
                    <a href="#ptr-setting">
                        <i class="icon-AdminAdmin"></i>
                        {l s='Настройки'} <span class="badge"></span>
                    </a>
                </li>
                <li>
                    <a href="#ptr-axiomus">
                        {l s='Axiomus'} <span class="badge"></span>
                    </a>
                </li>
                <li>
                    <a href="#ptr-topdelivery">
                        {l s='TopDelivery'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-dpd">
                        {l s='DPD'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-boxberry">
                        {l s='BoxBerry'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-axiomus-carry">
                        {l s='axiomus(самовывоз)'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-topdelivery-carry">
                        {l s='TopDelivery(самовывоз)'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-dpd-carry">
                        {l s='DPD(самовывоз)'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-boxberry-carry">
                        {l s='BoxBerry(самовывоз)'}
                    </a>
                </li>
                <li>
                    <a href="#ptr-russianpost-carry">
                        {l s='RussianPost(самовывоp)'}
                    </a>
                </li>
            </ul>
            <!-- Tab Moscow content -->
            <div class="tab-content panel">

                <!-- Tab mscw-setting -->
                <div class="tab-pane active" id="ptr-setting">
                    <h4>{l s='Settings'} </h4>
                </div>
                <!-- Tab mscw-axiomus -->
                <div class="tab-pane" id="ptr-axiomus">
                    <h4>{l s='Axiomus'} </h4>
                </div>
                <!-- Tab mscw-topdelivery -->
                <div class="tab-pane" id="ptr-topdelivery">
                    <h4>{l s='TopDelivery'} </h4>
                </div>
                <!-- Tab mscw-dpd -->
                <div class="tab-pane" id="ptr-dpd">
                    <h4>{l s='DPD'} </h4>
                </div>
                <!-- Tab mscw-boxberry -->
                <div class="tab-pane" id="ptr-boxberry">
                    <h4>{l s='Boxberry'} </h4>
                </div>
                <!-- Tab mscw-axiomus-carry -->
                <div class="tab-pane" id="ptr-axiomus-carry">
                    <h4>{l s='Axiomus carry'} </h4>
                </div>
                <!-- Tab mscw-topdelivery-carry -->
                <div class="tab-pane" id="ptr-topdelivery-carry">
                    <h4>{l s='TopDelivery carry'} </h4>
                </div>
                <!-- Tab mscw-dpd-carry -->
                <div class="tab-pane" id="ptr-dpd-carry">
                    <h4>{l s='DPD carry'} </h4>
                </div>
                <!-- Tab mscw-boxberry-carry -->
                <div class="tab-pane" id="ptr-boxberry-carry">
                    <h4>{l s='boxberry carry'} </h4>
                </div>
                <!-- Tab mscw-russianpost-carry -->
                <div class="tab-pane" id="ptr-russianpost-carry">
                    <h4>{l s='russian post'} </h4>
                </div>
            </div>


            {*{if !$order->isVirtual()}*}
                <!-- Return block -->
                {*{if $order->getReturn()|count > 0}*}
                    {*<div class="table-responsive">*}
                        {*<table class="table">*}
                            {*<thead>*}
                            {*<tr>*}
                                {*<th><span class="title_box ">Date</span></th>*}
                                {*<th><span class="title_box ">Type</span></th>*}
                                {*<th><span class="title_box ">Carrier</span></th>*}
                                {*<th><span class="title_box ">Tracking number</span></th>*}
                            {*</tr>*}
                            {*</thead>*}
                            {*<tbody>*}
                            {*{foreach from=$order->getReturn() item=line}*}
                                {*<tr>*}
                                    {*<td>{$line.date_add}</td>*}
                                    {*<td>{$line.type}</td>*}
                                    {*<td>{$line.state_name}</td>*}
                                    {*<td class="actions">*}
                                        {*<span class="shipping_number_show">{if isset($line.url) && isset($line.tracking_number)}<a href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number}</a>{elseif isset($line.tracking_number)}{$line.tracking_number}{/if}</span>*}
                                        {*{if $line.can_edit}*}
                                            {*<form method="post" action="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$order->id|intval}&amp;id_order_invoice={if $line.id_order_invoice}{$line.id_order_invoice|intval}{else}0{/if}&amp;id_carrier={if $line.id_carrier}{$line.id_carrier|escape:'html':'UTF-8'}{else}0{/if}">*}
													{*<span class="shipping_number_edit" style="display:none;">*}
														{*<button type="button" name="tracking_number">*}
															{*{$line.tracking_number|htmlentities}*}
														{*</button>*}
														{*<button type="submit" class="btn btn-default" name="submitShippingNumber">*}
															{*{l s='Update'}*}
														{*</button>*}
													{*</span>*}
                                                {*<button href="#" class="edit_shipping_number_link">*}
                                                    {*<i class="icon-pencil"></i>*}
                                                    {*{l s='Edit'}*}
                                                {*</button>*}
                                                {*<button href="#" class="cancel_shipping_number_link" style="display: none;">*}
                                                    {*<i class="icon-remove"></i>*}
                                                    {*{l s='Cancel'}*}
                                                {*</button>*}
                                            {*</form>*}
                                        {*{/if}*}
                                    {*</td>*}
                                {*</tr>*}
                            {*{/foreach}*}
                            {*</tbody>*}
                        {*</table>*}
                    {*</div>*}
                {*{else}*}
                    {*<div class="list-empty hidden-print">*}
                        {*<div class="list-empty-msg">*}
                            {*<i class="icon-warning-sign list-empty-icon"></i>*}
                            {*{l s='No merchandise returned yet'}*}
                        {*</div>*}
                    {*</div>*}
                {*{/if}*}
                {*{if $carrierModuleCall}*}
                    {*{$carrierModuleCall}*}
                {*{/if}*}
            {*{/if}*}
        </div>
        <div class="tab-pane" id="settings">
            <h4>{l s='Настройки'}</h4>
            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                <div id="setting-form" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-2">{l s='Токен к Axiomus API'}</label>
                        <div class="col-lg-4">
                            <input type="text" id="axiomus-token" class="" name="axiomus-token" value="{$axiomus_token}">
                        </div>
                    </div>
                </div>
                <div id="setting-form" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-2">{l s='Время жизни записи в кеше'}</label>
                        <div class="col-lg-4">
                            <input type="text" id="axiomus-cache-hourlife" class="" name="axiomus-cache-hourlife" value="{$axiomus_cache_hourlife}">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="submit" id="submitSettings" class="btn btn-primary pull-right" name="submitSettings">
                            {l s='Сохранить'}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        //ToDo добавить валидацию полей
        $('#tabDelivery a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
        $('#tabMoscow a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
        $('#tabPiter a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    </script>

{/block}