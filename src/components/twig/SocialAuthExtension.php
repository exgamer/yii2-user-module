<?php


namespace concepture\yii2user\components\twig;

use Yii;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Class SocialAuthExtension
 * @package concepture\yii2user\components\twig
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class SocialAuthExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('social', [$this, 'getSocialAuth'] , [ 'is_safe' => ['html'] ]),
        ];
    }

    public function getSocialAuth()
    {
        return yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['site/auth'],
        ]);
    }
}