<?php

namespace humhub\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use humhub\modules\user\models\Contact;
use yii\log\Logger;


/**
 * ContactSearch represents the model behind the search form about `humhub\modules\user\models\contact`.
 */
class ContactSearch extends Contact
{

    public $status = 'index';
    /**
     * @inheritdoc
     */

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['user.username', 'user.profile.firstname', 'user.profile.lastname']);
    }

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
//        $query = contact::find()->where(['user_id' => $id])->orderBy('contact_last');
        $query = Contact::find()->where(['user_id' => $id])->orWhere(['contact_user_id' => $id])->orderBy('contact_last');

        if ($this->status == 'index'){
            $query->andFilterWhere(['user_id' => $id]);
            $query->andWhere(['linked' => 1]);
            $query->andFilterCompare('contact_first','<>NULL');
        } elseif ($this->status == 'console') {
            $query->andFilterCompare('contact_user_id','<>NULL');
            $query->andWhere(['user_id' => $id, 'linked' => 0]);
            $query->orWhere(['contact_user_id' => $id]);
        }



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'user.profile.firstname',
                'user.profile.lastname',

                'contact_first',
                'contact_last',
                'contact_mobile',
                'contact_email',
                'nickname',
                'relation',

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
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'relation', $this->relation]);

        return $dataProvider;
    }
}
