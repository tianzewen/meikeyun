if( checkCookie('user') ){
	var user = getCookie('user');
	$('#signin').html(user['name']);
	$('#signin').attr('href', config('ip') + "views/home/userhome.html");
	$('#register').html('退出');
	$('#register').attr('href', 'javascript:signout()');
}
$("#logo").attr('src', config('pub_img') + 'logo64x64.png');