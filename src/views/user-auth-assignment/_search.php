<?php
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <label class="control-label" for="entity_type_id">
            <?= Yii::t('yii2admin', 'Поиск по названию');?>
        </label>
        <div class="form-group">
            <?= Html::textInput('name', $model->name, ['class' => 'form-control']);?>
        </div>
    </div>
</div>