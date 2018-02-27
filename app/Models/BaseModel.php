<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    static private $instance = null;

    static public function getModel() {
        if (is_null ( self::$instance ) || isset ( self::$instance )) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function bootIfNotBooted()
    {
        $this->_construct();
        return parent::bootIfNotBooted();
    }

    public function _construct()
    {
        return $this;
    }

}