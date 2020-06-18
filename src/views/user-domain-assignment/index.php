<?php

use concepture\yii2logic\enum\AccessTypeEnum;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\lists\grid\EditableColumn;
use kamaelkz\yii2admin\v1\enum\FlashAlertEnum;
use kamaelkz\yii2admin\v1\widgets\notifications\alert\Alert;

$this->setTitle(Yii::t('yii2admin', 'Доступные версии'));
$this->pushBreadcrumbs(['label' => Yii::t('yii2admin', 'Список пользователей'), 'url' => ['user/index']]);
$this->pushBreadcrumbs($this->title);
$this->viewHelper()->pushPageHeader(['user/index'], Yii::t('yii2admin', 'Список пользователей'),'icon-list');

?>
<?php Pjax::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">
                        <?= Yii::t('yii2admin', 'Список версий'); ?>
                    </h5>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $domainsDataProvider,
                'searchVisible' => false,
                'columns' => [
                    [
                        'attribute' => 'country_caption',
                        'label' => Yii::t('yii2admin', 'Версия'),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'dropdown' => false,
                        'template' => '{create}',
                        'buttons' => [
                            'create' => function ($url, $model) use ($user_id) {
                                return Html::a(
                                    '<i class="icon-checkmark3"></i>',
                                    [
                                        '/user/user-domain-assignment/create',
                                        'user_id' => $user_id,
                                        'domain_id' => $model['domain_id'],
                                        'access' => AccessTypeEnum::READ
                                    ],
                                    [
                                        'class' => 'admin-action list-icons-item',
                                        'title' => Yii::t('yii2admin', 'Назначить'),
                                        'data-pjax-id' => 'list-pjax',
                                        'data-pjax-url' => Url::current([], true),
                                    ]
                                );
                            },
                        ],
                    ]
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">
                        <?= Yii::t('yii2admin', 'Список доступных версий'); ?>
                    </h5>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $userDomainsDataProvider,
                'searchVisible' => false,
                'columns' => [
                    [
                        'attribute' => 'country_caption',
                        'label' => Yii::t('yii2admin', 'Версия'),
                    ],
                    [
                        'label'=> Yii::t('yii2admin', 'Текущий доступ'),
                        'value'=>function($data) {
                            return AccessTypeEnum::label($data['access']);
                        }
                    ],
                    [
                        'label'=> Yii::t('yii2admin', 'Смена доступа'),
                        'format' => 'raw',
                        'value'=>function($data) use ($user_id) {
                            if ($data['access'] == AccessTypeEnum::READ) {
                                return Html::a(
                                    '<i class="icon-pencil3"></i>',
                                    ['/user/user-domain-assignment/create', 'user_id' => $user_id, 'domain_id' => $data['domain_id'], 'access' => AccessTypeEnum::WRITE],
                                    [
                                        'class' => 'admin-action list-icons-item',
                                        'title' => Yii::t('backend', 'Дать доступ на редактирование'),
                                        'data-pjax-id' => 'list-pjax',
                                        'data-pjax-url' => Url::current([], true),
                                        'data-swal' => Yii::t('yii2admin', 'Дать доступ на редактирование'),
                                    ]
                                );
                            }

                            if ($data['access'] == AccessTypeEnum::WRITE) {
                                return Html::a(
                                    '<i class="icon-cross2"></i>',
                                    ['/user/user-domain-assignment/delete', 'user_id' => $user_id, 'domain_id' => $data['domain_id'], 'access' => AccessTypeEnum::WRITE],
                                    [
                                        'class' => 'admin-action list-icons-item',
                                        'title' => Yii::t('backend', 'Удалить доступ для редактирования'),
                                        'data-pjax-id' => 'list-pjax',
                                        'data-pjax-url' => Url::current([], true),
                                        'data-swal' => Yii::t('yii2admin' , 'Удалить доступ для редактирования'),
                                    ]
                                );
                            }
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'dropdown' => false,
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $data) use ($user_id) {
                                return Html::a(
                                    '<i class="icon-cross2"></i>',
                                    ['/user/user-domain-assignment/delete', 'user_id' => $user_id, 'domain_id' => $data['domain_id'], 'access' => $data['access']],
                                    [
                                        'class' => 'admin-action list-icons-item',
                                        'title' => Yii::t('backend', 'Удалить'),
                                        'data-pjax-id' => 'list-pjax',
                                        'data-pjax-url' => Url::current([], true),
                                        'data-swal' => Yii::t('yii2admin' , 'Удалить'),
                                    ]
                                );
                            },
                        ],
                    ]
                ]
            ]); ?>
        </div>
    </div>

<?php Pjax::end(); ?>