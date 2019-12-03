<?php

$this->setTitle(Yii::t('yii2admin', 'Редактирование'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', 'Авторизационные данные пользователей'), 'url' => ['index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader();
$this->viewHelper()->pushPageHeader(['view', 'id' => $originModel->id], Yii::t('yii2admin', 'Просмотр'),'icon-file-eye2');
$this->viewHelper()->pushPageHeader(['index'], Yii::t('yii2admin', 'Авторизационные данные пользователей'),'icon-list');
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
