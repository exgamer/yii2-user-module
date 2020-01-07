<?php

$this->setTitle(Yii::t('yii2admin', 'Новая запись'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', \concepture\yii2user\models\User::label()), 'url' => ['/user/user/index']]);
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', 'Роли пользователя ' . $user->username), 'url' => ['index', 'user_id' => $model->user_id]]);
$this->pushBreadcrumbs($this->title);
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
