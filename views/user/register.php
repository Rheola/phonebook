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


            <label for="inputEmail" class="sr-only">Email address</label>
            <input name="RegisterForm[email]" type="email" id="inputEmail"
                   class="form-control" placeholder="Email address" required autofocus value="<?= $form->email ?>">

            <label for="inputLogin" class="sr-only">Логин</label>
            <input name="RegisterForm[login]" type="text" id="inputLogin"
                   class="form-control" placeholder="Логин" required value="<?= $form->login ?>">

            <label for="inputPassword" class="sr-only">Password</label>
            <input name="RegisterForm[password]" type="password" id="inputPassword"
                   class="form-control" placeholder="Пароль" required value="<?= $form->password ?>">

            <label for="inputPassword" class="sr-only">Password </label>
            <input name="RegisterForm[passwordRepeat]" type="password" id="passwordRepeat"
                   class="form-control" placeholder="Повтор пароля" required value="<?= $form->passwordRepeat ?>">


            <button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</div>

