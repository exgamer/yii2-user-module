<?php

use yii\helpers\Html;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\formelements\activeform\ActiveForm;
?>

<?php Pjax::begin(['formSelector' => '#userroleform']); ?>

<?php $form = ActiveForm::begin(['id' => 'userroleform']); ?>
    <div class="card">
        <div class="card-body text-right">
            <?=  Html::submitButton(
                '<b><i class="icon-checkmark3"></i></b>' . Yii::t('yii2admin', 'Сохранить'),
                [
                    'class' => 'btn bg-success btn-labeled btn-labeled-left ml-1'
                ]
            ); ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($model, 'name'); ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($model, 'description'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
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