<?php

namespace common\models;

use yii\base\Component;
use yii\helpers\Json;
use GuzzleHttp;
use Yii;

class ApiComponent extends Component
{
    /**
     * Отправка запроса на регистрацию домена
     * @param $domain
     */
    public static function requestRegister($domain)
    {
        $authLogin = [
            'login' => \Yii::$app->params['login'],
            'password' => \Yii::$app->params['password'],
        ];

        $method = 'domainCreate';

        $requestFields = [
            'auth' => $authLogin,         //информация об авторизации
            'clientId' => \Yii::$app->params['clientId'],     //идентификатор клиента
//            'vendorId' => ['demo', 'demo'],     //идентификатор поставщика
//            'period' => ['demo', 'demo'],       //период регистрации домена
//            'authCode' => ['demo', 'demo'],     //код авторизации регистрации домена
//            'noCheck' => ['demo', 'demo'],      //режим без использования whois
            'domain' => $domain,       //объект с информацией о домене
        ];

        Yii::debug($requestFields);

        return self::request($method, $requestFields);
    }

    public static function request($method, $requestFields)
    {
        $client = new GuzzleHttp\Client();

        $res = $client->request('GET', "https://vrdemo.virtreg.ru/vr-api?method=$method&params=$requestFields");

// Получаем "200", это 200 OK
        echo $res->getStatusCode();

// Заголовок 'application/json; charset=utf8'
        echo $res->getHeader('content-type')[0];

// {"type":"User"...'
        echo $res->getBody();

// Оформляем асинхронный запрос
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');

// Инициализируем цепочку Promise
        $promise = $client->sendAsync($request)->then(function ($response) {
            echo 'I completed! ' . $response->getBody();
        });

// Ожидаем ответ Promise
        $promise->wait();

        try {
            $array = Json::decode($promise);
        } catch (\Exception $e) {
            Yii::error('Json decode error');
            Yii::error($e);
            return null;
        }
        \Yii::debug($array);

        if (empty($array['handle'])) {
            Yii::error('Empty handle');
            return null;
        }
        if (empty($array['id'])) {
            Yii::error('Empty id');
            return null;
        }

        return $array;
    }
}
