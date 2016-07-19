<?php

namespace app\models;

use Yii;
use yii\base\Model;

use yii\data\ActiveDataProvider;
use app\commands\Worksinfo;
use app\helpers\Tool;

/**
 * UserbasicinfoSearch represents the model behind the search form about `app\models\userbasicinfo`.
 */
class WorkbasicinfoSearch extends Worksinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workid'], 'unique'],
            [['worktitle', 'workimg', 'worktag', 'worktripe', 'workimglist'], 'safe'],
            [['makerid'], 'integer'],
            [['makerphone', 'makername', 'makermask', 'makersex', 'makerbirthday'], 'safe'],
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
    public function search()
    {
        $query = Worksinfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'defaultOrder' => [
					'workid' => SORT_DESC,
					'worktitle' => SORT_ASC,
				]
			],
        ]);
		
        // grid filtering conditions
        $query->andFilterWhere([
            'workid' => $this->workid,
            'makerid' => $this->makerid,
            'worktitle' => $this->worktitle,
			'worktag' => $this->worktag,
        ]);

        //$query->andFilterWhere(['like', 'title', $this->title])
           // ->andFilterWhere(['like', 'tag', $this->tag])->all();
		
		$data = array();
		foreach( $dataProvider->getModels() as $key => $value ){
			$data[$key] = $value->getAttributes();
		}
		
		return Tool::return_json( true, 'works', $data );
    }
}
