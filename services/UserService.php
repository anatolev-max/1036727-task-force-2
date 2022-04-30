<?php

namespace app\services;

use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use app\models\City;
use app\models\Review;
use app\models\Task;
use app\models\User;
use app\models\UserCategory;
use app\models\UserIdentity;
use app\models\UserProfile;
use app\models\forms\ProfileForm;
use app\models\forms\SecurityForm;
use app\models\forms\SignupForm;
use app\services\AuthService;
use anatolev\service\Task as Task2;

class UserService
{
    public const RBAC_ERROR_MESSAGE = 'Непредвиденная ошибка';

    /**
     * @param ClientInterface $client
     * @return void
     */
    public function authHandler(ClientInterface $client): void
    {
        $attributes = $client->getUserAttributes();
        $source = $client->getId();

        $sourceId = ArrayHelper::getValue($attributes, 'id');

        if ($auth = (new AuthService())->findOne($source, $sourceId)) {
            $this->login($auth->user->email);
        } elseif ($email = ArrayHelper::getValue($attributes, 'email')) {
            if ($user = $this->findByEmail($email)) {
                (new AuthService())->create($user->id, $source, $sourceId);
                $this->login($email);
            } else {
                $signupForm = new SignupForm();

                $signupForm->name = "{$attributes['first_name']} {$attributes['last_name']}";
                $signupForm->email = $attributes['email'];
                $signupForm->city_id = (new CityService())->findByName($attributes['city']['title'])->id ?? 1;
                $signupForm->is_executor = 1;

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $user = $this->create($signupForm);
                    (new AuthService())->create($user->id, $source, $attributes['id']);
                    $transaction->commit();
                    $this->login($email);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * @param SignupForm $model
     * @return void
     */
    public function create(SignupForm $model): ?User
    {
        $auth = Yii::$app->authManager;
        $executor = $auth->getRole('executor');
        $customer = $auth->getRole('customer');

        if (!isset($executor, $customer)) {
            Yii::$app->session->setFlash('error', self::RBAC_ERROR_MESSAGE);
            Yii::error(self::RBAC_ERROR_MESSAGE);
        }

        $hash = null;
        if (isset($model->password)) {
            $hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->attributes = $model->attributes;
            $user->password = $hash;
            $user->save();

            $userRole = $user->is_executor
                ? $executor
                : $customer;

            $auth->assign($userRole, $user->id);

            $profile = new UserProfile();
            $profile->user_id = $user->id;
            $profile->save();

            $transaction->commit();

            return $user;
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }

        return null;
    }

    /**
     * @param string $email
     * @return ?User
     */
    public function findByEmail(string $email): ?User
    {
        return User::findOne(['email' => $email]);
    }

    /**
     * @param int $userId
     * @return ?User
     */
    public function findOne(int $userId): ?User
    {
        return User::findOne($userId);
    }

    /**
     * @param int $userId
     * @return ?User $user
     */
    public function getExecutor(int $userId): ?User
    {
        $user = User::findOne(['id' => $userId, 'is_executor' => 1]);

        if (isset($user)) {
            $user->isBusy = $this->isBusy($userId);
            $user->placeInRating = $this->getPlaceInRating($userId);
            $user->showContacts = $this->showContacts($userId);
        }

        return $user;
    }

    /**
     * @param string $email
     * @return ?UserIdentity
     */
    public function getUserIdentity(string $email): ?UserIdentity
    {
        return UserIdentity::findOne(['email' => $email]);
    }

    /**
     * @param string $email
     * @return void
     */
    public function login(string $email): void
    {
        Yii::$app->user->login($this->getUserIdentity($email));
    }

    /**
     * @return bool
     */
    public function passwordIsset(): bool
    {
        return boolval(User::findOne(Yii::$app->user->id)?->password);
    }

    /**
     * @param int $userId;
     */
    public function updateCurrentRate(int $userId): void
    {
        $query = Review::find()
            ->joinWith('task t')
            ->where(['t.executor_id' => $userId]);
        $overallRating = $query->sum('rating');
        $reviewCount = $query->count();

        $query = UserProfile::find()->where(['user_id' => $userId]);
        $failedTaskCount = $query->one()->failed_task_count;

        $devider = $reviewCount + $failedTaskCount;
        $currentRate = $devider ? $overallRating / $devider : 0;

        $user = User::findOne($userId);
        $user->profile->current_rate = round($currentRate, 2);

        $user->profile->save();
    }

    /**
     * @param ProfileForm $model
     * @return void
     */
    public function updateProfile(ProfileForm $model): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = User::findOne(Yii::$app->user->id);
            $user->attributes = $model->attributes;
            $user->save();

            UserCategory::deleteAll(['user_id' => Yii::$app->user->id]);
            foreach ($model->categories as $categoryId) {
                $userCategory = new UserCategory();
                $userCategory->category_id = $categoryId;
                $userCategory->link('user', $user);
            }

            if (isset($model->avatar)) {
                $files = FileHelper::findFiles(Yii::getAlias('@avatars'), [
                    'filter' => fn($path) => strripos($path, $user->profile->avatar_path)
                ]);

                if ($avatar = array_values($files)[0] ?? null) {
                    FileHelper::unlink($avatar);
                }

                $filePath = uniqid("{$model->avatar->baseName}_") . '.' . $model->avatar->extension;
                $model->avatar->saveAs(Yii::getAlias('@avatars') . '/' . $filePath);
                $user->profile->avatar_path = $filePath;
            }

            $user->profile->attributes = $model->attributes;
            $user->profile->birthday = $model->birthday;
            $user->profile->save();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @param SecurityForm $model
     * @return void
     */
    public function updateSecurity(SecurityForm $model): void
    {
        $hash = Yii::$app->getSecurity()->generatePasswordHash($model->new_password);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = User::findOne(Yii::$app->user->id);
            $user->password = $hash;
            $user->save();

            $user->profile->private_contacts = $model->private_contacts;
            $user->profile->save();

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }

    /**
     * @param int $userId
     * @return void
     */
    public function updateTaskCounter(int $userId): void
    {
        $user = UserProfile::findOne($userId);
        $user->updateCounters(["done_task_count" => 1]);

        $user->save();
    }

    /**
     * @param int $userId
     * @return int
     */
    private function getPlaceInRating(int $userId): int
    {
        $query = User::find()
            ->joinWith('profile p')
            ->where(['user.is_executor' => 1])
            ->orderBy('p.current_rate DESC');

        $users = $query->asArray()->all();

        return array_search($userId, array_column($users, 'id')) + 1;
    }

    /**
     * @param int $userId
     * @return bool
     */
    private function isBusy(int $userId): bool
    {
        $condition = ['executor_id' => $userId, 'status_id' => Task2::STATUS_WORK_ID];

        return Task::find()->where($condition)->exists();
    }

    /**
     * @param int $userId
     * @return bool
     */
    private function showContacts(int $userId): bool
    {
        $privateContacts = $this->findOne($userId)->profile->private_contacts;

        $conditions = [
            'status_id' => Task2::STATUS_WORK_ID,
            'customer_id' => Yii::$app->user->id,
            'executor_id' => $userId
        ];

        $isExecutor = Task::find()->where($conditions)->exists();
        $myProfile = $userId === Yii::$app->user->id;

        return !$privateContacts || $isExecutor || $myProfile;
    }
}
