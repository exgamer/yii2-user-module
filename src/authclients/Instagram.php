<?php

namespace concepture\yii2user\authclients;

use yii\authclient\OAuth2;

/**
 * Как зарегать приложение
 * https://developers.facebook.com/docs/instagram-basic-display-api/getting-started
 *
 * Class Instagram
 * @package concepture\yii2user\authclients
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class Instagram extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.instagram.com/oauth/authorize';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.instagram.com/v1';

    public $scope = 'user_profile';
    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $response = $this->api('users/self', 'GET');

        return $response['data'];
    }

    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        return $this->sendRequest($method, $url . '?access_token=' . $accessToken->getToken(), $params, $headers);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'instagram';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Instagram';
    }
}