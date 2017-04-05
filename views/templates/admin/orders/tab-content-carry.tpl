<div class="tab-pane" id="shipping_axiomus">
    <h4 class="visible-print">Axiomus</h4>
    <!-- Shipping block -->
    <div class="col-lg-12 alert alert-warning" style="display: none" id="axiomusErrorBlock">
    </div>
    <div class="row">
        <table class="table table-bordered">
            <colgroup>
                <col class="col-lg-4">
                <col class="col-lg-8">
            </colgroup>
            <tr class="info">
                <td>Тип доставки:</td>
                <td>{$order_axiomus_data.type}</td>
            </tr>
            <tr>
                <td>Перевозчик:</td>
                <td>{$delivery_name}</td>
            </tr>
            <tr>
                <td>Город доставки:</td>
                <td>{$carry_row.city_name}</td>
            </tr>
            <tr>
                <td>Точка самовывоза:</td>
                <td>{$carry_row.name}</td>
            </tr>
            <tr>
                <td>Адрес точки самовывоза:</td>
                <td>{$carry_row.address}</td>
            </tr>
            <tr>
                <td>Код в системе перевозчика:</td>
                <td>{$axiomus_succes_code}</td>
            </tr>
        </table>
    </div>
    {if ($axiomus_succes)}
        <div class="row">
            <div>Отправлено в систему Axiomus, через <b>{$delivery_name}</b> код: <b>{$axiomus_succes_code}</b></div>
            <button type="submit" class="btn btn-danger pull-right" id="submitSendToAxiomusReturn">Анулировать заявку</button>
        </div>
    {/if}
    {if (!$axiomus_succes)}
        {if $deliveries_used.axiomus}
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#axiomusModal">Отправить через Axiomus</button>
            {include file="$_axiomus_module_path/views/templates/admin/orders/modal-axiomus.tpl"}
        {/if}
        {if $deliveries_used.dpd}
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#dpdModal">Отправить через DPD</button>
            {include file="$_axiomus_module_path/views/templates/admin/orders/modal-dpd.tpl"}
        {/if}
        {if $deliveries_used.boxberry}
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#boxberryModal">Отправить через BoxBerry</button>
            {include file="$_axiomus_module_path/views/templates/admin/orders/modal-boxberry.tpl"}
        {/if}
        {if $deliveries_used.russianpost}
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#russianpostModal">Отправить через RussianPost</button>
            {include file="$_axiomus_module_path/views/templates/admin/orders/modal-russianpost.tpl"}
        {/if}
        {if $deliveries_used.pecom}
            <button type="submit" class="btn btn-primary"   data-toggle="modal" data-target="#pecomModal">Отправить через ПЭК</button>
            {include file="$_axiomus_module_path/views/templates/admin/orders/modal-pecom.tpl"}
        {/if}
    {/if}







</div>

<script>
    $(document).ready(function () {
        $('#submitSendToAxiomus').click(function () {
            event.preventDefault();
            sendTo('axiomus', 'new', $('#axiomus_form').serialize());
        });
        $('#submitSendToDPD').click(function () {
            event.preventDefault();
            sendTo('dpd', 'new', $('#dpd_form').serialize());
        });
        $('#submitSendToBoxBerry').click(function () {
            event.preventDefault();
            sendTo('boxberry', 'new', $('#boxberry_form').serialize());
        });
        $('#submitSendToRussianPost').click(function () {
            event.preventDefault();
            sendTo('russianpost', 'new', $('#russianpost_form').serialize());
        });
        $('#submitSendToPecom').click(function () {
            event.preventDefault();
            sendTo('pecom', 'new', $('#pecom_form').serialize());
        });
        $('#submitSendToAxiomusReturn').click(function () {
            sendTo('{$delivery_name}', 'delete');
            sendTo('pecom', 'delete');
        });

        function sendTo(delivery = '', action, formData = '') {
            data = 'carry=1&delivery='+delivery+'&action='+action+'&order_id={$order_id}&cart_id={$cart_id}&'+formData;
            $.ajax({
                type: 'POST',
                url: '{$_axiomus_sendto_link}',
                data: data,
                beforeSend: function () {
                    console.log('sending...');
                },
                success: function(data){
                    console.log('end... true');

                    console.log(data); //Invalid Security token
                    if(data == '1') {
                        location.reload();
                    }else{
                        data = JSON.parse(data);
                        errorStr = '';
                        for (var key in data) {
                            errorStr +=  key+': '+data[key]+'; ';
                        }
                        $('#axiomusErrorBlock').text(errorStr);
                        $('#axiomusErrorBlock').show();
                        console.log(errorStr);
                    }
                }
            });
        }
    });
</script>