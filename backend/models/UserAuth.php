<?php
namespace backend\models;

//use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class UserAuth extends ActiveRecord implements IdentityInterface
{
 
    // Роли пользователей
    const FUND_USER = 100;
    const INSURED_USER = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_users';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public static function findByUsername($username)
    {
        return self::findOne(['login'=>$username]);
    }
    
    public function getId() {
	return $this->user_id;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        //return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

}
