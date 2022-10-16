<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\History;
use Yii;
use yii\helpers\Html;

class FaxEvent implements RenderableEvent
{
    private $eventNames = [
        History::EVENT_OUTGOING_FAX,
        History::EVENT_INCOMING_FAX,
    ];

    public function isApplicable(History $model): bool
    {
        return in_array($model->event, $this->eventNames);
    }

    public function createView(History $model): string
    {
        $fax = $model->fax;

        return Yii::$app->view->render('_item_common', [
            'user' => $model->user,
            'body' => $model->eventText .
                ' - ' .
                (isset($fax->document) ? Html::a(
                    Yii::t('app', 'view document'),
                    $fax->document->getViewUrl(),
                    [
                        'target' => '_blank',
                        'data-pjax' => 0
                    ]
                ) : ''),
            'footer' => Yii::t('app', '{type} was sent to {group}', [
                'type' => $fax ? $fax->getTypeText() : 'Fax',
                'group' => isset($fax->creditorGroup) ? Html::a($fax->creditorGroup->name, ['creditors/groups'], ['data-pjax' => 0]) : ''
            ]),
            'footerDatetime' => $model->ins_ts,
            'iconClass' => 'fa-fax bg-green'
        ]);
    }
}