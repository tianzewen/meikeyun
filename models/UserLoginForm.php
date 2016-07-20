<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Userbasicinfo;
use app\helpers\Tool;

class UserLoginForm extends Model
{
	public $phone;
	public $icode;
	public $password;
	
    public function rules()
    {
        return [
            [['phone', 'password'], 'required'],
			['phone', 'string', 'length' => [11]],
			['phone', 'exist', 'targetClass' => 'app\commands\Userbasicinfo', 'message' => '账号不存在'],
			['password', 'string', 'length' => [8, 16]],
        ];
    }
	
	/**
	 * 登录
	 */
	public function login()
	{
		$user = new Userbasicinfo;
		$identity = $user::findOne(['phone' => $this->phone]);
		
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
            'password' => '密码',
            'phone' => '账号',
        ];
    }
}