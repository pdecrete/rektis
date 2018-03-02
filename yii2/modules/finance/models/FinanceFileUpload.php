<?php
namespace modules\finance\models;

use yii\base\Model;

class FinanceFileUpload extends Model
{
    public $file;
    
    public function rules()
    {
        return [[['file'], 'file']];
    }
}

