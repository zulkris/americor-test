<?php

namespace app\models\search;

use app\commands\ExportController;
use app\models\History;
use gri3li\yii2csvdataprovider\CsvDataProvider;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

/**
 * HistorySearch represents the model behind the search form about `app\models\History`.
 *
 * @property array $objects
 */
class HistorySearch extends History
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = History::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'ins_ts' => SORT_DESC,
                'id' => SORT_DESC
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            History::find()->where('0=1');
            return $dataProvider;
        }

        $query->addSelect('history.*');
        $query->with([
            'customer',
            'user',
            'sms',
            'task',
            'call',
            'fax',
        ]);

        return $dataProvider;
    }

    public function getCsvDataProvider(): CsvDataProvider
    {
       return new CsvDataProvider([
           'filename' => Yii::getAlias('@app/runtime/export/') . ExportController::HISTORY_EXPORT_FILE,
       ]);
    }
}
