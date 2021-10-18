<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus('404 Not Found');
@define('ERROR_404','Y');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetPageProperty("hide_inner_sidebar", "Y");
$APPLICATION->SetPageProperty("hide_outer_sidebar", "Y");

$APPLICATION->SetTitle('Страница не найдена');?>

<div class="container">
    <div class="b-error-page">
        <div class="b-error-page__code">404</div>
        <h3 class="b-error-page__title">Страница не найдена</h3>
        <div class="b-error-page__note">Не расстраивайтесь, у нас на сайте много есть интересного</div>
        <div class="b-error-page__btn">
             <a class="btn btn-primary" href="<?=SITE_DIR?>catalog/">Каталог товаров</a>
            <a class="btn btn-primary ml-sm-4 ml-md-0 ml-lg-4" href="<?=SITE_DIR?>">Вернуться на главную</a>
        </div>
    </div>
</div>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>