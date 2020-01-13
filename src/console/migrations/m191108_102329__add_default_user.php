<?php

use yii\db\Migration;
use concepture\yii2user\forms\SignUpForm;
use concepture\yii2user\forms\UserRoleHandbookForm;
use concepture\yii2user\forms\UserRoleForm;
use concepture\yii2user\enum\UserRoleEnum;

/**
 * Class m190706_141342__add_default_user
 */
class m191108_102329__add_default_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->language = "ru";
        $form = new SignUpForm();
        $form ->username = "admin";
        $form->identity = "admin@example.com";
        $form->validation = "123456";
        $model = Yii::$app->authService->signUp($form);
        $form = new UserRoleForm();
        $form->role = UserRoleEnum::ADMIN;
        $form->user_id = $model->id;
        Yii::$app->userRoleService->create($form);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190706_141342__add_default_user cannot be reverted.\n";

        return false;
    }
}
