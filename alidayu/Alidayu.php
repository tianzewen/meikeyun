<?php

namespace app\alidayu;

use Yii;
class Alidayu
{
	private $appkey = '23360699';
	private $secret = '2cea008d88ce3e1b4d9eb67521cbd554';
	
	//发送手机验证码--阿里大鱼
	public function smsAlidayu($phone,$ok_code='0'){
		require 'TopSdk.php';//引入加载相关的类文件
	//  require './extensions/msg/Alidayu/top/TopClient.php';
	//  require './extensions/msg/Alidayu/top/request/AlibabaAliqinFcSmsNumSendRequest.php';

		$c = new \TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$c->format = 'json';
	 
		$v_code = mt_rand(100000,999999);       //生成六位随机数
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("");
		$req->setSmsType("normal");
		$req->setSmsFreeSignName("别具匠心");
		$req->setSmsParam("{icode:'{$v_code}'}");
		$req->setRecNum($phone);
		$req->setSmsTemplateCode("SMS_10686075");
		$resp = $c->execute($req);
	 
		$sms_ok = '2';              //默认情况下不成功
		if(isset($resp->result->err_code)){
			if($resp->result->err_code == '0'){   //发送成功
				$sms_ok = '1';
				$session = Yii::$app->session;
				$session['tel_'.$phone] = [
					'icode' => $v_code,
					'lifetime' => 60,
				];
				$success_arr['status'] = true;
				return Json_encode( $success_arr );
			}
		} else {
			$error_arr['status'] = false;
			$error_arr['phone'] = "频繁获取手机验证码";
			return Json_encode( $error_arr );
		}
		/*$model = new top\WebsiteSmsList;        //记录发送验证码的信息到数据库
		$model->code = $v_code;
		$model->name = $phone;
		$model->add_time = time();
		$model->isNewRecord = true;
		if($sms_ok == '1'){     //发送成功的情况下
			$model->status = '1';
			$model->remark = '发送成功';
			$model->save();
			$this->_end($ok_code,'手机验证码发送成功.','');
		}else{
			$model->status = '2';
			$model->remark = '发送失败';
			$model->save();
			$this->_end('1','非常抱歉,短信验证码发送失败,请稍后再试.','');
		}*/
	}
}