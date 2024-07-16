<?php

namespace frontend\controllers;

use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\UpdateDnsForm;
use frontend\models\ApplicationFilingForm;
use yii\web\Response;

class SiteController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws Exception|GuzzleException
     */
    public function actionApplicationFiling(): string
    {
        $model = new ApplicationFilingForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            try {
                $name = $model->registration();
                if ($name) {
                    Yii::$app->session->setFlash('success', 'Запрос на регистрацию домена успешно отправлен.');
                    Yii::$app->session->setFlash('info', "Имя зарегистрированного домена: $name");
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось отправить запрос на регистрацию домена.');
                }
            } catch (ErrorException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('application-filing', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function actionUpdateDns(): Response|string
    {
        $model = new UpdateDnsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            try {
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', 'DNS успешно обновлен.');
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось отправить запрос на обновление DNS.');
                }
            } catch (ErrorException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update-dns', [
            'model' => $model,
        ]);
    }
}
