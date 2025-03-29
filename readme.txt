Ushbu kod hosting provayderi yoki serverga yuklanganidan so'ng, albatta kerakli fayllar 1 daqiqalik cron qilinishi kerak.


— Cron qilinishi kerak bo'lgan fayllar:

1. cron.php
• 1 daqiqaga moslab cron qilinadi.

2. send.php
• 1 daqiqaga moslab cron qilinadi va fayl havolasini davomidan (qo'shilib yozilgan holda) "?update=send" qo'shimchasi qo'shiladi.
Ushbu qo'shimcha xabar yuborilganida "send" bilan update (yangilanish) kelgan bo'lsa, kodni ishga tushurishini ta'minlaydi.


— Qulayliklar:

• Kod 3 xil tilda ishlaydi, agar til qo'shishni istasangiz qo'shib olavering.

• Admin paneli mukammal tuzilgan va har bir admin uchun alohida permission (ruxsatlar) berishingiz mumkin. Masalan biror bir admin Kanal sozlamalariga kira olmasligini xohlasangiz buni cheklab qo'yishingiz mumkin.

• Majburiy a'zolik uchun kanal qo'shishda, kanal obunachilari soni kiritilgan miqdorga yetganida avtomatik ravishda u kanalni majburiy a'zolikdan olib tashlaydigan funksiya qo'shilgan.
Agar bu funksiyani kerakli kanal uchun ishlatmoqchi bo'lmasangiz, "Tashlab ketish" tugmasi orqali limit kiritmasligingiz mumkin.


— Muhim eslatmalar:

• Ushbu kod 100% ishlaydi. Agar ishlata olmasangiz bu kodga tegmang. Iltimos, bo'lar-bo'lmas ma'nosiz savollaringiz bilan meni bezovta qilmang!

• Koddagi API'lar eskirgan. Agar rostdan ham kodni ishga tushurib ishlatmoqchi bo'lsangiz, openai.com saytidan rasmiy ravishda API limitlarini sotib olishingizni tavsiya qilaman.

• Webhook qilsangiz lekin bot ishlamasa, ma'lumotlar bazasi ulanganligini tekshiring. Agar ulanmagan bo'lsa, uni ulang. Shunda bot kerakli tilni saqlab sizga to'g'ri matnlarni chiqarib bera oladi.

• Agar xabar yuborish bo'limini ishlatmoqchi bo'lsangiz, send.php fayliga bot tokeningizni qo'shing!

• Kod biror bir kanalda tarqatilganida albatta unda manba ko'rsatilishi shart. Agar kanalingizni mualliflik huquqini saqlab qolmoqchi bo'lsangiz manbani qo'yib keting!

• Manba: Alijonov Abdulbosit (@AlijonovUz)


P/s: Shuncha ma'lumotlardan keyin ham ishlata olmasangiz, bilmadim endi :/