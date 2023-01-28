<?php

namespace app\controllers;

use app\models\TenderSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {

        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'tenders'],
                'rules' => [
                    [
                        'actions' => ['logout', 'tenders'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];

    }

    /**
     * {@inheritdoc}
     */
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
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);

    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {

        Yii::$app->user->logout();

        return $this->goHome();

    }

    /**
     * @return string
     */
    public function actionTenders(): string
    {

        $searchModel = new TenderSearch();

        return $this->render('tenders', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams)
        ]);

    }

}
