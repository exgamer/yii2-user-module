<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model concepture\user\models\User */

$this->title = Yii::t('backend', 'Редактировать пользователя: {name}', [
    'name' => $originModel->username,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $originModel->id, 'url' => ['view', 'id' => $originModel->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Редактировать');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
