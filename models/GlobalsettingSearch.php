<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Globalsetting;

/**
 * GlobalsettingSearch represents the model behind the search form of `app\models\Globalsetting`.
 */
class GlobalsettingSearch extends Globalsetting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['global_settings_id', 'created_by', 'updated_by'], 'integer'],
            [['organization_name', 'organization_code', 'organization_email', 'address', 'phone', 'currency', 'currency_symbol', 'footer_text', 'timezone', 'date_format', 'facebook_url', 'twitter_url', 'linkedin_url', 'youtube_url', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Globalsetting::find();

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
            'global_settings_id' => $this->global_settings_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'organization_code', $this->organization_code])
            ->andFilterWhere(['like', 'organization_email', $this->organization_email])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'currency_symbol', $this->currency_symbol])
            ->andFilterWhere(['like', 'footer_text', $this->footer_text])
            ->andFilterWhere(['like', 'timezone', $this->timezone])
            ->andFilterWhere(['like', 'date_format', $this->date_format])
            ->andFilterWhere(['like', 'facebook_url', $this->facebook_url])
            ->andFilterWhere(['like', 'twitter_url', $this->twitter_url])
            ->andFilterWhere(['like', 'linkedin_url', $this->linkedin_url])
            ->andFilterWhere(['like', 'youtube_url', $this->youtube_url]);

        return $dataProvider;
    }
}
