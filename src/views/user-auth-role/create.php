<?php

$this->setTitle(Yii::t('yii2admin', 'Новая запись'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', "Роли пользователей"), 'url' => ['/user/user-auth-role/index']]);
$this->pushBreadcrumbs($this->title);
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
