<?php

use yii\db\Migration;

/**
 * Class m200415_101718_user_role_transfer
 */
class m200415_101718_user_role_transfer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $roles = $this->db->createCommand('SELECT * FROM user_role')->queryAll();
        $insert = [];
        foreach ($roles as $role){
            $data = [
                str_replace("_", "", $role['role']),
                $role['user_id'],
            ];

            $insert [] = "(" . implode(',', $data) . ")";
        }
        $this->execute("INSERT INTO user_auth_assignment (item_name, user_id) VALUES " . implode(",", $insert));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200415_101718_user_role_transfer cannot be reverted.\n";

        return false;
    }
}
