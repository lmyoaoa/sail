<?php
/**
 * HTTP相关功能封装
 * @author limingyou
 */
class Http {

    const TIMEOUT = 10;
    
	/*********
	功能：模拟post
	作者：limingyou
	日期：2012-06-04
	参数：  $url post目标地址
            $postdata post数据
            $proxy 代理地址 (无需
	**********/
    public static function post($url, $postdata, $proxy="") {
        try {
            $proxy=trim($proxy);
            $user_agent ="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
            $ch = curl_init();    // 初始化CURL句柄
            if(!empty($proxy)) {
                curl_setopt ($ch, CURLOPT_PROXY, $proxy);//设置代理服务器
            }

            curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);// 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
            curl_setopt($ch, CURLOPT_POST, 1);//启用POST提交
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); //设置POST提交的字符串
            curl_setopt($ch, CURLOPT_TIMEOUT, 25); // 超时时间
            curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);//HTTP请求User-Agent:头
            curl_setopt($ch,CURLOPT_HTTPHEADER, array(
                'Accept-Language: zh-cn',
                'Connection: Keep-Alive',
                'Cache-Control: no-cache'
            ));//设置HTTP头信息
            $document = curl_exec($ch); //执行预定义的CURL
            $info=curl_getinfo($ch); //得到返回信息的特性

            curl_close($ch);
            return $document;
        } catch (Exception $e) {
            return false;
        }
    }

	/***
	功能：获取内容
	作者：limingyou
	日期：2012-11-23
	***/
	public static function get($url, $referer='') {
		$referer = $referer ? $referer : $url;
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_REFERER,$referer);
		curl_setopt($curl,CURLOPT_URL,$url);
		#curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:17.0) Gecko/20100101 Firefox/17.0");
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C)");
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$con = curl_exec($curl);
		curl_close($curl);
		return $con;
	}

}
