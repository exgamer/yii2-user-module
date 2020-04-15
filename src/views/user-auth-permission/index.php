<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;

$this->setTitle(Yii::t('yii2admin', 'Полномочия'));
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['create']);
?>
<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'searchVisible' => false,
    'columns' => [
        [
            'label' => Yii::t('yii2admin', 'Наименование'),
            'attribute' => 'name',
        ],
        [
            'label' => Yii::t('yii2admin', 'Описание'),
            'attribute' => 'description',
        ],
        [
            'class'=>'yii\grid\ActionColumn',
            'template'=>' {delete}',
            'buttons'=>[
                'delete'=> function ($url, $model) {
                    return Html::a(
                        '<i class="icon-trash"></i>'. Yii::t('yii2admin', 'Удалить'),
                        ['delete', 'name' => $model->name],
                        [
                            'class' => 'admin-action dropdown-item',
                            'data-pjax-id' => 'list-pjax',
                            'data-pjax-url' => \yii\helpers\Url::current([], true),
                            'data-swal' => Yii::t('yii2admin' , 'Удалить'),
                        ]
                    );
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>
