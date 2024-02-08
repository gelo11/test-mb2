<?php

namespace app\models;

use yii\base\Model;

class AppleForm extends Model
{
    public $qty;

    public function rules()
    {
        return [
            [['qty'], 'required'],
            [['qty'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'qty' => 'Количество яблок'
        ];
    }
}