<?php


namespace concepture\yii2user\components\twig;

use concepture\yii2user\helpers\SsoHelper;
use Yii;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Class JwtHelperExtension
 * @package concepture\yii2user\components\twig
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class JwtHelperExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('jwt_token', function(array $array = []) {
                return SsoHelper::getSsoJwtToken($array);
            }),
        ];
    }
}