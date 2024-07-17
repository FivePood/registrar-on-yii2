<?php

namespace frontend\models;

use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use common\models\Domain;
use common\models\ApiComponent;
use yii\db\Exception;

class UpdateDnsForm extends Model
{
    public $domainId;
    public $dns;

    public function rules(): array
    {
        return [
            [['domainId', 'dns'], 'required'],
            [['domainId'], 'integer'],
            ['dns', 'string'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'domainId' => 'Идентификатор домена',
            'dns' => 'DNS',
        ];
    }


    /**
     * @return bool
     * @throws ErrorException
     * @throws Exception|GuzzleException
     */
    public function update(): bool
    {
        $domainInfo = $this->getDomainInfo();

        if (!empty($domainInfo['message'])) {
            throw new ErrorException($domainInfo['message']);
        }

        if (empty($domainInfo['domain']['name'])) {
            throw new ErrorException('Ошибка при получении имени домена.');
        }

        $response = $this->sendDomainDNS($domainInfo['domain']['name'], $domainInfo['domain']['clientId']);

        if (!empty($response['message'])) {
            throw new ErrorException($response['message']);
        }

        if (is_null($response)) {
            return false;
        }

        $domain = new Domain();
        $domain->name = $domainInfo['domain']['name'];
        $domain->registeredId = $response['id'];
        $domain->handle = $response['handle'];
        $domain->comment = 'Обновление DNS';
        $domain->createdAt = time();
        $domain->updatedAt = time();
        $domain->save();

        return true;
    }

    /**
     * @return mixed|null
     * @throws GuzzleException
     */
    public function getDomainInfo(): mixed
    {
        $requestFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'domainInfo',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                'id' => (int)$this->domainId
            ],
        ];

        Yii::debug($requestFields);

        return ApiComponent::request($requestFields);
    }

    /**
     * @param $domainName
     * @param $clientId
     * @return mixed|null
     * @throws GuzzleException
     */
    public function sendDomainDNS($domainName, $clientId): mixed
    {
        $newDns[] = $domainName . ' ' . $this->dns;

        $requestFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'domainUpdate',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                'id' => (int)$this->domainId,
                'clientId' => (int)$clientId,
                'domain' => [
                    'nservers' => $newDns,
                    'delegated' => 0
                ],
            ],
        ];

        Yii::debug($requestFields);

        return ApiComponent::request($requestFields);
    }
}
