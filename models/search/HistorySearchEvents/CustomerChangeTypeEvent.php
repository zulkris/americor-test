<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\Customer;
use app\models\History;
use Yii;

class CustomerChangeTypeEvent implements RenderableEvent
{
    private $eventNames = [
        History::EVENT_CUSTOMER_CHANGE_TYPE,
    ];

    public function isApplicable(History $model): bool
    {
        return in_array($model->event, $this->eventNames);
    }

    public function createView(History $model): string
    {
        return Yii::$app->view->render('_item_statuses_change', [
            'model' => $model,
            'oldValue' => Customer::getTypeTextByType($model->getDetailOldValue('type')),
            'newValue' => Customer::getTypeTextByType($model->getDetailNewValue('type'))
        ]);
    }
}