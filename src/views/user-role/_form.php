<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model concepture\user\models\UserRole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-role-form">

    <?php $form = ActiveForm::begin(); ?>

    <? echo $form->field($model, 'user_id')->hiddenInput()->label(false); ?>
    <?= Html::activeLabel($model, 'user_id')?>
    <?= \yii\jui\AutoComplete::widget([
        'name' => 'name',
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'source' => \yii\helpers\Url::to(['/user/user/list']),
            'minLength'=>'2',
            'autoFill'=>true,
            'select' => new \yii\web\JsExpression("function( event, ui ) {
			        $('#userroleform-user_id').val(ui.item.id);
             }")]
    ]); ?>
    <?= Html::error($model, 'user_id')?>

    <?= $form->field($model, 'role_id')->dropDownList(\concepture\yii2user\enum\UserRoleEnum::arrayList());?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('user', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
