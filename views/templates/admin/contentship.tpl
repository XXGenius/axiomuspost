<div class="tab-pane" id="shipping_axiomus">
    <h4 class="visible-print">Axiomus</h4>
    <!-- Shipping block -->
    {if ($axiomus_succes)}
        <div class="row">
            <div>Отправлено в систему Axiomus, код: <b>{$axiomus_succes_code}</b></div>
            <button type="submit" class="btn btn-danger pull-right" id="submitSendToAxiomusReturn">Анулировать заявку</button>
        </div>
    {/if}
    {if (!$axiomus_succes)}
        <button type="submit" class="btn btn-success" id="submitSendToAxiomus">Отправить через Axiomus</button>
    {/if}
</div>

<script>
    $(document).ready(function () {
        $('#submitSendToAxiomus').click(function () {
            updatePrice('axiomus', 'new');
        });
        $('#submitSendToAxiomusReturn').click(function () {
            updatePrice('axiomus', 'delete');
        });
        function updatePrice(delivery, action) {
            data = 'dellivery='+delivery+'&action='+action+'&order_id={$order_id}&cart_id={$cart_id}';
            $.ajax({
                type: 'POST',
                url: '{$_axiomus_sendto_link}',
                data: data,
                beforeSend: function () {
                    console.log('sending...');
                },
                success: function(data){
                    console.log('end... true');
                    if(data = 'true'){
                        location.reload();
                    }else{
                        console.log(data);
                    }
                }
            });
        }
    });
</script>