<?
$MESS['EASYDIRECT_CRON_ERR'] = "Агенты не выполняются на CRON или обнаружена другая проблема с CRON";
$MESS['EASYDIRECT_CRON_ERR_TXT'] = "Для стабильной работы модуля настройте выполнение агентов на CRON!<br>Выполните <a href='/bitrix/admin/site_checker.php'>проверку системы</a> для точной диагностики. Если проверка системы не обнаруживает проблем с CRON, игнорируйте это сообщение.";

$MESS['EASYDIRECT_CURL_ERR'] = "Не найдено расширение Curl для PHP";
$MESS['EASYDIRECT_CURL_ERR_TXT'] = "Для работы модуля необходимо наличие билиотеки Curl для PHP. Без нее модуль работать не будет!<br>Обратитесь в техническую поддержку вашего хостинга с просьбой установить расширение Curl для PHP.";

$MESS['EASYDIRECT_TAB_SET'] = "Основные настройки";
$MESS['EASYDIRECT_TIT_TOKEN'] = "Привязка модуля к аккаунту Яндекс.Директа";
$MESS['EASYDIRECT_TOKEN_STATUS'] = "Статус привязки:";
$MESS['EASYDIRECT_TOKEN_STATUS_ON'] = "настроена";
$MESS['EASYDIRECT_TOKEN_STATUS_OFF'] = "не настроена, модуль не работает";

$MESS['EASYDIRECT_TOKEN_GET_SECRET']="Связать модуль с Яндекс.Директом";
$MESS['EASYDIRECT_TOKEN_WAITER']="Отправляется запрос, подождите...";
$MESS['EASYDIRECT_TOKEN_UNKNOWN_ERROR']="Неизвестная ошибка";
$MESS['EASYDIRECT_TOKEN_ERROR']="Ошибка ";
$MESS['EASYDIRECT_TOKEN_ERROR_NOTE']="Попробуйте еще раз, если ошибка повторится, обратитесь в техническую поддержку.";
$MESS['EASYDIRECT_TOKEN_GET_TOKEN']="Активировать связку и обновить данные аккаунта";
$MESS['EASYDIRECT_TOKEN_CHECKAPI']="Проверить связь с Директом, обновить данные аккаунта";
$MESS['EASYDIRECT_TOKEN_SET_USER']="Перепривязать или сменить аккаунт Директа:";
$MESS['EASYDIRECT_TOKEN_SET_USER_BUT']="Перепривязать аккаунт";

$MESS['EASYDIRECT_APITEST_OK'] = "Связка с Директом работает, данные обновлены";
$MESS['EASYDIRECT_APITEST_SUBCLIENT'] = "Вы работаете с аккаунта, который является клиентом агентства. Стабильная работа модуля не гарантируется!";
$MESS['EASYDIRECT_APITEST_ERR'] = "Связь с Директом не работает, перепривяжите аккаунт";
$MESS['EASYDIRECT_APITEST_USER_ERR'] = "Не смог получить данные аккаунта в Директе";

$MESS['EASYDIRECT_TIT_TOKEN_DATA'] = "Данные связанного аккаунта";
$MESS['EASYDIRECT_PARAM_LOGIN'] = "Логин пользователя в Яндексе:";
$MESS['EASYDIRECT_PARAM_CURRENCY'] = "Валюта:";

$MESS['EASYDIRECT_TAB_DOPSET'] = "Дополнительные настройки";
$MESS['EASYDIRECT_PARAM_DEFMETOD']="Метод расчета по умолчанию (поиск):";
$MESS['EASYDIRECT_PARAM_DEFMETOD_RSYA']="Метод расчета по умолчанию (рекламные сети):";
$MESS['EASYDIRECT_PARAM_NOTICE_EMAIL']="E-mail для уведомлений:";
$MESS['EASYDIRECT_PARAM_TIME_SET_PRICE']="Обновлять ставки раз в [минут]:<br><small>(От 5 до 120. Чем меньше, тем выше нагрузка и расход баллов.)</small>";
$MESS['EASYDIRECT_PARAM_DEFMAXPRICE']="Максимальная цена клика по умолчанию (на поиске):";
$MESS['EASYDIRECT_PARAM_DEFRSYAMAXPRICE']="Максимальная цена клика по умолчанию (для РСЯ):";

$MESS['EASYDIRECT_TIT_CATALOG'] = "Интеграция с каталогом";
$MESS['EASYDIRECT_CATALOG_IB_ID'] = "Каталоги товаров:<br><small>(не выбирайте инфоблоки предложений)</small>";
$MESS['EASYDIRECT_CATALOG_IB_ID_NOT_SELECT'] = "Интеграция отключена";
$MESS['EASYDIRECT_CATALOG_NOT_INSTALL'] = "Для отключения по остаткам и обновления цен необходим \"Торговый каталог\"<br>доступный в Битрикс \"Малый Бизнес\" и \"Бизнес\"";
$MESS['EASYDIRECT_CATALOG_AUTO_AVAILABLE'] = "Отключать/включать объявления по остаткам или активности товаров:";
$MESS['EASYDIRECT_CATALOG_AUTO_PRICE'] = "Обновлять цены в объявлениях:";
$MESS['EASYDIRECT_CATALOG_DEF_PRICE_TYPE'] = "Тип цены для Директа:";
$MESS['EASYDIRECT_CATALOG_CURRENCY'] = "Валюта для объявлений:";
$MESS['EASYDIRECT_UPDATE_SPEED_INFO'] = "Внимание! Повышение скорости обновления товаров увеличивает нагрузку на ваш хостинг/сервер.<br>Cтоит выставлять разумные значения.<br>Всего связанных с объявлениями товаров: #CNT# шт.";
$MESS['EASYDIRECT_CATALOG_UPDATE_SPEED'] = "Скорость обновления информации о товарах [товаров за 1 час]:";
$MESS['EASYDIRECT_CATALOG_UPDATE_INTERVAL'] = "Частота проверки информации о товаре [минут]:";

$MESS['EASYDIRECT_TIT_STAT'] = "Статистика и отчеты";
$MESS['EASYDIRECT_PARAM_VIEWLOGTIME']="Показывать системные сообщения за последние (часов):";
$MESS['EASYDIRECT_PARAM_DETAILLOD']="Показывать все действия в системных сообщениях:";
$MESS['EASYDIRECT_PARAM_WRITE_PHRASE_LOG']="Хранить статистику ставок:<br><small>(Создает повышенную нагрузку. Отключите, если ваш сайт не справляется с нагрузкой)</small>";
$MESS['EASYDIRECT_PARAM_SAVETIME_NOTE']="Статистика ставок расходует много места в БД, внимательно устанавливайте срок хранения.<br>Статистика очищается автоматически 1 раз в сутки. Изменения вступят в силу завтра.<br>Cистемные сообщения занимают: #SYS_LOG# Mb<br>Статистика ставок занимает: #PHRASE_LOG# Mb";
$MESS['EASYDIRECT_PARAM_LOG_SAVETIME']="Сколько дней хранить системные сообщения:";
$MESS['EASYDIRECT_PARAM_PHRASE_LOG_SAVETIME']="Сколько дней хранить статистику ставок:";
$MESS['EASYDIRECT_PARAM_LOG_DELETE']="Освободить место сразу после изменения срока хранения:<br><small>(обязательно дождитесь окончания загрузки)</small>";

$MESS['EASYDIRECT_TIT_YAXML'] = "Настройки определения СЕО позиций через Yandex.XML";
$MESS['EASYDIRECT_NOTE_YAXML'] = "Получить URL и узнать лимиты Вы можете <a href=\"https://xml.yandex.ru/settings/\" target=\"_blank\">перейдя по этой ссылке</a>.";
$MESS['EASYDIRECT_NOTE_YAXML_1'] = "<br>В поле \"Основной IP-адрес\" добавьте IP вашего сервера#IP#, иначе проверка работать не будет.";
$MESS['EASYDIRECT_NOTE_YAXML_2'] = "<br>По заданным параметрам будет проверено <b>#CNT#</b> слов.";
$MESS['EASYDIRECT_PARAM_ISYAXML'] = "Учитывать позиции в постоянной выдаче:";
$MESS['EASYDIRECT_PARAM_URLYAXML'] = "URL для запросов:";
$MESS['EASYDIRECT_PARAM_YAXML_IP_ADDR'] = "IP-адрес с которого производить запросы [по умолчанию пустой]:<br><small>(заполняйте только если у вашего сайта несколько ip-адресов)</small>";
$MESS['EASYDIRECT_PARAM_YAXML_REGION'] = "Регион для определения СЕО позиций:";
$MESS['EASYDIRECT_PARAM_YAXML_REGION_auto']="Автоматически (брать из объявлений)";
$MESS['EASYDIRECT_PARAM_YAXML_DAYLIMIT'] = "Максимальное кол-во проверок в день:";
$MESS['EASYDIRECT_PARAM_YAXML_HOURLIMIT'] = "Максимальное кол-во проверок в час:";
$MESS['EASYDIRECT_PARAM_YAXML_NEEDSHOWS'] = "Проверять слова с кол-вом показов больше:";
$MESS['EASYDIRECT_PARAM_YAXML_NEDDBET'] = "Проверять слова у которых спецразмещение дороже:";

$MESS['EASYDIRECT_TAB_HELP'] = "Инструкции и помощь";
$MESS['EASYDIRECT_HELP_1'] = "Получение Токена";
$MESS['EASYDIRECT_HELP_2'] = "Генерация объявлений";
$MESS['EASYDIRECT_HELP_3'] = "Связка каталога и Директа";
$MESS['EASYDIRECT_HELP_4'] = "Ручной подбор ключевых слов";
$MESS['EASYDIRECT_HELP_5'] = "Полная инструкция";
$MESS['EASYDIRECT_HELP_6'] = "История версий модуля";
$MESS['EASYDIRECT_TP_TITLE'] = "Техническая поддержка";
$MESS['EASYDIRECT_TP_CHAT'] = "Онлайн-чат поддержки";
$MESS['EASYDIRECT_TP_TEL'] = "Телефон";

$MESS['EASYDIRECT_TITLE_INSTRUCTIONS'] = "Инструкции по модулю";
$MESS['EASYDIRECT_SENDMAIL'] = "Задайте вопрос / сообщите об ошибке / пожелание";
$MESS['EASYDIRECT_SENDMAIL_EMAIL_SUBJECT'] = "Вопрос по модулю ED c ";
$MESS['EASYDIRECT_SENDMAIL_NAME'] = "Ваше имя";
$MESS['EASYDIRECT_SENDMAIL_EMAIL'] = "На какой e-mail прислать ответ";
$MESS['EASYDIRECT_SENDMAIL_ABOUT'] = "Вопрос/Проблема/Пожелание";
$MESS['EASYDIRECT_SENDMAIL_DEBUG'] = "Технические данные:<br><small>(для обращения в техподдержку)</small>";
$MESS['EASYDIRECT_SENDMAIL_SUBMIT'] = "Отправить сообщение";
$MESS['EASYDIRECT_SENDMAIL_OK'] = "Ваше сообщение отправлено";
$MESS['EASYDIRECT_SENDMAIL_OK_NOTE'] = "Ответ будет предоставлен на указанный e-mail в ближайшее время.";
$MESS['EASYDIRECT_SENDMAIL_ERROR'] = "Произошла ошибка в процессе отправки сообщения.";
$MESS['EASYDIRECT_SENDMAIL_ERROR_NOTE'] = "Задайте Ваш вопрос через <a target='blank' href='http://www.easydirect.ru/doc/support/'>форму техподдержки</a>. Ваше сообщение:<br>";

?>