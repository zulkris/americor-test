<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\Customer;
use app\models\History;
use Yii;

class CustomerChangeQualityEvent implements RenderableEvent
{
    private $eventNames = [
        History::EVENT_CUSTOMER_CHANGE_QUALITY,
    ];

    public function isApplicable(History $model): bool
    {
        return in_array($model->event, $this->eventNames);
    }

    public function createView(History $model): string
    {
        return Yii::$app->view->render('_item_statuses_change', [
            'model' => $model,
            'oldValue' => Customer::getQualityTextByQuality($model->getDetailOldValue('quality')),
            'newValue' => Customer::getQualityTextByQuality($model->getDetailNewValue('quality')),
        ]);
    }
}