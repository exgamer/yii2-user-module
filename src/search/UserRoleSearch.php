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
    public $caption;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['caption','username'], 'safe'],
        ];
    }

    protected function extendQuery(ActiveQuery $query)
    {
        $query->joinWith(['user','role']);
        $query->andFilterWhere([
            'id' => $this->id
        ]);
        $query->andFilterWhere(['like', User::tableName().'.username', $this->username]);
        $query->andFilterWhere(['like', UserRoleHandbook::tableName().'.caption', $this->caption]);
    }

    protected function extendDataProvider(ActiveDataProvider $dataProvider)
    {
        $dataProvider->sort->attributes['username'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC],
            'desc' => [User::tableName().'.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['caption'] = [
            'asc' => [UserRoleHandbook::tableName().'.caption' => SORT_ASC],
            'desc' => [UserRoleHandbook::tableName().'.caption' => SORT_DESC],
        ];
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge($labels, [
            'username' => Yii::t('user', 'Пользователь'),
            'caption' => Yii::t('user', 'Роль')
        ]);
    }
}
