<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/15/2016
 * Time: 10:21 AM
 */

namespace humhub\modules\admin\models;


use humhub\modules\user\models\Device;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DeviceSearch extends Device
{


    public function attributes()
    {
        return array_merge(parent::attributes(), ['user.id', 'user.username']); // TODO: Change the autogenerated stub
    }

    public function rules()
    {
        return [
            [['device_id', 'phone', 'user.id', 'user.username'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Device::find()->joinWith('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'device_id',
                'phone',
                'user.id',
                'user.username'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['device_id' => $this->device_id]);
        return $dataProvider;

    }

}