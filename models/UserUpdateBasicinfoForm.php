<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Userbasicinfo;
use app\helpers\Tool;

class UserUpdateBasicinfoForm extends Model
{
    //public $id;
	private $user;
	
	public $phone;
	public $name;
	public $sex;
    public $mask;

    public function rules()
    {
        return [
            [['phone'], 'required'],
			['phone', 'string', 'length' => [11]],
			['phone', 'exist', 'targetClass' => 'app\commands\Userbasicinfo', 'message' => '账号不存在'],
            [['sex'], 'safe'],
            //[['birthday'], 'safe'],
			[['name'], 'string', 'length'=>[1,12]],
            [['mask'], 'string', 'max' => 60],
        ];
    }
	
	/*
	 * 修改信息
	 */
	public function updatebasicinfo(){
		
		$image_path = "";
		if( !empty($this->mask) ){
			$this->mask['name'] = explode(".", $this->mask['name']);
			$this->mask['name'] = $this->mask['name'][1];
			$image_path = '../uploadfile/user_mask/'.Tool::timeToMillisecond(microtime()).'.'.$this->mask['name'];
			if(Tool::uploadimage( $this->mask, $image_path ) !== true){
				return Tool::return_json( false, 'mask', '图片保存失败' );
			}
		}
		
		$user = new Userbasicinfo();
		$user = Userbasicinfo::findOne(['phone' => $this->phone]);
		$user->name = empty($this->name)?$user->name:$this->name;
		$user->sex = empty($this->sex)?$user->sex:$this->sex;
		$user->mask = empty($this->mask)?$user->mask:$image_path;
		
		$user->update();
		
		$session = Yii::$app->session;
		$session['user'] = [
			'id' => $user->id,
			'permissions' => $user->permissions,
			'phone' => $user->phone,
			'password' => $user->password,
			'mask' => $user->mask,
			'name' => $user->name,
			'sex' => $user->sex,
			'birthday' => $user->birthday,
		];
		return Tool::return_json( true, 'user', array_diff_assoc($session['user'], ['password' => $session['user']['password']]) );
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