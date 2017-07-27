
<head>
    <meta charset="UTF-8">
    <title>1 step purchase</title>
    {*<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">*}
    {*<script rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>*}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
</head>


<div class="container">
    <h2>Оформление заказа</h2>
    <h3>Данные заказа</h3>
     <h4>Сумма заказа: {$productprice}р.</h4>
    <form id="carryform" action="{$link->getModuleLink('axiomuspostcarrier', 'validationoneclick', [], true)|escape:'html'}" method="post">
        <h4 id="delivery-price-block" style=" display:none">Сумма доставки: <img src="/img/loader.gif" id="delivery-price-loader"><span id="delivery-price"></span></h4>
        <div class="row">
            <div class="delivery-checkout">
                <div class="col-md-12">
                    <fieldset class="form-group" > <h3>Выберите тип перевозки</h3>
                        <label for="exampleSelect1"></label>
                        <div class="form-check">
                            <label class="form-check-label delivery-type" id="opt-delivery-parent"><input type="radio" name="delivery-type" value="0" id="opt-carry" checked>Самовывоз</label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label delivery-type"><input type="radio" name="delivery-type" value="1" id="opt-delivery">Доставка до двери</label>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="row opt-delivery" style="display:none">
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-region-block">
                <div class="form-group">
                    <label for="select-delivery-region">Выберите место доставки</label>
                    <select class="form-control" id="select-delivery-region">
                        <option value="0" selected>Москва</option>
                        <option value="1">Санкт-Петербург</option>
                        <option value="2">Россия</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-city-loader" style="display:none">
                <img src="/img/loader.gif" style="margin-top: 20px">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-city-block" style="display:none">
                <div class="delivery-pecom form-group">
                    <label for="select-delivery-city">Уточните Город</label>
                    <select class="form-control" name="select-delivery-city"  id="select-delivery-city">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-kad-loader" style="display:none">
                <img src="/img/loader.gif" style="margin-top: 20px">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-kad-block" style="display:none">
                <div class="delivery-moscow form-group">
                    <label for="select-delivery-kad">удалённость от МКАДа</label>
                    <select class="form-control carry-moscow" id="select-delivery-kad">
                        <option>в пределах МКАД</option>
                        <option>до 5 км от МКАД</option>
                        <option>от 5м до 10 км от МКАД</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-time-loader" style="display:none">
                <img src="/img/loader.gif" style="margin-top: 20px">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-delivery-time-block" style="display:none">
                <div class="delivery-moscow form-group">
                    <label for="select-delivery-time">Время доставки</label>
                    <select class="form-control " id="select-delivery-time">
                        <option>от 10 до 14</option>
                        <option>от 14 до 18</option>
                        <option>от 18 до 22</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="row opt-carry">
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
                <div class="form-group">
                    <label for="select-region">Выберите место доставки</label>
                    <select class="form-control" name="select-region" id="select-region">
                        <option value="0" selected>Москва и Область</option>
                        <option value="1">Санкт-Петербург и область</option>
                        <option value="2">Россия</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-city-loader" style="display:none">
                <img src="/img/loader.gif" style="margin-top: 20px">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-city-block" style="display:none">
                <div class="form-group">
                    <label for="select-city">Укажите город:</label>
                    <select class="form-control" name="select-city" id="select-city">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-point-loader" style="display:none">
                <img src="/img/loader.gif" style="margin-top: 20px">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6" id="select-point-block" style="display:none">

                <div class="form-group">
                    <label for="select-point">Укажите точку самовывоза:</label>
                    <select class="form-control" name="select-point" id="select-point">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>


        <h3>Заполните данные для доставки</h3>
        <p>* - поля, обязательные для заполнения</p>

        <div class="opt-delivery" style="display:none">
            <!-- PERSONAL info row  NAME -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="lastname">Фамилия</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="lastname" placeholder="Фамилия">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="firstname">Имя</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="firstname" placeholder="Имя">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="patronymic">Отчество</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="patronymic" placeholder="Отчество">
                </div>
            </div>
            <!-- PERSONAL info row  CITY -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="city">Город</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="city" id="city" placeholder="город">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="email">Email</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="email" placeholder="Email">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="phone">Телефон</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="phone" placeholder="Телефон">
                </div>
            </div>
            <!-- PERSONAL info row  CITY -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="street">Улица</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="street" placeholder="Улица">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="home">Дом</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="home" placeholder="Дом">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="housing">Корпус</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="housing" placeholder="Корпус">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="entrance">Домофон</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="entrance" placeholder="Домофон">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="apartament">Квартира</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="apartament" placeholder="Квартира">
                </div>
            </div>

        </div>


        <div class="form-group opt-carry">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="lastname">Фамилия</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12"  name="lastname-carry" placeholder="Фамилия">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="firstname">Имя</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="firstname-carry" placeholder="Имя">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="patronymic">Отчество</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="patronymic-carry" placeholder="Отчество">
                </div>
            </div>
            <!-- PERSONAL info row  CITY -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="email">Email</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="email-carry" placeholder="Email">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="phone">Телефон</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" name="phone-carry" placeholder="Телефон">
                </div>
            </div>
            <button type="submit" name="processCarrier" class="button btn btn-default standard-checkout button-medium">
            <span>
                {l s='Продолжить' mod='axiomuspostcarrier'}
                <i class="icon-chevron-right right"></i>
            </span>
            </button>
        </div>
    </form>

</div>

<script>
$(document).ready(function (){

    $('.delivery-type').change(function () { //выбор доставки или самовывоза
        radioInputDelivery = $("input[name='delivery-type']");
        if(radioInputDelivery.prop('checked')) {
            $('.opt-carry').show();
            $('.opt-delivery').hide();
            $("#select-region").change();

        }else{
            $('.opt-carry').hide();
            $('.opt-delivery').show();
            $("#select-delivery-region").change();
            $('#select-delivery-region-block div.selector').css("width", '100%');
            $('#select-delivery-region-block span').css("width", '100%');
            $('#select-delivery-kad-block div.selector').css("width", '100%');
            $('#select-delivery-kad-block span').css("width", '100%');
            $('#select-delivery-time-block div.selector').css("width", '100%');
            $('#select-delivery-time-block span').css("width", '100%');

        }
    });

    $('#select-region').change(function(){ // выбор места самовывоза + ajax запрос CarryPoint
        region = $('#select-region option:selected').attr("value");

        if(region === "0" || region==="1" ){
            $('#select-city-block').hide();
            setOptionToSelect('select-point', 'carrypoint', region, false);
        }else {
            $('#select-point-block').hide();
            $('#select-city-block').show();
            setOptionToSelect('select-city', 'carrypoint', region, false);
        }

    });

    $('#select-city').change(function(){
        $('#select-point-block').show();
        city_id = $('#select-city option:selected').attr("value");
        setOptionToSelect('select-point', 'getpointcountry', city_id, false);
    });

    $('#select-point').change(function(){
        getPriceCarry();
    });

    $('#select-delivery-city').change(function(){
        getPriceDeliveryRegion();
    });

    $('#select-delivery-kad').change(function(){
        getPriceDelivery();
    });

    $('#select-delivery-time').change(function(){
        getPriceDelivery();
    });

    $('#select-delivery-region').change(function(){ // выбор места самовывоза + ajax запрос CarryPoint
        let region_delivery = $('#select-delivery-region option:selected').attr("value");

        console.log('region_delivery:'+region_delivery);
        if(region_delivery === "0" || region_delivery==="1" ){
            $('#select-delivery-city-block').hide();
            setOptionToSelect('select-delivery-kad', 'kad', region_delivery, false);
            setOptionToSelect('select-delivery-time', 'time', region_delivery, false);
        }else {
            $('#select-delivery-kad-block').hide();
            $('#select-delivery-time-block').hide();
            setOptionToSelect('select-delivery-city', 'carrypoint', region_delivery, false);
        }

    });

    function setOptionToSelect(select_name, controller, data_id, need_update_price = true) {
        let data = 'data_id='+data_id;

        $('#'+select_name+'-block').hide();
        $('#'+select_name+'-loader').show();  //показать индикатор загрузки
        $.ajax({
            type: 'POST',
            url: '/index.php?fc=module&module=axiomuspostcarrier&controller='+controller,
            data: data,
            success: function(data) {
                if (data != '') {
                    data = JSON.parse(data);
                    if (data.length > 0) {
                        $("#" + select_name).empty();
                        first = true;
                        for (var id in data) {
                            select_param = (first) ? 'selected' : '';
                            $("#" + select_name).append(
                                '<option value="' + data[id]['id'] + '" ' + select_param + '>' + data[id]['name'] + '</option>'
                            );

                            first = false;
                        }
                        $("#" + select_name).change();
                        $('#' + select_name + '-block').show();
                        $('#' + select_name + '-block div.selector').css("width", '100%');
                        $('#' + select_name + '-block span').css("width", '100%');

                        $('#' + select_name + '-loader').hide(); //ToDo Спрятать индикатор загрузки
//                        if (need_update_price) getPriceCarry();
                    }
                }
            }
        })
    }
    
    function getPriceCarry() {
        region = $('#select-region option:selected').attr("value");
        point = $('#select-point option:selected').attr("value");

        let data = 'region='+region+'&point_id='+point+'&cart_id='+{$cart_id};

        $('#delivery-price-block').show();  //показать блок с ценой
        $('#delivery-price').text('');
        $('#delivery-price-loader').show();

        $.ajax({
            type: 'POST',
            url: '/index.php?fc=module&module=axiomuspostcarrier&controller=getpricecarry',
            data: data,
            success: function(data) {
                data = JSON.parse(data);
                if (data.error) {
                    $('#delivery-price').text('будет доступна после оформления заказа');
                    $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                }else{
                    console.log(data);

                    if (data.value !== undefined) {

                        $('#delivery-price').text(data.value+'р.');
                        $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                    }
                }
            }
        })
    }

    function getPriceDeliveryRegion() {
        let region = $('#select-delivery-region option:selected').attr("value");
        let city = $('#select-delivery-city option:selected').attr("value");


        let data = 'region='+region+'&city_id='+city+'&cart_id='+{$cart_id};
        $('#delivery-price').text('');
        $('#delivery-price-loader').show();
        $('#delivery-price-block').show();  //показать блок с ценой
        $.ajax({
            type: 'POST',
            url: '/index.php?fc=module&module=axiomuspostcarrier&controller=getpricedeliveryregion',
            data: data,
            success: function(data) {
                data = JSON.parse(data);
                if (data.error) {
                    $('#delivery-price').text('будет доступна после оформления заказа');
                    $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                }else{
                    console.log(data);

                    if (data.value !== undefined) {

                        $('#delivery-price').text(data.value+'р.');
                        $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                    }
                }
            }
        })
    }
    function getPriceDelivery() {
        let region = $('#select-delivery-region option:selected').attr("value");
        let kad = $('#select-delivery-kad option:selected').attr("value");
        let time = $('#select-delivery-time option:selected').attr("value");

        let data = 'region='+region+'&kad_id='+kad+'&time_id='+time+'&cart_id='+{$cart_id};
        $('#delivery-price').text('');
        $('#delivery-price-loader').show();
        $('#delivery-price-block').show();  //показать блок с ценой
        $.ajax({
            type: 'POST',
            url: '/index.php?fc=module&module=axiomuspostcarrier&controller=getpricedelivery',
            data: data,
            success: function(data) {
                data = JSON.parse(data);
                if (data.error) {
                    $('#delivery-price').text('будет доступна после оформления заказа');
                    $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                }else{
                    console.log(data);

                    if (data.value !== undefined) {

                        $('#delivery-price').text(data.value+'р.');
                        $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                    }
                }
            }
        })
    }


    $("#select-region").change();


    $("#carryform").validate({
        rules:{
            lastname:{
                required: true,
            },
            firstname:{
                required: true,
            },
            patronymic:{
                required: true,
            },
            email:{
                required: true,
                email: true
            },
            phone:{
                required: true,
                number: true,
//                max_length: 12,
//                min_length: 9
            },
        },
        messages:{
            lastname:{
                required: "Это поле обязательно для заполнения",
            },
            firstname:{
                required: "Это поле обязательно для заполнения",
            },
            patronymic:{
                required: "Это поле обязательно для заполнения",
            },
            email:{
                required: "Это поле обязательно для заполнения",
            },
            phone:{
                required: "Это поле обязательно для заполнения",
                minlength: "Введите номер без восьмёрки",
                maxlength: "Введите номер без восьмёрки",
            },


        }

    });

    $("#deliveryform").validate({
        rules:{
            lastname:{
                required: true,
            },
            firstname:{
                required: true,
            },
            patronymic:{
                required: true,
            },
            email:{
                required: true,
                email: true
            },
            phone:{
                required: true,
                number: true,
                max_length: 10,
                min_length: 9
            },
            city:{
                required: true,
            },
            house:{
                required: true,
            },
            apartament:{
                required: true,
            },
            street:{
                required: true,
            },
            housing:{
                required: true,
            },
            entrance:{
                required: true,
            },
        },
        messages:{
            lastname:{
                required: "Это поле обязательно для заполнения",
            },
            firstname:{
                required: "Это поле обязательно для заполнения",
            },
            patronymic:{
                required: "Это поле обязательно для заполнения",
            },
            email:{
                required: "Это поле обязательно для заполнения",
            },
            phone:{
                required: "Это поле обязательно для заполнения",
                minlength: "Введите номер без восьмёрки",
                maxlength: "Введите номер без восьмёрки",
            },
            city:{
                required: "Это поле обязательно для заполнения",
            },
            house:{
                required: "Это поле обязательно для заполнения",
            },
            apatament:{
                required: "Это поле обязательно для заполнения",
            },
            street:{
                required: "Это поле обязательно для заполнения",
            },
             housing:{
                required: "Это поле обязательно для заполнения",
            },
            entrance:{
                required: "Это поле обязательно для заполнения",
            },


        }

    });

});
</script>
