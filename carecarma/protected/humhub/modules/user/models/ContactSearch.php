<?php

namespace humhub\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use humhub\modules\user\models\Contact;


/**
 * ContactSearch represents the model behind the search form about `humhub\modules\user\models\contact`.
 */
class ContactSearch extends Contact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'user_id'], 'integer'],
            [['contact_first', 'contact_last', 'contact_mobile', 'contact_email', 'nickname'], 'safe'],
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
    public function search($params, $id)
    {
        $query = contact::find()->where(['user_id' => $id])->orderBy('contact_last');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'contact_first',
                'contact_last',
                'contact_mobile',
                'contact_email',
                'nickname',

            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'contact_id' => $this->contact_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'contact_first', $this->contact_first])
            ->andFilterWhere(['like', 'contact_last', $this->contact_last])
            ->andFilterWhere(['like', 'contact_mobile', $this->contact_mobile])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}
