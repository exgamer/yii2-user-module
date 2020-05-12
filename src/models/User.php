<?php
namespace concepture\yii2user\models;

use common\pojo\PaymentSystem;
use concepture\yii2logic\pojo\Social;
use common\pojo\Spoiler;
use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\helpers\SsoHelper;
use concepture\yii2user\WebUser;
use Yii;
use yii\base\NotSupportedException;
use concepture\yii2logic\models\ActiveRecord;
use yii\web\IdentityInterface;
use concepture\yii2logic\models\traits\IsDeletedTrait;
use concepture\yii2logic\models\traits\StatusTrait;
use kamaelkz\yii2cdnuploader\traits\ModelTrait;

/**
 * Модель пользователя
 *
 * Class User
 *
 * @property integer $id
 * @property string $username
 * @property integer $locale
 * @property integer $status
 * @property datetime $created_at
 * @property datetime $updated_at
 *
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $allow_physical_delete = false;


    use IsDeletedTrait;
    use StatusTrait;
    use ModelTrait;

    public function behaviors()
    {
        return [
            'JsonFieldsBehavior' => [
                'class' => 'concepture\yii2logic\models\behaviors\JsonFieldsBehavior',
                'jsonAttr' => [
                    'social' => [
                        'class' => Social::class,
                        'uniqueKey' => 'social'
                    ],
                ],
            ],
        ];
    }


    /**
     * Возвращает онлайн ли пользователь
     * метка выставляется  в классе concepture\yii2user\WebUser при обновлении статуса авторизации
     *
     * @return bool
     */
    public function isOnline()
    {
        if (Yii::$app->has('cache') && Yii::$app->cache->get(WebUser::$isActivePrefix . $this->id)) {
            return true;
        }

        return false;
    }

    /**
     * Возвращает дату последнего появления онлайн
     *
     * @return date
     */
    public function getLastSeen()
    {
        return $this->last_seen;
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:label()
     *
     * @return string
     */
    public static function label()
    {
        return Yii::t('user', 'Пользователи');
    }

    /**
     * @see \concepture\yii2logic\models\ActiveRecord:toString()
     * @return string
     */
    public function toString()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'string', 'min' => 2, 'max' => 100],
            ['website', 'string', 'max' => 255],
            [
                [
                    'logo',
                    'description',
                ],
                'string',
                'max' => 1024
            ],
            [
                [
                    'status',
                    'locale',
                    'famous',
                ]
                , 'integer'
            ],
            [
                [
                    'social',
                ],
                'safe'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'username' => Yii::t('user','Имя пользователя'),
            'website' => Yii::t('user','Сайт'),
            'social' => Yii::t('user','Социальные сети'),
            'logo' => Yii::t('user','Аватар'),
            'description' => Yii::t('user','Описание'),
            'famous' => Yii::t('common','Известный'),
            'status' => Yii::t('user','Статус'),
            'locale' => Yii::t('user',' Язык'),
            'created_at' => Yii::t('user', 'Дата создания'),
            'updated_at' => Yii::t('user', 'Дата обновления'),
            'is_deleted' => Yii::t('user','Удален'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return Yii::$app->userService->findById($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return Yii::$app->userService->getOneByCondition(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        $cred = $this->getUserCredentialService()->findByType($this->id, UserCredentialTypeEnum::EMAIL);
        if (! $cred || (! $cred && SsoHelper::isSsoEnabled())){
            return null;
        }

        return $cred->validation;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return UserCredentialService
     */
    public function getUserCredentialService()
    {
        return Yii::$app->userCredentialService;
    }
}
