<?php
/***
 * @var $this  \controllers\UserController
 * @var $form \models\forms\RegisterForm
 */

?>

<div class="row justify-content-md-center">
    <div class="col col-lg-6">
        <form class="reg-form" method="post">
            <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>

            <?php
            if ($form->hasErrors()) {
                $form->printErrors();
            }
            ?>
            <div class="form-group">
                <label for="inputEmail">Email </label>
                <input name="RegisterForm[email]" type="email" id="inputEmail"
                       class="form-control" placeholder="Email" required autofocus value="<?= $form->email ?>">

            </div>


            <div class="form-group">
                <label for="inputLogin">Логин</label>
                <input name="RegisterForm[login]" type="text" id="inputLogin"
                       class="form-control" placeholder="Логин" required value="<?= $form->login ?>">

            </div>

            <div class="form-group">
                <label for="inputPassword">Пароль</label>
                <input name="RegisterForm[password]" type="password" id="inputPassword"
                       class="form-control" placeholder="Пароль" required value="<?= $form->password ?>">

            </div>


            <div class="form-group">
                <label for="inputPassword">Повтор пароля </label>
                <input name="RegisterForm[passwordRepeat]" type="password" id="passwordRepeat"
                       class="form-control" placeholder="Повтор пароля" required value="<?= $form->passwordRepeat ?>">

            </div>


            <div class="form-group">
                <label for="inputCaptcha">Капча </label>
                <input name="RegisterForm[captcha]" type="text" id="inputCaptcha"
                       class="form-control captcha-input" placeholder="Код" required value="<?= $form->captcha ?>">

            </div>


            <button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>

            <a href="/user/login">Войти</a>

        </form>
    </div>
</div>

