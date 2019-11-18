<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\search\UserRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Роли пользователей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('user', 'Добавить роль'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'username',
                'value' => 'user.username'
            ],
            [
                'attribute'=>'role',
                'filter'=> \concepture\yii2user\enum\UserRoleEnum::arrayList(),
                'value'=>function($data) {
                    return $data->getRoleLabel();
                }
            ],
            'created_at',
            [
                'class'=>'yii\grid\ActionColumn',
                'template'=>'{view} {delete}',
                'buttons'=>[
                    'view',
                    'delete'
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
