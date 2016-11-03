<?php

namespace humhub\modules\user\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use humhub\modules\user\models\Users;

/**
 * UsersSearch represents the model behind the search form about `humhub\modules\user\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'twitterid', 'completesignup', 'createdyear', 'taskerboundary'], 'integer'],
            [['firstname', 'lastname', 'username', 'profilename', 'email', 'mobile', 'password', 'usertype', 'taskeravailability', 'montaskeravailability', 'tuetaskeravailability', 'wedtaskeravailability', 'thutaskeravailability', 'fritaskeravailability', 'sattaskeravailability', 'suntaskeravailability', 'sunstarttime', 'sunendtime', 'monstarttime', 'monendtime', 'tuestarttime', 'tueendtime', 'wedstarttime', 'wedendtime', 'thustarttime', 'thuendtime', 'fristarttime', 'friendtime', 'satstarttime', 'satendtime', 'questions', 'takeradditiontocommunity', 'takernotworking', 'takermakesure', 'havevechile', 'vechiletype', 'taskerthingstobring', 'taskeraddthings', 'quickpitch', 'experience', 'skills', 'workarea', 'status', 'background', 'insurance', 'logintype', 'mobileveificationcode', 'emailverificationcode', 'createdon', 'modifiedon', 'lastlogindate', 'lastloginip', 'image', 'biography', 'unitnumber', 'address', 'city', 'state', 'country', 'postalcode', 'paypalemail', 'accountverified', 'facebook', 'google', 'dob', 'gender', 'adminremarks', 'privilege', 'phonetype', 'lattidude', 'longitude', 'latlonname', 'activation_code', 'ipaddress', 'sandbox_stripe_access_token', 'sandbox_stripe_refresh_token', 'sandbox_stripe_publishable_key', 'sandbox_stripe_user_id', 'sandbox_stripe_token_type', 'live_stripe_access_token', 'live_stripe_refresh_token', 'live_stripe_publishable_key', 'live_stripe_user_id', 'live_stripe_token_type', 'stripe_customerid', 'polylat', 'polylon', 'polygoncoordinate', 'distanceby'], 'safe'],
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
        $query = Users::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'createdon' => $this->createdon,
            'modifiedon' => $this->modifiedon,
            'lastlogindate' => $this->lastlogindate,
            'dob' => $this->dob,
            'twitterid' => $this->twitterid,
            'completesignup' => $this->completesignup,
            'createdyear' => $this->createdyear,
            'taskerboundary' => $this->taskerboundary,
        ]);

        $query->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'profilename', $this->profilename])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'usertype', $this->usertype])
            ->andFilterWhere(['like', 'taskeravailability', $this->taskeravailability])
            ->andFilterWhere(['like', 'montaskeravailability', $this->montaskeravailability])
            ->andFilterWhere(['like', 'tuetaskeravailability', $this->tuetaskeravailability])
            ->andFilterWhere(['like', 'wedtaskeravailability', $this->wedtaskeravailability])
            ->andFilterWhere(['like', 'thutaskeravailability', $this->thutaskeravailability])
            ->andFilterWhere(['like', 'fritaskeravailability', $this->fritaskeravailability])
            ->andFilterWhere(['like', 'sattaskeravailability', $this->sattaskeravailability])
            ->andFilterWhere(['like', 'suntaskeravailability', $this->suntaskeravailability])
            ->andFilterWhere(['like', 'sunstarttime', $this->sunstarttime])
            ->andFilterWhere(['like', 'sunendtime', $this->sunendtime])
            ->andFilterWhere(['like', 'monstarttime', $this->monstarttime])
            ->andFilterWhere(['like', 'monendtime', $this->monendtime])
            ->andFilterWhere(['like', 'tuestarttime', $this->tuestarttime])
            ->andFilterWhere(['like', 'tueendtime', $this->tueendtime])
            ->andFilterWhere(['like', 'wedstarttime', $this->wedstarttime])
            ->andFilterWhere(['like', 'wedendtime', $this->wedendtime])
            ->andFilterWhere(['like', 'thustarttime', $this->thustarttime])
            ->andFilterWhere(['like', 'thuendtime', $this->thuendtime])
            ->andFilterWhere(['like', 'fristarttime', $this->fristarttime])
            ->andFilterWhere(['like', 'friendtime', $this->friendtime])
            ->andFilterWhere(['like', 'satstarttime', $this->satstarttime])
            ->andFilterWhere(['like', 'satendtime', $this->satendtime])
            ->andFilterWhere(['like', 'questions', $this->questions])
            ->andFilterWhere(['like', 'takeradditiontocommunity', $this->takeradditiontocommunity])
            ->andFilterWhere(['like', 'takernotworking', $this->takernotworking])
            ->andFilterWhere(['like', 'takermakesure', $this->takermakesure])
            ->andFilterWhere(['like', 'havevechile', $this->havevechile])
            ->andFilterWhere(['like', 'vechiletype', $this->vechiletype])
            ->andFilterWhere(['like', 'taskerthingstobring', $this->taskerthingstobring])
            ->andFilterWhere(['like', 'taskeraddthings', $this->taskeraddthings])
            ->andFilterWhere(['like', 'quickpitch', $this->quickpitch])
            ->andFilterWhere(['like', 'experience', $this->experience])
            ->andFilterWhere(['like', 'skills', $this->skills])
            ->andFilterWhere(['like', 'workarea', $this->workarea])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'background', $this->background])
            ->andFilterWhere(['like', 'insurance', $this->insurance])
            ->andFilterWhere(['like', 'logintype', $this->logintype])
            ->andFilterWhere(['like', 'mobileveificationcode', $this->mobileveificationcode])
            ->andFilterWhere(['like', 'emailverificationcode', $this->emailverificationcode])
            ->andFilterWhere(['like', 'lastloginip', $this->lastloginip])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'biography', $this->biography])
            ->andFilterWhere(['like', 'unitnumber', $this->unitnumber])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'postalcode', $this->postalcode])
            ->andFilterWhere(['like', 'paypalemail', $this->paypalemail])
            ->andFilterWhere(['like', 'accountverified', $this->accountverified])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'google', $this->google])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'adminremarks', $this->adminremarks])
            ->andFilterWhere(['like', 'privilege', $this->privilege])
            ->andFilterWhere(['like', 'phonetype', $this->phonetype])
            ->andFilterWhere(['like', 'lattidude', $this->lattidude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'latlonname', $this->latlonname])
            ->andFilterWhere(['like', 'activation_code', $this->activation_code])
            ->andFilterWhere(['like', 'ipaddress', $this->ipaddress])
            ->andFilterWhere(['like', 'sandbox_stripe_access_token', $this->sandbox_stripe_access_token])
            ->andFilterWhere(['like', 'sandbox_stripe_refresh_token', $this->sandbox_stripe_refresh_token])
            ->andFilterWhere(['like', 'sandbox_stripe_publishable_key', $this->sandbox_stripe_publishable_key])
            ->andFilterWhere(['like', 'sandbox_stripe_user_id', $this->sandbox_stripe_user_id])
            ->andFilterWhere(['like', 'sandbox_stripe_token_type', $this->sandbox_stripe_token_type])
            ->andFilterWhere(['like', 'live_stripe_access_token', $this->live_stripe_access_token])
            ->andFilterWhere(['like', 'live_stripe_refresh_token', $this->live_stripe_refresh_token])
            ->andFilterWhere(['like', 'live_stripe_publishable_key', $this->live_stripe_publishable_key])
            ->andFilterWhere(['like', 'live_stripe_user_id', $this->live_stripe_user_id])
            ->andFilterWhere(['like', 'live_stripe_token_type', $this->live_stripe_token_type])
            ->andFilterWhere(['like', 'stripe_customerid', $this->stripe_customerid])
            ->andFilterWhere(['like', 'polylat', $this->polylat])
            ->andFilterWhere(['like', 'polylon', $this->polylon])
            ->andFilterWhere(['like', 'polygoncoordinate', $this->polygoncoordinate])
            ->andFilterWhere(['like', 'distanceby', $this->distanceby]);

        return $dataProvider;
    }
}
