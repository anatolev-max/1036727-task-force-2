<?php

namespace app\services;

use app\models\TaskFile;

class TaskFileService
{
    /**
     * @param string $file_path
     * @param int $task_id
     * @return void
     */
    public function create(string $file_path, int $task_id): void
    {
        $task_file = new TaskFile();
        $task_file->path = $file_path;
        $task_file->task_id = $task_id;

        $task_file->save();
    }
}
