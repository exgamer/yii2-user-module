<?php
use yii\helpers\Html;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\formelements\activeform\ActiveForm;

$this->setTitle(Yii::t('yii2admin', 'Заблокировать ' . $originModel->identity));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', \concepture\yii2user\models\User::label()), 'url' => ['/user/user/index']]);
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', 'Авторизационные данные пользователя ' . $user->username), 'url' => ['index', 'user_id' => $originModel->user_id]]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['create', 'user_id' => $originModel->user_id]);
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
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?= $form
                    ->field($model, 'domain_id')
                    ->dropDownList($domainsArray, [
                        'class' => 'form-control custom-select',
                        'prompt' => ''
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

