<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model concepture\user\models\UserCredential */

$this->title = Yii::t('user', 'Update User Credential: {name}', [
    'name' => $originModel->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Credentials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $originModel->id, 'url' => ['view', 'id' => $originModel->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Update');
?>
<div class="user-credential-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
