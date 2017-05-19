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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'update_user_id'], 'integer'],
            [['title', 'description'], 'safe'],
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
                'description',
                'update_user_id',

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
            'update_user_id' => $this->update_user_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
