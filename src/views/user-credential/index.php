<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;

$this->setTitle($searchModel::label());
$this->pushBreadcrumbs($this->title);
?>
<?php Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'searchVisible' => true,
    'searchParams' => [
        'model' => $searchModel
    ],
    'columns' => [
        'id',
        [
            'attribute' => 'username',
            'value' => 'user.username'
        ],
        'identity',
        'created_at',
        'updated_at'
    ],
]); ?>

<?php Pjax::end(); ?>
