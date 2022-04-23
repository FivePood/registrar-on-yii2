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
    public $clientId;
    public $name;
    public $vendorId;
    public $period;
    public $authCode;
    public $noCheck;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'clientId'], 'required'],
            [['clientId', 'period'], 'integer'],
            [['name', 'vendorId', 'authCode'], 'string', 'max' => 255],
            ['noCheck', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'clientId' => 'Идентификатор клиента',
            'name' => 'Имя домена',
            'vendorId' => 'Идентификатор поставщика',
            'period' => 'Период регистрации домена (дней)',
            'authCode' => 'Код авторизации регистрации домена',
            'noCheck' => 'Режим без использования whois',
        ];
    }

    /**
     * @throws ErrorException
     */
    public function sendRequest()
    {
        $matches = parse_url($this->name);
        $domainName = !empty($matches['host']) ? $matches['host'] : $matches['path'];
        preg_match("/^((?!-)[A-Za-z0-9-.]{1,63}(?))/", $domainName, $name);

        if (empty($name)) {
            throw new ErrorException('Неверное имя домена');
        }
        $name = $name[0];

        $requestFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'domainCreate',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                'clientId' => (int)$this->clientId,
                'domain' => [
                    'name' => $name,
                    'comment' => 'created via API'
                ],
            ],
        ];

        if (!empty($this->vendorId)) {
            $requestFields['params']['vendorId'] = $this->vendorId;
        }

        if (!empty($this->period)) {
            $period = (int)$this->period * 24 * 60 * 60;
            $requestFields['params']['period'] = $period;
        }

        if (!empty($this->authCode)) {
            $requestFields['params']['authCode'] = $this->authCode;
        }

        if (!empty($this->noCheck)) {
            $requestFields['params']['noCheck'] = (int)$this->noCheck;
        }

        Yii::debug($requestFields);

        $response = ApiComponent::request($requestFields);

        if (!empty($response['message'])) {
            throw new ErrorException($response['message']);
        }

        if (is_null($response)) {
            return false;
        }

        $domain = new Domain();
        $domain->name = $name;
        $domain->registeredId = $response['id'];
        $domain->handle = $response['handle'];
        $domain->comment = 'Регистрация домена';
        $domain->createdAt = time();
        $domain->updatedAt = time();
        $domain->save();

        return $name;
    }
}
