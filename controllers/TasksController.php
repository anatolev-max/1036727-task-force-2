<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use app\models\forms\AddTaskForm;
use app\models\forms\SearchForm;
use app\models\forms\CompleteForm;
use app\models\forms\ResponseForm;
use app\services\CategoryService;
use app\services\TaskService;
use anatolev\service\ActCancel;
use anatolev\service\ActDone;
use anatolev\service\ActRefuse;
use anatolev\service\Task;

class TasksController extends SecuredController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['createTask'],
                        'actions' => ['create']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['index', 'user-tasks', 'view']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['cancel'],
                        'roles' => ['cancelOwnTask'],
                        'roleParams' => ['taskId' => Yii::$app->request->get('taskId', 0)]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['complete'],
                        'roles' => ['completeOwnTask'],
                        'roleParams' => [
                            'taskId' => Yii::$app->request->post('CompleteForm')['task_id'] ?? 0
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['refuse'],
                        'roles' => ['refuseOwnTask'],
                        'roleParams' => ['taskId' => Yii::$app->request->get('taskId', 0)]
                    ],
                ]
            ]
        ];
    }

    public function actionCreate()
    {
        $addTaskForm = new AddTaskForm();

        if (Yii::$app->request->isPost) {
            $addTaskForm->load(Yii::$app->request->post());
            $addTaskForm->files = UploadedFile::getInstances($addTaskForm, 'files');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($addTaskForm);
            }

            if ($addTaskForm->validate()) {
                $taskId = (new TaskService())->create($addTaskForm);

                return $this->redirect(['tasks/view', 'taskId' => $taskId]);
            }
        }

        $categories = (new CategoryService())->findAll();

        return $this->render('add', [
            'model' => $addTaskForm,
            'categories' => $categories
        ]);
    }

    public function actionCancel(int $taskId)
    {
        (new TaskService())->cancel($taskId);

        return $this->redirect(['tasks/view', 'taskId' => $taskId]);
    }

    public function actionRefuse(int $taskId)
    {
        (new TaskService())->refuse($taskId);

        return $this->redirect(['tasks/view', 'taskId' => $taskId]);
    }

    public function actionComplete()
    {
        $completeForm = new CompleteForm();

        if (Yii::$app->request->isPost) {
            $completeForm->load(Yii::$app->request->post());

            if ($completeForm->validate()) {
                (new TaskService())->complete($completeForm);

                return $this->redirect(['tasks/view', 'taskId' => $completeForm->task_id]);
            }
        }
    }

    public function actionUserTasks()
    {
        $query = null;
        $userId = Yii::$app->user->id;
        $filter = Yii::$app->request->get('filter');

        if (Yii::$app->user->can('executor')) {
            $query = (new TaskService())->getExecutorTasks($userId, $filter);
        } else {
            $query = (new TaskService())->getCustomerTasks($userId, $filter);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5]
        ]);

        return $this->render('user-tasks', [
            'query' => $query,
            'filter' => $filter,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionIndex()
    {
        $searchForm = new SearchForm();
        $categories = (new CategoryService())->findAll();

        if (Yii::$app->request->isPost) {
            $searchForm->load(Yii::$app->request->post());
        } elseif ($category = Yii::$app->request->get('category')) {
            if ($id = (new CategoryService())->getId($category)) {
                $searchForm->categories[] = $id;
            }
        }

        $query = (new TaskService())->getAllQuery();

        if ($searchForm->validate()) {
            $query = (new TaskService())->getFilterQuery(
                $searchForm,
                $this->user->city_id
            );
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5]
        ]);

        return $this->render('index', [
            'model' => $searchForm,
            'categories' => $categories,
            'dataProvider' => $dataProvider,
            'period_values' => SearchForm::PERIOD_VALUES
        ]);
    }

    public function actionView(int $taskId)
    {
        if (!$task = (new TaskService())->findOne($taskId)) {
            throw new NotFoundHttpException();
        }

        $completeForm = new CompleteForm();
        $responseForm = new ResponseForm();

        $availableAction = (new Task($taskId))->getAvailableAction();

        return $this->render('view', [
            'task' => $task,
            'completeForm' => $completeForm,
            'responseForm' => $responseForm,
            'availableAction' => $availableAction
        ]);
    }
}
