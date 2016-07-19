<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Userbasicinfo;
use app\helpers\Tool;

class UserEditpermissionsForm extends Model
{
    //public $id;
	private $user;
	
	public $phone;

    public function rules()
    {
        return [
            [['phone'], 'required'],
			['phone', 'string', 'length' => [11]],
			['phone', 'exist', 'targetClass' => 'app\commands\Userbasicinfo', 'message' => '账号不存在'],
        ];
    }
	
	/*
	 * Maker权限
	 */
	private function makerPermissions( $phone )
	{
		$user = new Userbasicinfo();
		$user = Userbasicinfo::findOne(['phone' => $phone]);
		$user->permissions = 'maker';
		
		if( $user->update() ){
			$session = Yii::$app->session;
			$_SESSION['user']['permissions'] = 'maker';
			return true;
		}
		
		return false;
	}
	
	/*
	 * 权限更改成Maker
	 */
	public function permissionsToMaker(){
		
		$session = Yii::$app->session;
		
		if( !isset($session['user']) || empty($session['user']) ){
			return Tool::return_json( false, 'user', '请登录后操作' );
		}
		
		if( $this->makerPermissions($this->phone) ){
			return Tool::return_json( true, 'permissions', 'maker' );
		}
		
		return Tool::return_json( false, 'change', '申请失败' );
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