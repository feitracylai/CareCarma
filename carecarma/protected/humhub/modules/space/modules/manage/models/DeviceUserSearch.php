<?php
/**
 * User: wufei
 * Date: 4/4/2016
 * Time: 2:26 PM
 */

namespace humhub\modules\space\modules\manage\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use humhub\modules\space\models\Membership;

class DeviceUserSearch extends Membership
{
//    public $status = Membership::STATUS_DEVICE;

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['user.username', 'user.email', 'user.profile.firstname', 'user.profile.lastname']);
    }

    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['user.profile.firstname', 'user.profile.lastname', 'user.username', 'user.email'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Membership::find()->where(['space_membership.group_id' => 'device']);
//        $query->andWhere(['space_membership.status' => $this->status]);
        $query->joinWith(['user', 'user.profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'user.profile.firstname' => [
                    'asc' => ['profile.firstname' => SORT_ASC],
                    'desc' => ['profile.firstname' => SORT_DESC],
                ],
                'user.profile.lastname' => [
                    'asc' => ['profile.lastname' => SORT_ASC],
                    'desc' => ['profile.lastname' => SORT_DESC],
                ],
                'user.email',
                'user.username',
                'last_visit',
            ]]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['space_membership.space_id' => $this->space_id]);

        $query->andFilterWhere(['like', 'profile.lastname', $this->getAttribute('user.profile.lastname')]);
        $query->andFilterWhere(['like', 'profile.firstname', $this->getAttribute('user.profile.firstname')]);
        $query->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')]);
        $query->andFilterWhere(['like', 'user.email', $this->getAttribute('user.email')]);

        return $dataProvider;
    }
}