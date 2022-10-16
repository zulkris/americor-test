<?php

/**
 * @var $this yii\web\View
 * @var $model \app\models\History
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $exportType string
 */

use app\widgets\Export\Export;

$filename = 'history';
$filename .= '-' . time();

ini_set('max_execution_time', 0);
ini_set('memory_limit', '2048M');
?>

<?= Export::widget([
    'dataProvider' => $dataProvider,
//    'columns' => [
//        [
//            'attribute' => 'ins_ts',
//            'label' => Yii::t('app', 'Date'),
//            'format' => 'datetime'
//        ],
//        [
//            'label' => Yii::t('app', 'User'),
//            'value' => isset($model['username']) ??  Yii::t('app', 'System'),
//        ],
//        [
//            'label' => Yii::t('app', 'Type'),
//            'value' => $model['object'],
//        ],
//        [
//            'label' => Yii::t('app', 'Event'),
//            'value' => $model['event'],
//        ],
//        [
//            'label' => Yii::t('app', 'Message'),
//            'value' => $model['event'],
////
////            'value' => function ($model) {
////                return strip_tags(HistoryListHelper::getBodyByModel($model));
////            }
//        ]
//    ],
    'exportType' => $exportType,
    'batchSize' => 2000,
    'filename' => $filename
]);