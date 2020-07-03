<?php


namespace controllers;


use core\Captcha;

class CaptchaController
{

    public function actionIndex()
    {

        $captcha = new Captcha();

        $code = $captcha->getCaptchaCode();

        $captcha->setSession('captcha_code', $code);

        $imageData = $captcha->createCaptchaImage($code);

        $captcha->renderCaptchaImage($imageData);
    }
}