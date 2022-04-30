<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use app\services\TaskService;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    $this->redirect(['tasks/index']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $tasks = (new TaskService())->getNewTasks(4);

        return $this->render('index', [
            'tasks' => $tasks
        ]);
    }
}
