<?php


namespace models;


use core\App;

class Phone extends Model

{

    public $id;
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $file;


    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'phone';
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = (array)$this;
        unset($array['user_id']);
        unset($array['isNewRecord']);
        foreach ($array as $key => $val) {
            if ($val === null) {
                $array[$key] = '';
            }
            if (is_array($val)) {
                unset($array[$key]);
            }
        }
        $array['phone'] = $this->formattedPhone();

        return $array;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        if ($this->file) {
            $this->deleteFile();
        }

        return parent::delete();
    }

    public function update()
    {
        parent::update();
    }

    /**
     *
     */
    public function deleteFile()
    {
        $filePath = $this->fullFilePath();
        if (is_file($filePath)) {
            unlink($filePath);
        }
        $minPath = $this->minFilePath();
        if (is_file($minPath)) {
            unlink($minPath);
        }
    }

    /**
     * @return string
     */
    public function fullFilePath()
    {
        return App::getInstance()->getRootPath() . 'upload/' . $this->file;
    }

    /**
     * @return string
     */
    public function minFilePath()
    {
        return App::getInstance()->getRootPath() . 'upload/min/' . $this->file;
    }

    /**
     * @param $width
     * @param $height
     */
    public function makeThumbnails($width, $height)
    {
        $imageDetails = getimagesize($this->fullFilePath());

        $originalWidth = $imageDetails[0];
        $originalHeight = $imageDetails[1];
        if ($originalWidth > $originalHeight) {
            $newWidth = $width;
            $newHeight = (int)($originalHeight * $newWidth / $originalWidth);
        } else {
            $newHeight = $width;
            $newWidth = (int)($originalWidth * $newHeight / $originalHeight);
        }
        $destX = (int)(($width - $newWidth) / 2);
        $destY = (int)(($height - $newHeight) / 2);
        if ($imageDetails[2] == IMAGETYPE_GIF) {
            $imgt = 'ImageGIF';
            $imgcreatefrom = 'ImageCreateFromGIF';
        }
        if ($imageDetails[2] == IMAGETYPE_JPEG) {
            $imgt = 'ImageJPEG';
            $imgcreatefrom = 'ImageCreateFromJPEG';
        }
        if ($imageDetails[2] == IMAGETYPE_PNG) {
            $imgt = 'ImagePNG';
            $imgcreatefrom = 'ImageCreateFromPNG';
        }
        if ($imgt) {
            $old_image = $imgcreatefrom($this->fullFilePath());
            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresized($new_image, $old_image, $destX, $destY, 0, 0, $newWidth, $newHeight, $originalWidth,
                $originalHeight);
            $imgt($new_image, $this->minFilePath());
        }
    }


    public function phoneToText()
    {
        $string = [];
        // словарь необходимых чисел
        $words = [
            1 => 'один',
            2 => 'два',
            3 => 'три',
            4 => 'четыре',
            5 => 'пять',
            6 => 'шесть',
            7 => 'семь',
            8 => 'восемь',
            9 => 'девять',
            10 => 'десять',
            11 => 'одиннадцать',
            12 => 'двенадцать',
            13 => 'тринадцать',
            14 => 'четырнадцать',
            15 => 'пятнадцать',
            16 => 'шестнадцать',
            17 => 'семнадцать',
            18 => 'восемнадцать',
            19 => 'девятнадцать',
            20 => 'двадцать',
            30 => 'тридцать',
            40 => 'сорок',
            50 => 'пятьдесят',
            60 => 'шестьдесят',
            70 => 'семьдесят',
            80 => 'восемьдесят',
            90 => 'девяносто',
            100 => 'сто',
            200 => 'двести',
            300 => 'триста',
            400 => 'четыреста',
            500 => 'пятьсот',
            600 => 'шестьсот',
            700 => 'семьсот',
            800 => 'восемьсот',
            900 => 'девятьсот',
        ];

        $level = [
            ['тысяча', 'тысячи', 'тысяч'],
            ['миллион', 'миллиона', 'миллионов'],
            ['миллиард', 'миллиарда', 'миллиардов'],
        ];

        $plur = [
            2, //тысяч
            0, //тысяча
            1,
            1,
            1,
            2, //тысяча
        ];
        $number = str_pad($this->phone, ceil(strlen($this->phone) / 3) * 3, 0, STR_PAD_LEFT);

        $parts = array_reverse(str_split($number, 3));


        foreach ($parts as $i => $part) {

            if ($part == 0) {
                continue;
            }

            $digits = [];

            if ($part > 99) {
                $digits[] = floor($part / 100) * 100;
            }

            if ($mod1 = $part % 100) {

                $mod2 = $part % 10;
                $flag = $i == 1 && $mod1 != 11 && $mod1 != 12 && $mod2 < 3 ? -1 : 1;
                if ($mod1 < 20 || !$mod2) {
                    $digits[] = $flag * $mod1;
                } else {
                    $digits[] = floor($mod1 / 10) * 10;
                    $digits[] = $flag * $mod2;
                }
            }

             $last = abs(end($digits));

             foreach ($digits as $j => $digit) {
                $digits[$j] = $words[$digit];
            }

             $plurValue = min($last % 10, 5);

            if (($last %= 100) >= 5 && $last < 20) {
                // 5 - 20 тысяч
                $razrIdx = 2;
            } else {
                $razrIdx = $plur[$plurValue];
            }
            if (isset($level[$i - 1])) {
                $digits[] = $level[$i - 1][$razrIdx];

            }

            $tail = implode(' ', $digits);

            array_unshift($string, $tail);
        }

         return implode(' ', $string);
    }

    public function formattedPhone()
    {
        $mask = '#';
        $formats = [
            '4' => '##-##',
            '5' => '###-##',
            '6' => '##-##-##',
            '7' => '###-##-##',
            '8' => '###-###-##',
            '9' => '## ###-##-##',
            '10' => '### ###-##-##',
            '11' => '# ### ###-##-##',
        ];

        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        if (array_key_exists(strlen($phone), $formats)) {
            $format = $formats[strlen($phone)];
        } else {
            return $this->phone;
        }


        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter)
            {
                return '${' . (++$counter) . '}';
            },
            $format
        );

        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }

}