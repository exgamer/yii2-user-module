<div class="access-as-user">
    <?= Yii::t('common', '{admin} <br/> просматривает как <br/> {user}', ['admin' => $user->username, 'user' => $username])?>
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
        z-index: 1000;
        text-align: center;
        padding-left: 5px;
        padding-right: 5px;
    }
</style>