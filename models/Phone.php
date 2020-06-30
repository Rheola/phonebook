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
    public function tableName()
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
}