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
    public $legal;
    public $userName;
    public $toBirthString;
    public $type;
    public $series;
    public $number;
    public $issuer;
    public $issued;
    public $email;
    public $phone;
    public $index;
    public $city;
    public $street;


    public $domainName;
    public $formattedDomainName;
    public $vendorId;
    public $period;
    public $authCode;
    public $noCheck;

    const LEGAL_ORG = 'org';
    const LEGAL_PERSON = 'person';
    const LEGAL_PROPRIETOR = 'proprietor';

    const LEGAL_ORG_LABEL = 'Юридическое лицо';
    const LEGAL_PERSON_LABEL = 'Физическое лицо';
    const LEGAL_PROPRIETOR_LABEL = 'Индивидуальный предприниматель';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domainName'], 'required'],
            [['period', 'issued'], 'integer'],
            [['legal', 'userName', 'type', 'series', 'number', 'issuer', 'index', 'city', 'street', 'domainName', 'email', 'phone',
                'vendorId', 'authCode'], 'string', 'max' => 255],
            ['noCheck', 'boolean'],
            ['toBirthString', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'legal' => 'Юридический статус',
            'userName' => 'ФИО или название организации, как это указано в идентифицирующих документах',
            'birthday' => 'Дата рождения',
            'type' => 'Идентификатор типа документа',
            'series' => 'Серия',
            'number' => 'Номер',
            'issuer' => 'Кем выдан',
            'issued' => 'Дата выдачи',
            'email' => 'Список адресов email',
            'phone' => 'Список номеров телефонов',
            'index' => 'Почтовый индекс или код',
            'city' => 'Название населенного пункта',
            'street' => 'Информация о местоположении в населенном пункте',

            'domainName' => 'Имя домена',
            'vendorId' => 'Идентификатор поставщика',
            'period' => 'Период регистрации домена (дней)',
            'authCode' => 'Код авторизации регистрации домена',
            'noCheck' => 'Режим без использования whois',
        ];
    }

    public static function legalLabels()
    {
        return [
            self::LEGAL_ORG => self::LEGAL_ORG_LABEL,
            self::LEGAL_PERSON => self::LEGAL_PERSON_LABEL,
            self::LEGAL_PROPRIETOR => self::LEGAL_PROPRIETOR_LABEL
        ];
    }

    public static function typeLabels()
    {
        return [
            self::LEGAL_ORG_LABEL,
            self::LEGAL_PERSON_LABEL,
            self::LEGAL_PROPRIETOR_LABEL
        ];
    }

    /**
     * @return false
     * @throws ErrorException
     */
    public function registration()
    {
        $client = $this->sendClientRegistrationRequest();

        $response = $this->sendDomainRegistrationRequest($client['id']);

        if (!empty($response['message'])) {
            throw new ErrorException($response['message']);
        }

        if (is_null($response)) {
            return false;
        }

        $domain = new Domain();
        $domain->name = $this->formattedDomainName;
        $domain->registeredId = $response['id'];
        $domain->handle = $response['handle'];
        $domain->comment = 'Регистрация домена';
        $domain->createdAt = time();
        $domain->updatedAt = time();
        $domain->save();

        return $this->formattedDomainName;
    }

    /**
     * @return mixed|null
     */
    protected function sendClientRegistrationRequest()
    {
        $birthday =!empty($this->toBirthString) ? (int)\Yii::$app->formatter->asTimestamp($this->toBirthString) : null;

        $clientFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'clientCreate',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                "client" => [
                    'legal' => $this->legal,
                    'nameLocal' => $this->userName,
                    'birthday' => $birthday,
                    'identity' => [
                        'type' => $this->type,
                        'series' => $this->series,
                        'number' => $this->number,
                        'issuer' => $this->issuer,
                        'issued' => $this->issued
                    ],
                    'emails' => [
                        $this->email
                    ],
                    'phones' => [
                        $this->phone
                    ],
                    'addressLocal' => [
                        'index' => $this->index,
                        'country' => 'RU',
                        'city' => $this->city,
                        'street' => $this->street
                    ]
                ],
            ],
        ];

        return ApiComponent::request($clientFields);
    }

    /**
     * @param null $clientId
     * @return mixed|null
     * @throws ErrorException
     */
    public function sendDomainRegistrationRequest($clientId = null)
    {
        if (is_null($clientId)) {
            throw new ErrorException('Не удалось отправить запрос на регистрацию домена.');
        }

        $matches = parse_url($this->domainName);
        $url = !empty($matches['host']) ? $matches['host'] : $matches['path'];
        preg_match("/^((?!-)[A-Za-z0-9-.]{1,63}(?))/", $url, $domainName);

        if (empty($domainName)) {
            throw new ErrorException('Неверное имя домена');
        }
        $this->formattedDomainName = $domainName[0];

        $requestFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'domainCreate',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                'clientId' => (int)$clientId,
                'domain' => [
                    'name' => $this->formattedDomainName,
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

        return ApiComponent::request($requestFields);
    }
}
