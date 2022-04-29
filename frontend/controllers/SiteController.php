<?php

namespace frontend\controllers;

use Yii;
use yii\base\ErrorException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\UpdateDnsForm;
use frontend\models\ApplicationFilingForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
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

    /**
     * {@inheritdoc}
     */
    public function actions()
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
     */
    public function actionApplicationFiling()
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
     * @return string|\yii\web\Response
     */
    public function actionUpdateDns()
    {
        $model = new UpdateDnsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            try {
                if ($model->sendRequest()) {
                    Yii::$app->session->setFlash('success', 'Запрос на обновления DNS для домена успешно отправлен.');
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось отправить запрос на обновления DNS для домена.');
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
