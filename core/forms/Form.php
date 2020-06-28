<?php


namespace core\forms;


trait Form
{
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

        return $this->errors[0];
    }

    /**
     *
     */
    public function printErrors()
    {
        if (!$this->hasErrors()) {
            return;
        }
        $error = $this->firstError();
        echo "<p><b style='color: red'>$error</b></p>";
    }

    public function load()
    {
        // todo
    }
}