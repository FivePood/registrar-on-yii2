<?php

namespace frontend\models;

use common\models\Domain;
use yii\base\Model;
use common\models\ApiComponent;

/**
 * ApplicationFilingForm is the model behind the Application Filing form.
 */
class ApplicationFilingForm extends Model
{
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя домена',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest()
    {
        $domainName = [
            'name' => $this->name,
            'comment' => 'created via API'
        ];

        $response = ApiComponent::requestRegister($domainName);

        $domain = new Domain();
        $domain->name = $this->name;
        $domain->registeredId = $response['id'];
        $domain->handle = $response['handle'];
        $domain->createdAt = time();
        $domain->updatedAt = time();
        $domain->save();

        return true;
    }
}
