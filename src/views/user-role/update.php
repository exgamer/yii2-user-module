<?php

$this->setTitle(Yii::t('user', 'Редактирование'));
$this->pushBreadcrumbs(['label' => Yii::t('user', 'Роли пользователей'), 'url' => ['index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader();
$this->viewHelper()->pushPageHeader(['view', 'id' => $originModel->id], Yii::t('user', 'Просмотр'),'icon-file-eye2');
$this->viewHelper()->pushPageHeader(['index'], Yii::t('user', 'Роли пользователей'),'icon-list');
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
