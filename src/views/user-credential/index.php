<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use yii\helpers\Url;
use concepture\yii2logic\enum\StatusEnum;

$this->setTitle(Yii::t('yii2admin', 'Авторизационные данные пользователя ' . $user->username));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', \concepture\yii2user\models\User::label()), 'url' => ['/user/user/index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['create', 'user_id' => $searchModel->user_id]);
?>
<?php Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'searchVisible' => true,
    'searchParams' => [
        'model' => $searchModel
    ],
    'columns' => [
        'id',
//        [
//            'label' => Yii::t('yii2admin', 'Пользователь'),
//            'attribute' => 'username',
//            'value' => 'user.username'
//        ],
        'identity',
        [
            'attribute'=>'status',
            'filter'=> StatusEnum::arrayList(),
            'value'=>function($data) {
                return $data->statusLabel();
            }
        ],
        [
            'attribute'=>'domain_id',
            'value'=>function($data) {
                return $data->getDomainName();
            }
        ],
        'created_at',
        'updated_at',
        [
            'class'=>'yii\grid\ActionColumn',
            'template'=>'{update} {activate} {deactivate}',
            'buttons'=>[
                'update'=> function ($url, $model) {

                    return Html::a(
                        '<i class="icon-pencil6"></i>'. Yii::t('yii2admin', 'Смена пароля'),
                        ['update', 'id' => $model['id']],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('yii2admin', 'Смена пароля'),
                            'title' => Yii::t('yii2admin', 'Смена пароля'),
                            'data-pjax' => '0'
                        ]
                    );
                },
                'activate'=> function ($url, $model) {
                    if ($model['status'] == StatusEnum::ACTIVE){
                        return '';
                    }

                    return Html::a(
                        '<i class="icon-checkmark4"></i>'. Yii::t('yii2admin', 'Активировать'),
                        ['status-change', 'id' => $model['id'], 'status' => StatusEnum::ACTIVE],
                        [
                            'class' => 'admin-action dropdown-item',
                            'data-pjax-id' => 'list-pjax',
                            'data-pjax-url' => Url::current([], true),
                            'data-swal' => Yii::t('yii2admin' , 'Активировать'),
                        ]
                    );
                },
                'deactivate'=> function ($url, $model) {
                    if ($model['status'] == StatusEnum::INACTIVE){
                        return '';
                    }
                    return Html::a(
                        '<i class="icon-cross2"></i>'. Yii::t('yii2admin', 'Деактивировать'),
                        ['status-change', 'id' => $model['id'], 'status' => StatusEnum::INACTIVE],
                        [
                            'class' => 'admin-action dropdown-item',
                            'data-pjax-id' => 'list-pjax',
                            'data-pjax-url' => Url::current([], true),
                            'data-swal' => Yii::t('yii2admin' , 'Деактивировать'),
                        ]
                    );
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>
