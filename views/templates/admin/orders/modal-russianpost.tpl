<!-- Modal -->
<div class="modal fade" id="russianpostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Дополнительные параметры доставки RussianPost</h4>
            </div>
            <div class="modal-body">
    <form action="{$_axiomus_sendto_link|escape:'html':'UTF-8'}&amp;" method="post" id="russianpost_form">
        <div id="setting-form" class="form-horizontal">
            <div class="form-group">
                <div class="row">
                    <label class="control-label col-lg-3">Количество упаковок</label>
                    <div class="col-lg-9">
                        <input type="text" id="position_count" class="" name="position_count" value="1">
                    </div>
                </div>
            </div>
        </div>
    </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="submitSendToRussianPost">Отправить в RussianPost</button>
            </div>
        </div>
    </div>
</div>