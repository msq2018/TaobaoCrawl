<?php
/**
 * Created by PhpStorm.
 * User: msq
 * Date: 2018/3/6
 * Time: 14:12
 */

namespace App\Extensions\Form;

use Encore\Admin\Form\Field;

class Options extends Field
{

    protected $view = "admin.form.options";

    protected static $js = [
      "/vendor/template.js-0.7.1/template.js"
    ];

    public function __construct($column, array $arguments)
    {
        parent::__construct($column, $arguments);
    }

    public function render()
    {
        return parent::render(); // TODO: Change the autogenerated stub
    }
}