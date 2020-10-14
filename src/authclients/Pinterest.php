<?php

namespace concepture\yii2user\authclients;

use yii\authclient\OAuth2;

/**
 * Class Pinterest
 * @package concepture\yii2user\authclients
 *
 * Register your application in Pinterest  https://www.pinterest.com/login/?next=http%3A%2F%2Fdevelopers.pinterest.com%2Fapps%2F
 *
 *   'components' => [
 *       'authClientCollection' => [
 *           'class' => 'yii\authclient\Collection',
 *           'clients' => [
     *           'pinterest' => [
         *           'class' => 'isudakoff\authclient\Pinterest',
         *           'clientId' => 'pinterest_app_id',
         *           'clientSecret' => 'pinterest_app_secret',
     *           ],
 *           ],
 *       ],
 *   ]
 *
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class Pinterest extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.pinterest.com/oauth/';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.pinterest.com/v1/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.pinterest.com/v1';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = 'read_public';
        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET');
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'pinterest';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Pinterest';
    }
}