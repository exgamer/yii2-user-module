<?php
namespace concepture\yii2user\models;

use concepture\yii2user\enum\UserCredentialTypeEnum;
use Yii;
use yii\base\NotSupportedException;
use concepture\yii2logic\models\ActiveRecord;
use yii\web\IdentityInterface;
use concepture\yii2domain\models\traits\DomainTrait;

/**
 * Модель пользователя
 *
 * Class User
 *
 * @property integer $id
 * @property string $username
 * @property integer $locale
 * @property integer $domain_id
 * @property datetime $created_at
 * @property datetime $updated_at
 *
 * @package concepture\yii2user\models
 * @author Olzhas Kulzhambekov <exgamer@live.ru>
 */
class User extends ActiveRecord implements IdentityInterface
{
    use DomainTrait;
    
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
                    'locale',
                    'domain_id'
                ]
                , 'integer'
            ],
            ['username', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', '#'),
            'username' => Yii::t('user',' Имя пользователя'),
            'locale' => Yii::t('user',' Язык'),
            'domain_id' => Yii::t('user',' Домен'),
            'created_at' => Yii::t('user', 'Дата создания'),
            'updated_at' => Yii::t('user', 'Дата обновления')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
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
        return static::findOne(['username' => $username]);
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
        return $this->getUserCredentialService()->findByType($this->id, UserCredentialTypeEnum::EMAIL);
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
    public function getRoles()
    {
        return $this->hasMany(
            UserRoleHandbook::className(),
            ['id'=>'id_role'])
            ->viaTable(UserRole::tableName(),['user_id'=>'id']);
    }

    /**
     * переопределем обработку связей
     * @param type $name
     * @param type $records
     */
    public function populateRelation($name, $records)
    {
        if($name=='roles'){
            $this->roles=[];
            foreach($records as $r){
                $this->roles[] = $r->name;
            }
            return;
        }
        parent::populateRelation($name, $records);
    }
}
