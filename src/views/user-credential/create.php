<?php

$this->setTitle(Yii::t('yii2admin', 'Новая запись'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', 'Авторизационные данные пользователей'), 'url' => ['index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['index'], Yii::t('yii2admin', 'Авторизационные данные пользователей'),'icon-list');
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
