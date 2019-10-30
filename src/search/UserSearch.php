<?php

namespace concepture\yii2user\search;

use concepture\yii2user\models\User;
use yii\db\ActiveQuery;

/**
 * Search модель для пользователя
 *
 * Class UserSearch
 * @package concepture\yii2user\search
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username'], 'safe'],
        ];
    }

    protected function extendQuery(ActiveQuery $query)
    {
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);
    }

    public static function getListSearchKeyAttribute()
    {
        return 'id';
    }

    public static function getListSearchAttribute()
    {
        return 'username';
    }
}
