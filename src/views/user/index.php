<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use concepture\yii2handbook\converters\LocaleConverter;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\enum\IsDeletedEnum;

$this->setTitle(Yii::t('user', 'Пользователи'));
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader();
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
        'username',
        [
            'attribute'=>'locale',
            'filter'=> Yii::$app->localeService->catalog(),
            'value'=>function($data) {
                return LocaleConverter::value($data->locale);
            }
        ],
        [
            'attribute'=>'domain_id',
            'filter'=> Yii::$app->domainService->catalog(),
            'value'=>function($data) {
                return $data->getDomainName();
            }
        ],
        [
            'attribute'=>'status',
            'filter'=> StatusEnum::arrayList(),
            'value'=>function($data) {
                return $data->statusLabel();
            }
        ],
        'created_at',
        'updated_at',
        [
            'attribute'=>'is_deleted',
            'filter'=> IsDeletedEnum::arrayList(),
            'value'=>function($data) {
                return $data->isDeletedLabel();
            }
        ],

        [
            'class'=>'yii\grid\ActionColumn',
            'template'=>'{view} {update} {activate} {deactivate} {delete}',
            'buttons'=>[
                'view'=> function ($url, $model) {
                    return Html::a(
                        '<i class="icon-file-eye2"></i>' . Yii::t('user', 'Просмотр'),
                        ['view', 'id' => $model['id']],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('user', 'Просмотр'),
                            'title' => Yii::t('user', 'Просмотр'),
                            'data-pjax' => '0'
                        ]
                    );
                },
                'update'=> function ($url, $model) {
                    if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                        return '';
                    }

                    return Html::a(
                        '<i class="icon-pencil6"></i>'. Yii::t('user', 'Редактировать'),
                        ['update', 'id' => $model['id']],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('user', 'Редактировать'),
                            'title' => Yii::t('user', 'Редактировать'),
                            'data-pjax' => '0'
                        ]
                    );
                },
                'activate'=> function ($url, $model) {
                    if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                        return '';
                    }

                    if ($model['status'] == StatusEnum::ACTIVE){
                        return '';
                    }

                    return Html::a(
                        '<i class="icon-checkmark4"></i>'. Yii::t('user', 'Активировать'),
                        ['status-change', 'id' => $model['id'], 'status' => StatusEnum::ACTIVE],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('user', 'Активировать'),
                            'title' => Yii::t('user', 'Активировать'),
                            'data-confirm' => Yii::t('user', 'Активировать ?'),
                            'data-method' => 'post',
                        ]
                    );
                },
                'deactivate'=> function ($url, $model) {
                    if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                        return '';
                    }
                    if ($model['status'] == StatusEnum::INACTIVE){
                        return '';
                    }
                    return Html::a(
                        '<i class="icon-cross2"></i>'. Yii::t('user', 'Деактивировать'),
                        ['status-change', 'id' => $model['id'], 'status' => StatusEnum::INACTIVE],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('user', 'Деактивировать'),
                            'title' => Yii::t('user', 'Деактивировать'),
                            'data-confirm' => Yii::t('user', 'Деактивировать ?'),
                            'data-method' => 'post',
                        ]
                    );
                },
                'delete'=> function ($url, $model) {
                    if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                        return '';
                    }

                    return Html::a(
                        '<i class="icon-trash"></i>'. Yii::t('user', 'Удалить'),
                        ['delete', 'id' => $model['id']],
                        [
                            'title' => Yii::t('user', 'Удалить'),
                            'data-confirm' => Yii::t('user', 'Удалить ?'),
                            'data-method' => 'post',
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('user', 'Удалить'),
                            'title' => Yii::t('user', 'Удалить'),
                        ]
                    );
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>
