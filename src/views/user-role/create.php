<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model concepture\user\models\UserRole */

$this->title = Yii::t('user', 'Добавить роль');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Роли пользователей'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
