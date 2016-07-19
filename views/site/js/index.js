$(function(){
	//定义动画位置
	index = 0;
	size();
	$(window).resize(function(){
		size();
	});
})
function size(){
	var banner_img_lg = $(".banner_img_div").length;
	var body_wid = $("body").width();
	var window_hig = $(window).height();
	$(".banner_div").height( window_hig - 60 );
	$(".banner").height( window_hig - 60 );
	$(".banner_img_div").height( window_hig - 60 );
	$(".banner_img_div img").css( 'max-height', window_hig - 60 );
	$(".banner_img_div").width( body_wid );
	$(".banner").width( banner_img_lg * body_wid );
	if( index > 0 ){ index--; }
	$(".banner").css("left", -index * $("body").width());
	stop()
	//开始动画
	star();
}
function star(){
	t = setInterval("bannerAnimation()", 5000);
}
function stop(){
	if( typeof(t) != "undefined"){
		clearInterval(t);
	}
}
function bannerAnimation() {
	if( (index + 1) > $(".banner_img_div").length ){
		index = 0;
	}
	$(".banner").animate({left:-index * $("body").width()}, 1000);
	index++;
}
function minBtnClick( number ){
	index = number;
	$(".banner").animate({left:-index * $("body").width()}, 500);
	index++;
}
window.onmousewheel=function(e){
	hei = parseInt($(".banner").height() + 60 );
	var e = event || window.event
	var start = e.wheelDelta;
	var scrtop=0;
	if(isIE()){
		scrtop= parseInt($("html,body").scrollTop())
	}else{
		scrtop=$("body").scrollTop();
	}
	
	$("body").stop();
	if( start < 0 && scrtop < hei ){
		$("body,html").animate({scrollTop:hei},600);
	}
	if( start > 0 && scrtop < hei + 100 ) {
		$("body,html").animate({scrollTop:0},600);
	}
}
function isIE() {
	if (!!window.ActiveXObject || "ActiveXObject" in window)
		return true;
	else
		return false;
}