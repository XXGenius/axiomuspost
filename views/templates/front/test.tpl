
<head>
    <meta charset="UTF-8">
    <title>1 step purchase</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {*<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>*}
</head>


<div class="container">
    <h2>Оформление заказа</h2>
    <h3>Данные заказа</h3>
     <p id="productPrice">Сумма заказа: {$productprice}р.</p>
    <div class="delivery-checkout">
        <div class="col-md-12 ">
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
    <div class="row opt-delivery" style="display:none">
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-6">
                <div class="form-group ">
                    <label for="SelectCityDelivery">Выберите место доставки</label>
                    <select class="form-control" id="SelectCityDelivery">
                        <option value="0" selected>Москва и Область</option>
                        <option value="1">Санкт-Петербург и область</option>
                        <option value="2">Россия</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-lg-4 col-sm-4 col-xs-6">
                <div class="delivery-moscow form-group ">
                    <label for="exampleSelect">удалённость от МКАДа</label>
                    <select class="form-control carry-moscow" id="carrymoscow"">
                    <option>в пределах МКАД</option>
                    <option>до 5 км от МКАД</option>
                    <option>от 5м до 10 км от МКАД</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-lg-4 col-sm-4 col-xs-6" >
                <div class="delivery-moscow form-group " ">
                    <label for="exampleSelect">Время доставки</label>
                    <select class="form-control " id="exampleSelect"">
                    <option>от 10 до 14</option>
                    <option>от 14 до 18</option>
                    <option>от 18 до 22</option>
                    </select>
                </div>
            <div class="col-md-3 col-lg-6 col-sm-4 col-xs-6" >
                <div class="delivery-pecom form-group " style="display:none">
                    <label for="exampleSelect">Уточните Город</label>
                    <select class="form-control " id="exampleSelect"">
                    <option>город 1</option>
                    <option>город 2</option>
                    <option>город 3</option>
                    </select>
                </div>
            </div>
            </div>
        </div>

<div class="row opt-carry">
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-6"">
                <div class="form-group ">
                    <label for="SelectCity">Выберите место доставки</label>
                    <select class="form-con" id="SelectCity">
                        <option value="0" select="selected">Москва и Область</option>
                        <option value="1">Санкт-Петербург и область</option>
                        <option value="2">Россия</option>
                    </select>
                </div>
            <div class="col-md-3 col-lg-6 col-sm-4 col-xs-6">
                    <div class="carry-moscow form-group">
                        <label for="exampleSelect">Уточните</label>
                        <select class="form-control carry-moscow" id="exampleSelect"">
                        <option>точка 1</option>
                        <option>точка 2</option>
                        <option>точка 3</option>
                        </select>
                    </div>
            </div>
                <div class="col-md-3 col-lg-6 col-sm-4 col-xs-6" >
                    <div class="carry-pecom form-group" style="display:none">
                        <label for="exampleSelect">Уточните Город</label>
                        <select class="form-control " id="carry-country"">
                        <option value="0"></option>
                        </select>
                    </div>
                </div>
        </div></div>
      </div>

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

<script>
$(document).ready(function (){

    $('.delivery-type').change(function () {
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

    $('#SelectCity').change(function(){
        city = $('#SelectCity option:selected').attr("value");
        console.log(city);
        if(city === "0" || city==="1" ){
            $('.carry-moscow').show();
            $('.carry-pecom').hide();
            Carry();
        }else{
            $('.carry-moscow').hide();
            $('.carry-pecom').show();
            Carry();
        }
    });

    $('#SelectCityDelivery').change(function(){
        city = $('#SelectCityDelivery option:selected').attr("value");
        console.log(city);
        if(city === "0" || city==="1" ){
            $('.delivery-moscow').show();
            $('.delivery-pecom').hide();
        }else{
            $('.delivery-moscow').hide();
            $('.delivery-pecom').show();
        }
    });
    $('.delivery-type').click(function () {
        Carry();
    });


//    var cities = ['Волгоград', 'Самара', 'Волгодонск'];
//    $("#carry-country").empty();
//    $.each(cities ,function(index,value){
//        $("#carry-country").append( $('<option value="'+ index +'">'+ value +'</option>'));
//        console.log('Индекс: ' + index.toString() + '; Значение: ' + value.toString());
//    });



    function Carry() {
        city = $('#SelectCity option:selected').attr("value");
        data = city
        console.log(data);
        $.ajax({
            type: 'POST',
            url: '/index.php?fc=module&module=axiomuspostcarrier&controller=test',
            data: data,
            success: function(data){
                data = JSON.parse(data);
                console.log(data);
                for (var id in data) {
                    $('#carry-country').append($('<option value="' + id + '">' + data[id] + '</option>'));
                }
            }
        })
    }
});
</script>
