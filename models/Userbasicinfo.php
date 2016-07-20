<?php

namespace app\models;

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
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
	
	public $icode;
	
	public function rules()
	{
		return [
			// 在任何场景下都需要验证
			['phone', 'string', 'length' => [11]],
			['password', 'string', 'length' => [8, 16]],
            [['birthday', 'sex', 'permissions'], 'safe'],
			[['name'], 'string', 'length'=>[1,12]],
            [['mask'], 'string', 'max' => 60],
			
			// 在"register" 场景下 phone 和 password 必须有值
			[['phone', 'password'], 'required', 'on' => 'register'],
            [['phone'], 'unique'],

			// 在 "login" 场景下 phone 、 icode 和 password 必须有值
			[['phone', 'password'], 'required', 'on' => 'login'],
			['phone', 'exist', 'on' => 'login'],
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

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['phone', 'password'],
            self::SCENARIO_REGISTER => ['phone', 'password'],
        ];
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
	
	/***
	 * 注册
	 */
	public function register()
	{
		//查看手机号是否已生成验证码
		// if( !isset(Yii::$app->session['tel_'.$this->phone]) || empty(Yii::$app->session['tel_'.$this->phone]) ){
			// return Tool::return_json( false, 'icode', '验证码失效，请重新获取验证码' );
		// }
		//用户输入密码是否与生成的验证码一致
		// if( $this->icode != Yii::$app->session['tel_'.$this->phone]['icode']){
			// return Tool::return_json( false, 'icode', '验证码错误' );
		// }
		
		$this->id = Tool::timeToMillisecond(microtime());
		$this->permissions = 'user';
		$this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
		//添加用户信息
		if( $this->save() ){
			return Tool::return_json( true );
		}
		var_dump($this->getErrors());
		return Tool::return_json( false, 'message', '数据库保存失败' );
	}
	
	/***
	 * 登录
	 */
	public function login()
	{
		$identity = self::findOne(['phone' => $this->phone]);
		
		if(Yii::$app->getSecurity()->validatePassword($this->password, $identity->password))
		{
			Yii::$app->user->login($identity);
			return Tool::return_json( true );
		}
	}

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
