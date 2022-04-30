<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use app\models\forms\ProfileForm;
use app\models\forms\SecurityForm;
use app\services\CategoryService;
use app\services\UserService;

class ProfileController extends SecuredController
{
    public function actionSettings()
    {
        $tab = Yii::$app->request->get('tab');

        $profileForm = new ProfileForm($this->user);
        $securityForm = new SecurityForm($this->user);

        if (Yii::$app->request->isPost) {
            $profileForm->load(Yii::$app->request->post());
            $securityForm->load(Yii::$app->request->post());
            $profileForm->avatar = UploadedFile::getInstance($profileForm, 'avatar');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($profileForm);
            }

            if (in_array($tab, ['profile', 'security'])) {
                [$model, $method] = ["{$tab}Form", "update{$tab}"];

                if ($$model->validate()) {
                    (new UserService())->{$method}($$model);
                    $userId = Yii::$app->user->id;

                    return match (true) {
                        !$this->user->is_executor => $this->refresh(),
                        default => $this->redirect(['profile/view', 'userId' => $userId])
                    };
                }
            }
        }

        $categories = (new CategoryService())->findAll();

        return $this->render('settings', [
            'tab' => $tab,
            'profileForm' => $profileForm,
            'securityForm' => $securityForm,
            'categories' => $categories,
        ]);
    }

    public function actionView(int $userId)
    {
        if (!$user = (new UserService())->getExecutor($userId)) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'user' => $user,
        ]);
    }
}
