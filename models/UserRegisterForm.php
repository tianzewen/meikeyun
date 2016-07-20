<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Userbasicinfo;
use app\helpers\Tool;
use app\alidayu\Alidayu;

class UserRegisterForm extends Model
{
    //public $id;
	private $user;
	
	public $phone;
	public $icode;
    public $password;

    public function rules()
    {
        return [
            [['phone', 'password'], 'required'],
			['icode', 'default', 'value' => null],
			['phone', 'unique', 'targetClass' => 'app\commands\Userbasicinfo', 'message' => '电话号码已存在'],
			['phone', 'string', 'length' => [11]],
			['password', 'string', 'length' => [8, 16]],
        ];
    }
	
	/*
	 * 发送注册验证码并生成session
	 */
	public function makeicode( $phone ){
		if( $this->checkphone($phone) ){
			//生成随机数，创建session，发送短信
			$alidayu = new Alidayu;
			return $alidayu->smsAlidayu( $phone );
		}
		//如果个数不为0则返回错误信息
		return Tool::return_json( false, 'phone', '手机号码已存在' );
	}
	
	/*
	 * 手机号查重
	 */
	public function checkphone( $phone )
	{
		$user = new Userbasicinfo();
		//查询数据表中该号码的个数
		$only = $user::find()
			->where( ['phone'=>$phone] )
			->count();
		if( $only==0 ){
			return true;
		}
		return false;
	}
	
	/*
	 * 注册
	 */
	public function regitser()
	{
		//查看手机号是否已生成验证码
		if( !isset(Yii::$app->session['tel_'.$this->phone]) || empty(Yii::$app->session['tel_'.$this->phone]) ){
			return Tool::return_json( false, 'icode', '验证码失效，请重新获取验证码' );
		}
		//用户输入密码是否与生成的验证码一致
		if( $this->icode != Yii::$app->session['tel_'.$this->phone]['icode']){
			return Tool::return_json( false, 'icode', '验证码错误' );
		}
		//实例化userbasicinfo的AR
		$user = new Userbasicinfo();
		$user->setAttributes([
			'id' => Tool::timeToMillisecond(microtime()),
			//默认权限普通用户
			'permissions' => 'user',
			'phone' => $this->phone,
			//哈希双向加密
			'password' => Yii::$app->getSecurity()->generatePasswordHash($this->password),
		]);
		//添加用户信息
		if( $user->save() ){
			return Tool::return_json( true );
		}
		
		return Tool::return_json( false, 'message', '数据库保存失败' );
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