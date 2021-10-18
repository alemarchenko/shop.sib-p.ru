<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Доставка");
?>

<div class="b-delivery-review"> 
    <div class="row justify-content-center">
        <div class="offset-lg-1 col-lg-4">
            <div class="b-delivery-review__face">
                <img class="b-delivery-review__img" src="#SITE_DIR#delivery/img/avatar-delivery.png" alt="Доставка"> 
            </div>
        </div>
        <div class="col-lg-7 align-self-center">
            <div class="b-delivery-review__content">
                <div class="b-delivery-review__offer">
                    <div class="b-delivery-review__offer-title">Отдел доставки</div>
                    <div class="b-delivery-review__offer-phone">+7 (495) 000-00-00</div>
                    <div class="b-delivery-review__schedule">Без выходных 09:00-22:00</div>
                </div>
                <div class="b-delivery-review__message"> Если купленная вещь не подошла, вы всегда можете вернуть ее при условии сохранения её товароного вида, упаковки и этикетки</div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive delivery-table">
    <table align="left" width="100%">
        <tbody>
            <tr>
                <td width="39.18%" class="delivery-table__title bg-extra-gray">Территориальная зона доставки</td>
                <td colspan="3" class="delivery-table__title bg-extra-gray">Стоимость и условия доставки</td>
            </tr>
            <tr>
                <td></td>
                <td width="19.7%">Вес заказа <br> до 20 кг</td>
                <td width="19.7%">Вес заказа <br> от 20 кг до 900 кг</td>
                <td>Вес заказа <br> свыше 900 кг</td>
            </tr>
            <tr> 
                <td colspan="4" class="delivery-table__title bg-extra-gray">Москва</td>
            </tr>
            <tr>
                <td>Все районы (кроме Зеленограда и мкр. Жулебино)</td>
                <td>300р <br> (Минимальная сумма заказа 1000 р.)</td>
                <td>500р</td>
                <td>Инивидуальный расчет в зависимости от стоимость заказа</td>
            </tr>
            <tr>
                <td colspan="4" class="delivery-table__title bg-extra-gray">Москва за МКАД и Московская область</td>
            </tr>
            <tr>
                <td>Люберецкий район, Некрасовка <br> и Жулебино (Люберцы, Красково, Томилино, Жилино, Малаховка, Мирный, Октябрьский, Пехорка, Соснова, Часовня, Чкалово)</td>
                <td> Бесплатно (Минимальная сумма заказа 500р.)</td>
                <td> Бесплатно</td>
                <td> 500р</td>
            </tr>
            <tr>
                <td>Кожухово, Латракино, Островцы, Жуковский, Раменское, Дзержинский, Котельники, Реутов, Железнодорожный</td>
                <td>300р <br> (Минимальная сумма заказа 1000 р.)</td>
                <td>300р <br> (Минимальная сумма заказа 1000 р.)</td>
                <td>500р</td>
            </tr>
            <tr>
                <td>Другие районы и города Московской области в пределах от МКАД до Московского Малого</td>
                <td>1000р (Минимальная сумма заказа 3000 р.)</td>
                <td>1000р</td>
                <td>Индивидуальный расчет в зависимости от стоимость заказа</td>
            </tr>
            <tr>
                <td>Другие районы и города Московской области за пределы Московского Малого Кольца</td>
                <td>1500р (Минимальная сумма заказа 3000 р.)</td>
                <td>1500р</td>
                <td>Индивидуальный расчет в зависимости от стоимость заказа</td>
            </tr>
            <tr>
                <td colspan="4" class="delivery-table__title bg-extra-gray">За пределами Московской области</td>
            </tr>
            <tr>
                <td>Доставка по областям: Рязанская, Владимирская, Ярославская, Тверская, Смоленская, Калужская, Тульская</td>
                <td colspan="3">Индивидуальный расчет в зависимости от стоимости заказа</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Место доставки</h3>
<p>Доставка осуществляется по адресу, указанному при оформлении заказа. Если необходимо доставить товар по иному адресу, необходимо сообщить адерс менеджеру Службы доставки, который свяжется с вами непосредственно после оформления заказа на сайте.</p>

<h3>Время доставки</h3>
<p>Время доставки согласовывается с менеджером Службы доставки, который обязательно свяжется с вами сразу после того, как вы разместите свой заказ.</p>
<p>Внимание! Неправильно указанный номер телефона, неточный или неполный адрес могут привести к дополнительной задержке! Пожалуйста, внимательно проверяйте ваши персональные данные при регистрации и оформлении заказа. Конфиденциальность ваших регистрационных данных гарантируется.</p>
<p>Доставка выполняется ежедневно с 10:00 до 20:00 часов, в субботу с 10:00 до 14:00, в воскрсенье доставки нет. Товары, заказанные вами в субботу и воскресенье, доставляются в понедельник. Время осуществления доставки зависит от времени размещения заказа и наличия товара на складе:</p>
<ul class="delivery-list">
    <li class="delivery-list__item mb-4"> Если заказ подтвержден менеджером Службы доставки до 12:00, товар может быть доставлен на следующий рабочий день между 10:00 и 15:00 или между 15:00 и 20:00;</span></li>
    <li class="delivery-list__item mb-4"> Если заказ подтвержден менеджером Службы доставки после 12:00, товар может быть доставлен на следующий рабочий день между 15:00 и 18:00;</li>
</ul>
<p>Вы также можете указать любое другое удобное время доставки, и покупка будет доставлена в удобное вам время. Иное время доставки, а также время доставки в населенные пункты области определяются по договоренности с клиентом.</p>                            
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
