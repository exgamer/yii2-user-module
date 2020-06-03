<?php

namespace concepture\yii2user\search;

use concepture\yii2user\models\User;
use concepture\yii2user\models\UserCredential;
use yii\db\ActiveQuery;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Search модель для авторизационных данных пользователя
 *
 * Class UserCredentialSearch
 * @package concepture\yii2user\search
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserCredentialSearch extends UserCredential
{
    public $username;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'domain_id', 'user_id'], 'integer'],
            [['identity','username'], 'safe'],
        ];
    }

    public function extendQuery(ActiveQuery $query)
    {
        $query->joinWith(['user']);
        $query->andWhere([static::tableName() . '.parent_id' => null]);
        $query->andFilterWhere([
            static::tableName() . '.id' => $this->id
        ]);
        $query->andFilterWhere([
            static::tableName() . '.domain_id' => $this->domain_id
        ]);
        $query->andFilterWhere([
            static::tableName() . '.user_id' => $this->user_id
        ]);
        $query->andFilterWhere(['like', static::tableName() . '.identity', $this->identity]);
        $query->andFilterWhere(['like', User::tableName().'.username', $this->username]);
    }

    public function extendDataProvider(ActiveDataProvider $dataProvider)
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
