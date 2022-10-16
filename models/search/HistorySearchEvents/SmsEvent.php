<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\History;
use app\models\Sms;
use Yii;

class SmsEvent implements RenderableEvent
{
    private $eventNames = [
        History::EVENT_INCOMING_SMS,
        History::EVENT_OUTGOING_SMS,
    ];

    public function isApplicable(History $model): bool
    {
        return in_array($model->event, $this->eventNames);
    }

    public function createView(History $model): string
    {
        return Yii::$app->view->render('_item_common', [
            'user' => $model->user,
            'body' => $model->sms->message ? $model->sms->message : '',
            'footer' => $model->sms->direction == Sms::DIRECTION_INCOMING ?
                Yii::t('app', 'Incoming message from {number}', [
                    'number' => $model->sms->phone_from ?? ''
                ]) : Yii::t('app', 'Sent message to {number}', [
                    'number' => $model->sms->phone_to ?? ''
                ]),
            'iconIncome' => $model->sms->direction == Sms::DIRECTION_INCOMING,
            'footerDatetime' => $model->ins_ts,
            'iconClass' => 'icon-sms bg-dark-blue'
        ]);
    }
}