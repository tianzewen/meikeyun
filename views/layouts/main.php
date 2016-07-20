<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="别具匠心,手工教程,手工皮革,手工定制,个性体验,时尚生活"/>
	<meta name="description" content="交流分享有关手工的各种图片教程,定制手工饰品,手工服饰,手工皮革等手工制品"/>
	<link rel="shortcut icon" type="image/x-icon" href="/views/public/images/logo.ico"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<link href="/views/public/css/core.css" rel="stylesheet">
	<link href="/views/public/css/nav.css" rel="stylesheet">
	
	<script src="/views/public/js/jquery-3.0.0.js" ></script>
	<script src="/views/public/js/jsrender.min.js"></script>
	<script src="/views/public/js/config.js"></script>
	<script src="/views/public/js/functions.js"></script>
</head>
<body>

<div class="wrap">
	<nav class="nav">
		<div class="pub_wid nav-tripe">
			<ul>
				<div class="menu_logoDiv">
					<img id="logo" src="/views/public/images/logo_wid60.jpg" onclick="window.location.href=config('ip')" />
				</div>
				<li class="menu animation_header_a">
					<a class="padding--" href="#">设计师</a>
					<ul>
						<li class="padding--">时间线</li>
						<li class="padding--">那些年</li>
						<li class="padding--">现如今</li>
					</ul>
				</li>
				<li class="menu animation_header_a">
					<a class="padding--" href="#">作品</a>
					<ul>
						<li class="padding--">空间装饰</li>
						<li class="padding--">手工艺品</li>
						<li class="padding--">服饰服配</li>
						<li class="padding--">手办模型</li>
						<li class="padding--">生活日用</li>
					</ul>
				</li>
				<li class="menu animation_header_a">
					<a class="padding--" href="#">创意设计</a>
					<ul>
						<li class="padding--">这创意</li>
						<li class="padding--">那创意</li>
					</ul>
				</li>
				<li class="menu animation_header_a">
					<a class="padding--" href="#">私人定制</a>
					<ul>
						<li class="padding--">定制这个</li>
						<li class="padding--">定制那个</li>
					</ul>
				</li>
				<li class="menu animation_header_a">
					<a class="padding--" href="#">超能魅客</a>
					<ul>
						<li class="padding--" onclick="window.location.href=config('ip') + 'views/home/uploadwork.html'">上传作品</li>
						<li class="padding--">上传教程</li>
					</ul>
				</li>
				<div class="user">
					<?php
					$session = Yii::$app->session;
					echo Yii::$app->user->isGuest ? (
						'<a class="padding-- animation_header_a" id="signin" href="javascript:openWin(\'signinForm\')">登录</a>'.
						'<a class="padding-- animation_header_a" id="register"  href="javascript:openWin(\'registerForm\')">注册</a>'
					) : (
						'<a class="padding-- animation_header_a" id="signin" href="/web/site/userhome">'.Yii::$app->user->identity->name.'</a>'.
						'<a class="padding-- animation_header_a" id="register" href="javascript:userLogoutSub()">注销</a>'
					);
					?>
				</div>
			</ul>
		</div>
	</nav>
	<div class="bigBackground" style="display:{{:signinForm}}" id="signinForm" onclick="closeWin()">
		<div class="siginForm_div">
			<!--<div class="close_btn" onclick="closeWin('signinForm')">×</div>-->
			<form id="userSigninForm" class="userForm" name="userSigninForm" method="post" action="/web/user/login">
				<img class="logo" src="/views/public/images/logo64x64.png" />
				<input type='hidden' name='_csrf' value="{{:csrf}}"/>
				<input class="login_input" name="UserLoginForm[phone]" autoComplete="off" placeholder="手机号码" type="text" />
				<input class="login_input" name="UserLoginForm[password]" placeholder="密码" type="password" />
				<div class="login_checkbox_div"><input id="remember" type="checkbox" /><label for="remember"> 记住我</label></div>
				<div class="login_forget_div"><a>忘记密码？</a></div>
				<input class="login_input login_sub" onclick="userSigninSub()" readonly type="text" value="登录"/>
				<div class="login_other">使用第三方登录:</div>
				<a class="to_register" onclick="openWin('registerForm')">注册魅客云账号</a>
				<input type="submit" style="display:none;" value="提交按钮，测试用"/>
			</form>
		</div>
	</div>
	<div class="bigBackground" style="display:{{:registerForm}}" id="registerForm" onclick="closeWin()">
		<div class="registerForm_div">
			<!--<div class="close_btn" onclick="closeWin('signinForm')">×</div>-->
			<form id="userRegisterForm" class="userForm" name="userRegisterForm" method="post" action="/web/user/register">
				<img class="logo" src="/views/public/images/logo64x64.png" />
				<input type='hidden' name='_csrf' value="{{:csrf}}"/>
				<input class="login_input" name="userRegisterForm[phone]" autoComplete="off" placeholder="手机号码" type="text" />
				<input class="icode" name="userRegisterForm[icode]" autoComplete="off" placeholder="6位验证码" type="text" />
				<div class="get_icode_btn" onclick="getIcode()">获取验证码</div>
				<input class="login_input" name="userRegisterForm[password]" placeholder="8-16密码" type="password" />
				<input class="login_input register_sub" onclick="useRegisterSub()" readonly type="text" value="注册"/>
				<a class="to_login" onclick="openWin('signinForm')">已有账号，去登录</a>
			</form>
		</div>
	</div>
	<script>
		//登录框的方法
		function closeWin(){
			$("#signinForm").fadeOut();
			$("#registerForm").fadeOut();
		}

		function openWin(id){
			if( id == "signinForm"){
				$("#signinForm").fadeIn();
				$("#registerForm").fadeOut();
			} else {
				$("#registerForm").fadeIn();
				$("#signinForm").fadeOut();
			}
		}
		//点击停止冒泡
		$('.siginForm_div').click(function(e){
			e.stopPropagation();
		})
		//点击停止冒泡
		$('.registerForm_div').click(function(e){
			e.stopPropagation();
		})
		//提交用户登录表单
		function userSigninSub(){
			var formData = new FormData(document.getElementById("userSigninForm"));
			//console.log(formData);
			$.ajax({
			  type:"post",
			  url:config('ip') + "web/user/login",
			  data:formData,
			  processData:false,// 告诉jQuery不要去处理发送的数据
			  contentType:false,// 告诉jQuery不要去设置Content-Type请求头
			  async:false,
			  success:function(data){
				  var obj = eval ("(" + data + ")");
				  if(obj['status']){
					  //setCookie(obj.user);
					  location.reload();
				  } else {
					  //删除对象中的元素
					  delete obj.status;
					  for(var key in obj){
						  alert(obj[key]);
					  }
				  }
			  },
			  error:function(error){
				  alert("提交表单失败");
			  }
			});
		}
		function userLogoutSub(){
			$.ajax({
			  type:"post",
			  url:config('ip') + "web/user/logout",
			  processData:false,// 告诉jQuery不要去处理发送的数据
			  contentType:false,// 告诉jQuery不要去设置Content-Type请求头
			  async:false,
			  success:function(data){
				  location.reload();
			  },
			  error:function(error){
				  alert("提交表单失败");
			  }
			});
		}
		//提交用户注册表单
		function useRegisterSub(){
			var formData = new FormData(document.getElementById("userRegisterForm"));
			//console.log(formData);
			$.ajax({
			  type:"post",
			  url:config('ip') + "web/user/register",
			  data:formData,
			  processData:false,// 告诉jQuery不要去处理发送的数据
			  contentType:false,// 告诉jQuery不要去设置Content-Type请求头
			  async:false,
			  success:function(data){
				  var obj = eval ("(" + data + ")");
				  if(obj['status']){
					  openWin('signinForm');
				  } else {
					  //删除对象中的元素
					  delete obj.status;
					  for(var key in obj){
						  alert(obj[key]);
					  }
				  }
			  },
			  error:function(error){
				  alert("提交表单失败");
			  }
			});
		}
		//获取验证码
		function getIcode(){
			var phone = $("input[name='userRegisterForm[phone]']").val();
			$.ajax({
			  type:"get",
			  url:config('ip') + "web/user/checkphone?phone=" + phone,
			  processData:false,// 告诉jQuery不要去处理发送的数据
			  contentType:false,// 告诉jQuery不要去设置Content-Type请求头
			  async:false,
			  success:function(data){
				  var obj = eval ("(" + data + ")");
				  if(obj['status']){
					  alert("验证码成功生成");
				  } else {
					  //删除对象中的元素
					  delete obj.status;
					  for(var key in obj){
						  alert(obj[key]);
					  }
				  }
			  },
			  error:function(error){
				  alert("提交表单失败");
			  }
			});
		}
	</script>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
</footer>

</body>
</html>
<?php $this->endPage() ?>
