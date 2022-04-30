<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseInflector;
use yii\helpers\FileHelper;
use anatolev\utils\DataConverter;

class DataController extends Controller
{
    public const CSV_MIME_TYPES = ['application/csv', 'text/csv'];

    /**
     * @return int Exit code
     */
    public function actionImport(): int
    {
        $tables = Yii::$app->db->createCommand('SHOW TABLES')->queryColumn();

        foreach ($tables as $table) {
            $file_path = Yii::getAlias('@data') . '/' . $table . '.csv';
            $table = (new BaseInflector())->camelize($table);
            $classname = '\app\models\\' . $table;

            foreach ((new DataConverter($file_path))->convert()->asArray() as $row) {
                $table = new $classname();
                $table->attributes = $row;
                $table->save();
            }
        }

        return $this->run('rbac/init');
    }

    /**
     * @return int Exit code
     */
    public function actionConvert(): int
    {
        foreach (FileHelper::findFiles(Yii::getAlias('@data')) as $file) {
            $file_type = FileHelper::getMimeType($file);

            if (in_array($file_type, self::CSV_MIME_TYPES)) {
                $converter = new DataConverter($file);
                $converter->convert()->dumpToSqlFile(Yii::getAlias('@sql'));
            }
        }

        return ExitCode::OK;
    }
}
