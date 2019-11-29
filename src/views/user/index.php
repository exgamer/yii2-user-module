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
        'filterModel' => $searchModel,
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
                            '<i class="file-eye2"></i>',
                            ['view', 'id' => $model['id']],
                            ['data-pjax' => '0']
                        );
                    },
                    'update'=> function ($url, $model) {
                        if ($model['is_deleted'] == IsDeletedEnum::DELETED){
                            return '';
                        }

                        return Html::a(
                            '<i class="pencil6"></i>',
                            ['update', 'id' => $model['id']],
                            ['data-pjax' => '0']
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
                            '<i class="glyphicon glyphicon-ok"></i>',
                            ['status-change', 'id' => $model['id'], 'status' => StatusEnum::ACTIVE],
                            [
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
                            '<i class="glyphicon glyphicon-remove"></i>',
                            ['status-change', 'id' => $model['id'], 'status' => StatusEnum::INACTIVE],
                            [
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
                            '<i class="bin2"></i>',
                            ['delete', 'id' => $model['id']],
                            [
                                'title' => Yii::t('user', 'Удалить'),
                                'data-confirm' => Yii::t('user', 'Удалить ?'),
                                'data-method' => 'post',
                            ]
                        );
                    }
                ]
            ],
        ],
    ]); ?>

<?php Pjax::end(); ?>
