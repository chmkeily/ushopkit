<?php

/**
* @version 0.1
* @author hemingchen@tencent.com
* @class RPCService
* @brief 提供一套基于HTTP的工具
*/
class RPCService
{
	/**
	* @brief 发起http请求
	* @param $url {string}	所需要访问的url
	* @param $data {string} 所需要post的数据
	*/
	public static function http_request($url, $data = null)
	{
		if (function_exists('curl_init'))
		{
			return self::curl_fetch($url, $data);
		}

		return false;
	}

	/**
	* @brief 使用curl发送http请求
	* @param $url {string}	目标url
	* @param $post {string|array}	POST内容
	* @return 请求失败返回false, 否则返回响应内容(不含HTTP头)
	*/
	function curl_fetch($url, $post = null)
	{
		//默认OPTIONS
		$options = array(
				CURLOPT_URL				=> $url,
				CURLOPT_TIMEOUT			=> 5,
				CURLOPT_HEADER			=> false,
				CURLOPT_AUTOREFERER		=> true,
				CURLOPT_RETURNTRANSFER	=> true,

				CURLOPT_SSL_VERIFYPEER	=> false,	//跳过SSL整数检查
			);

		//设置POST内容
		if (!empty($post))
		{
			if (is_array($post)) {
				$post = http_build_query($post);
			}

			$options[CURLOPT_POST]			= true;
			$options[CURLOPT_POSTFIELDS]	= $post;
		}

		//发起RPC请求
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$result	= curl_exec($ch);
		$errno 	= curl_errno($ch);
		curl_close($ch);

		return (0 === $errno) ? ($result) : false;
	}
}

?>