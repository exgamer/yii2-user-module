<?php

$this->setTitle(Yii::t('user', 'Новая запись'));
$this->pushBreadcrumbs(['label' => Yii::t('user', 'Авторизационные данные пользователей'), 'url' => ['index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['index'], Yii::t('user', 'Авторизационные данные пользователей'),'icon-list');
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
