<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use common\models\Domain;
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
     * @throws ErrorException
     */
    public function sendRequest()
    {
        $requestFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'domainCreate',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],         //информация об авторизации
                'clientId' => \Yii::$app->params['clientId'],     //идентификатор клиента
//            'vendorId' => $this->vendorId,     //идентификатор поставщика
//            'period' => ['demo', 'demo'],       //период регистрации домена
//            'authCode' => $this->authCode,     //код авторизации регистрации домена
//            'noCheck' => $this->noCheck,      //режим без использования whois
                'domain' => [
                    'name' => $this->name,
                    'comment' => 'created via API'
                ],       //объект с информацией о домене
            ],
        ];

        Yii::debug($requestFields);

        $response = ApiComponent::request($requestFields);

        if (!empty($response['message'])) {
            throw new ErrorException($response['message']);
        }

        if (is_null($response)) {
            return false;
        }

        $domain = new Domain();
        $domain->name = $this->name;
        $domain->registeredId = $response['id'];
        $domain->handle = $response['handle'];
        $domain->comment = 'Регистрация домена';
        $domain->createdAt = time();
        $domain->updatedAt = time();
        $domain->save();

        return true;
    }
}
