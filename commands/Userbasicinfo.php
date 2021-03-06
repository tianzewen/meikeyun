<?php

namespace app\commands;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use app\helpers\Tool;

class Userbasicinfo extends ActiveRecord implements IdentityInterface
{
	public static function tableName()
	{
		return 'userbasicinfo';
	}
	
	public function rules()
	{
        return [
            [['id', 'password', 'phone'], 'required'],
            [['id'], 'integer'],
            [['birthday', 'sex', 'permissions'], 'safe'],
			[['name'], 'string', 'length'=>[1,12]],
            [['password', 'mask'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 11],
            [['phone'], 'unique'],
        ];
	}

    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        // return static::findOne($id);
		$temp = parent::find()->where(['id'=>$id])->one();
		return isset($temp)?new static($temp):null;
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
	
	// public function beforeSave($insert)
    // {
        // if (parent::beforeSave($insert)) {
            // if ($this->isNewRecord) {
                // $this->auth_key = \Yii::$app->security->generateRandomString();
            // }
            // return true;
        // }
        // return false;
    // }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => '密码',
            'phone' => '账号',
            'name' => '姓名',
            'mask' => '头像',
            'sex' => '性别',
            'birthday' => '出生日期',
        ];
    }
}