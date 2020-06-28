<?php

?>

<p>&nbsp;</p>

<p>
    <strong>Тестовое
        задание </strong><strong>PHP</strong><strong>+</strong><strong>MySQL</strong><strong>+</strong><strong>Ajax</strong>
</p>

<p>Программа&nbsp;&mdash; &laquo;Телефонная книга&raquo;.</p>

<p>Задача:</p>

<p>Организовать телефонную книгу для пользователей. Любой желающий может зарегистрироваться и&nbsp;создать себе
    телефонную книгу.</p>

<p>Организовать авторизацию; загрузку файлов jpg, png; редактирование и&nbsp;отображение информации.</p>

<h3>Страницы:</h3>

<ol>
    <li> Страница авторизации</li>

    <li> Страница регистрации (Требования к&nbsp;логину: только латинские буквы и&nbsp;цифры. Проверка почты на&nbsp;правильность.
        Требование к&nbsp;паролю: должен содержать и&nbsp;цифры, и&nbsp;буквы.)
    </li>

    <li> Страница работы с&nbsp;книгой (все операции без перезагрузки страницы, с&nbsp;помощью ajax)</li>
</ol>


<h3>Таблицы:</h3>
<ol>
    <li> Таблица пользователей, поля: логин, пароль и&nbsp;т.д.</li>

    <li> Таблица с&nbsp;записями книги: данные записей (Имя, Фамилия, телефон, email, фото-записи и&nbsp;т.д....)</li>

</ol>

<h3>Функции:</h3>

<ol>
    <li> Авторизация</li>

    <li> Добавление новой записи и&nbsp;загрузка к&nbsp;ней картинки</li>

    <li> Редактирование существующих записей</li>

    <li> Отображение, как общего списка, так и&nbsp;отдельных записей, сортировка списка
        <ul>
            <li>создать функцию, которая переводила&nbsp;бы цифровое обозначение цифр в&nbsp;буквенное до&nbsp;числа 999
                999999999, например, 21125 =&gt; &rsquo;двадцать одна тысяча сто двадцать пять&rsquo;. Применить ее&nbsp;к&nbsp;отображению
                телефонного номера в&nbsp;отдельных записях
            </li>
        </ul>
    </li>
    <li> Выход</li>
</ol>


<p>Условия:</p>

<p>1. Версия PHP 5.5.38</p>

<p>2. Не&nbsp;использовать фреймворки и&nbsp;библиотеки PHP</p>

<p>3. Использовать</p>

<p>a. JQuery</p>

<p>b. Создать простой класс&nbsp;Db (singleton) с&nbsp;использованием PDO для обращений к&nbsp;базе MySQL</p>

<p>c. MVC-подход (разделение как минимум на&nbsp;контроллер и&nbsp;представление)</p>

<p>d. Для форм авторизации и&nbsp;регистрации проверка Captcha</p>

<p>e. В&nbsp;качестве основы для оформления использовать Bootstrap http://getbootstrap.com/</p>

<p>4. обязательная проверка полей со&nbsp;стороны клиента и&nbsp;сервера</p>

<p>5. Файл картинки не&nbsp;более 2Mb, только jpg, png</p>

<p>Результат задания:</p>

<p>1. Файл db-structure.sql</p>

<p>2. PHP файлы</p>

<p>3. Сколько времени было потрачено на&nbsp;выполнение задания?</p>

<p>&nbsp;</p>
