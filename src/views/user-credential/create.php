<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model concepture\user\models\UserCredential */

$this->title = Yii::t('backend', 'Create User Credential');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Credentials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-credential-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
