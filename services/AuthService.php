<?php

namespace app\services;

use app\models\Auth;

class AuthService
{
    /**
     * @param int $userId
     * @param string $source
     * @param int $sourceId
     * @return bool
     */
    public function create(int $userId, string $source, string $sourceId): bool
    {
        $auth = new Auth();

        $auth->user_id = $userId;
        $auth->source = $source;
        $auth->source_id = $sourceId;

        return $auth->save();
    }

    /**
     * @param string $source
     * @param string $sourceId
     * @return ?Auth
     */
    public function findOne(string $source, string $sourceId): ?Auth
    {
        return Auth::findOne(['source' => $source, 'source_id' => $sourceId]);
    }
}
