<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;

$this->setTitle($searchModel::label());
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader();
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
            [
                'attribute'=>'role',
                'value'=>function($data) {
                    return $data->getRoleLabel();
                }
            ],
            'created_at',

            [
                'class'=>'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
            ],
        ],
    ]); ?>

<?php Pjax::end(); ?>
