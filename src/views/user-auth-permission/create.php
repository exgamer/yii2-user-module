<?php

$this->setTitle(Yii::t('yii2admin', 'Новая запись'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', "Полномочия"), 'url' => ['/user/user-auth-permission/index']]);
$this->pushBreadcrumbs($this->title);
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
