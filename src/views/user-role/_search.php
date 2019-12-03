<?php

use kamaelkz\yii2admin\v1\modules\uikit\enum\UiikitEnum;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\enum\IsDeletedEnum;
?>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <?= $form->field($model,'id')->textInput();?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <?= $form->field($model,'username')->textInput();?>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <?= $form
            ->field($model, 'locale')
            ->dropDownList(\concepture\yii2user\enum\UserRoleEnum::arrayList(), [
                'class' => 'form-control custom-select',
                'prompt' => ''
            ]);
        ?>
    </div>
</div>