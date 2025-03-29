<?php

$texts = array(
    'uz' => [
        'text' => [
            'error_api' => "⚠️ <b>API tizimida nosozlik yuzaga keldi. Keyinroq urunib ko'ring!</b>",
            'error_rejected' => "⚠️ <b>Kechirasiz, so'rovingiz rad etildi!</b>",
            'error_server' => "⚠️ <b>OpenAI dan: Ichki server xatosi!</b>",
            'error_bots' => "⚠️ <b>Botdan foydalanish vaqtincha faolsizlantirilgan. Keyinroq urunib ko'ring!</b>",

            'start_gpt' => "Salom, Men ChatGPT botman. Men OpenAI tomonidan yaratilganman. Men sizga savollarga javob berishda yordam bera olishim mumkin. Menga istalgan savolni bering!",
            'start_img' => "Salom, Men ChatGPT botman. Men OpenAI tomonidan yaratilganman. Men sizga tasvirlarni generatsiya qilishga yordam bera olishim mumkin. Menga istalgan tasvir tavsifini bering!",

            'settings' => "⚙ <b>Sozlamalar bo'limidasiz!</b>
	
<i>Quyidagi bo'limlardan birini tanlang:</i>",

            'department' => "Bo'lim yopildi!",

            'limits_finish' => "<i>Sizning kunlik limitlaringiz tugagan!</i>",
            'limits_there_is' => "<i>Sizda kunlik %limits% ta limit mavjud!</i>",
            'limits_text' => "⏳ <b>Limitlar bo'limidasiz!</b>

%limits%

✅ <b>Limitlaringiz quyida ko'rsatilgan vaqtdan so'ng, maksimal darajaga yetadi!</b>",

            'loading' => "⏱ <b>Yuklanmoqda...</b>",
            'preparing' => "⏱️ <b>Tayyorlanmoqda...</b>",

            'history' => "⏱ <b>Tarix bo'limidasiz!</b>

<i>Quyidagilardan birini tanlang:</i>",

            'history_cleaning' => "✅ <b>Muvaffaqiyatli tozalandi!</b>",
            'not_found' => "Topilmadi!",
            'no_limits' => "⏳ <b><i>Sizga taqdim etilgan bugungi limitlar tugadi!</i></b>

<i>%date% dan so'ng, qayta urunib ko'ring!</i>",

            'interfeys' => "✅ <b>Interfeys tili o'zgartirildi.</b>

<i>Botni to'liq tilga o'tkazish uchun yana /start buyrug'ini yuboring!</i>",
            'interfeys_choose' => "💬 <b>Quyidagi interfeys tillaridan birini tanlang:</b>",
            'interfeys_use' => "⚠️ Siz bu tildan foydalanyapsiz!",

            'isset_text' => "Afsus, men faqat matnlar orqali suhbatlasha olaman. Savollaringiz yoki takliflaringiz bormi? Menga yozib qoldiring!",

            'account' => "🔑 <b>Sizning ID raqamingiz:</b> <code>%id%</code>

💵 <b>Umumiy balansingiz:</b> %balance% so'm",
            'input' => "👇 <b>Quyidagi to'lov tizimlaridan birini tanlang!</b>",
            'click' => "<b>📋 To'lov tizimi:</b> CLICK

<b>💳 Hamyon ( yoki karta ):</b> <code>%wallet%</code>
<b>📝 Izoh:</b> <code>%id%</code>

<b>Tavsif: Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>

<b>1)</b> %pul% so'm miqdorni yuqoridagi hamyonga tashlang,
<b>2)</b> «To'lov qildim ✅» tugmasini bosing!

❗️<b>Sizning to'lovingiz miqdoriga %tolov% so'm qo'shildi, bu sizning to'lovingizni boshqa to'lovlar orasida aniqlash uchun kerak. Ushbu to'lov tizimiga izoh kiritish majburiy emas!</b>",
            'check_no_complete' => "⚠️ To'lov bajarilmagan!",
            'check_complete' => "✅ To'lov bajarilgan!",
            'amount' => "💵 <b>To'lov miqdorini kiriting:

Boshlang'ich miqdor:</b> %amount%",
            'sendInvoice' => "Hisobingiz %amount% so'm ga to'ldiriladi!",
            'payment_complete' => "💵 <b>Hisobingiz %amount% so'm ga to'ldirildi!</b>",
            'check_admin' => "💵 <b>Foydalanuvchi (</b>%id%<b>) hisobi %amount% so'm ga to'ldirildi.</b>",

            'api' => "🔑 <b>API bo'limidasiz!</b>
	
<i>Quyidagi bo'limlardan birini tanlang:</i>",
            'api_key' => "🔑 <b>Sizning API kalitingiz:</b> <code>%api_key%</code>

⏳ <b>Limitlaringiz soni:</b> %limits% ta",
            'reset' => "⚠️ <b>API kalitingizni yangisiga almashtirishga ishonchingiz komilmi?</b>

❔ <i>API kalitingiz yangilanganda, avvalgi API kalitdan foydalana olmaysiz.</i>",
            'agree' => "✅ <b>API kalit yangilandi.</b>

<i>Yangi API kalit:</i> <code>%api_key%</code>",
            'api_shop' => "💵 <b>Qancha limit sotib olmoqchisiz?</b>

<i>1 ta limit narxi: %sum% so'm</i>",
            'shop_hisob' => "<b>Hisobingizda yetali mablag' mavjud emas!</b>

Qaytadan urunib ko'ring:",
            'is_numeric' => "<b>Faqat raqamlardan foydalaning!</b>",
            'acceptance' => "<b>Qabul qilinmadi!</b>

Qaytadan urunib ko'ring:",
            'shop_complete' => "💵 <b>Hisobingizga %limit% ta limit qo'shildi va hisobingizdan %sum% so'm olib tashlandi!</b>",

            'ai' => "🧠 <b>Sun'iy intellektlar bo'limidasiz!</b>

<i>Quyidagilardan birini tanlang:</i>",
            'ai_use' => "⚠️ Siz ushbu sun'iy intellektdan foydalanyapsiz!",
        ],
        'button' => [
            'interfeys' => "💬 Interfeys tilini o'zgartirish",
            'uz' => "🇺🇿 O'zbek tili - ✅",
            'ru' => "🇷🇺 Rus tili",
            'en' => "🇬🇧 Ingliz tili",
            'limits' => "⏳ Limitlar",
            'update' => "🔄 Yangilash",
            'history' => "⏱ Tarix",
            'history_seen' => "💭 Tarixni ko'rish",
            'history_cleaning' => "🧹 Tarixni tozalash",
            'account' => "💰 Mening hisobim",
            'input' => "💵 Hisobni to'ldirish",
            'stars' => "⭐ Telegram Stars",
            'click' => "🔵 CLICK",
            'check' => "✅ To'lov qildim",
            'api_manual' => "📚 Qo'llanma",
            'api_shop' => "🛍️ Sotib olish",
            'api_key' => "🔑 API kalit",
            'reset' => "🔄️ Qayta o'rnatish",
            'agree' => "✅ Roziman",
            'gpt' => "🤖 ChatGPT",
            'img' => "🎨 Tasvir generatori",
            'back' => "◀️ Orqaga",
            'close' => "Yopish",
        ],

        'commands' => [
            'start' => "Qayta ishga tushurish!",
            'settings' => "Botni sozlash!",
            'ai' => "Sun'iy intellektni o'zgartirish!",
            'panel' => "Boshqaruv paneli!",
        ],
    ],

    'ru' => [
        'text' => [
            'error_api' => "⚠️ <b>В системе API произошла ошибка. Попробуйте позже!</b>",
            'error_rejected' => "⚠️ <b>Извините, ваш запрос отклонен!</b>",
            'error_server' => "⚠️ <b>От OpenAI: Внутренняя ошибка сервера!</b>",
            'error_bots' => "⚠️ <b>Использование бота временно отключено. Попробуйте позже!</b>",

            'start_gpt' => "Привет, я бот ChatGPT. Меня создал OpenAI. Я могу помочь вам ответить на вопросы. Задайте мне любой вопрос!",
            'start_img' => "Привет, я бот ChatGPT. Я создан OpenAI. Я могу помочь вам создавать изображения. Дайте мне любое описание изображения!",

            'settings' => "⚙ <b>Вы находитесь в разделе Настройки!</b>

<i>Выберите один из следующих разделов:</i>",

            'department' => "Раздел закрыт!",

            'limits_finish' => "<i>Ваши дневные лимиты исчерпаны!</i>",
            'limits_there_is' => "<i>У вас есть дневной лимит %limits%!</i>",
            'limits_text' => "⏳<b>Вы находитесь в разделе лимиты!</b>

%limits%

✅ <b>Ваши лимиты будут исчерпаны по истечении времени, указанного ниже!</b>",

            'loading' => "⏱ <b>Загрузка...</b>",
            'preparing' => "⏱️ <b>Подготовка...</b>",

            'history' => "⏱ <b>Вы находитесь в разделе история!</b>

<i>Выберите один из следующих вариантов:</i>",

            'history_cleaning' => "✅ <b>Очищено успешно!</b>",
            'not_found' => "Не найдено!",

            'no_limits' => "⏳ <b><i>Сегодняшние лимиты, представленные вам, закончились!</i></b>

<i>Пожалуйста, повторите попытку через %date%!</i>",

            'interfeys' => "✅ <b>Изменен язык интерфейса.</b>

<i>Отправьте команду /start еще раз, чтобы переключить бот на полный язык!</i>",
            'interfeys_choose' => "💬 <b>Выберите один из следующих языков интерфейса:</b>",
            'interfeys_use' => "⚠️ Вы используете этот язык!",

            'isset_text' => "Извините, я могу общаться только через текстовые сообщения.  Есть вопросы или предложения?  Напиши мне!",

            'account' => "🔑 <b>Ваш идентификационный номер:</b> <code>%id%</code>

💵 <b>Ваш общий баланс:</b> %balance% сум",
            'input' => "👇 <b>Выберите одну из следующих платежных систем!</b>",
            'click' => "<b>📋 Платежная система:</b> CLICK 

<b>💳 Кошелёк (или карта):</b> <code>%wallet%</code>
<b>📝 Комментарий:</b> <code>%id%</code>

<b>Описание: Чтобы обмен прошел успешно, выполните следующие действия:</b>

<b>1)</b> Поместите сумму %pul% в кошелек выше,
<b>2)</b> Нажмите кнопку «Я оплатил ✅»!

❗️<b>К сумме вашего платежа добавлен %tolov% сум, что необходимо для идентификации вашего платежа среди других платежей. Комментировать данную платежную систему необязательно!</b>",
            'check_no_complete' => "⚠️ Платеж не завершен!",
            'check_complete' => " ✅ Платеж завершен!",
            'amount' => "💵 <b>Введите сумму платежа:

Начальная сумма:</b> %amount%",
            'check_admin' => "💵 <b>Счет пользователя (</b>%id%<b>) пополнен на сумму %amount% сум.</b>",
            'sendInvoice' => "Ваш счет будет пополнен на сумму %amount%!",
            'payment_complete' => "💵 <b>Ваш счет пополнен на %amount%!</b>",

            'api' => "🔑 <b>Вы находитесь в разделе API!</b>

<i>Выберите один из разделов ниже:</i>",
            'api_key' => "🔑 <b>Ваш ключ API:</b> <code>%api_key%</code>

⏳ <b>Количество ваших лимитов:</b> %limits%",
            'reset' => "⚠️ <b>Вы уверены, что хотите изменить свой ключ API?</b>

❔ <i>При обновлении вашего ключа API вы не сможете использовать предыдущий ключ API.</i>",
            'agree' => "✅ <b>Ключ API обновлен.</b>

<i>Новый ключ API:</i> <code>%api_key%</code>",
            'api_shop' => "💵 <b>Сколько лимитов вы хотите купить?</b>

<i>1 предел таркси: %sum% сум</i>",
            'shop_hisob' => "<b>На вашем счету недостаточно средств!</b>

Пожалуйста, попробуйте еще раз:",
            'is_numeric' => "<b>Просто используйте цифры!</b>",
            'acceptance' => "<b>Не принято!</b>

Пожалуйста, попробуйте еще раз:",
            'shop_complete' => "💵 К вашей учетной записи добавлен <b>%limit% лимита, а суммы %sum% удалены из вашей учетной записи!</b>",

            'ai' => "🧠 <b>Вы в отделе искусственного интеллекта!</b>

<i>Выберите один из следующих вариантов:</i>",
            'ai_use' => "⚠️ Вы используете этот ИИ!",
        ],
        'button' => [
            'interfeys' => "💬 Изменить язык интерфейса",
            'uz' => "🇺🇿 Узбекский язык",
            'ru' => "🇷🇺 Русский язык - ✅",
            'en' => "🇬🇧 Английский язык",
            'limits' => "⏳ Лимиты",
            'update' => "🔄 Обновлять",
            'history' => "⏱ История",
            'history_seen' => "💭 Посмотреть историю",
            'history_cleaning' => "🧹 Чистая история",
            'account' => "💰 Мой счет",
            'input' => "💵 Пополнить счет",
            'stars' => "⭐ Telegram Stars",
            'click' => "🔵 CLICK",
            'check' => "✅ Я заплатил",
            'api_manual' => "📚 Руководство",
            'api_shop' => "🛍️ Покупка",
            'api_key' => "🔑 API ключ",
            'reset' => "🔄️ Сброс",
            'agree' => "✅Я согласен",
            'gpt' => "🤖 ЧатГПТ",
            'img' => "🎨 Генератор изображений",
            'back' => "◀️ Назад",
            'close' => "Закрыть",
        ],

        'commands' => [
            'start' => "Перезапуск!",
            'settings' => "Настройки бота!",
            'ai' => "Изменить ИИ!",
            'panel' => "Панель управления!",
        ],
    ],

    'en' => [
        'text' => [
            'error_api' => "⚠️ <b>An error occurred in the API system.  Try again later!</b>",
            'error_rejected' => "⚠️ <b>Sorry, your query has been rejected!</b>",
            'error_server' => "⚠️ <b>From OpenAI: Internal server error!</b>",
            'error_bots' => "⚠️ <b>Bot usage has been temporarily disabled.  Try again later!</b>",

            'start_gpt' => "Hi, I'm ChatGPT bot.  I was created by OpenAI.  I can help you answer the questions.  Ask me any question!",
            'start_img' => "Hi, I'm ChatGPT bot. I'm made by OpenAI. I can help you generate images. Give me any image description!",

            'settings' => "⚙ <b>You are in Settings!</b>

<i>Select one of the sections below:</i>",

            'department' => "The section is closed!",

            'limits_finish' => "<i>Your daily limits are over!</i>",
            'limits_there_is' => "<i>You have a daily limit of %limits%!</i>",
            'limits_text' => "⏳ <b>You are in the Limits section!</b>

%limits%

✅ <b>Your limits expire after the time shown below!</b>",

            'loading' => "⏱ <b>Loading...</b>",
            'preparing' => "⏱️ <b>Preparing...</b>",

            'history' => "⏱ <b>You are in the history section!</b>

<i>Select one of the following:</i>",

            'history_cleaning' => "✅ <b>Cleared successfully!</b>",
            'not_found' => "Not found!",

            'no_limits' => "⏳ <b><i>Today's limits presented to you are over!</i></b>

<i>Please try again after %date%!</i>",

            'interfeys' => "✅ <b>The interface language has been changed.</b>

<i>Send the /start command again to switch the bot to full language!</i>",
            'interfeys_choose' => "💬 <b>Choose one of the interface languages ​​below:</b>",
            'interfeys_use' => "⚠️ You are using this language!",

            'isset_text' => "Sorry, I can only chat via texts.  Have questions or suggestions?  Write me!",

            'account' => "🔑 <b>Your ID number:</b> <code>%id%</code>

💵 <b>Your total balance:</b> %balance% sum",
            'input' => "👇 <b>Choose one of the following payment systems!</b>",
            'click' => "<b>📋 Payment system:</b> CLICK

<b>💳 Wallet (or card):</b> <code>%wallet%</code>
<b>📝 Comment:</b> <code>%id%</code>

<b>Description: To make your exchange successful, please do the following:</b>

<b>1)</b> Put %pul% amount of sum in the wallet above,
<b>2)</b> Click on the «I have paid ✅» button!

❗️<b>%tolov% soum has been added to your payment amount, which is necessary to identify your payment among other payments. Commenting on this payment system is optional!</b>",
            'check_no_complete' => "⚠️ Payment incomplete!",
            'check_complete' => " ✅ Payment completed!",
            'amount' => "💵 <b>Enter the payment amount:

Initial amount:</b> %amount%",
            'check_admin' => "💵 <b>The account of the user (</b>%id%<b>) was replenished with %amount% sum.</b>",
            'sendInvoice' => "Your account will be replenished to %amount% sum!",
            'payment_complete' => "💵 <b>Your account has been replenished by %amount%!</b>",

            'api' => "🔑 <b>You are in the API section!</b>

<i>Select one of the sections below:</i>",
            'api_key' => "🔑 <b>Your API Key:</b> <code>%api_key%</code>

⏳ <b>Number of your limits:</b> %limits%",
            'reset' => "⚠️ <b>Are you sure you want to change your API key?</b>

❔ <i>When your API key is renewed, you cannot use the previous API key.</i>",
            'agree' => "✅ <b>API key updated.</b>

<i>New API key:</i> <code>%api_key%</code>",
            'api_shop' => "💵 <b>How many limits do you want to buy?</b>

<i>1 limit price: %sum% sum</i>",
            'shop_hisob' => "<b>Your account does not have enough funds!</b>

Please try again:",
            'is_numeric' => "<b>Just use numbers!</b>",
            'acceptance' => "<b>Not accepted!</b>

Please try again:",
            'shop_complete' => "💵 <b>%limit% of limit has been added to your account and %sum% sums have been removed from your account!</b>",

            'ai' => "🧠 <b>You are in the artificial intelligence department!</b>

<i>Select one of the following:</i>",
            'ai_use' => "⚠️ You are using this AI!",
        ],

        'button' => [
            'interfeys' => "💬 Change the interface language",
            'uz' => "🇺🇿 Uzbek language",
            'ru' => "🇷🇺 Russian language",
            'en' => "🇬🇧 English language - ✅",
            'limits' => "⏳ Limits",
            'update' => "🔄 Update",
            'history' => "⏱ History",
            'history_seen' => "💭 View history",
            'history_cleaning' => "🧹 Clear history",
            'account' => "💰 My account",
            'input' => "💵 Top up your account",
            'stars' => "⭐ Telegram Stars",
            'click' => "🔵 CLICK",
            'check' => "✅ I paid",
            'api_manual' => "📚 Manual",
            'api_shop' => "🛍️ Purchase",
            'api_key' => "🔑 API key",
            'reset' => "🔄️ Reset",
            'agree' => "✅ I agree",
            'gpt' => "🤖 ChatGPT",
            'img' => "🎨 Image generator",
            'back' => "◀️ Back",
            'close' => "Shut down",
        ],

        'commands' => [
            'start' => "Restart!",
            'settings' => "Bot settings!",
            'ai' => "Change AI!",
            'panel' => "Control Panel!",
        ],
    ],
);

?>