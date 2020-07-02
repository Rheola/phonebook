<?php


namespace core;


class Captcha
{

    public function getCaptchaCode()
    {

        return mt_rand(1000, 9999);
    }

    public function setSession($key, $value)
    {
        $_SESSION[(string)$key] = $value;
    }

    public function getSession($key)
    {
        $value = '';
        if (!empty($key) && !empty($_SESSION[(string)$key])) {
            $value = $_SESSION[(string)$key];
        }

        return $value;
    }

    public function createCaptchaImage($code)
    {
        $layer = imagecreatetruecolor(100, 40);
        $background = imagecolorallocate($layer, 204, 204, 204);

        imagefill($layer, 0, 0, $background);
        $text_color = imagecolorallocate($layer, 0, 0, 0);
        imagestring($layer, 5, 30, 10, $code, $text_color);

        return $layer;
    }

    public function renderCaptchaImage($imageData)
    {
        header('Content-type: image/jpeg');
        imagejpeg($imageData);
    }

    public function validateCaptcha($formData)
    {
        $isValid = false;
        $capchaSessionData = $this->getSession('captcha_code');

        if ($capchaSessionData == $formData) {
            $isValid = true;
        }

        return $isValid;
    }
}