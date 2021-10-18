<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Способы оплаты");
?>	<div class="b-payment-icon mb-3">
		<img alt="Безналичный расчет" src="img/cashless.png" title="Безналичный расчет">
	</div>
	<h4>Безналичный расчет</h4>
	<p> Это единственный способ оплаты в случае, если заказ оформляется на юридическое лицо. Минимальная сумма заказа для выставления счета составляет 3500 рублей. </p>
	<p>При получении заказа необходимо иметь при себе доверенность от организации-заказчика и удостоверние личности. Вместе с заказом выдаются счет, счет-фактура и накладная.</p>
    <br>
    
	<div class="b-payment-icon mb-3">
		<img alt="Банковская карта" src="img/card.png" title="Банковска карта">
	</div>
	<h4>Банковской картой</h4>
	<p>Мы принимаем онлайн-платежи по следующим платежным сисетмам: Visa, MasterCard, JCB, DCL</p>
	<figure class="b-payment-logos d-flex mt-3 mb-3">
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img alt="visa" src="/payment/img/visa.png"> 
		</div>
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img alt="mastercard" src="/payment/img/mastercard.png"> 
		</div>
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img alt="jcb" src="/payment/img/jcb.png"> 
		</div>
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img alt="dcl" src="/payment/img/dcl.png">  
		</div>
	</figure>
    <br>
	<p>К оплате не принимаются банковские карты Visa и MasterCard без кода CVV2 / CVC2. <br> Оплата заказа производится через интернет непосредственно после его оформления.<br> Минимальная сумма платежа составляет 500 рублей.</p>
	<p>В случае, если Вы оплатили заказ банковской карточкой и затем отказались от него, возврат переведенных средств производится на Ваш банковский (карточный) счет. 


 </p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>