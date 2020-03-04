<?php

namespace concepture\yii2user\authclients;

use yii\authclient\InvalidResponseException;
use yii\authclient\OAuth2;

/**
 * https://api.mail.ru/docs/guides/oauth/sites/
 *
 * In order to use Mail.ru OAuth you must register your application at <http://api.mail.ru/sites/my/add>.
 *
 * @see http://api.mail.ru/sites/my/add/
 * @see http://api.mail.ru/sites/my/
 * @see http://api.mail.ru/docs/reference/rest/users-getinfo/
 *
 * @author Ilya Sudakov <isudakoff@gmail.com>
 */
class MailRu extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://connect.mail.ru/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://connect.mail.ru/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'http://www.appsmail.ru/platform/api?method=';

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $request = $this->createApiRequest()->setMethod('GET')->setUrl('users.getInfo');
        $response = $request->send();
        $response->setFormat('json');

        if ($response->isOk && $response->data && $response->data['0']) {
            return $response->data['0'];
        }

        throw new InvalidResponseException($response);
    }

    /**
     * @inheritdoc
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        parent::applyAccessTokenToRequest($request, $accessToken);

        $data = $request->getData();

        $data['method'] = str_replace('/', '', $request->getUrl());
        $data['uids'] = $accessToken->getParam('x_mailru_vid');
        $data['app_id'] = $this->clientId;
        $data['secure'] = 1;
        $data['sig'] = $this->sig($data, $this->clientSecret);

        $request->setUrl('');
        $request->setData($data);
    }

    /**
     * Generate signature for API mail.ru
     *
     * @return string
     */
    public function sig(array $request_params, $secret_key) {
        ksort($request_params);
        $params = '';

        foreach ($request_params as $key => $value) {
            $params .= "$key=$value";
        }

        return md5($params . $secret_key);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'mailru';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'MailRu';
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'id' => 'uid'
        ];
    }
}