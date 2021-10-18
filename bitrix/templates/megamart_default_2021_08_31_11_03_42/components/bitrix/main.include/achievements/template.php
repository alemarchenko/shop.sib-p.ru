<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arAchivements = CUtil::JsObjectToPhp($arParams['~COMPANY_ACHIVEMENTS']);
?>
<div class="row text-center mb-4">
	<?php foreach ($arAchivements as $arAchivement): ?>
		<div class="col-lg-3 col-6 mb-5">
			<div class="b-about-achievement">
				<div class="b-about-achievement__number text-primary">
					<?=$arAchivement[0]?>
				</div>
				<div class="b-about-achievement__desc">
					<?=$arAchivement[1]?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<!--
        <div class="col-lg-3 col-6 mb-5">
            <div class="b-about-achievement">
                <div class="b-about-achievement__number text-primary">
                    600 000
                </div>
                <div class="b-about-achievement__desc">
                    Посетителей сайта <br> ежемесячно
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-5">
            <div class="b-about-achievement">
                <div class="b-about-achievement__number text-primary">
                    12
                </div>
                <div class="b-about-achievement__desc">
                    Регионов <br> присутствия
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-5">
            <div class="b-about-achievement">
                <div class="b-about-achievement__number text-primary">
                    48 000
                </div>
                <div class="b-about-achievement__desc">
                    Покупок каждый <br> месяц
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-5">
            <div class="b-about-achievement">
                <div class="b-about-achievement__number text-primary">
                    15 000
                </div>
                <div class="b-about-achievement__desc">
                    Товаров <br> на складах
                </div>
            </div>
        </div>
    </div> -->
