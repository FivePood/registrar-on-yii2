<?php

namespace frontend\models;

use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\ErrorException;
use common\models\Domain;
use common\models\ApiComponent;
use yii\db\Exception;

class ApplicationFilingForm extends Model
{
    public $userName;
    public $inn;
    public $legal;
    public $type;
    public $toBirthString;
    public $toIssuedString;
    public $series;
    public $number;
    public $issuer;
    public $kpp;
    public $okpo;
    public $index;
    public $city;
    public $street;
    public $email1;
    public $email2;
    public $email3;
    public $phones;
    public $faxes;

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

    const TYPE_PASSPORT = 'passport';
    const TYPE_FOREIGN_PASS = 'foreignpass';
    const TYPE_SAILOR_PASS = 'sailorpass';
    const TYPE_IDENTIFICATION = 'id';
    const TYPE_MILITARY_CARD = 'militarycard';
    const TYPE_TEMPORARY_IDENTIFICATION = 'tempid';
    const TYPE_BIRTH_CERTIFICATE = 'birthcert';
    const TYPE_MILITARY_ID = 'militaryid';
    const TYPE_RESIDENT_VIEW = 'residencecert';
    const TYPE_ANOTHER = 'other';

    const TYPE_PASSPORT_LABEL = 'Паспорт';
    const TYPE_FOREIGN_PASS_LABEL = 'Паспорт иностранного гражданина';
    const TYPE_SAILOR_PASS_LABEL = 'Паспорт моряка';
    const TYPE_IDENTIFICATION_LABEL = 'Удостоверение личности';
    const TYPE_MILITARY_CARD_LABEL = 'Военный билет';
    const TYPE_TEMPORARY_IDENTIFICATION_LABEL = 'Временное удостоверение личности';
    const TYPE_BIRTH_CERTIFICATE_LABEL = 'Свидетельство о рождении';
    const TYPE_MILITARY_ID_LABEL = 'Удостоверение личности военнослужащего';
    const TYPE_RESIDENT_VIEW_LABEL = 'Вид на жительство';
    const TYPE_ANOTHER_LABEL = 'Другой';

    public function rules(): array
    {
        return [
            [['legal', 'userName', 'index', 'city', 'street', 'domainName', 'email1', 'phones'], 'required'],
            [['userName', 'legal',
                'type', 'series', 'number', 'issuer',
                'city', 'street',
                'phones', 'faxes',
                'domainName', 'vendorId', 'authCode'], 'string', 'max' => 255],
            [['email1', 'email2', 'email3'], 'email'],
            [['toBirthString', 'toIssuedString'], 'safe'],
            [['period', 'inn'], 'integer'],
            [['index'], 'string', 'min' => 6],
            [['kpp'], 'string', 'min' => 9],
            [['okpo'], 'string', 'min' => 8],
            ['noCheck', 'boolean'],
            [
                ['type', 'series', 'number', 'issuer', 'toBirthString', 'toIssuedString'], 'required',
                'when' => function () {
                    return ($this->legal == self::LEGAL_PERSON || $this->legal == self::LEGAL_PROPRIETOR);
                },
                'whenClient' => 'function(attribute,value){
                    return ($("#legal").val()=="person" || $("#legal").val()=="proprietor");
                }',
            ],
            [
                ['inn', 'kpp', 'okpo'], 'required',
                'when' => function () {
                    return ($this->legal == self::LEGAL_ORG);
                },
                'whenClient' => 'function(attribute,value){
                    return ($("#legal").val()=="org");
                }',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'legal' => 'Юридический статус',
            'userName' => 'ФИО или название организации, как это указано в идентифицирующих документах',
            'birthday' => 'Дата рождения',
            'type' => 'Документ',
            'series' => 'Серия',
            'number' => 'Номер',
            'issuer' => 'Кем выдан',
            'email1' => 'Список адресов E-mail',
            'email2' => 'Список адресов E-mail',
            'email3' => 'Список адресов E-mail',
            'phones' => 'Список номеров телефонов',
            'faxes' => 'Список номеров факсов',
            'index' => 'Почтовый индекс',
            'city' => 'Название населенного пункта',
            'street' => 'Адрес',
            'inn' => 'ИНН',
            'kpp' => 'КПП',
            'okpo' => 'ОКПО',
            'domainName' => 'Имя домена',
            'vendorId' => 'Идентификатор поставщика',
            'period' => 'Период регистрации домена (дней)',
            'authCode' => 'Код авторизации регистрации домена',
            'noCheck' => 'Режим без использования whois',
        ];
    }

    /**
     * @return string[]
     */
    public static function legalLabels(): array
    {
        return [
            self::LEGAL_PERSON => self::LEGAL_PERSON_LABEL,
            self::LEGAL_ORG => self::LEGAL_ORG_LABEL,
            self::LEGAL_PROPRIETOR => self::LEGAL_PROPRIETOR_LABEL
        ];
    }

    /**
     * @return string[]
     */
    public static function typeLabels(): array
    {
        return [
            self::TYPE_PASSPORT => self::TYPE_PASSPORT_LABEL,
            self::TYPE_FOREIGN_PASS => self::TYPE_FOREIGN_PASS_LABEL,
            self::TYPE_SAILOR_PASS => self::TYPE_SAILOR_PASS_LABEL,
            self::TYPE_IDENTIFICATION => self::TYPE_IDENTIFICATION_LABEL,
            self::TYPE_MILITARY_CARD => self::TYPE_MILITARY_CARD_LABEL,
            self::TYPE_TEMPORARY_IDENTIFICATION => self::TYPE_TEMPORARY_IDENTIFICATION_LABEL,
            self::TYPE_BIRTH_CERTIFICATE => self::TYPE_BIRTH_CERTIFICATE_LABEL,
            self::TYPE_MILITARY_ID => self::TYPE_MILITARY_ID_LABEL,
            self::TYPE_RESIDENT_VIEW => self::TYPE_RESIDENT_VIEW_LABEL,
            self::TYPE_ANOTHER => self::TYPE_ANOTHER_LABEL
        ];
    }

    /**
     * @return string
     * @throws ErrorException
     * @throws InvalidConfigException
     * @throws Exception|GuzzleException
     */
    public function registration(): string
    {
        $client = $this->sendClientRegistrationRequest();

        if (!empty($client['message'])) {
            throw new ErrorException($client['message']);
        }

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
     * @throws InvalidConfigException
     * @throws ErrorException|GuzzleException
     */
    protected function sendClientRegistrationRequest(): mixed
    {
        $emails = [];
        array_push($emails, $this->email1, $this->email2, $this->email3);
        $emails = array_diff($emails, array(''));
        $phones = explode(", ", $this->phones);
        if (!empty($phones[0]) && strlen($phones[0]) != 14) {
            $this->addError('', 'Не верно введено «Номер первого телефона».');
        }
        if (!empty($phones[1]) && strlen($phones[1]) != 14) {
            $this->addError('', 'Не верно введено «Номер второго телефона».');
        }
        if (!empty($phones[2]) && strlen($phones[2]) != 14) {
            $this->addError('', 'Не верно введено «Номер третьего телефона».');
        }

        $clientFields = [
            'jsonrpc' => '2.0',
            'id' => '',
            'method' => 'clientCreate',
            'params' => [
                'auth' => [
                    'login' => \Yii::$app->params['login'],
                    'password' => \Yii::$app->params['password'],
                ],
                'client' => [
                    'legal' => $this->legal,
                    'nameLocal' => $this->userName,
                    'addressLocal' => [
                        'index' => $this->index,
                        'country' => 'RU',
                        'city' => $this->city,
                        'street' => $this->street
                    ],
                    'emails' => $emails,
                    'phones' => $phones
                ],
            ],
        ];

        if (!empty($this->inn)) {
            $clientFields['params']['client']['inn'] = $this->inn;
        }

        if ($this->legal == self::LEGAL_PERSON || $this->legal == self::LEGAL_PROPRIETOR) {
            $birthday = !empty($this->toBirthString) ? \Yii::$app->formatter->asDate($this->toBirthString, 'yyyy-MM-dd') : null;
            $issued = !empty($this->toIssuedString) ? \Yii::$app->formatter->asDate($this->toIssuedString, 'yyyy-MM-dd') : null;

            preg_match("/^[0-9A-ZА-ЯЁ]{1,10}\z/ui", $this->series, $series);
            preg_match("/^\d{1,15}\z/", $this->number, $number);
            preg_match("/^(?!-)[A-ZА-яЁa-zё0-9-_'. ]{1,128}(?)/ui", $this->issuer, $issuer);

            if (empty($series)) {
                $this->addError('', 'Не верно введено «Серия».');
            }

            if (empty($number)) {
                $this->addError('', 'Не верно введено «Номер».');
            }

            if (empty($issuer)) {
                $this->addError('', 'Не верно введено «Кем выдан».');
            }

            $clientFields['params']['client']['birthday'] = $birthday;
            $clientFields['params']['client']['identity'] = [
                'type' => $this->type,
                'series' => $series[0],
                'number' => $number[0],
                'issuer' => $issuer[0],
                'issued' => $issued
            ];
        }

        if ($this->legal == self::LEGAL_ORG) {
            $clientFields['params']['client']['addressLegal'] = [
                'index' => $this->index,
                'country' => 'RU',
                'city' => $this->city,
                'street' => $this->street
            ];
            $clientFields['params']['client']['kpp'] = $this->kpp;
            $clientFields['params']['client']['okpo'] = $this->okpo;
        }

        if (!empty($this->faxes)) {
            $faxes = explode(", ", $this->faxes);
            if (!empty($faxes[0]) && strlen($faxes[0]) != 14) {
                $this->addError('', 'Не верно введено «Номер первого факса».');
            }
            if (!empty($faxes[1]) && strlen($faxes[1]) != 14) {
                $this->addError('', 'Не верно введено «Номер второго факса».');
            }
            if (!empty($faxes[2]) && strlen($faxes[2]) != 14) {
                $this->addError('', 'Не верно введено «Номер третьего факса».');
            }
            $clientFields['params']['client']['faxes'] = $faxes;
        }

        $errors = $this->getErrors();

        if (!empty($errors)) {
            throw new ErrorException(implode('<br>', $errors[""]));
        }

        return ApiComponent::request($clientFields);
    }

    /**
     * @param null $clientId
     * @return mixed|null
     * @throws ErrorException|GuzzleException
     */
    public function sendDomainRegistrationRequest($clientId = null): mixed
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
