<?php

$this->setTitle(Yii::t('yii2admin', 'Новая запись'));
$this->pushBreadcrumbs(['label' => $model::label(), 'url' => ['index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['index'], Yii::t('yii2admin', 'Пoльзователи'),'icon-list');
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
