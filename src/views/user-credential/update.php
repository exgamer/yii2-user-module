<?php
use yii\helpers\Html;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\formelements\activeform\ActiveForm;

$this->setTitle(Yii::t('yii2admin', 'Смена пароля' . $originModel->identity));
$this->pushBreadcrumbs(['label' => $model::label(), 'url' => ['index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader();
$this->viewHelper()->pushPageHeader(['view', 'id' => $originModel->id], Yii::t('yii2admin', 'Просмотр'),'icon-file-eye2');
$this->viewHelper()->pushPageHeader(['index'], $model::label(),'icon-list');
?>

<?php Pjax::begin(['formSelector' => '#user-credential-form']); ?>

<?php $form = ActiveForm::begin(['id' => 'user-credential-form']); ?>
<div class="card">
    <div class="card-body text-right">
        <?=  Html::submitButton(
            '<b><i class="icon-checkmark3"></i></b>' . Yii::t('yii2admin', 'Сохранить'),
            [
                'class' => 'btn bg-success btn-labeled btn-labeled-left ml-1'
            ]
        ); ?>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?= $form->field($model, 'validation')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>

    <div class="card-body text-right">
        <?=  Html::submitButton(
            '<b><i class="icon-checkmark3"></i></b>' . Yii::t('yii2admin', 'Сохранить'),
            [
                'class' => 'btn bg-success btn-labeled btn-labeled-left ml-1'
            ]
        ); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

