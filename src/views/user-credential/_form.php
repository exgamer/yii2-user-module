<?php

use yii\helpers\Html;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\formelements\activeform\ActiveForm;
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
                <?= Html::activeHiddenInput($model, 'user_id') ?>
                <?= Html::activeLabel($model, 'user_id')?>
                <?= \yii\jui\AutoComplete::widget([
                    'name' => 'name',
                    'value' => $model->getUserName(),
                    'options' => ['class' => 'form-control'],
                    'clientOptions' => [
                        'source' => \yii\helpers\Url::to(['/user/user/list']),
                        'minLength'=>'2',
                        'autoFill'=>true,
                        'select' => new \yii\web\JsExpression("function( event, ui ) {
                                    $('#userroleform-user_id').val(ui.item.id);
                             }")]
                ]); ?>
                <?= Html::error($model, 'user_id', ['class' => 'text-danger form-text'])?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?= $form->field($model, 'identity')->textInput(['maxlength' => true]) ?>
            </div>
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
