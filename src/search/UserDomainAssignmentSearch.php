<?php

namespace concepture\yii2user\search;

use concepture\yii2user\models\UserDomainAssignment;
use yii\db\ActiveQuery;

/**
 * This is the search class for model "concepture\yii2user\models\UserDomainAssignment".
 *
 */
class UserDomainAssignmentSearch extends UserDomainAssignment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            'user_id',
            'integer'
        ];
    }

    public function extendQuery(ActiveQuery $query)
    {
        $query->andFilterWhere(['user_id' => $this->user_id]);
    }
}
