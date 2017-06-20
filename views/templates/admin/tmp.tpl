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
                                                    <label for="use-ptr-pecom-off">{l s='Нет'}</label>
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

                                <td class="row-selector text-center"><input type="checkbox" name="ptr-axiomus-weightprice-carry" class="noborder"></td>
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
                                    <button type="submit" id="submitPtrAxiomusWeightPrice" class="btn btn-primary pull-right" name="submitPtrAxiomusWeightType">
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

                                    <td class="row-selector text-center"><input type="checkbox" name="ptr-axiomus-weightprice-carry" class="noborder" {if ($line.carry == 1)}checked{/if}"></td>
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
