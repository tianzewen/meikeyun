function getCsrf(){
	var scrfToken = "";
	$.ajax({
		type:"get",
		url:config('ip') + "web/home/csrf",
	    async:false,
        success:function(data){
	  	    scrfToken = data;
	    }
	});
	return scrfToken;
}
/*获取url参数*/
function _get() {
	var url = location.href;
	var parameter = new Array();
	if(url.indexOf("?") != -1) {
		url = url.split("?");
		url = new Array(url[1]);
	} else {
		return "";
	}
	if(url[0].indexOf("&") != -1) {
		url = url[0].split("&");
	}
	for(var i=0;i < url.length;i++) {
		var one_parameter = url[i].split("=");
		parameter[one_parameter[0]] = one_parameter[1];
	}
	return parameter;
}
/*选择图片后验证
function setImagePreview(inputid, canvasid) {
	//获取input[type='file']控件
	var docObj = document.getElementById(inputid);
	//获取canvas控件
	var imgObjPreview=document.getElementById(canvasid);
	var exp = /.jpg$|.png$|.jpeg$/;
	if(docObj.files && exp.exec(docObj.value) == null){
		alert('仅支持JPEG、JPG、PNG格式图片');
		docObj.outerHTML = docObj.outerHTML;
	}
	//获取文件列表
	var file = docObj.files;
	//循环文件列表
	for(var key=0;key<file.length;key++){
		//实例化文件读取类
		var reader = new FileReader();
		reader.readAsDataURL(file[key]);
		reader.onload = function(e){
			//实例化img
			var img = new Image();
			//img加载图片
			img.src = e.target.result;//this.result
			img.onload = function(){
				var old_px = img.width*img.height;
				if(img.width > 1980){
					img.height *= 1980/img.width;
					img.width = 1980;
				} else if(img.height > 1080){
					img.width *= 1080/img.height;
					img.height = 1080;
				}
				var new_px = img.width*img.height;
				var ctx = canvas.getContext("2d");
				//清理canvas
				ctx.clearRect(0, 0, canvas.width, canvas.height);
				//定义宽高
				canvas.width = img.width;
				canvas.height = img.height;
				//canvas加载图片
				ctx.drawImage(img, 0, 0, img.width, img.height);
				//canvas.toDataURL().length;
			}
		}
	}
}*/
function setImagePreview(inputid, previewid) {
	//获取input[type='file']控件
	var docObj = document.getElementById(inputid);
	//获取img控件
	var imgObjPreview=document.getElementById(previewid);
	var exp = /.jpg$|.png$|.jpeg$/;
	if(docObj.value == ""){
		imgObjPreview.src = "images/add.jpg";
		return ;
	}
	if(docObj.files && exp.exec(docObj.value) == null){
		alert('仅支持JPEG、JPG、PNG格式图片');
		docObj.outerHTML = docObj.outerHTML;
	}
	imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);
}
/*动态加载*/
var head_loading = {
    css: function(path){
		if(!path || path.length === 0){
			throw new Error('argument "path" is required !');
		}
		var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.href = path;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        head.appendChild(link);
    },
    js: function(path){
		if(!path || path.length === 0){
			throw new Error('argument "path" is required !');
		}
		var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.src = path;
        script.type = 'text/javascript';
        head.appendChild(script);
    }
}
//加载html
function loadHTML(name, json){
	$.get(config('pub_html') + name + '.html',
		function(tpl, status){
			json.ip = config('ip');
			json.csrf = getCsrf();
			if( checkCookie('user') ){
				var user = getCookie('user');
				json.username = user.name;
				json.usernameOnclick = "window.location.href = config('ip') + 'views/home/userhome.html'";
				json.usersignoutText = '退出';
				json.usersignoutOnclik = 'signout()';
				
				json.signinForm = 'none';
				json.registerForm = 'none';
			} else {
				json.username = '登录';
				json.usernameOnclick = "openWin('signinForm')";
				json.usersignoutText = '注册';
				json.usersignoutOnclik = "openWin('registerForm')";
			}
			var myTmpl = $.templates(tpl);
			var html = myTmpl.render(json);
			$(name).html(html);
			//$(name).html(tpl);
		}
	);
	// head_loading.js(config('pub_js') + name + '.js');
	head_loading.css(config('pub_css') + name + '.css');
}
//数组转json
function arrToJson(arr){
	var json = {};
	for(var key in arr){
		json[key] = arr[key];
	}
	return JSON.stringify(json); 
}
//json转数组
function jsonToArr(arr){
	return eval(arr); 
}
//验证cookie是否存在
function checkCookie(name) {
    var c = document.cookie.indexOf(name + "=");
    if (c != -1) {
        return true;
    }
    else {
		return false;
    }
}
//生成cookie
function setCookie(arr){
	var cookie = "user=";
	var date = new Date();
	date.setTime(date.getTime() + 36000000);
	//大致是因为JS会自己解码一次，所以编码的时候要编码两次。。。。。。有能力的补充注释吧
	cookie += encodeURI(encodeURI(arrToJson(arr))) + ";Path=/;expires=" + date.toGMTString();
	//为了让Safari浏览器检验中文，把cookie先进行ACSII编码
	document.cookie = cookie;
}
//获取cookie
function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    if (arr = document.cookie.match(reg)) {
		return eval('(' + decodeURI(unescape(arr[2])) + ')');
    }
    else
        return null;
}
//删除cookiie
function deleteCookie(name){
	document.cookie = "PHPSESSID=;expires="+(new Date(0)).toGMTString()+";Path=/";
	document.cookie = name+"=;expires="+(new Date(0)).toGMTString()+";Path=/";
}
//注销登录
function signout(){
	deleteCookie('user');
	window.location.href = window.location.href;
}
//浏览器类型
function myBrowser(){
    var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
    var isOpera = userAgent.indexOf("Opera") > -1;
    if (isOpera) {
        return "Opera"
    }; //判断是否Opera浏览器
    if (userAgent.indexOf("Firefox") > -1) {
        return "FF";
    } //判断是否Firefox浏览器
    if (userAgent.indexOf("Chrome") > -1){
		return "Chrome";
	}
    if (userAgent.indexOf("Safari") > -1) {
        return "Safari";
    } //判断是否Safari浏览器
    if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && !isOpera) {
        return "IE";
    }; //判断是否IE浏览器
}