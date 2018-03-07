<?php

namespace App\Extensions;

use Encore\Admin\Form;

class CustomForm extends Form {



    public function validator($data){
        if ($validationMessages = $this->validationMessages($data)) {
            return $validationMessages;
            //return back()->withInput()->withErrors($validationMessages);
        }
        return true;
    }

}