<?php

namespace concepture\yii2user\search;

use concepture\yii2user\models\User;
use concepture\yii2user\models\UserRole;
use concepture\yii2user\models\UserRoleHandbook;
use yii\db\ActiveQuery;
use Yii;
use yii\data\ActiveDataProvider;


/**
 * Search модель для ролей пользователя
 *
 * Class UserRoleSearch
 * @package concepture\yii2user\search
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserRoleSearch extends UserRole
{
    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['role','username'], 'safe'],
        ];
    }

    protected function extendQuery(ActiveQuery $query)
    {
        $query->joinWith(['user']);
        $query->andFilterWhere([
            'id' => $this->id
        ]);
        $query->andFilterWhere([
            'role' => $this->role
        ]);
        $query->andFilterWhere(['like', User::tableName().'.username', $this->username]);
    }

    protected function extendDataProvider(ActiveDataProvider $dataProvider)
    {
        $dataProvider->sort->attributes['username'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC],
            'desc' => [User::tableName().'.username' => SORT_DESC],
        ];
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'username' => Yii::t('user', 'Пользователь')
        ]);
    }
}
