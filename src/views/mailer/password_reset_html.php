<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([$route, 'token' => $token]);
?>
<div class="password-reset">
    <p>Вас приветствует <?php echo \Yii::$app->name?>,</p>

    <p>Пройдите по ссылке ниже, для смены пароля:</p>

    <p><?= Html::a("Смена пароля", $resetLink) ?></p>
</div>
