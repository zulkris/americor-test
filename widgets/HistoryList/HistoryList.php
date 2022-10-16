<?php

namespace app\widgets\HistoryList;

use app\models\search\EventResolver;
use app\models\search\HistorySearch;
use app\models\search\HistorySearchEvents\CallEvent;
use app\models\search\HistorySearchEvents\CustomerChangeQualityEvent;
use app\models\search\HistorySearchEvents\CustomerChangeTypeEvent;
use app\models\search\HistorySearchEvents\FaxEvent;
use app\models\search\HistorySearchEvents\SmsEvent;
use app\models\search\HistorySearchEvents\TaskEvent;
use app\models\search\HistorySearchEvents\NullEvent;
use app\widgets\Export\Export;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class HistoryList extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $model = new HistorySearch();
        $eventResolver = EventResolver::create([
            new CallEvent(),
            new CustomerChangeQualityEvent(),
            new CustomerChangeTypeEvent(),
            new FaxEvent(),
            new SmsEvent(),
            new TaskEvent(),
            new NullEvent(),
        ]);

        return $this->render('main', [
            'model' => $model,
            'eventResolver' => $eventResolver,
            'dataProvider' => $model->search(Yii::$app->request->queryParams),
            'linkExport' => $this->getLinkExport(),
        ]);
    }

    /**
     * @return string
     */
    private function getLinkExport()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        $params = ArrayHelper::merge([
            'exportType' => Export::FORMAT_CSV
        ], $params);
        $params[0] = 'site/export';

        return Url::to($params);
    }
}
