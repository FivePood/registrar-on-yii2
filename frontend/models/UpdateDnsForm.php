<?php

namespace frontend\models;

use yii\base\Model;

/**
 * UpdateDnsForm is the model behind the Update Dns form.
 */
class UpdateDnsForm extends Model
{
    public $id;
    public $dnskey;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'dnskey'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор домена',
            'dnskey' => 'Запись DNSSEC',
        ];
    }
}
