<?php
declare(strict_types=1);

namespace app\commands;

use Generator;
use SplFileObject;
use Yii;
use yii\console\Controller;

class ExportController extends Controller
{
    public $size = 100;
    public const HISTORY_EXPORT_FILE = 'hello.csv';

    public function options($actionID)
    {
        return ['size'];
    }

    public function optionAliases()
    {
        return ['s' => 'size'];
    }

    public function actionCreate()
    {
        $connection = Yii::$app->db;
        $connection->open();

        $file = new SplFileObject(Yii::getAlias('@app/runtime/export/') . self::HISTORY_EXPORT_FILE, 'w');

        foreach ($this->getRecords($connection) as $record) {
            $file->fputcsv($record);
        }

        $connection->close();
    }

    public function getRecords($connection): Generator
    {
        $startIndex = 0;

        while (true) {
            $command = $connection->createCommand('
                SELECT
                    history.ins_ts,
                    u.username,
                    history.object,
                    history.event,
                    history.message
                FROM  history
                LEFT JOIN `call` c on c.id = history.object_id and history.object = "call"
                LEFT JOIN `sms` s on s.id = history.object_id and history.object = "sms"
                LEFT JOIN `customer` cm on cm.id = history.object_id and history.object = "customer"
                LEFT JOIN `task` t on t.id = history.object_id and history.object = "task"
                LEFT JOIN `fax` f on f.id = history.object_id and history.object = "fax"
                LEFT JOIN `user` u on u.id = history.user_id
                ORDER BY history.ins_ts DESC, history.id DESC
                LIMIT :limit
                OFFSET :offset
            ',
            [
                'limit' => $this->size,
                'offset' => $startIndex
            ]
            );

            $startIndex += $this->size;

            $resultArray = $command->queryAll();
            if (count($resultArray) === 0) {
                break;
            }
            foreach($resultArray as $record) {
                yield $record;
            }
        }
    }
}