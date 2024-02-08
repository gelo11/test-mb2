<?php

namespace app\models;

use app\models\Apple;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AppleSearch represents the model behind the search form of `app\models\Apple`.
 */
class AppleSearch extends Apple
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'color', 'state'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['prs'], 'number'],
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
        $query = Apple::find();

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
            'color' => $this->color,
            'created' => $this->created,
            'updated' => $this->updated,
            'state' => $this->state,
            'prs' => $this->prs,
        ]);

//        $query->andWhere(['!=', 'state', Apple::ROT_STATE]);
        $query->andWhere(['>', 'prs', 0]);

        return $dataProvider;
    }
}
