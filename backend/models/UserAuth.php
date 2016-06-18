<?php
namespace backend\models;

//use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class UserAuth extends ActiveRecord implements IdentityInterface
{
 
    // Роли пользователей
    const FUND_USER = 'ТФОМС';
    const INSURED_USER = 'СМО';

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

    public function isTfomsRole($id) {
        
        $query = new \yii\db\Query();
        $orgType = $query->select('com.text')
                         ->from('ref_users u')
                         ->innerJoin('ref_company cnt','u.company_id = cnt.company_id')
                         ->innerJoin('ref_common com', 'cnt.type_ref_id = com.ref_id')
                         ->where(['user_id' => $id])->one();
        
        if ($orgType) {
            if ($orgType['text'] == 'ТФОМС') {
                return true;
            } else 
                return false;
        }
        
        return true;
        
    }
    
}
