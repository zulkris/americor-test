<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\History;
use Yii;

class TaskEvent implements RenderableEvent
{
    private $eventNames = [
        History::EVENT_CREATED_TASK,
        History::EVENT_COMPLETED_TASK,
        History::EVENT_UPDATED_TASK,
    ];

    public function isApplicable(History $model): bool
    {
        return in_array($model->event, $this->eventNames);
    }

    public function createView(History $model): string
    {
        $task = $model->task;

        return Yii::$app->view->render('_item_common', [
            'user' => $model->user,
            'body' => "$model->eventText: " . ($model->task->title ?? ''),
            'iconClass' => 'fa-check-square bg-yellow',
            'footerDatetime' => $model->ins_ts,
            'footer' => isset($task->customerCreditor->name) ? "Creditor: " . $task->customerCreditor->name : ''
        ]);
    }
}