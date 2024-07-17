<?php

namespace common\models;

use GuzzleHttp;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

class ApiComponent extends Component
{
    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public static function request($requestFields)
    {
        $client = new GuzzleHttp\Client();
        $response = $client->post(\Yii::$app->params['uri'], [
            'json' => $requestFields
        ]);

        if ($response->getStatusCode() != 200) {
            return null;
        }

        $response = $response->getBody()->getContents();

        try {
            $array = Json::decode($response);
        } catch (\Exception $e) {
            Yii::error('Json decode error');
            Yii::error($e);
            return null;
        }
        \Yii::debug($array);
        if (!empty($array['error'])) {
            Yii::error('Error');
            return $array['error'];
        }

        if (empty($array['result']['domain']) && empty($array['result']['handle'])) {
            Yii::error('Empty handle');
            return null;
        }

        if (empty($array['result']['domain']) && empty($array['result']['id'])) {
            Yii::error('Empty id');
            return null;
        }

        return $array['result'];
    }
}
