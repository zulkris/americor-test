<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\History;
use Yii;

class NullEvent implements RenderableEvent
{
    public function isApplicable(History $model): bool
    {
        return true;
    }

    public function createView(History $model): string
    {
        return Yii::$app->view->render('_item_common', [
            'user' => $model->user,
            'body' => $model->eventText,
            'bodyDatetime' => $model->ins_ts,
            'iconClass' => 'fa-gear bg-purple-light'
        ]);
    }
}