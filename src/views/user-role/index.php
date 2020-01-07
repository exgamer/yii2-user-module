<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;

$this->setTitle(Yii::t('yii2admin', 'Роли пользователя ' . $user->username));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', \concepture\yii2user\models\User::label()), 'url' => ['/user/user/index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['create', 'user_id' => $searchModel->user_id]);
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
            'label' => Yii::t('yii2admin', 'Пользователь'),
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
