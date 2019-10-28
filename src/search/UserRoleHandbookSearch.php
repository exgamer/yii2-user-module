<?php

namespace concepture\yii2user\search;

use concepture\yii2user\models\UserRoleHandbook;
use yii\db\ActiveQuery;

class UserRoleHandbookSearch extends UserRoleHandbook
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer']
        ];
    }

    protected function extendQuery(ActiveQuery $query)
    {
        $query->andFilterWhere([
            'id' => $this->id
        ]);
    }
}
