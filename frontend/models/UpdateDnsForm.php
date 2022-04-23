<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use common\models\Domain;
use common\models\ApiComponent;

/**
 * UpdateDnsForm is the model behind the Update Dns form.
 */
class UpdateDnsForm extends Model
{
    public $clientId;
    public $domainId;
    public $dnskey;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domainId', 'dnskey', 'clientId'], 'required'],
            [['domainId', 'clientId'], 'integer'],
            ['dnskey', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clientId' => 'Идентификатор клиента',
            'domainId' => 'Идентификатор домена',
            'dnskey' => 'Запись DNSKEY',
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
            'method' => 'domainDnssec',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                'id' => (int)$this->domainId,
                'clientId' => (int)$this->clientId,
                'dnssec' => ['dnssec' => $this->dnskey],
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
        $domain->name = null;
        $domain->registeredId = $response['id'];
        $domain->handle = $response['handle'];
        $domain->comment = 'Обновление DNS';
        $domain->createdAt = time();
        $domain->updatedAt = time();
        $domain->save();

        return true;
    }
}
