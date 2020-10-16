<div class="access-as-user">
    <?= Yii::t('common', 'Вы просматриваете сайт как <br/> {user}' , ['user' => $username]); ?>
</div>
<div class="access-as-user-actions">
    <?= \yii\helpers\Html::a(Yii::t('common', 'Выход'), ['/site/logout'], ['class' => 'link' ,'style' => 'color: #CE272D;']); ?>
</div>
<style>
    .access-as-user{
        position: fixed;
        right: 0;
        top: 0;
        display: flex;
        height: 4rem;
        background-color: #eee;
        border-radius: 0 0 0 3px;
        z-index: 2000;
        text-align: center;
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 5px;
        opacity: 0.7;
        color: #000000;
    }

    .access-as-user-actions{
        position: fixed;
        right: 0;
        top: 4rem;
        display: flex;
        height: 2rem;
        background-color: #eee;
        border-radius: 0 0 0 3px;
        z-index: 2000;
        text-align: center;
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 5px;
        opacity: 0.7;
        color: #000000;
    }
</style>