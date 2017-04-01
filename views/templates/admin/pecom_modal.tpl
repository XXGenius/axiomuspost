<!-- Modal -->
<div class="modal fade" id="pecomModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Дополнительные параметры доставки</h4>
            </div>
            <div class="modal-body">
    <form action="{$_axiomus_sendto_link|escape:'html':'UTF-8'}&amp;" method="post" id="axiomus_form">
        <div id="setting-form" class="form-horizontal">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <label class="control-label col-lg-2">Количество упаковок</label>
                    <div class="col-lg-8">
                        <input type="text" id="pecom_position_count" class="" name="pecom_position_count" value="1">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <label class="control-label col-lg-2">Объем одного места</label>
                    <div class="col-lg-8">
                        <input type="text" id="pecom_volume_one" class="" name="pecom_volume_one" value="{$pecom_volume_one}">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="control-label">Хрупкий груз</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_fragile" id="pecom_is-fragile-on" value="1" {if $pecom_is_fragile} checked="checked"{/if}>
                            <label for="pecom_is-fragile-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_fragile" id="pecom_is-fragile-off" value="0" {if !$pecom_is_fragile} checked="checked"{/if} />
                            <label for="pecom_is-fragile-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Стекло</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_glass" id="pecom_is-glass-on" value="1" {if $pecom_is_glass} checked="checked"{/if}>
                            <label for="pecom_is-glass-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_glass" id="pecom_is-glass-off" value="0" {if !$pecom_is_glass} checked="checked"{/if} />
                            <label for="pecom_is-glass-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Жидкость</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_liquid" id="pecom_is-liquid-on" value="1" {if $pecom_is_liquid} checked="checked"{/if}>
                            <label for="pecom_is-liquid-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_liquid" id="pecom_is-liquid-off" value="0" {if !$pecom_is_liquid} checked="checked"{/if} />
                            <label for="pecom_is-liquid-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Груз другого типа</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_othertype" id="pecom_is-othertype-on" value="1" {if $pecom_is_othertype} checked="checked"{/if}>
                            <label for="pecom_is-othertype-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_othertype" id="pecom_is-othertype-off" value="0" {if !$pecom_is_othertype} checked="checked"{/if} />
                            <label for="pecom_is-othertype-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Описание груза другого типа</label>
                        <div class="col-lg-11">
                            <input type="text" id="pecom_othertype_description" class="" name="pecom_othertype_description" value="{$pecom_othertype_description}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Необходима открытая машина</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_opencar" id="pecom_is-opencar-on" value="1" {if $pecom_is_opencar} checked="checked"{/if}>
                            <label for="pecom_is-opencar-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_opencar" id="pecom_is-opencar-off" value="0" {if !$pecom_is_opencar} checked="checked"{/if} />
                            <label for="pecom_is-opencar-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Необходима боковая погрузка</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_sideload" id="pecom_is-sideload-on" value="1" {if $pecom_is_sideload} checked="checked"{/if}>
                            <label for="pecom_is-sideload-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_sideload" id="pecom_is-sideload-off" value="0" {if !$pecom_is_sideload} checked="checked"{/if} />
                            <label for="pecom_is-sideload-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Необходимо специальное оборудование</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_special_eq" id="pecom_is-special-eq-on" value="1" {if $pecom_is_special_eq} checked="checked"{/if}>
                            <label for="pecom_is-special-eq-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_special_eq" id="pecom_is-special-eq-off" value="0" {if !$pecom_is_special_eq} checked="checked"{/if} />
                            <label for="pecom_is-special-eq-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Необходима растентовка</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_uncovered" id="pecom_is-uncovered-on"
                                   value="1" {if $pecom_is_uncovered} checked="checked"{/if}>
                            <label for="pecom_is-uncovered-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_uncovered"
                                   id="pecom_is-uncovered-off"
                                   value="0" {if !$pecom_is_uncovered} checked="checked"{/if} />
                            <label for="pecom_is-uncovered-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Необходим забор день в день</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_daybyday" id="pecom_is-daybyday-on"
                                   value="1" {if $pecom_is_daybyday} checked="checked"{/if}>
                            <label for="pecom_is-daybyday-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_daybyday" id="pecom_is-daybyday-off"
                                   value="0" {if !$pecom_is_daybyday} checked="checked"{/if} />
                            <label for="pecom_is-daybyday-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Представитель какой стороны оформляет заявки </label>
                        <select class="form-control" id="pecom_register_type" name="pecom_register_type">
                            <option value="1" {if ($pecom_register_type == 1)}selected{/if}>Отправитель</option>
                            <option value="2" {if ($pecom_register_type == 2)}selected{/if}>Получатель</option>
                            <option value="3" {if ($pecom_register_type == 3)}selected{/if}>Третье лицо</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label class="control-label">ФИО ответственного за оформление заявки</label>
                        <input type="text" id="pecom_responsible_person" class="" name="pecom_responsible_person" value="{$pecom_responsible_person}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Изготовление жесткой упаковки</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_hp" id="pecom_is-hp-on"
                                   value="1" {if $pecom_is_hp} checked="checked"{/if}>
                            <label for="pecom_is-hp-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_hp" id="pecom_is-hp-off"
                                   value="0" {if !$pecom_is_hp} checked="checked"{/if} />
                            <label for="pecom_is-hp-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Количество мест для жесткой упаковки</label>
                        <input type="text" id="pecom_hp_position_count" class="" name="pecom_hp_position_count" value="{$pecom_hp_position_count}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Дополнительное страхование груза</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_insurance" id="pecom_is-insurance-on"
                                   value="1" {if $pecom_is_insurance} checked="checked"{/if}>
                            <label for="pecom_is-insurance-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_insurance"
                                   id="pecom_is-insurance-off"
                                   value="0" {if !$pecom_is_insurance} checked="checked"{/if} />
                            <label for="pecom_is-insurance-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Стоимость груза для страхования, руб</label>
                        <input type="text" id="pecom_insurance_price" class="" name="pecom_insurance_price"
                                       value="{$pecom_insurance_price}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Пломбировка груза (только до 3 кг)</label>

                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_sealing" id="pecom_is-sealing-on"
                                   value="1" {if $pecom_is_sealing} checked="checked"{/if}>
                            <label for="pecom_is-sealing-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_sealing" id="pecom_is-sealing-off"
                                   value="0" {if !$pecom_is_sealing} checked="checked"{/if} />
                            <label for="pecom_is-sealing-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Количество мест для пломбировки</label>
                        <input type="text" id="pecom_sealing_position_count" class="" name="pecom_sealing_position_count" value="{$pecom_sealing_position_count}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Упаковка груза стреппинг‑лентой</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_strapping" id="pecom_is-strapping-on"
                                   value="1" {if $pecom_is_strapping} checked="checked"{/if}>
                            <label for="pecom_is-strapping-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_strapping"
                                   id="pecom_is-strapping-off"
                                   value="0" {if !$pecom_is_strapping} checked="checked"{/if} />
                            <label for="pecom_is-strapping-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Возврат документов</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_documents_return"
                                   id="pecom_is-documents-return-on"
                                   value="1" {if $pecom_is_documents_return} checked="checked"{/if}>
                            <label for="pecom_is-documents-return-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_documents_return"
                                   id="pecom_is-documents-return-off"
                                   value="0" {if !$pecom_is_documents_return} checked="checked"{/if} />
                            <label for="pecom_is-documents-return-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Необходима погрузка силами «ПЭК»</label>
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pecom_is_loading" id="pecom_is_loading-on"
                                   value="1" {if $pecom_is_loading} checked="checked"{/if}>
                            <label for="pecom_is_loading-on">{l s='Да'}</label>
                            <input type="radio" name="pecom_is_loading" id="pecom_is_loading-off"
                                   value="0" {if !$pecom_is_loading} checked="checked"{/if} />
                            <label for="pecom_is_loading-off">{l s='Нет'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="submitSendToPecom">Отправить в ПЭК</button>
            </div>
        </div>
    </div>
</div>