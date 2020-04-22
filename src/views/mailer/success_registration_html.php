<?php
use yii\helpers\Html;

?>
<div class="password-reset">
    <p>Вас приветствует <?php echo \Yii::$app->name?>,</p>
    <p>Привет <?= $form->identity; ?></p>
    <p>Ваш пароль <?= $password; ?></p>
</div>
