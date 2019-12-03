<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use concepture\yii2handbook\converters\LocaleConverter;
use concepture\yii2logic\enum\StatusEnum;
use concepture\yii2logic\enum\IsDeletedEnum;

$this->setTitle($searchModel::label());
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
                        '<i class="icon-file-eye2"></i>' . Yii::t('yii2admin', 'Просмотр'),
                        ['view', 'id' => $model['id']],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('yii2admin', 'Просмотр'),
                            'title' => Yii::t('yii2admin', 'Просмотр'),
                            'data-pjax' => '0'
                        ]
                    );
                },
                'update'=> function ($url, $model) {
                    if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                        return '';
                    }

                    return Html::a(
                        '<i class="icon-pencil6"></i>'. Yii::t('yii2admin', 'Редактировать'),
                        ['update', 'id' => $model['id']],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('yii2admin', 'Редактировать'),
                            'title' => Yii::t('yii2admin', 'Редактировать'),
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
                        '<i class="icon-checkmark4"></i>'. Yii::t('yii2admin', 'Активировать'),
                        ['status-change', 'id' => $model['id'], 'status' => StatusEnum::ACTIVE],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('yii2admin', 'Активировать'),
                            'title' => Yii::t('yii2admin', 'Активировать'),
                            'data-confirm' => Yii::t('yii2admin', 'Активировать ?'),
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
                        '<i class="icon-cross2"></i>'. Yii::t('yii2admin', 'Деактивировать'),
                        ['status-change', 'id' => $model['id'], 'status' => StatusEnum::INACTIVE],
                        [
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('yii2admin', 'Деактивировать'),
                            'title' => Yii::t('yii2admin', 'Деактивировать'),
                            'data-confirm' => Yii::t('yii2admin', 'Деактивировать ?'),
                            'data-method' => 'post',
                        ]
                    );
                },
                'delete'=> function ($url, $model) {
                    if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                        return '';
                    }

                    return Html::a(
                        '<i class="icon-trash"></i>'. Yii::t('yii2admin', 'Удалить'),
                        ['delete', 'id' => $model['id']],
                        [
                            'title' => Yii::t('yii2admin', 'Удалить'),
                            'data-confirm' => Yii::t('yii2admin', 'Удалить ?'),
                            'data-method' => 'post',
                            'class' => 'dropdown-item',
                            'aria-label' => Yii::t('yii2admin', 'Удалить'),
                            'title' => Yii::t('yii2admin', 'Удалить'),
                        ]
                    );
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>
