<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ModalForm extends Widget
{
    public $model;
    public $formType;
    public $taskId;

    public function run()
    {
        return $this->render("{$this->formType}-form", [
            'model' => $this->model,
            'taskId' => $this->taskId
        ]);
    }
}
