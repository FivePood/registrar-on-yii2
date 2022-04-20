<?php

namespace common\models;

use yii\base\Component;
use yii\helpers\Json;
use GuzzleHttp;
use Yii;

class ApiComponent extends Component
{
    /**
     * Отправка запроса
     * @return mixed|null
     */
    public function requestRegister()
    {
        $postfields = [
        ];

        Yii::debug($postfields);

// Инициализируем клиент Guzzle
        $client = new GuzzleHttp\Client();

// Делаем GET запрос к https://api.github.com/user (попутно авторизовываясь в GitHub)
        $res = $client->request('GET', 'https://api.github.com/user', [
            'auth' => ['user', 'pass']
        ]);

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

        if (empty($array['orderId'])) {
            Yii::error('Empty orderId');
            return null;
        }
        if (empty($array['formUrl'])) {
            Yii::error('Empty formUrl');
            return null;
        }

        return $array;
    }
}
