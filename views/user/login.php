<?php
/**
 * @var $this  \controllers\UserController
 * @var $loginForm \models\forms\LoginForm
 */

?>

<div class="row justify-content-md-center">
    <div class="col col-lg-6">
        <form class="reg-form" method="post">

            <h1 class="h3 mb-3 font-weight-normal">Вход</h1>

            <?php
            if ($loginForm->hasErrors()) {
                $loginForm->printErrors();
            }
            ?>

            <label for="inputEmail">Email</label>
            <input name="LoginForm[email]" type="email" id="inputEmail" class="form-control"
                   placeholder="Email address" required autofocus value="<?= $loginForm->email ?>">


            <label for="inputPassword">Пароль</label>
            <input name="LoginForm[password]" type="password" id="inputPassword" class="form-control"
                   placeholder="Пароль" required value="">

            <label for="inputCaptcha">Капча </label>
            <input name="LoginForm[captcha]" type="text" id="inputCaptcha"
                   class="form-control captcha-input" placeholder="Код" required value="<?= $loginForm->captcha ?>">

            <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
        </form>

        <a href="/user/register">Зарегистрироваться</a>

    </div>
</div>

