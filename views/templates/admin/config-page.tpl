
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
        {******************************************************************************** Moscow **************************************************************}
        <!-- Tab Moscow -->
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
                {if ($use_mscw_pecom_carry)}
                <li {if $subtab == 9}class="active"{/if}>
                    <a href="#mscw-carry-pecom">
                        {l s='Самовывоз ПЭК'}
                    </a>
                </li>
                {/if}
                    {*<li {if $subtab == 10}class="active"{/if}>*}
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
                                                <strong>Внимание!</strong> После изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий пункт при выборе способа доставки в <strong>админской </strong> части магазина в управления заказами
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
                                            <label class="control-label col-lg-3">{l s='Использовать доставку DPD'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-mscw-dpd" id="use-mscw-dpd-on" value="1"  disabled>
                                                    <label for="use-mscw-dpd-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-mscw-dpd" id="use-mscw-dpd-off" value="0"  checked="checked"/>
                                                    <label for="use-mscw-pecom-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать доставку ПЭК'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-mscw-pecom" id="use-mscw-pecom-on" value="1" {if $use_mscw_pecom} checked="checked"{/if} disabled>
                                                    <label for="use-mscw-pecom-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-mscw-pecom" id="use-mscw-pecom-off" value="0" {if !$use_mscw_pecom} checked="checked"{/if}/>
                                                    <label for="use-mscw-pecom-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-lg-12 alert alert-info">
                                                <strong>Внимание!</strong> После изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий пункт при выборе способа доставки в <strong>фронтальной </strong> части магазина, при выборе способа доставки
                                            </div>
                                        </div>
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
                                                    <input type="radio" name="use-mscw-russianpost-carry" id="use-mscw-russianpost-carry-on" value="1" {if $use_mscw_russianpost_carry} checked="checked"{/if} disabled>
                                                    <label for="use-mscw-russianpost-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-mscw-russianpost-carry" id="use-mscw-russianpost-carry-off" value="0" {if !$use_mscw_russianpost_carry} checked="checked"{/if}" />
                                                    <label for="use-mscw-russianpost-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать самовывоз ПЭК'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-mscw-pecom-carry" id="use-mscw-pecom-carry-on" value="1" {if $use_mscw_pecom_carry} checked="checked"{/if}>
                                                    <label for="use-mscw-pecom-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-mscw-pecom-carry" id="use-mscw-pecom-carry-off" value="0" {if !$use_mscw_pecom_carry} checked="checked"{/if}" />
                                                    <label for="use-mscw-pecom-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <button type="submit" id="submitMscwUseDelivery" class="btn btn-primary pull-right" name="submitMscwUseDelivery">
                                                    {l s='Сохранить'}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6">

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

                                        <th><span class="title_box ">Тип веса</span></th>
                                        <th><span class="title_box ">Сумма</span></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                    <tr class="current-edit">
                                        <td></td>


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
                                            <button type="submit" id="submitMscwAxiomusWeightPrice" class="btn btn-primary pull-right" name="submitMscwAxiomusWeightPrice">
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


                                            <td>
                                                <select class="form-control " id="mscw-axiomus-weightprice-type" name="mscw-axiomus-weightprice-type">
                                                    {foreach from=$AxiomusPost->getAllWeightType('Москва') key=k item=linetype}
                                                        <option value="{$k}" {if ($line.type == $linetype.id)}selected{/if}>{$linetype.name}</option>
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

                                                <td><input type="text" name="mscw-axiomus-conditionprice-sumfrom" class="form-control" value="{$line.sumfrom}"></td>
                                                <td><input type="text" name="mscw-axiomus-conditionprice-sumto" class="form-control" value="{$line.sumto}"></td>
                                                <td>
                                                    <select class="form-control " id="mscw-axiomus-conditionprice-timetype" name="mscw-axiomus-conditionprice-timetype">
                                                        {foreach from=$AxiomusPost->getAllTimeType('Москва') key=k item=linetime}
                                                            <option value="{$linetime.id}" {if ($line.timetype == $linetime.id)}selected{/if}>{$linetime.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control " id="mscw-axiomus-conditionprice-kadtype" name="mscw-axiomus-conditionprice-kadtype">
                                                        {foreach from=$AxiomusPost->getAllKadType('Москва') key=k item=linekad}
                                                            <option value="{$linekad.id}" {if ($line.kadtype == $linekad.id)}selected{/if}>{$linekad.name}</option>
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
                                <div class="form-group">
                                    <div class="row">
                                        <label class="control-label col-lg-2">Количество дней хранения</label>
                                        <div class="col-lg-4">
                                            <input type="text" id="mscw-carry-axiomus-daycount" class="" name="mscw-carry-axiomus-daycount" value="{$mscw_carry_axiomus.daycount}">
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
                <div class="tab-pane {if $subtab == 9}active{/if}" id="mscw-carry-pecom">
                    <div class="row">
                        <!-- carryAxiomus -->
                        <div class="panel">
                            <div class="panel-heading">
                                <i class="icon-money"></i>
                                Самовывоз ПЭК
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                            <div id="setting-form" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                    <div class="col-lg-4">
                                        <input type="text" id="mscw-carry-dpd-price" class="" name="mscw-carry-pecom-price" value="{$AxiomusPost->getCarryPriceByName('Москва', 'pecom')}">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="submit" id="submitMscwPecomPrice" class="btn btn-primary pull-right" name="submitMscwPecomPrice">
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

        {******************************************************************************** Peter **************************************************************}
        <!-- Tab returns -->
        <div class="tab-pane {if $maintab == 1}active{/if}" id="piter">
            <!-- Tab Peter -->
            <div class="tab-pane {if $maintab == 0}active{/if}" id="piter">
                <!-- Tab nav -->
                <!-- Виды доставок в москве-->
                <ul class="nav nav-tabs" id="tabPiter">
                    <li {if $subtab == 0}class="active"{/if}>
                        <a href="#ptr-settings">
                            <i class="icon-AdminAdmin"></i>
                            {l s='Настройки'} <span class="badge"></span>
                        </a>
                    </li>
                    <li {if $subtab == 1}class="active"{/if}>
                        <a href="#ptr-weighttype">
                            {l s='Интервалы веса'} <span class="badge"></span>
                        </a>
                    </li>
                    <li {if $subtab == 2}class="active"{/if}>
                        <a href="#ptr-timetype">
                            <i class="icon-clock-o"></i>
                            {l s='Временные интервалы'}
                        </a>
                    </li>
                    <li {if $subtab == 3}class="active"{/if}>
                        <a href="#ptr-kadtype">
                            <i class="icon-road"></i>
                            {l s='МКАД'}
                        </a>
                    </li>
                    <li {if $subtab == 4}class="active"{/if}>
                        <a href="#ptr-weightprice">
                            <i class="icon-ruble"></i>
                            {l s='Прайс по весу'}
                        </a>
                    </li>
                    <li {if $subtab == 5}class="active"{/if}>
                        <a href="#ptr-conditionprice">
                            <i class="icon-plus"></i>
                            {l s='Надбавка по условию'}
                        </a>
                    </li>
                    {if ($use_ptr_axiomus_carry)}
                        <li {if $subtab == 6}class="active"{/if}>
                            <a href="#ptr-carry-axiomus">
                                {l s='Самовывоз Axiomus'}
                            </a>
                        </li>
                    {/if}
                    {if ($use_ptr_dpd_carry)}
                        <li {if $subtab == 7}class="active"{/if}>
                            <a href="#ptr-carry-dpd">
                                {l s='Самовывоз DPD'}
                            </a>
                        </li>
                    {/if}
                    {if ($use_ptr_boxberry_carry)}
                        <li {if $subtab == 8}class="active"{/if}>
                            <a href="#ptr-carry-boxberry">
                                {l s='Самовывоз BoxBerry'}
                            </a>
                        </li>
                    {/if}
                    {if ($use_ptr_pecom_carry)}
                        <li {if $subtab == 9}class="active"{/if}>
                            <a href="#ptr-carry-pecom">
                                {l s='Самовывоз ПЭК'}
                            </a>
                        </li>
                    {/if}
                    {*<li {if $subtab == 10}class="active"{/if}>*}
                    {*<a href="#ptr-russianpost-carry">*}
                    {*{l s='RussianPost(самовывоp)'}*}
                    {*</a>*}
                    {*</li>*}
                </ul>
                <!-- Tab Piter content -->
                <div class="tab-content panel">
                    <!-- Tab ptr-setting -->
                    <div class="tab-pane {if $subtab == 0}active{/if}" id="ptr-settings">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <i class="icon-AdminAdmin"></i>
                                        Использование доставки
                                    </div>
                                    <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                    <div id="ptr-setting-form" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-lg-12 alert alert-info">
                                                <strong>Внимание!</strong> После изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий пункт при выборе способа доставки в <strong>админской </strong> части магазина в управления заказами
                                            </div>
                                        </div>
                                        <!-- Доставки -->
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать доставку Axiomus'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-axiomus" id="use-ptr-axiomus-on" value="1" {if $use_ptr_axiomus} checked="checked"{/if}>
                                                    <label for="use-ptr-axiomus-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-axiomus" id="use-ptr-axiomus-off" value="0" {if !$use_ptr_axiomus} checked="checked"{/if} />
                                                    <label for="use-ptr-axiomus-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать доставку Стриж'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-strizh" id="use-ptr-strizh-on" value="1" {if $use_ptr_strizh} checked="checked"{/if}>
                                                    <label for="use-ptr-strizh-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-strizh" id="use-ptr-strizh-off" value="0" {if !$use_ptr_strizh} checked="checked"{/if}/>
                                                    <label for="use-ptr-strizh-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать доставку DPD'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-dpd" id="use-ptr-dpd-on" value="1"  disabled>
                                                    <label for="use-ptr-dpd-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-dpd" id="use-ptr-dpd-off" value="0"  checked="checked"/>
                                                    <label for="use-ptr-dpd-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать доставку ПЭК'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-pecom" id="use-ptr-pecom-on" value="1" {if $use_ptr_pecom} checked="checked"{/if} disabled>
                                                    <label for="use-ptr-pecom-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-pecom" id="use-ptr-pecom-off" value="0" {if !$use_ptr_pecom} checked="checked"{/if}/>
                                                    <label for="use-ptr-pecom-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-lg-12 alert alert-info">
                                                <strong>Внимание!</strong> После изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий пункт при выборе способа доставки в <strong>фронтальной </strong> части магазина, при выборе способа доставки
                                            </div>
                                        </div>
                                        <!-- Самовывоз -->
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать самовывоз Axiomus'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-axiomus-carry" id="use-ptr-axiomus-carry-on" value="1" {if $use_ptr_axiomus_carry} checked="checked"{/if}>
                                                    <label for="use-ptr-axiomus-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-axiomus-carry" id="use-ptr-axiomus-carry-off" value="0" {if !$use_ptr_axiomus_carry} checked="checked"{/if} />
                                                    <label for="use-ptr-axiomus-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать самовывоз DPD'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-dpd-carry" id="use-ptr-dpd-carry-on" value="1" {if $use_ptr_dpd_carry} checked="checked"{/if}>
                                                    <label for="use-ptr-dpd-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-dpd-carry" id="use-ptr-dpd-carry-off" value="0" {if !$use_ptr_dpd_carry} checked="checked"{/if} />
                                                    <label for="use-ptr-dpd-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать самовывоз Boxberry'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-boxberry-carry" id="use-ptr-boxberry-carry-on" value="1" {if $use_ptr_boxberry_carry} checked="checked"{/if}>
                                                    <label for="use-ptr-boxberry-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-boxberry-carry" id="use-ptr-boxberry-carry-off" value="0" {if !$use_ptr_boxberry_carry} checked="checked"{/if} />
                                                    <label for="use-ptr-boxberry-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать самовывоз RussianPost'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-russianpost-carry" id="use-ptr-russianpost-carry-on" value="1" {if $use_ptr_russianpost_carry} checked="checked"{/if} disabled>
                                                    <label for="use-ptr-russianpost-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-russianpost-carry" id="use-ptr-russianpost-carry-off" value="0" {if !$use_ptr_russianpost_carry} checked="checked"{/if}" />
                                                    <label for="use-ptr-russianpost-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Использовать самовывоз ПЭК'}</label>
                                            <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-ptr-pecom-carry" id="use-ptr-pecom-carry-on" value="1" {if $use_ptr_pecom_carry} checked="checked"{/if}>
                                                    <label for="use-ptr-pecom-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-ptr-pecom-carry" id="use-ptr-pecom-carry-off" value="0" {if !$use_ptr_pecom_carry} checked="checked"{/if}" />
                                                    <label for="use-ptr-pecom-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-5">
                                                <button type="submit" id="submitPtrUseDelivery" class="btn btn-primary pull-right" name="submitPtrUseDelivery">
                                                    {l s='Сохранить'}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6">

                            </div>
                        </div>
                    </div>
                    <!-- Tab ptr-weighttype -->
                    <div class="tab-pane {if $subtab == 1}active{/if}" id="ptr-weighttype">
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
                                                <input type=text name="ptr-axiomus-weighttype-name" class="form-control">
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-weighttype-weightfrom" class="form-control">
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-weighttype-weightto" class="form-control">
                                            </td>
                                            <td>
                                                <button type="submit" id="submitPtrAxiomusWeightType" class="btn btn-primary pull-right" name="submitPtrAxiomusWeightType">
                                                    {l s='Добавить'}
                                                </button>
                                            </td>
                                        </tr>

                                        </form>
                                        </tfoot>
                                        <tbody>
                                        {foreach from=$AxiomusPost->getAllWeightType('Санкт-Петербург') item=line}
                                            <tr>
                                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                                <td><input type="hidden" name="ptr-axiomus-weighttype-id" value="{$line.id}">{$line.id}</td>
                                                <td><input type="text" name="ptr-axiomus-weighttype-name" value="{$line.name}"></td>
                                                <td><input type="text" name="ptr-axiomus-weighttype-weightfrom" value="{$line.weightfrom}"></td>
                                                <td><input type="text" name="ptr-axiomus-weighttype-weightto" value="{$line.weightto}"></td>
                                                <td class="fixed-width-sm">
                                                    <div class="row">
                                                        <button type="submit" id="deletePtrAxiomusWeightType" class="btn btn-danger pull-right" name="deletePtrAxiomusWeightType">
                                                            <i class="icon-remove"></i>
                                                        </button>
                                                        <button type="submit" id="updatePtrAxiomusWeightType" class="btn btn-success pull-right" name="updatePtrAxiomusWeightType">
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
                    <!-- Tab ptr-timetype -->
                    <div class="tab-pane {if $subtab == 2}active{/if}" id="ptr-timetype">
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
                                                <input type=text name="ptr-axiomus-timetype-name" class="form-control">
                                                {*customTimePicker*}
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-timetype-timefrom" class="form-control customTimePicker">
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-timetype-timeto" class="form-control customTimePicker">
                                            </td>
                                            <td>
                                                <button type="submit" id="submitPtrAxiomusTimeType" class="btn btn-primary pull-right" name="submitPtrAxiomusTimeType">
                                                    {l s='Добавить'}
                                                </button>
                                            </td>
                                        </tr>

                                        </form>
                                        </tfoot>
                                        <tbody>
                                        {foreach from=$AxiomusPost->getAllTimeType('Санкт-Петербург') item=line}
                                            <tr>
                                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                                <input type="hidden" name="ptr-axiomus-timetype-id" value="{$line.id}">
                                                <td>{$line.id}</td>
                                                <td><input type="text" name="ptr-axiomus-timetype-name" class="form-control" value="{$line.name}"></td>
                                                <td><input type="text" name="ptr-axiomus-timetype-timefrom" class="form-control customTimePicker" value="{$line.timefrom}"></td>
                                                <td><input type="text" name="ptr-axiomus-timetype-timeto" class="form-control customTimePicker" value="{$line.timeto}"></td>
                                                <td class="fixed-width-sm">
                                                    <div class="row">
                                                        <button type="submit" id="deletePtrAxiomusTimeType" class="btn btn-danger pull-right" name="deletePtrAxiomusTimeType">
                                                            <i class="icon-remove"></i>
                                                        </button>
                                                        <button type="submit" id="updatePtrAxiomusTimeType" class="btn btn-success pull-right" name="updatePtrAxiomusTimeType">
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
                    <!-- Tab ptr-kadtype -->
                    <div class="tab-pane {if $subtab == 3}active{/if}" id="ptr-kadtype">
                        <div class="row">
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-road"></i>
                                    промежутки расстояния от КАД
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
                                                <input type=text name="ptr-axiomus-kadtype-name" class="form-control">
                                                {*customTimePicker*}
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-kadtype-rangefrom" class="form-control">
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-kadtype-rangeto" class="form-control">
                                            </td>
                                            <td>
                                                <button type="submit" id="submitPtrAxiomusKadType" class="btn btn-primary pull-right" name="submitPtrAxiomusKadType">
                                                    {l s='Добавить'}
                                                </button>
                                            </td>
                                        </tr>

                                        </form>
                                        </tfoot>
                                        <tbody>
                                        {foreach from=$AxiomusPost->getAllKadType('Санкт-Петербург') item=line}
                                            <tr>
                                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                                <td><input type="hidden" name="ptr-axiomus-kadtype-id" value="{$line.id}">{$line.id}</td>
                                                <td><input type="text" name="ptr-axiomus-kadtype-name" class="form-control" value="{$line.name}"></td>
                                                <td><input type="text" name="ptr-axiomus-kadtype-timefrom" class="form-control" value="{$line.rangefrom}"></td>
                                                <td><input type="text" name="ptr-axiomus-kadtype-timeto" class="form-control" value="{$line.rangeto}"></td>
                                                <td class="fixed-width-sm">
                                                    <div class="row">
                                                        <button type="submit" id="deletePtrAxiomusKadType" class="btn btn-danger pull-right" name="deletePtrAxiomusKadType">
                                                            <i class="icon-remove"></i>
                                                        </button>
                                                        <button type="submit" id="updatePtrAxiomusKadType" class="btn btn-success pull-right" name="updatePtrAxiomusKadType">
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
                    <!-- Tab ptr-weightprice -->
                    <div class="tab-pane {if $subtab == 4}active{/if}" id="ptr-weightprice">
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
                                            <th><span class="title_box ">Тип веса</span></th>
                                            <th><span class="title_box ">Сумма</span></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">

                                        <tr class="current-edit">
                                            <td></td>
                                            <td>
                                                <select class="form-control " id="ptr-axiomus-weightprice-type" name="ptr-axiomus-weightprice-type">
                                                    {foreach from=$AxiomusPost->getAllWeightType('Санкт-Петербург') key=k item=line}
                                                        <option value="{$k+1}">{$line.name}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-weightprice-sum" class="form-control">
                                            </td>
                                            <td>
                                                <button type="submit" id="submitPtrAxiomusWeightPrice" class="btn btn-primary pull-right" name="submitPtrAxiomusWeightPrice">
                                                    {l s='Добавить'}
                                                </button>
                                            </td>
                                        </tr>

                                        </form>
                                        </tfoot>
                                        <tbody>
                                        {foreach from=$AxiomusPost->getAllWeightPrice('Санкт-Петербург') item=line}
                                            <tr>
                                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                                <td><input type="hidden" name="ptr-axiomus-weightprice-id" class="form-control" value="{$line.id}">{$line.id}</td>
                                                <td>
                                                    <select class="form-control " id="ptr-axiomus-weightprice-type" name="ptr-axiomus-weightprice-type">
                                                        {foreach from=$AxiomusPost->getAllWeightType('Санкт-Петербург') key=k item=linetype}
                                                            <option value="{$k}" {if ($line.type == $linetype.id)}selected{/if}>{$linetype.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td><input type=text name="ptr-axiomus-weightprice-sum" class="form-control" value="{$line.sum}"></td>
                                                <td class="fixed-width-sm">
                                                    <div class="row">
                                                        <button type="submit" id="deletePtrAxiomusWeightPrice" class="btn btn-danger pull-right" name="deletePtrAxiomusWeightPrice">
                                                            <i class="icon-remove"></i>
                                                        </button>
                                                        <button type="submit" id="updatePtrAxiomusWeightPrice" class="btn btn-success pull-right" name="updatePtrAxiomusWeightPrice">
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
                    <!-- Tab ptr-conditionprice -->
                    <div class="tab-pane {if $subtab == 5}active{/if}" id="ptr-conditionprice">
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

                                            <td>
                                                <input type=text name="ptr-axiomus-conditionprice-sumfrom" class="form-control">
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-conditionprice-sumto" class="form-control">
                                            </td>
                                            <td>
                                                <select class="form-control " id="ptr-axiomus-conditionprice-timetype" name="ptr-axiomus-conditionprice-timetype">
                                                    {foreach from=$AxiomusPost->getAllTimeType('Санкт-Петербург') key=k item=line}
                                                        <option value="{$k+1}">{$line.name}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control " id="ptr-axiomus-conditionprice-kadtype" name="ptr-axiomus-conditionprice-kadtype">
                                                    {foreach from=$AxiomusPost->getAllKadType('Санкт-Петербург') key=k item=line}
                                                        <option value="{$k+1}">{$line.name}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td>
                                                <input type=text name="ptr-axiomus-conditionprice-sum" class="form-control">
                                            </td>
                                            <td>
                                                <button type="submit" id="submitPtrAxiomusConditionPrice" class="btn btn-primary pull-right" name="submitPtrAxiomusConditionPrice">
                                                    {l s='Добавить'}
                                                </button>
                                            </td>
                                        </tr>

                                        </form>
                                        </tfoot>
                                        <tbody>
                                        {foreach from=$AxiomusPost->getAllConditionPrice('Санкт-Петербург') item=line}
                                            <tr>
                                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                                <td><input type="hidden" name="ptr-axiomus-conditionprice-id" value="{$line.id}">{$line.id}</td>

                                                <td><input type="text" name="ptr-axiomus-conditionprice-sumfrom" class="form-control" value="{$line.sumfrom}"></td>
                                                <td><input type="text" name="ptr-axiomus-conditionprice-sumto" class="form-control" value="{$line.sumto}"></td>
                                                <td>
                                                    <select class="form-control " id="ptr-axiomus-conditionprice-timetype" name="ptr-axiomus-conditionprice-timetype">
                                                        {foreach from=$AxiomusPost->getAllTimeType('Санкт-Петербург') key=k item=linetime}
                                                            <option value="{$linetime.id}" {if ($line.timetype == $linetime.id)}selected{/if}>{$linetime.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control " id="ptr-axiomus-conditionprice-kadtype" name="ptr-axiomus-conditionprice-kadtype">
                                                        {foreach from=$AxiomusPost->getAllKadType('Санкт-Петербург') key=k item=linekad}
                                                            <option value="{$linekad.id}" {if ($line.kadtype == $linekad.id)}selected{/if}>{$linekad.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td><input type="text" name="ptr-axiomus-conditionprice-sum" value="{$line.sum}"></td>
                                                <td class="fixed-width-sm">
                                                    <div class="row">
                                                        <button type="submit" id="deletePtrAxiomusConditionPrice" class="btn btn-danger pull-right" name="deletePtrAxiomusConditionPrice">
                                                            <i class="icon-remove"></i>
                                                        </button>
                                                        <button type="submit" id="updatePtrAxiomusConditionPrice" class="btn btn-success pull-right" name="updatePtrAxiomusConditionPrice">
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
                    <div class="tab-pane {if $subtab == 6}active{/if}" id="ptr-carry-axiomus">
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
                                            <input type="text" id="ptr-carry-axiomus-price" class="" name="ptr-carry-axiomus-price" value="{$AxiomusPost->getCarryPriceByName('Санкт-Петербург', 'axiomus')}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="control-label col-lg-2">Количество дней хранения</label>
                                        <div class="col-lg-4">
                                            <input type="text" id="ptr-carry-axiomus-daycount" class="" name="ptr-carry-axiomus-daycount" value="{$ptr_carry_axiomus.daycount}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitPtrAxiomusCarryPrice" class="btn btn-primary pull-right" name="submitPtrAxiomusCarryPrice">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end condition price -->
                    <div class="tab-pane {if $subtab == 7}active{/if}" id="ptr-carry-dpd">
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
                                            <input type="text" id="ptr-carry-dpd-price" class="" name="ptr-carry-dpd-price" value="{$AxiomusPost->getCarryPriceByName('Санкт-Петербург', 'dpd')}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitPtrDPDPrice" class="btn btn-primary pull-right" name="submitPtrDPDPrice">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end condition price -->
                    <div class="tab-pane {if $subtab == 8}active{/if}" id="ptr-carry-boxberry">
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
                                            <input type="text" id="ptr-carry-boxberry-price" class="" name="ptr-carry-boxberry-price" value="{$AxiomusPost->getCarryPriceByName('Санкт-Петербург', 'boxberry')}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitPtrBoxBerryPrice" class="btn btn-primary pull-right" name="submitPtrBoxBerryPrice">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane {if $subtab == 9}active{/if}" id="ptr-carry-pecom">
                        <div class="row">
                            <!-- carryAxiomus -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-money"></i>
                                    Самовывоз ПЭК
                                </div>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="setting-form" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                        <div class="col-lg-4">
                                            <input type="text" id="ptr-carry-dpd-price" class="" name="ptr-carry-pecom-price" value="{$AxiomusPost->getCarryPriceByName('Санкт-Петербург', 'pecom')}">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <button type="submit" id="submitPtrPecomPrice" class="btn btn-primary pull-right" name="submitPtrPecomPrice">
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

        </div>
        {******************************************************************************** REGIONS **************************************************************}
        <div class="tab-pane {if $maintab == 2}active{/if}" id="region">
            <ul class="nav nav-tabs" id="tabRegions">
                <li {if $subtab == 0}class="active"{/if}>
                    <a href="#region-settings" >
                        <i class="icon-AdminAdmin"></i>
                        {l s='Настройки'}
                    </a>
                </li>
                {if $use_region_axiomus_carry}
                <li {if $subtab == 1}class="active"{/if}>
                    <a href="#region-axiomus">
                        <i class="icon-truck"></i>
                        {l s='Самовывоз Аксиомус'} {*<span class="badge"></span>*}
                    </a>
                </li>
                {/if}
                {if $use_region_dpd_carry}
                <li {if $subtab == 2}class="active"{/if}>
                    <a href="#region-dpd" >
                        <i class="icon-plane"></i>
                        {l s='Самовывоз DPD'}
                    </a>
                </li>
                {/if}
                {if $use_region_boxberry_carry}
                <li {if $subtab == 3}class="active"{/if}>
                    <a href="#region-boxberry" >
                        <i class="icon-AdminAdmin"></i>
                        {l s='Самовывоз BoxBerry'}
                    </a>
                </li>
                {/if}
                {if $use_region_russianpost_carry}
                <li {if $subtab == 4}class="active"{/if}>
                    <a href="#region-russianpost" >
                        <i class="icon-AdminAdmin"></i>
                        {l s='Самовывоз RussianPost'}
                    </a>
                </li>
                {/if}
                {if $use_region_pecom_carry}
                <li {if $subtab == 5}class="active"{/if}>
                    <a href="#region-pecom" >
                        <i class="icon-AdminAdmin"></i>
                        {l s='Самовывоз ПЭК'}
                    </a>
                </li>
                {/if}
            </ul>
            <div class="tab-content panel">
            <!-- Tab shipping -->
                <div class="tab-pane {if $subtab == 0}active{/if}" id="region-settings">
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
                                            <strong>Внимание!</strong> После изменении параметров идущих ниже будет <strong>создан/удален</strong> соответствующий пункт при выборе способа доставки в <strong>админской </strong> части магазина в управления заказами
                                        </div>
                                    </div>
                                    <!-- Самовывоз -->
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">{l s='Использовать самовывоз Axiomus'}</label>
                                        <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-region-axiomus-carry" id="use-region-axiomus-carry-on" value="1" {if $use_region_axiomus_carry} checked="checked"{/if}>
                                                    <label for="use-region-axiomus-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-region-axiomus-carry" id="use-region-axiomus-carry-off" value="0" {if !$use_region_axiomus_carry} checked="checked"{/if} />
                                                    <label for="use-region-axiomus-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">{l s='Использовать самовывоз DPD'}</label>
                                        <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-region-dpd-carry" id="use-region-dpd-carry-on" value="1" {if $use_region_dpd_carry} checked="checked"{/if}>
                                                    <label for="use-region-dpd-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-region-dpd-carry" id="use-region-dpd-carry-off" value="0" {if !$use_region_dpd_carry} checked="checked"{/if} />
                                                    <label for="use-region-dpd-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">{l s='Использовать самовывоз Boxberry'}</label>
                                        <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-region-boxberry-carry" id="use-region-boxberry-carry-on" value="1" {if $use_region_boxberry_carry} checked="checked"{/if}>
                                                    <label for="use-region-boxberry-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-region-boxberry-carry" id="use-region-boxberry-carry-off" value="0" {if !$use_region_boxberry_carry} checked="checked"{/if} />
                                                    <label for="use-region-boxberry-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">{l s='Использовать самовывоз RussianPost'}</label>
                                        <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-region-russianpost-carry" id="use-region-russianpost-carry-on" value="1" {if $use_region_russianpost_carry} checked="checked"{/if} disabled>
                                                    <label for="use-region-russianpost-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-region-russianpost-carry" id="use-region-russianpost-carry-off" value="0" {if !$use_region_russianpost_carry} checked="checked"{/if}" />
                                                    <label for="use-region-russianpost-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">{l s='Использовать самовывоз ПЭК'}</label>
                                        <div class="col-lg-1">
                                                <span class="switch prestashop-switch fixed-width-lg">
                                                    <input type="radio" name="use-region-pecom-carry" id="use-region-pecom-carry-on" value="1" {if $use_region_pecom_carry} checked="checked"{/if}>
                                                    <label for="use-region-pecom-carry-on">{l s='Да'}</label>
                                                    <input type="radio" name="use-region-pecom-carry" id="use-region-pecom-carry-off" value="0" {if !$use_region_pecom_carry} checked="checked"{/if}" />
                                                    <label for="use-region-pecom-carry-off">{l s='Нет'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <button type="submit" id="submitRegionsUseDelivery" class="btn btn-primary pull-right" name="submitRegionsUseDelivery">
                                                {l s='Сохранить'}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6">

                        </div>
                    </div>
                </div>
                <div class="tab-pane {if $subtab == 1}active{/if}" id="region-axiomus">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-AdminAdmin"></i>
                            Самовывоз Axiomus
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="setting-form" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                <div class="col-lg-4">
                                    <input type="text" id="region-carry-axiomus-price" class="" name="region-carry-axiomus-price" value="{$AxiomusPost->getCarryPriceByName('регионы', 'axiomus')}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" id="submitRegionAxiomusPrice" class="btn btn-primary pull-right" name="submitRegionAxiomusPrice">
                                    {l s='Сохранить'}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane {if $subtab == 2}active{/if}" id="region-dpd">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-AdminAdmin"></i>
                            Самовывоз DPD
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="setting-form" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                <div class="col-lg-4">
                                    <input type="text" id="region-carry-dpd-price" class="" name="region-carry-dpd-price" value="{$AxiomusPost->getCarryPriceByName('регионы', 'dpd')}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" id="submitRegionDpdPrice" class="btn btn-primary pull-right" name="submitRegionDpdPrice">
                                    {l s='Сохранить'}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane {if $subtab == 3}active{/if}" id="region-boxberry">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-AdminAdmin"></i>
                            Самовывоз BoxBerry
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="setting-form" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                <div class="col-lg-4">
                                    <input type="text" id="region-carry-boxberry-price" class="" name="region-carry-boxberry-price" value="{$AxiomusPost->getCarryPriceByName('регионы', 'boxberry')}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" id="submitRegionBoxBerryPrice" class="btn btn-primary pull-right" name="submitRegionBoxBerryPrice">
                                    {l s='Сохранить'}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane {if $subtab == 4}active{/if}" id="region-russianpost">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-AdminAdmin"></i>
                            Самовывоз RussianPost
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="setting-form" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                <div class="col-lg-4">
                                    <input type="text" id="region-carry-russianpost-price" class="" name="region-carry-russianpost-price" value="{$AxiomusPost->getCarryPriceByName('регионы', 'russianpost')}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" id="submitRegionRussianPostPrice" class="btn btn-primary pull-right" name="submitRegionRussianPostPrice">
                                    {l s='Сохранить'}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane {if $subtab == 5}active{/if}" id="region-pecom">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-AdminAdmin"></i>
                            Самовывоз ПЭК
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="setting-form" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-lg-2">{l s='Стоимость для самовывоза'}</label>
                                <div class="col-lg-4">
                                    <input type="text" id="region-carry-pecom-price" class="" name="region-carry-pecom-price" value="{$AxiomusPost->getCarryPriceByName('регионы', 'pecom')}">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" id="submitRegionPecomPrice" class="btn btn-primary pull-right" name="submitRegionPecomPrice">
                                    {l s='Сохранить'}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane {if $maintab == 3}active{/if}" id="settings">
            <ul class="nav nav-tabs" id="tabSettings">
                <li {if $subtab == 0}class="active"{/if}>
                    <a href="#settings-axiomus">
                        <i class="icon-truck "></i>
                        {l s='Аксиомус'} {*<span class="badge"></span>*}
                    </a>
                </li>
                <li {if $subtab == 1}class="active"{/if}>
                    <a href="#settings-pecom" >
                        <i class="icon-plane"></i>
                        {l s='ПЭК'}
                    </a>
                </li>
                <li {if $subtab == 2}class="active"{/if}>
                    <a href="#settings-cache" >
                        <i class="icon-AdminAdmin"></i>
                        {l s='Кэш'}
                    </a>
                </li>
            </ul>
            <!-- Tab content -->
            <div class="tab-content panel">
                <!-- Tab shipping -->
                <div class="tab-pane {if $subtab == 0}active{/if}" id="settings-axiomus">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-AdminAdmin"></i>
                            Настройки Аксиомус
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="setting-form" class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label">Axiomus ukey</label>
                                <input type="text" id="axiomus-ukey" class="" name="axiomus-ukey" value="{$axiomus_ukey}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Axiomus uid</label>
                                <input type="text" id="axiomus-uid" class="" name="axiomus-uid" value="{$axiomus_uid}">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Axiomus url</label>
                                <input type="text" id="axiomus-url" class="" name="axiomus-url" value="{$axiomus_url}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                                <button type="submit" id="submitSettingsAxiomus" class="btn btn-primary pull-right" name="submitSettingsAxiomus">
                                    {l s='Сохранить'}
                                </button>
                        </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane {if $subtab == 1}active{/if}" id="settings-pecom">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-AdminAdmin"></i>
                                    Основные настройки
                                </div>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                    <div id="setting-form" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Имя пользователя в системе "ПЭК"</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_nickname" class="" name="pecom_nickname" value="{$pecom_settings.pecom_nickname}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">API "ПЭК"</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_api" class="" name="pecom_api" value="{$pecom_settings.pecom_api}">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <button type="submit" id="submitSettingsPecom" class="btn btn-primary pull-right" name="submitSettingsPecom">
                                                {l s='Сохранить'}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-AdminAdmin"></i>
                                    Настройки Отправителя "ПЭК"
                                </div>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                    <div id="setting-form" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Город</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_city" class="" name="pecom_sender_city" value="{$pecom_sender.pecom_sender_city}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Наименование организации</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_title" class="" name="pecom_sender_title" value="{$pecom_sender.pecom_sender_title}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Контактное лицо</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_person" class="" name="pecom_sender_person" value="{$pecom_sender.pecom_sender_person}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Телефон</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_phone" class="" name="pecom_sender_phone" value="{$pecom_sender.pecom_sender_phone}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">E-mail</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_email" class="" name="pecom_sender_email" value="{$pecom_sender.pecom_sender_email}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Адрес офиса</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_address_office" class="" name="pecom_sender_address_office" value="{$pecom_sender.pecom_sender_address_office}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Комментарий к адресу офиса</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_address_office_comment" class="" name="pecom_sender_address_office_comment" value="{$pecom_sender.pecom_sender_address_office_comment}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Адрес склада</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_address_stock" class="" name="pecom_sender_address_stock" value="{$pecom_sender.pecom_sender_address_stock}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                            <label class="control-label col-lg-5">Комментарий к адресу склада</label>
                                                <div class="col-lg-7">
                                            <input type="text" id="pecom_sender_address_stock_comment" class="" name="pecom_sender_address_stock_comment" value="{$pecom_sender.pecom_sender_address_stock_comment}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Время начала рабочего дня</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_work_time_from" class="" name="pecom_sender_work_time_from" value="{$pecom_sender.pecom_sender_work_time_from}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Время окончания рабочего дня</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_work_time_to" class="" name="pecom_sender_work_time_to" value="{$pecom_sender.pecom_sender_work_time_to}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Время начала обеденного перерыва</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_lunch_break_from" class="" name="pecom_sender_lunch_break_from" value="{$pecom_sender.pecom_sender_lunch_break_from}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Время окончания обеденного перерыва</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_lunch_break_to" class="" name="pecom_sender_lunch_break_to" value="{$pecom_sender.pecom_sender_lunch_break_to}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-5">Для получения груза необходима доверенность ПЭК</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_sender_is_auth_needed" class="" name="pecom_sender_is_auth_needed" value="{$pecom_sender.pecom_sender_is_auth_needed}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Документ удостоверяющий личность</label>
                                                <div class="col-lg-7">
                                                    <select class="form-control " id="pecom_sender_identity_type" name="pecom_sender_identity_type">
                                                        <option value="1" {if ($pecom_sender.pecom_sender_identity_type == 1)}selected{/if}>Паспорт иностранного гражданина</option>
                                                        <option value="2" {if ($pecom_sender.pecom_sender_identity_type == 2)}selected{/if}>Разрешение на временное проживание</option>
                                                        <option value="3" {if ($pecom_sender.pecom_sender_identity_type == 3)}selected{/if}>Водительское удостоверение</option>
                                                        <option value="4" {if ($pecom_sender.pecom_sender_identity_type == 4)}selected{/if}>Вид на жительство</option>
                                                        <option value="5" {if ($pecom_sender.pecom_sender_identity_type == 5)}selected{/if}>Заграничный паспорт</option>
                                                        <option value="6" {if ($pecom_sender.pecom_sender_identity_type == 6)}selected{/if}>Удостоверение беженца</option>
                                                        <option value="7" {if ($pecom_sender.pecom_sender_identity_type == 7)}selected{/if}>Временное удостоверение личности гражданина РФ</option>
                                                        <option value="8" {if ($pecom_sender.pecom_sender_identity_type == 8)}selected{/if}>Свидетельство о предоставлении временного убежища на территрории РФ</option>
                                                        <option value="9" {if ($pecom_sender.pecom_sender_identity_type == 9)}selected{/if}>Паспорт моряка</option>
                                                        <option value="10" {if ($pecom_sender.pecom_sender_identity_type == 10)}selected{/if}>Паспорт гражданина РФ</option>
                                                        <option value="11" {if ($pecom_sender.pecom_sender_identity_type == 11)}selected{/if}>Свидетельство о рассмотрении ходатайства о признании беженцем</option>
                                                        <option value="12" {if ($pecom_sender.pecom_sender_identity_type == 12)}selected{/if}>Военный билет</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Серия документа</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_identity_series" class="" name="pecom_sender_identity_series" value="{$pecom_sender.pecom_sender_identity_series}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Номер документа</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_identity_number" class="" name="pecom_sender_identity_number" value="{$pecom_sender.pecom_sender_identity_number}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-lg-5">Дата выдачи документа</label>
                                                <div class="col-lg-7">
                                                    <input type="text" id="pecom_sender_identity_date" class="" name="pecom_sender_identity_date" value="{$pecom_sender.pecom_sender_identity_date}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <button type="submit" id="submitSettingsPecomSender" class="btn btn-primary pull-right" name="submitSettingsPecomSender">
                                            {l s='Сохранить'}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <i class="icon-AdminAdmin"></i>
                                    Настройки параметров отправки по-умолчанию "ПЭК"
                                </div>
                                <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                                <div id="setting-form" class="form-horizontal">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">Объем одного места</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_volume_one" class="" name="pecom_volume_one" value="{$pecom_default.pecom_volume_one}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Хрупкий груз</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_fragile" id="pecom_is-fragile-on" value="1" {if $pecom_default.pecom_is_fragile} checked="checked"{/if}>
                                                <label for="pecom_is-fragile-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_fragile" id="pecom_is-fragile-off" value="0" {if !$pecom_default.pecom_is_fragile} checked="checked"{/if} />
                                                <label for="pecom_is-fragile-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Стекло</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_glass" id="pecom_is-glass-on" value="1" {if $pecom_default.pecom_is_glass} checked="checked"{/if}>
                                                <label for="pecom_is-glass-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_glass" id="pecom_is-glass-off" value="0" {if !$pecom_default.pecom_is_glass} checked="checked"{/if} />
                                                <label for="pecom_is-glass-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Жидкость</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_liquid" id="pecom_is-liquid-on" value="1" {if $pecom_default.pecom_is_liquid} checked="checked"{/if}>
                                                <label for="pecom_is-liquid-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_liquid" id="pecom_is-liquid-off" value="0" {if !$pecom_default.pecom_is_liquid} checked="checked"{/if} />
                                                <label for="pecom_is-liquid-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Груз другого типа</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_othertype" id="pecom_is-othertype-on" value="1" {if $pecom_default.pecom_is_othertype} checked="checked"{/if}>
                                                <label for="pecom_is-othertype-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_othertype" id="pecom_is-othertype-off" value="0" {if !$pecom_default.pecom_is_othertype} checked="checked"{/if} />
                                                <label for="pecom_is-othertype-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">Описание груза другого типа</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_othertype_description" class="" name="pecom_othertype_description" value="{$pecom_default.pecom_othertype_description}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Необходима открытая машина</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_opencar" id="pecom_is-opencar-on" value="1" {if $pecom_default.pecom_is_opencar} checked="checked"{/if}>
                                                <label for="pecom_is-opencar-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_opencar" id="pecom_is-opencar-off" value="0" {if !$pecom_default.pecom_is_opencar} checked="checked"{/if} />
                                                <label for="pecom_is-opencar-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Необходима боковая погрузка</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_sideload" id="pecom_is-sideload-on" value="1" {if $pecom_default.pecom_is_sideload} checked="checked"{/if}>
                                                <label for="pecom_is-sideload-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_sideload" id="pecom_is-sideload-off" value="0" {if !$pecom_default.pecom_is_sideload} checked="checked"{/if} />
                                                <label for="pecom_is-sideload-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Необходимо специальное оборудование</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_special_eq" id="pecom_is-special-eq-on" value="1" {if $pecom_default.pecom_is_special_eq} checked="checked"{/if}>
                                                <label for="pecom_is-special-eq-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_special_eq" id="pecom_is-special-eq-off" value="0" {if !$pecom_default.pecom_is_special_eq} checked="checked"{/if} />
                                                <label for="pecom_is-special-eq-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Необходима растентовка</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_uncovered" id="pecom_is-uncovered-on" value="1" {if $pecom_default.pecom_is_uncovered} checked="checked"{/if}>
                                                <label for="pecom_is-uncovered-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_uncovered" id="pecom_is-uncovered-off" value="0" {if !$pecom_default.pecom_is_uncovered} checked="checked"{/if} />
                                                <label for="pecom_is-uncovered-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Необходим забор день в день</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_daybyday" id="pecom_is-daybyday-on" value="1" {if $pecom_default.pecom_is_daybyday} checked="checked"{/if}>
                                                <label for="pecom_is-daybyday-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_daybyday" id="pecom_is-daybyday-off" value="0" {if !$pecom_default.pecom_is_daybyday} checked="checked"{/if} />
                                                <label for="pecom_is-daybyday-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">Представитель какой стороны оформляет заявки </label>
                                            <div class="col-lg-7">
                                                <select class="form-control" id="pecom_register_type" name="pecom_register_type">
                                                    <option value="1" {if ($pecom_default.pecom_register_type == 1)}selected{/if}>Отправитель</option>
                                                    <option value="2" {if ($pecom_default.pecom_register_type == 2)}selected{/if}>Получатель</option>
                                                    <option value="3" {if ($pecom_default.pecom_register_type == 3)}selected{/if}>Третье лицо</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">ФИО ответственного за оформление заявки</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_responsible_person" class="" name="pecom_responsible_person" value="{$pecom_default.pecom_responsible_person}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Изготовление жесткой упаковки</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_hp" id="pecom_is-hp-on" value="1" {if $pecom_default.pecom_is_hp} checked="checked"{/if}>
                                                <label for="pecom_is-hp-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_hp" id="pecom_is-hp-off" value="0" {if !$pecom_default.pecom_is_hp} checked="checked"{/if} />
                                                <label for="pecom_is-hp-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">Количество мест для жесткой упаковки</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_hp_position_count" class="" name="pecom_hp_position_count" value="{$pecom_default.pecom_hp_position_count}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Дополнительное страхование груза</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_insurance" id="pecom_is-insurance-on" value="1" {if $pecom_default.pecom_is_insurance} checked="checked"{/if}>
                                                <label for="pecom_is-insurance-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_insurance" id="pecom_is-insurance-off" value="0" {if !$pecom_default.pecom_is_insurance} checked="checked"{/if} />
                                                <label for="pecom_is-insurance-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">Стоимость груза для страхования, руб</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_insurance_price" class="" name="pecom_insurance_price" value="{$pecom_default.pecom_insurance_price}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Пломбировка груза (только до 3 кг)</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_sealing" id="pecom_is-sealing-on" value="1" {if $pecom_default.pecom_is_sealing} checked="checked"{/if}>
                                                <label for="pecom_is-sealing-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_sealing" id="pecom_is-sealing-off" value="0" {if !$pecom_default.pecom_is_sealing} checked="checked"{/if} />
                                                <label for="pecom_is-sealing-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-lg-5">Количество мест для пломбировки</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="pecom_sealing_position_count" class="" name="pecom_sealing_position_count" value="{$pecom_default.pecom_sealing_position_count}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Упаковка груза стреппинг‑лентой</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_strapping" id="pecom_is-strapping-on" value="1" {if $pecom_default.pecom_is_strapping} checked="checked"{/if}>
                                                <label for="pecom_is-strapping-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_strapping" id="pecom_is-strapping-off" value="0" {if !$pecom_default.pecom_is_strapping} checked="checked"{/if} />
                                                <label for="pecom_is-strapping-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Возврат документов</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_documents_return" id="pecom_is-documents-return-on" value="1" {if $pecom_default.pecom_is_documents_return} checked="checked"{/if}>
                                                <label for="pecom_is-documents-return-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_documents_return" id="pecom_is-documents-return-off" value="0" {if !$pecom_default.pecom_is_documents_return} checked="checked"{/if} />
                                                <label for="pecom_is-documents-return-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-5">Необходима погрузка силами «ПЭК»</label>
                                        <div class="col-lg-1">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                                <input type="radio" name="pecom_is_loading" id="pecom_is_loading-on" value="1" {if $pecom_default.pecom_is_loading} checked="checked"{/if}>
                                                <label for="pecom_is_loading-on">{l s='Да'}</label>
                                                <input type="radio" name="pecom_is_loading" id="pecom_is_loading-off" value="0" {if !$pecom_default.pecom_is_loading} checked="checked"{/if} />
                                                <label for="pecom_is_loading-off">{l s='Нет'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <button type="submit" id="submitSettingsPecomDefault" class="btn btn-primary pull-right" name="submitSettingsPecomDefault">
                                        {l s='Сохранить'}
                                    </button>
                                </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="tab-pane {if $subtab == 2}active{/if}" id="settings-cache">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-money"></i>
                            Кэш записей о самовывозе
                        </div>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div class="form-horizontal">
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
                        <div class="form-horizontal">
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
                        <div class="form-horizontal">
                            <div class="row">
                                Дата последнего обновления: <b>{$AxiomusPost->getLastUpdateCacheCarry('boxberry')}</b>
                                <button type="submit" id="submitRefreshCacheCarryAddressesBoxBerry" class="btn btn-success pull-right" name="submitRefreshCacheCarryAddressesBoxBerry">
                                    {l s='Обновить кэш BoxBerry'}
                                </button>
                            </div>
                        </div>
                        </form>
                        <br>
                        <form action="{$smarty.server.REQUEST_URI|escape:'html':'UTF-8'}&amp;" method="post"">
                        <div id="mscw-setting-form" class="form-horizontal">
                            <div class="row">
                                Дата последнего обновления: <b>{$AxiomusPost->getLastUpdateCacheCarry('pecom')}</b>
                                <button type="submit" id="submitRefreshCacheCarryAddressesPecom" class="btn btn-success pull-right" name="submitRefreshCacheCarryAddressesPecom">
                                    {l s='Обновить кэш ПЭК'}
                                </button>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
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
            $('#tabRegions a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            });
            $('#tabSettings a').click(function (e) {
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
