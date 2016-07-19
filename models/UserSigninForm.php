<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Userbasicinfo;
use app\helpers\Tool;

class UserSigninForm extends Model
{
    //public $id;
	private $user;
	
	public $phone;
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
	
	/*
	 * 查询用户信息
	 */
	private function checkuser( $phone, $password )
	{
		$user = new Userbasicinfo();
		//查询数据表中该号码的个数
		$only = $user::find()
			->where( ['phone'=>$phone] )
			->all();
		
		if( password_verify($password, $only[0]->password) ){
			$session = Yii::$app->session;
			$session['user'] = [
				'id' => $only[0]->id,
				'permissions' => $only[0]->permissions,
				'phone' => $only[0]->phone,
				'password' => $only[0]->password,
				'mask' => $only[0]->mask,
				'name' => $only[0]->name,
				'sex' => $only[0]->sex,
				'birthday' => $only[0]->birthday,
			];
			$this->user = $session['user'];
			return true;
		}
		
		return false;
	}
	
	/*
	 * 登录
	 */
	public function signin(){
		
		if( $this->checkuser( $this->phone, $this->password ) ){
			return Tool::return_json( true, 'user', array_diff_assoc($this->user, ['password' => $this->user['password']]) );
		}
		
		return Tool::return_json( false, 'message', '密码错误' );
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