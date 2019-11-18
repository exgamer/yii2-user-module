<?php

namespace concepture\yii2user\search;

use concepture\yii2user\models\EmailHandbook;
use yii\db\ActiveQuery;
use Yii;
use yii\data\ActiveDataProvider;


/**
 * Class EmailHandbookSearch
 * @package concepture\yii2user\search
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class EmailHandbookSearch extends EmailHandbook
{
    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['email'], 'safe'],
        ];
    }

    protected function extendQuery(ActiveQuery $query)
    {
        $query->andFilterWhere([
            'id' => $this->id
        ]);
        $query->andFilterWhere(['like', 'email', $this->email]);
    }
}
