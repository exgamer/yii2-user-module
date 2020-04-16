<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kamaelkz\yii2admin\v1\widgets\formelements\Pjax;
use kamaelkz\yii2admin\v1\widgets\lists\grid\EditableColumn;
use kamaelkz\yii2admin\v1\enum\FlashAlertEnum;
use kamaelkz\yii2admin\v1\widgets\notifications\alert\Alert;

$this->setTitle($title);
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
                        <?= $left_side_header; ?>
                    </h5>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $rolesDataProvider,
                'searchVisible' => true,
                'searchCollapsed' => false,
                'searchParams' => [
                    'model' => $roleSearchModel,
                ],
                'columns' => [
                    [
                        'attribute' => 'name',
                        'label' => $item_caption,
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
                                        '/user/user-auth-assignment/create',
                                        'user_id' => $user_id,
                                        'role' => $model->name
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
                        <?= $right_side_header; ?>
                    </h5>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $userRolesDataProvider,
                'searchVisible' => false,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'label' => $item_caption,
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'dropdown' => false,
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $data) use ($user_id) {
                                return Html::a(
                                    '<i class="icon-cross2"></i>',
                                    ['/user/user-auth-assignment/delete', 'user_id' => $user_id, 'role' => $data->name],
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