
<head>
    <meta charset="UTF-8">
    <title>1 step purchase</title>
    {*<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">*}
    {*<script rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>*}
    {*<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>*}
</head>


<div class="container">
    <h2>Оформление заказа</h2>
    <h3>Данные заказа</h3>
     <h4>Сумма заказа: {$productprice}р.</h4>

     <h4 id="delivery-price-block" style=" display:none">Сумма доставки: <img src="/img/loader.gif" id="delivery-price-loader"><span id="delivery-price"></span>р.</h4>
    <div class="row">
        <div class="delivery-checkout">
            <div class="col-md-12">
                <fieldset class="form-group" > <h3>Выберите тип перевозки</h3>
                    <label for="exampleSelect1"></label>
                    <div class="form-check" >
                        <label class="form-check-label delivery-type" id="opt-delivery-parent"><input type="radio" name="delivery-type" value="0" id="opt-carry" checked>Самовывоз</label>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label delivery-type"><input type="radio" name="delivery-type" value="1" id="opt-delivery">Доставка до дери</label>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="row opt-delivery" style="display:none">
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6">
            <div class="form-group ">
                <label for="select-regionDelivery">Выберите место доставки</label>
                <select class="form-control" id="select-regionDelivery">
                    <option value="0" selected>Москва</option>
                    <option value="1">Санкт-Петербург</option>
                    <option value="2">Россия</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6">
            <div class="delivery-moscow form-group">
                <label for="exampleSelect">удалённость от МКАДа</label>
                <select class="form-control carry-moscow" id="carrymoscow">
                    <option>в пределах МКАД</option>
                    <option>до 5 км от МКАД</option>
                    <option>от 5м до 10 км от МКАД</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6" >
            <div class="delivery-moscow form-group">
                <label for="exampleSelect">Время доставки</label>
                <select class="form-control " id="exampleSelect">
                    <option>от 10 до 14</option>
                    <option>от 14 до 18</option>
                    <option>от 18 до 22</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-6" >
            <div class="delivery-pecom form-group" style="display:none">
                <label for="exampleSelect">Уточните Город</label>
                <select class="form-control" name="select-point">
                <option></option>
                </select>
            </div>
        </div>
    </div>

    <div class="row opt-carry">
        <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
            <div class="form-group"><!--сиски-->
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

    <div class="row">
        <h3>Заполните данные для доставки</h3>
        <p>* - поля, обязательные для заполнения</p>

        <div class="form-group opt-delivery" style="display:none">
            <!-- PERSONAL info row  NAME -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="lastname">Фамилия</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="lastname" placeholder="Фамилия">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="firstname">Имя</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="firstname" placeholder="Имя">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="patronymic">Отчество</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="patronymic" placeholder="Отчество">
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
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="email" placeholder="Email">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="phone">Телефон</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="phone" placeholder="Телефон">
                </div>
            </div>
            <!-- PERSONAL info row  CITY -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="street">Улица</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="street" placeholder="Улица">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="home">Дом</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="home" placeholder="Дом">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="housing">Корпус</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="housing" placeholder="Корпус">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="entrance">Домофон</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="entrance" placeholder="Домофон">
                </div>
                <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                    <label class="sr-only" for="apartament">Квартира</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="apartament" placeholder="Квартира">
                </div>
            </div>
        </div>
        <div class="form-group opt-carry">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="lastname">Фамилия</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="lastname" placeholder="Фамилия">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="firstname">Имя</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="firstname" placeholder="Имя">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="patronymic">Отчество</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="patronymic" placeholder="Отчество">
                </div>
            </div>
            <!-- PERSONAL info row  CITY -->
            <div class="row">
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="email">Email</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="email" placeholder="Email">
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <label class="sr-only" for="phone">Телефон</label>
                    <input type="text" class="form-control col-md-4 col-sm-4 col-xs-12" id="phone" placeholder="Телефон">
                </div>
            </div>
        </div>
        <button type="submit" name="processCarrier" class="button btn btn-default standard-checkout button-medium">
            <span>
                {l s='Продолжить' mod='axiomuspostcarrier'}
                <i class="icon-chevron-right right"></i>
            </span>
        </button>
    </div>
</div>
<script>
$(document).ready(function (){

    $('.delivery-type').change(function () { //выбор доставки или самовывоза
        radioInputDelivery = $("input[name='delivery-type']");
        if(radioInputDelivery.prop('checked')) {
            $('.opt-carry').show();
            $('.opt-delivery').hide();
            $('')
        }else{
            $('.opt-carry').hide();
            $('.opt-delivery').show();
        }
    });

    $('#select-region').change(function(){ // выбор места самовывоза + ajax запрос CarryPoint
        region = $('#select-region option:selected').attr("value");

        if(region === "0" || region==="1" ){
            $('#select-city-block').hide();
            setOptionToSelect('select-point', 'carrypoint',region);
        }else {
            $('#select-point-block').hide();
            $('#select-city-block').show();
            setOptionToSelect('select-city', 'carrypoint', region);
        }

    });

    $('#select-city').change(function(){
        $('#select-point-block').show();
        city = $('#select-city option:selected').attr("value");
        setOptionToSelect('select-point', 'getpointcountry', city);
    });

//    $('#select-regionDelivery').change(function(){ //выбор места ДОСТАВКИ
//        city = $('#select-regionDelivery option:selected').attr("value");
//        console.log(city);
//        if(city === "0" || city==="1" ){
//            $('.delivery-moscow').show();
//            $('.delivery-pecom').hide();
//        }else{
//            $('.delivery-moscow').hide();
//            $('.delivery-pecom').show();
//        }
//    });

    function setOptionToSelect(select_name, controller, data_id) {
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
                                '<option value="' + data[id]['id'] + '" ' + select_param + '>' + data[id]['city'] + '</option>'
                            );

                            first = false;
                        }
                        $("#" + select_name).change();
                        $('#' + select_name + '-block').show();
                        $('#' + select_name + '-block div.selector').css("width", '100%');
                        $('#' + select_name + '-block span').css("width", '100%');

                        $('#' + select_name + '-loader').hide(); //ToDo Спрятать индикатор загрузки
                        getPrice();
                    }
                }
            }
        })
    }
    
    function getPrice() {
        region = $('#select-region option:selected').attr("value");
        point = $('#select-point option:selected').attr("value");

        let data = 'region='+region+'&point_id='+point+'&cart_id='+{$cart_id};

        $('#delivery-price-block').show();  //показать блок с ценой
        $.ajax({
            type: 'POST',
            url: '/index.php?fc=module&module=axiomuspostcarrier&controller=getpricecarry',
            data: data,
            success: function(data) {
                if (data != '') {
                    console.log(data);

                    if (data.length > 0) {

                        $('#delivery-price').text(data);
                        $('#delivery-price-loader').hide(); //ToDo Спрятать индикатор загрузки
                    }
                }
            }
        })
    }

    $("#select-region").change();
});
</script>
