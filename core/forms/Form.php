<?php


namespace core\forms;


class Form
{

    public function load($postData)
    {
        foreach ($this as $field => $val) {
            if (isset($postData[$field])) {
                $this->$field = trim($postData[$field]);
            }
        }
    }
}