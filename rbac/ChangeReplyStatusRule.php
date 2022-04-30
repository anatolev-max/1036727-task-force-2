<?php

namespace app\rbac;

use yii\rbac\Rule;
use app\services\ReplyService;
use app\services\TaskService;

class ChangeReplyStatusRule extends Rule
{
    public $name = 'changeReplyStatus';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $taskId = (new ReplyService())->findOne($params['replyId'])->task_id;

        return isset($taskId)
            ? (new TaskService())->canChangeReplyStatus($taskId, $user)
            : false;
    }
}
