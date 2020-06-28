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

    public $errors = [];


    /**
     * @return bool
     */
    public function hasErrors()
    {
        return \count($this->errors) > 0;
    }

    /**
     * @return string|null
     */
    public function firstError()
    {
        if (!$this->hasErrors()) {
            return null;
        }

        return array_shift($this->errors);
    }

    /**
     *
     */
    public function printErrors()
    {
        if (!$this->hasErrors()) {
            return;
        }
        $error = $this->firstError()[0];
        echo "<p><b style='color: red'>$error</b></p>";
    }

}