<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\Call;
use app\models\History;
use Yii;

class CallEvent implements RenderableEvent
{
    private $eventNames = [
        History::EVENT_INCOMING_CALL,
        History::EVENT_OUTGOING_CALL,
    ];

    public function isApplicable(History $model): bool
    {
        return in_array($model->event, $this->eventNames);
    }

    public function createView(History $model): string
    {
        /** @var Call $call */
        $call = $model->call;
        $answered = $call && $call->status == Call::STATUS_ANSWERED;

        return Yii::$app->view->render('_item_common', [
            'user' => $model->user,
            'content' => $call->comment ?? '',
            'body' => ($call ? $call->totalStatusText . ($call->getTotalDisposition(false) ? " <span class='text-grey'>" . $call->getTotalDisposition(false) . "</span>" : "") : '<i>Deleted</i> '),
            'footerDatetime' => $model->ins_ts,
            'footer' => isset($call->applicant) ? "Called <span>{$call->applicant->name}</span>" : null,
            'iconClass' => $answered ? 'md-phone bg-green' : 'md-phone-missed bg-red',
            'iconIncome' => $answered && $call->direction == Call::DIRECTION_INCOMING
        ]);
    }
}