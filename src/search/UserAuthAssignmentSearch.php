<?php

namespace concepture\yii2user\search;

use concepture\yii2logic\forms\Model;
use concepture\yii2user\models\EmailHandbook;
use yii\db\ActiveQuery;
use Yii;
use yii\data\ActiveDataProvider;


/**
 * @TODO костыльный search
 * используется только для передачи данных поэтому наследование от EmailHandbook неважно
 *
 * Class UserAuthAssignmentSearch
 * @package concepture\yii2user\search
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class UserAuthAssignmentSearch extends EmailHandbook
{
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }
}
