<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Авторизационные данные пользователей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-credential-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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

</div>
