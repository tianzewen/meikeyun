function config(item){
	var config = new Array();
	var ip = 'http://' + window.location.host + '/';
	config = {
		'ip':ip, 
		'pub_html':ip + 'views/public/html/',
		'pub_js':ip + 'views/public/js/', 
		'pub_css':ip + 'views/public/css/',
		'pub_img':ip + 'views/public/images/',
	};
	
	return config[item];
}