<div class="tab-pane" id="shipping_axiomus">
    <h4 class="visible-print">Axiomus</h4>
    <!-- Shipping block -->
    {if ($axiomus_succes)}
        <div class="row">
            <div>Отправлено в систему Axiomus, через <b>{$delivery_name}</b> код: <b>{$axiomus_succes_code}</b></div>
            <button type="submit" class="btn btn-danger pull-right" id="submitSendToAxiomusReturn">Анулировать заявку</button>
        </div>
    {/if}
    <div class="col-lg-12 alert alert-warning" style="display: none" id="axiomusErrorBlock">

    </div>
    {if (!$axiomus_succes)}
        {if $deliveries_used.axiomus}
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#axiomusModal">Отправить через Axiomus</button>
        {/if}
        {if $deliveries_used.strizh}
            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#axiomusModal">Отправить через Стриж</button>
        {/if}
        {if $deliveries_used.pecom}
            <button type="submit" class="btn btn-primary"   data-toggle="modal" data-target="#pecomModal">Отправить через ПЭК</button>
        {/if}
    {/if}

    {include file="$_axiomus_module_path/views/templates/admin/axiomus_modal.tpl"}
    {include file="$_axiomus_module_path/views/templates/admin/pecom_modal.tpl"}

//ToDo работа остановилась на том что нужно проделать страницу дефолтных настроке и для Axiomus

</div>

<script>
    $(document).ready(function () {
        $('#submitSendToAxiomus').click(function () {
            sendTo('axiomus', 'new');
        });
        $('#submitSendToStrizh').click(function () {
            sendTo('strizh', 'new');
        });
        $('#submitSendToPecom').click(function () {
            event.preventDefault();
            sendTo('pecom', 'new', $('#axiomus_form').serialize());
        });
        $('#submitSendToAxiomusReturn').click(function () {
            sendTo('{$delivery_name}', 'delete');
            sendTo('pecom', 'delete');
        });

        function sendTo(delivery = '', action, formData = '') {
            data = 'delivery='+delivery+'&action='+action+'&order_id={$order_id}&cart_id={$cart_id}&'+formData;
            $.ajax({
                type: 'POST',
                url: '{$_axiomus_sendto_link}',
                data: data,
                beforeSend: function () {
                    console.log('sending...');
                },
                success: function(data){
                    console.log('end... true');

                    console.log(data);
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