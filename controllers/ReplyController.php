<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\forms\ResponseForm;
use app\services\ReplyService;
use app\services\TaskService;
use anatolev\service\ActRespond;
use anatolev\service\Task;

class ReplyController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['accept'],
                        'roles' => ['changeOwnReplyStatus'],
                        'roleParams' => ['replyId' => Yii::$app->request->get('replyId', 0)],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['refuse'],
                        'roles' => ['changeOwnReplyStatus'],
                        'roleParams' => ['replyId' => Yii::$app->request->get('replyId', 0)],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createOwnReply'],
                        'roleParams' => [
                            'taskId' => Yii::$app->request->post('ResponseForm')['task_id'] ?? 0
                        ],
                    ]
                ]
            ]
        ];
    }

    public function actionCreate()
    {
        $responseForm = new ResponseForm();

        if (Yii::$app->request->isPost) {
            $responseForm->load(Yii::$app->request->post());

            if ($responseForm->validate()) {
                $taskId = (new ReplyService())->create($responseForm);

                return $this->redirect(['tasks/view', 'taskId' => $taskId]);
            }
        }
    }

    public function actionAccept(int $replyId)
    {
        $taskId = (new ReplyService())->accept($replyId);

        return $this->redirect(['tasks/view', 'taskId' => $taskId]);
    }

    public function actionRefuse(int $replyId)
    {
        $taskId = (new ReplyService())->refuse($replyId);

        return $this->redirect(['tasks/view', 'taskId' => $taskId]);
    }
}
