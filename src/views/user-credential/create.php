<?php
use yii\helpers\Html;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\formelements\activeform\ActiveForm;

$this->setTitle(Yii::t('yii2admin', 'Новая запись'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', \concepture\yii2user\models\User::label()), 'url' => ['/user/user/index']]);
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', 'Авторизационные данные пользователя ' . $user->username), 'url' => ['index', 'user_id' => $model->user_id]]);
$this->pushBreadcrumbs($this->title);
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
                <?= $form->field($model, 'identity')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?= $form->field($model, 'validation')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?= $form
                    ->field($model, 'domain_id')
                    ->dropDownList(Yii::$app->domainService->catalog(), [
                        'class' => 'form-control custom-select',
                        'prompt' => Yii::t('yii2admin', 'Выберите домен')
                    ]);
                ?>
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
