<?php

namespace app\controllers;

use Yii;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\services\CityService;
use app\services\UserService;

class UserController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client)
    {
        (new UserService())->authHandler($client);

        return $this->goHome();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['auth', 'login', 'signup'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $signupForm = new SignupForm();

        if (Yii::$app->request->isPost) {
            $signupForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($signupForm);
            }

            if ($signupForm->validate()) {
                (new UserService())->create($signupForm);

                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $signupForm,
        ]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->isPost) {
            $loginForm->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                (new UserService())->login($loginForm->email);

                return $this->redirect(['tasks/index']);
            }
        }

        return $this->goHome();
    }
}
