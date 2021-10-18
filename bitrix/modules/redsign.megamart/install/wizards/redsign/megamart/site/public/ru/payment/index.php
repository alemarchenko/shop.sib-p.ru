<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Способы оплаты");
?>
	

	<div class="b-payment-icon mb-3">
		<img src="img/cash.png" alt="Наличный расчет" title="Наличный расчет">
	</div>
	<h4>Наличный расчет</h3>
	<p>Самый распространенный и удобный способ оплаты покупок. Оплата производится наличными курьеру при доставке или в магазине при самовывозе. Вместе с товаром передается товарный и кассовый чеки, а также гарантийный талон.</p>
    <br>
    
    
	<div class="b-payment-icon mb-3">
		<img src="img/cashless.png" alt="Безналичный расчет" title="Безналичный расчет">
	</div>
	<h4>Безналичный расчет</h4>
	<p> Это единственный способ оплаты в случае, если заказ оформляется на юридическое лицо. Минимальная сумма заказа для выставления счета составляет 3500 рублей. </p>
	<p>При получении заказа необходимо иметь при себе доверенность от организации-заказчика и удостоверние личности. Вместе с заказом выдаются счет, счет-фактура и накладная.</p>
    <br>
    
	<div class="b-payment-icon mb-3">
		<img src="img/card.png" alt="Банковская карта" title="Банковска карта">
	</div>
	<h4>Банковской картой</h3>
	<p>Мы принимаем онлайн-платежи по следующим платежным сисетмам: Visa, MasterCard, JCB, DCL</p>
	<figure class="b-payment-logos d-flex mt-3 mb-3">
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img src="#SITE_DIR#payment/img/visa.png" alt="visa"> 
		</div>
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img src="#SITE_DIR#payment/img/mastercard.png" alt="mastercard"> 
		</div>
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img src="#SITE_DIR#payment/img/jcb.png" alt="jcb"> 
		</div>
		<div class="py-2 px-4 mr-4 mb-4 b-payment-logos__item">
			<img src="#SITE_DIR#payment/img/dcl.png" alt="dcl">  
		</div>
	</figure>
    <br>
	<p>К оплате не принимаются банковские карты Visa и MasterCard без кода CVV2 / CVC2. <br> Оплата заказа производится через интернет непосредственно после его оформления.<br> Минимальная сумма платежа составляет 500 рублей.</p>
	<p>В случае, если Вы оплатили заказ банковской карточкой и затем отказались от него, возврат переведенных средств производится на Ваш банковский (карточный) счет. 


 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
