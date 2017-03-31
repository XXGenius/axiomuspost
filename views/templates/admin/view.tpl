
{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}

    <!-- Tab nav -->
    <ul class="nav nav-tabs" id="tabDelivery">

        <li {if $maintab == 0}class="active"{/if}>
            <a href="#moscow">
                <i class="icon-truck "></i>
                {l s='Москва'} {*<span class="badge"></span>*}
            </a>
        </li>
        <li {if $maintab == 1}class="active"{/if}>
            <a href="#piter" >
                <i class="icon-truck"></i>
                {l s='Санкт-Петербург'}
            </a>
        </li>
        <li {if $maintab == 2}class="active"{/if}>
            <a href="#region">
                <i class="icon-plane"></i>
                {l s='Регионы'}
            </a>
        </li>
        <li {if $maintab == 3}class="active"{/if}>
            <a href="#settings">
                <i class="icon-AdminAdmin"></i>
                {l s='Настройки'}
            </a>
        </li>
    </ul>
    <!-- Tab content -->
    <div class="tab-content panel">
        <!-- Tab shipping -->
        <div class="tab-pane {if $maintab == 0}active{/if}" id="moscow">
            <!-- Tab nav -->
            <!-- Виды доставок в москве-->
            <ul class="nav nav-tabs" id="tabMoscow">
                <li {if $subtab == 0}class="active"{/if}>
                    <a href="#mscw-settings">
                        <i class="icon-AdminAdmin"></i>
                        {l s='Настройки'} <span class="badge"></span>
                    </a>
                </li>

                <li {if $subtab == 1}class="active"{/if}>
                    <a href="#mscw-weighttype">
                        {l s='Интервалы веса'} <span class="badge"></span>
                    </a>
                </li>


                <li {if $subtab == 2}class="active"{/if}>
                    <a href="#mscw-timetype">
                        <i class="icon-clock-o"></i>
                        {l s='Временные интервалы'}
                    </a>
                </li>


                <li {if $subtab == 3}class="active"{/if}>
                    <a href="#mscw-kadtype">
                        <i class="icon-road"></i>
                        {l s='МКАД'}
                    </a>
                </li>


                <li {if $subtab == 4}class="active"{/if}>
                    <a href="#mscw-weightprice">
                        <i class="icon-ruble"></i>
                        {l s='Прайс по весу'}
                    </a>
                </li>

                <li {if $subtab == 5}class="active"{/if}>
                    <a href="#mscw-conditionprice">
                        <i class="icon-plus"></i>
                        {l s='Надбавка по условию'}
                    </a>
                </li>
                {if ($use_mscw_axiomus_carry)}
                <li {if $subtab == 6}class="active"{/if}>
                    <a href="#mscw-carry-axiomus">
                        {l s='Самовывоз Axiomus'}
                    </a>
                </li>
                {/if}
                {if ($use_mscw_dpd_carry)}
                <li {if $subtab == 7}class="active"{/if}>
                    <a href="#mscw-carry-dpd">
                        {l s='Самовывоз DPD'}
                    </a>
                </li>
                {/if}
                {if ($use_mscw_boxberry_carry)}
                <li {if $subtab == 8}class="active"{/if}>
                    <a href="#mscw-carry-boxberry">
                        {l s='Самовывоз BoxBerry'}
                    </a>
                </li>
                {/if}
                    {*<li {if $subtab == 6}class="active"{/if}>*}
                        {*<a href="#mscw-topdelivery-carry">*}
                            {*{l s='TopDelivery(самовывоз)'}*}
                        {*</a>*}
                    {*</li>*}
               {**}
                    {*<li {if $subtab == 7}class="active"{/if}>*}
                        {*<a href="#mscw-dpd-carry">*}
                            {*{l s='DPD(самовывоз)'}*}
                        {*</a>*}
                    {*</li>*}
            {**}
                    {*<li {if $subtab == 8}class="active"{/if}>*}
                        {*<a href="#mscw-boxberry-carry">*}
                            {*{l s='BoxBerry(самовывоз)'}*}
                        {*</a>*}
                    {*</li>*}
                {**}
                    {*<li {if $subtab == 9}class="active"{/if}>*}
                        {*<a href="#mscw-russianpost-carry">*}
                            {*{l s='RussianPost(самовывоp)'}*}
                        {*</a>*}
                    {*</li>*}
            </ul>
            <!-- Tab Moscow content -->
            <div class="tab-content panel">
                <!-- Tab mscw-setting -->
                <div class="tab-pane {if $subtab == 0}active{/if}" id="mscw-settings">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-AdminAdmin"></i>
                                    Использование доставки
                                </div>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                    <div id="mscw-setting-form" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-lg-12 alert alert-info">
                                                <strong>Внимание!</strong> После изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий пункт при выборе способа доставки в фронтальной части магазина
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
                                            <label class="control-label col-lg-3">{l s='Использовать доставку Стриж'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-mscw-strizh" id="use-mscw-strizh-on" value="1" {if $use_mscw_strizh} checked="checked"{/if}>
                                                    <label for="use-mscw-strizh-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-mscw-strizh" id="use-mscw-strizh-off" value="0" {if !$use_mscw_strizh} checked="checked"{/if}/>
                                                    <label for="use-mscw-strizh-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать доставку ПЭК'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-mscw-pek" id="use-mscw-pek-on" value="1" {if $use_mscw_pek} checked="checked"{/if}>
                                                    <label for="use-mscw-pek-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-mscw-pek" id="use-mscw-pek-off" value="0" {if !$use_mscw_pek} checked="checked"{/if}/>
                                                    <label for="use-mscw-pek-off">{l s='Нет'}</label>
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
                        </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-money"></i>
                                    Кэш записей о самовывозе
                                </div>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                    <div id="mscw-setting-form" class="form-horizontal">
                                        <div class="row">
                                            Дата последнего обновления: <b>{$AxiomusPost->getLastUpdateCacheCarry('axiomus')}</b>
                                            <button type="submit" id="submitRefreshCacheCarryAddressesAxiomus" class="btn btn-success pull-right" name="submitRefreshCacheCarryAddressesAxiomus">
                                                {l s='Обновить кэш Axiomus'}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="mscw-setting-form" class="form-horizontal">
                                    <div class="row">
                                        Дата последнего обновления: <b>{$AxiomusPost->getLastUpdateCacheCarry('dpd')}</b>
                                        <button type="submit" id="submitRefreshCacheCarryAddressesDPD" class="btn btn-success pull-right" name="submitRefreshCacheCarryAddressesDPD">
                                            {l s='Обновить кэш DPD'}
                                        </button>
                                    </div>
                                </div>
                                </form>
                                <br>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="mscw-setting-form" class="form-horizontal">
                                    <div class="row">
                                        Дата последнего обновления: <b>{$AxiomusPost->getLastUpdateCacheCarry('boxberry')}</b>
                                        <button type="submit" id="submitRefreshCacheCarryAddressesBoxBerry" class="btn btn-success pull-right" name="submitRefreshCacheCarryAddressesBoxBerry">
                                            {l s='Обновить кэш BoxBerry'}
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab mscw-weighttype -->
                <div class="tab-pane {if $subtab == 1}active{/if}" id="mscw-weighttype">
                    <div class="row">
                        <!-- WeightType -->
                        <div class="panel">
                            <div class="panel-heading">
                                <i class=""></i>
                                Виды веса
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><span class="title_box ">id</span></th>
                                        <th><span class="title_box ">Имя</span></th>
                                        <th><span class="title_box ">Вес от(кг.)</span></th>
                                        <th><span class="title_box ">Вес до(кг.)</span></th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                    <tr class="current-edit">
                                        <td></td>
                                        <td>
                                            <input type=text name="mscw-axiomus-weighttype-name" class="form-control">
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-weighttype-weightfrom" class="form-control">
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-weighttype-weightto" class="form-control">
                                        </td>
                                        <td>
                                            <button type="submit" id="submitMscwAxiomusWeightType" class="btn btn-primary pull-right" name="submitMscwAxiomusWeightType">
                                                {l s='Добавить'}
                                            </button>
                                        </td>
                                    </tr>

                                    </form>
                                    </tfoot>
                                    <tbody>
                                    {foreach from=$AxiomusPost->getAllWeightType('Москва') item=line}
                                        <tr>
                                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                            <td><input type="hidden" name="mscw-axiomus-weighttype-id" value="{$line.id}">{$line.id}</td>
                                            <td><input type="text" name="mscw-axiomus-weighttype-name" value="{$line.name}"></td>
                                            <td><input type="text" name="mscw-axiomus-weighttype-weightfrom" value="{$line.weightfrom}"></td>
                                            <td><input type="text" name="mscw-axiomus-weighttype-weightto" value="{$line.weightto}"></td>
                                            <td class="fixed-width-sm">
                                                <div class="row">
                                                    <button type="submit" id="deleteMscwAxiomusWeightType" class="btn btn-danger pull-right" name="deleteMscwAxiomusWeightType">
                                                        <i class="icon-remove"></i>
                                                    </button>
                                                    <button type="submit" id="updateMscwAxiomusWeightType" class="btn btn-success pull-right" name="updateMscwAxiomusWeightType">
                                                        <i class="icon-pencil" ></i>
                                                    </button>
                                                </div>
                                            </td>
                                            </form>
                                        </tr>
                                    {/foreach}

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Tab mscw-timetype -->
                <div class="tab-pane {if $subtab == 2}active{/if}" id="mscw-timetype">
                    <div class="row">
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-clock-o"></i>
                                Временные интервалы
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><span class="title_box ">id</span></th>
                                        <th><span class="title_box ">Имя</span></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                    <tr class="current-edit">
                                        <td></td>
                                        <td>
                                            <input type=text name="mscw-axiomus-timetype-name" class="form-control">
                                            {*customTimePicker*}
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-timetype-timefrom" class="form-control customTimePicker">
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-timetype-timeto" class="form-control customTimePicker">
                                        </td>
                                        <td>
                                            <button type="submit" id="submitMscwAxiomusTimeType" class="btn btn-primary pull-right" name="submitMscwAxiomusTimeType">
                                                {l s='Добавить'}
                                            </button>
                                        </td>
                                    </tr>

                                    </form>
                                    </tfoot>
                                    <tbody>
                                    {foreach from=$AxiomusPost->getAllTimeType('Москва') item=line}
                                        <tr>
                                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                            <input type="hidden" name="mscw-axiomus-timetype-id" value="{$line.id}">
                                            <td>{$line.id}</td>
                                            <td><input type="text" name="mscw-axiomus-timetype-name" class="form-control" value="{$line.name}"></td>
                                            <td><input type="text" name="mscw-axiomus-timetype-timefrom" class="form-control customTimePicker" value="{$line.timefrom}"></td>
                                            <td><input type="text" name="mscw-axiomus-timetype-timeto" class="form-control customTimePicker" value="{$line.timeto}"></td>
                                            <td class="fixed-width-sm">
                                                <div class="row">
                                                    <button type="submit" id="deleteMscwAxiomusTimeType" class="btn btn-danger pull-right" name="deleteMscwAxiomusTimeType">
                                                        <i class="icon-remove"></i>
                                                    </button>
                                                    <button type="submit" id="updateMscwAxiomusTimeType" class="btn btn-success pull-right" name="updateMscwAxiomusTimeType">
                                                        <i class="icon-pencil" ></i>
                                                    </button>
                                                </div>
                                            </td>
                                            </form>
                                        </tr>
                                    {/foreach}

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Tab mscw-kadtype -->
                <div class="tab-pane {if $subtab == 3}active{/if}" id="mscw-kadtype">
                    <div class="row">
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-road"></i>
                                промежутки расстояния от МКАД
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><span class="title_box ">id</span></th>
                                        <th><span class="title_box ">Имя</span></th>
                                        <th><span class="title_box ">От(км.)</span></th>
                                        <th><span class="title_box ">До(км.)</span></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                    <tr class="current-edit">
                                        <td></td>
                                        <td>
                                            <input type=text name="mscw-axiomus-kadtype-name" class="form-control">
                                            {*customTimePicker*}
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-kadtype-rangefrom" class="form-control">
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-kadtype-rangeto" class="form-control">
                                        </td>
                                        <td>
                                            <button type="submit" id="submitMscwAxiomusKadType" class="btn btn-primary pull-right" name="submitMscwAxiomusKadType">
                                                {l s='Добавить'}
                                            </button>
                                        </td>
                                    </tr>

                                    </form>
                                    </tfoot>
                                    <tbody>
                                    {foreach from=$AxiomusPost->getAllKadType('Москва') item=line}
                                        <tr>
                                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                            <td><input type="hidden" name="mscw-axiomus-kadtype-id" value="{$line.id}">{$line.id}</td>
                                            <td><input type="text" name="mscw-axiomus-kadtype-name" class="form-control" value="{$line.name}"></td>
                                            <td><input type="text" name="mscw-axiomus-kadtype-timefrom" class="form-control" value="{$line.rangefrom}"></td>
                                            <td><input type="text" name="mscw-axiomus-kadtype-timeto" class="form-control" value="{$line.rangeto}"></td>
                                            <td class="fixed-width-sm">
                                                <div class="row">
                                                    <button type="submit" id="deleteMscwAxiomusKadType" class="btn btn-danger pull-right" name="deleteMscwAxiomusKadType">
                                                        <i class="icon-remove"></i>
                                                    </button>
                                                    <button type="submit" id="updateMscwAxiomusKadType" class="btn btn-success pull-right" name="updateMscwAxiomusKadType">
                                                        <i class="icon-pencil" ></i>
                                                    </button>
                                                </div>
                                            </td>
                                            </form>
                                        </tr>
                                    {/foreach}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab mscw-weightprice -->
                <div class="tab-pane {if $subtab == 4}active{/if}" id="mscw-weightprice">
                    <div class="row">
                       <!-- WeightPrice -->
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-ruble"></i>
                                Сумма в зависимости от веса
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><span class="title_box ">id</span></th>
                                        <th><span class="title_box ">Самовывоз</span></th>
                                        <th><span class="title_box ">Тип веса</span></th>
                                        <th><span class="title_box ">Сумма</span></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                    <tr class="current-edit">
                                        <td></td>

                                        <td class="row-selector text-center"><input type="checkbox" name="mscw-axiomus-weightprice-carry" class="noborder"></td>
                                        <td>
                                            <select class="form-control " id="mscw-axiomus-weightprice-type" name="mscw-axiomus-weightprice-type">
                                                {foreach from=$AxiomusPost->getAllWeightType('Москва') key=k item=line}
                                                    <option value="{$k+1}">{$line.name}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td>
                                            <input type=text name="mscw-axiomus-weightprice-sum" class="form-control">
                                        </td>
                                        <td>
                                            <button type="submit" id="submitMscwAxiomusWeightPrice" class="btn btn-primary pull-right" name="submitMscwAxiomusWeightType">
                                                {l s='Добавить'}
                                            </button>
                                        </td>
                                    </tr>

                                    </form>
                                    </tfoot>
                                    <tbody>
                                    {foreach from=$AxiomusPost->getAllWeightPrice('Москва') item=line}
                                        <tr>
                                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                            <td><input type="hidden" name="mscw-axiomus-weightprice-id" class="form-control" value="{$line.id}">{$line.id}</td>

                                            <td class="row-selector text-center"><input type="checkbox" name="mscw-axiomus-weightprice-carry" class="noborder" {if ($line.carry == 1)}checked{/if}"></td>
                                            <td>
                                                <select class="form-control " id="mscw-axiomus-weightprice-type" name="mscw-axiomus-weightprice-type">
                                                    {foreach from=$AxiomusPost->getAllWeightType('Москва') key=k item=linetype}
                                                        <option value="{$k}">{$linetype.name}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td><input type=text name="mscw-axiomus-weightprice-sum" class="form-control" value="{$line.sum}"></td>
                                            <td class="fixed-width-sm">
                                                <div class="row">
                                                    <button type="submit" id="deleteMscwAxiomusWeightPrice" class="btn btn-danger pull-right" name="deleteMscwAxiomusWeightPrice">
                                                        <i class="icon-remove"></i>
                                                    </button>
                                                    <button type="submit" id="updateMscwAxiomusWeightPrice" class="btn btn-success pull-right" name="updateMscwAxiomusWeightPrice">
                                                        <i class="icon-pencil" ></i>
                                                    </button>
                                                </div>
                                            </td>
                                            </form>
                                        </tr>
                                    {/foreach}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab mscw-conditionprice -->
                <div class="tab-pane {if $subtab == 5}active{/if}" id="mscw-conditionprice">
                    <div class="row">
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-plus"></i>
                                Добавка к сумме по условию
                            </div>
                            <!-- KAD price -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><span class="title_box ">id</span></th>

                                        <th><span class="title_box ">Самовывоз</span></th>
                                        <th><span class="title_box ">Сумма от</span></th>
                                        <th><span class="title_box ">Сумма до</span></th>
                                        <th><span class="title_box ">Время</span></th>
                                        <th><span class="title_box ">МКАД</span></th>
                                        <th><span class="title_box ">Сумма</span></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                                <tr class="current-edit">
                                                    <td></td>

                                                    <td class="row-selector text-center"><input type="checkbox" name="mscw-axiomus-conditionprice-carry" class="noborder"></td>
                                                    <td>
                                                        <input type=text name="mscw-axiomus-conditionprice-sumfrom" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type=text name="mscw-axiomus-conditionprice-sumto" class="form-control">
                                                    </td>
                                                    <td>
                                                        <select class="form-control " id="mscw-axiomus-conditionprice-timetype" name="mscw-axiomus-conditionprice-timetype">
                                                            {foreach from=$AxiomusPost->getAllTimeType('Москва') key=k item=line}
                                                                <option value="{$k+1}">{$line.name}</option>
                                                            {/foreach}
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control " id="mscw-axiomus-conditionprice-kadtype" name="mscw-axiomus-conditionprice-kadtype">
                                                            {foreach from=$AxiomusPost->getAllKadType('Москва') key=k item=line}
                                                                <option value="{$k+1}">{$line.name}</option>
                                                            {/foreach}
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type=text name="mscw-axiomus-conditionprice-sum" class="form-control">
                                                    </td>
                                                    <td>
                                                        <button type="submit" id="submitMscwAxiomusConditionPrice" class="btn btn-primary pull-right" name="submitMscwAxiomusConditionPrice">
                                                            {l s='Добавить'}
                                                        </button>
                                                    </td>
                                                </tr>

                                        </form>
                                    </tfoot>
                                    <tbody>
                                    {foreach from=$AxiomusPost->getAllConditionPrice('Москва') item=line}
                                        <tr>
                                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                                <td><input type="hidden" name="mscw-axiomus-conditionprice-id" value="{$line.id}">{$line.id}</td>

                                                <td class="row-selector text-center"><input type="checkbox" name="mscw-axiomus-conditionprice-carry" class="noborder" {if ($line.carry == 1)}checked{/if}></td>
                                                <td><input type="text" name="mscw-axiomus-conditionprice-sumfrom" class="form-control" value="{$line.sumfrom}"></td>
                                                <td><input type="text" name="mscw-axiomus-conditionprice-sumto" class="form-control" value="{$line.sumto}"></td>
                                                <td>
                                                    <select class="form-control " id="mscw-axiomus-conditionprice-timetype" name="mscw-axiomus-conditionprice-timetype">
                                                        {foreach from=$AxiomusPost->getAllTimeType('Москва') key=k item=linetime}
                                                            <option value="{$k}" {if ($line.timetype == $linetime.id)}selected{/if}>{$linetime.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control " id="mscw-axiomus-conditionprice-kadtype" name="mscw-axiomus-conditionprice-kadtype">
                                                        {foreach from=$AxiomusPost->getAllKadType('Москва') key=k item=linekad}
                                                            <option value="{$k}" {if ($line.kadtype == $linekad.id)}selected{/if}>{$linekad.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td><input type="text" name="mscw-axiomus-conditionprice-sum" value="{$line.sum}"></td>
                                                <td class="fixed-width-sm">
                                                    <div class="row">
                                                        <button type="submit" id="deleteMscwAxiomusConditionPrice" class="btn btn-danger pull-right" name="deleteMscwAxiomusConditionPrice">
                                                            <i class="icon-remove"></i>
                                                        </button>
                                                        <button type="submit" id="updateMscwAxiomusConditionPrice" class="btn btn-success pull-right" name="updateMscwAxiomusConditionPrice">
                                                            <i class="icon-pencil" ></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </form>
                                        </tr>
                                    {/foreach}

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end condition price -->
                <div class="tab-pane {if $subtab == 6}active{/if}" id="mscw-carry-axiomus">
                    <div class="row">
                        <!-- carryAxiomus -->
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-money"></i>
                                Самовывоз Axiomus
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="setting-form" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                        <div class="col-lg-4">
                                            <input type="text" id="mscw-carry-axiomus-price" class="" name="mscw-carry-axiomus-price" value="{$AxiomusPost->getCarryPriceByName('Москва', 'axiomus')}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitMscwAxiomusCarryPrice" class="btn btn-primary pull-right" name="submitMscwAxiomusCarryPrice">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end condition price -->
                <div class="tab-pane {if $subtab == 7}active{/if}" id="mscw-carry-dpd">
                    <div class="row">
                        <!-- carryAxiomus -->
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-money"></i>
                                Самовывоз DPD
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="setting-form" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                        <div class="col-lg-4">
                                            <input type="text" id="mscw-carry-dpd-price" class="" name="mscw-carry-dpd-price" value="{$AxiomusPost->getCarryPriceByName('Москва', 'dpd')}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitMscwDPDPrice" class="btn btn-primary pull-right" name="submitMscwDPDPrice">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end condition price -->
                <div class="tab-pane {if $subtab == 8}active{/if}" id="mscw-carry-boxberry">
                    <div class="row">
                        <!-- carryAxiomus -->
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-money"></i>
                                Самовывоз BoxBerry
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="setting-form" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                        <div class="col-lg-4">
                                            <input type="text" id="mscw-carry-boxberry-price" class="" name="mscw-carry-boxberry-price" value="{$AxiomusPost->getCarryPriceByName('Москва', 'boxberry')}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitMscwBoxBerryPrice" class="btn btn-primary pull-right" name="submitMscwBoxBerryPrice">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tab returns -->
        <div class="tab-pane {if $maintab == 1}active{/if}" id="piter">
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
            </ul>
            <!-- Tab Moscow content -->
            <div class="tab-content panel">

                <!-- Tab mscw-setting -->
                <div class="tab-pane active" id="ptr-setting">
                    <h4>{l s='Settings'} </h4>
                </div>
            </div>
        </div>
        <div class="tab-pane {if $maintab == 2}active{/if}" id="region">
            <h4>{l s='Регионы'}</h4>
        </div>
        <div class="tab-pane {if $maintab == 3}active{/if}" id="settings">
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
        $(document).ready(function () {
            $('.customTimePicker').timepicker({
                'step': 60,
                'timeFormat': 'H:i',
                'useSelect': 'true',
                'showDuration': false
            });
//
//            $('#time_from').timepicker('setTime', new Date(0, 0, 0, 10, 0, 0, 0));


        //ToDo добавить валидацию полей
            $('#tabDelivery a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            });
            $('#tabMoscow a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            });
            $('#tabPiter a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            });

            $('.customTimePicker').timepicker({
                'step': 60,
                'timeFormat': 'H:i',
                'useSelect': 'true',
                'showDuration': false
            });
        });
    </script>

{/block}