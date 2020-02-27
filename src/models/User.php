<?php
namespace concepture\yii2user\models;

use concepture\yii2user\enum\UserCredentialTypeEnum;
use concepture\yii2user\helpers\SsoHelper;
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

    /**
     * Users roles array
     * Contains associative array, where key is role and value is instance of UserRoles class
     * Example
     *        [
     *              'admin
     *        ]
     * @var array of UserRoles models
     */
    public $roles=[];

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
                ]
                , 'integer'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'username' => Yii::t('user','Имя пользователя'),
            'logo' => Yii::t('user','Аватар'),
            'description' => Yii::t('user','Описание'),
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
        if (! $cred && SsoHelper::isSsoEnabled()){
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

    /**
     * Возвращает связь для ролей пользователя
     *
     * @return ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'id']);
    }

    /**
     * переопределем обработку связей
     * @param type $name
     * @param type $records
     */
    public function populateRelation($name, $records)
    {
        if($name=='userRoles'){
            $this->roles=[];
            foreach($records as $r){
                $this->roles[] = $r->role;
            }
            return;
        }
        parent::populateRelation($name, $records);
    }
}
