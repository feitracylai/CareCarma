<?php

namespace humhub\modules\reminder\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReminderDeviceSearch represents the model behind the search form about `humhub\modules\reminder\models\ReminderDevice`.
 */
class ReminderDeviceSearch extends ReminderDevice
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['times.time', 'times.date', 'times.day']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'sent', 'update_user_id'], 'integer'],
            [['title', 'times.time', 'times.date', 'description'], 'safe'],
        ];
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
        $query = ReminderDevice::find()->joinWith('times');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'title',
                'times.time',
                'times.date',
                'times.day',
                'description',


                'sent',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'sent' => $this->sent,
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
