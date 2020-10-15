<?php
use kamaelkz\yii2cdnuploader\widgets\CdnUploader;
use kamaelkz\yii2cdnuploader\widgets\Uploader;
use concepture\yii2handbook\enum\TargetAttributeEnum;
use kamaelkz\yii2admin\v1\modules\uikit\enum\UiikitEnum;
use kamaelkz\yii2cdnuploader\enum\StrategiesEnum;
use kamaelkz\yii2cdnuploader\enum\UploaderTypeEnum;
?>

<?php if (isset(Yii::$app->params['concepture']['uploader']['type']) && Yii::$app->params['concepture']['uploader']['type'] === UploaderTypeEnum::LOCAL):?>
    <?= $form
        ->field($model, $attribute)
        ->widget(Uploader::class, [
            'model' => $model,
            'modelId' => isset($originModel) ? $originModel->id : null,
            'url' => 'image-upload',
            'attribute' => $attribute,
            //                                    'width' => 313,
            //                                    'height' => 235,
            'options' => [
                'plugin-options' => [
                    # todo: похоже не пашет
                    'maxFileSize' => 2000000,
                ]
            ],
            'clientEvents' => [
                'fileuploaddone' => new \yii\web\JsExpression('function(e, data) {
                                                        console.log(e);
                                                    }'),
                'fileuploadfail' => new \yii\web\JsExpression('function(e, data) {
                                                        console.log(e);
                                                    }'),
            ],
        ])
        ->error(false)
        ->hint(false);
    ?>

<?php else: ?>
    <?= $form
        ->field($model, $attribute)
        ->widget(CdnUploader::class, [
            'model' => $model,
            //                        'modelId' => isset($originModel) ? $originModel->id : null,
            //                        'url' => 'image-upload',
            'attribute' => $attribute,
            'strategy' => StrategiesEnum::BY_REQUEST,
            'resizeBigger' => false,
            //                                    'width' => 313,
            //                                    'height' => 235,
            'options' => [
                'plugin-options' => [
                    # todo: похоже не пашет
                    'maxFileSize' => 2000000,
                ]
            ],
            'clientEvents' => [
                'fileuploaddone' => new \yii\web\JsExpression('function(e, data) {
                                                        console.log(e);
                                                    }'),
                'fileuploadfail' => new \yii\web\JsExpression('function(e, data) {
                                                        console.log(e);
                                                    }'),
            ],
        ])
        ->error(false)
        ->hint(false);
    ?>
<?php endif;?>
