<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">projectUA Demo</h1>
    <br>
</p>

TASKS
------------
1. Розгорнути додаток на фреймворку Yii2 (basic/advanced). У додатку повинна бути стандартна авторизація користувача. Після авторизації потрапляємо на сторінку виводу віджету GridView з посторінковою навігацією (До авторизації, бачимо сторінку для логіну користувача) - **DONE**
2. необхідно створити консольний скрипт Yii2, що буде забирати останні 10 сторінок з API (див. поле next_page, можна з використанням offset, limit), обробляти (в тому числі помилки), та записувати в БД (mysql) дані по тендерах - **DONE**
3. Ці дані використовуємо у виводі GridView - **DONE**
4. Код покрити тестами. Процес отримання даних і обробку помилок - логувати - **DONE**

NOTES
------------
1. Custom code is built targeting php 7.2
2. Only unit tests were created
3. Live version is at https://zaphod.napc.com/projectUA/
4. All debug and error logging for console script **./yii tender** goes to runtime/tenders.log
5. **PasswordHelper.php** is in the root folder