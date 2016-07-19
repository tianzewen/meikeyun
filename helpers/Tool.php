<?php

namespace app\helpers;

class Tool 
{
	/*
	 * 生成时间戳
	 */
	public static function timeToMillisecond($time) 
	{
		list($YMD, $HMS) = explode(' ',$time);
		return (float)sprintf('%.0f',(floatval($YMD)+floatval($HMS))*1000);
	}
	
	/*
	 * 生成返回数组
	 */
	public static function return_json( $bool, $array=null, $value=null )
	{
		$return_arr = array();
		 
		$return_arr['status'] = $bool;
		 
		if( is_array($array) ){
			$return_arr += $array;
		} else if( isset($value) ){
			$return_arr[$array] = $value;
		}
		 
		return Json_encode( $return_arr );
	}
	
	/*
	 * 上传图片
	 */
	public static function uploadimage( $image, $path )
	{
		if ((($image["type"] == "image/png")
		|| ($image["type"] == "image/jpeg")
		|| ($image["type"] == "image/pjpeg"))
		&& ($image["size"] < 10485760))
		{
			if ($image["error"] > 0)
			{
				return false;
			} else {
				if (file_exists($path))
				{
					return false;
				} else {
					move_uploaded_file($image["tmp_name"],$path);
					return true;
				}
			}
		} else {
			return false;
		}
	}
	
}