<?php
/**
 * HTTP获取相关操作
 * @author limingyou
 */

class Request {
    /**
     *  判断 http method 是否为get
     */
    public static function isGet() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
     * 判断页面是否为post请求
     * @return boolean
     */
    public static function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
    
    private static function _stripSlashes(&$arr) {
        if (is_array ($arr)) {
            foreach ($arr as &$v) {
                self::_stripSlashes($v);
            }
        } else {
            $arr = stripslashes($arr);
        }
    }

    /**
     * 魔术引用问题
     * */
    public static function filterStripSlashes() {
        self::_stripSlashes($_GET);
        self::_stripSlashes($_POST);
        self::_stripSlashes($_COOKIE);
        self::_stripSlashes($_REQUEST);
    }

    /**
    * 取得当前页面url
    */
    public static function getCurrentUrl() {
        $pageURL = 'http';
        if (! empty ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";
        $pageURL .= "://";
        $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }

    /**
     * 本页的URL，可用于登录返回用等
     * @param bool $isQuery 是否获取参数
     * @return string
     */
    public static function thisPageUrl($isQuery=true) {
        if($isQuery && $_SERVER['QUERY_STRING']) {
            $return_url = urlencode($_SERVER['SCRIPT_URI'].'?'.$_SERVER['QUERY_STRING']);
        }else{
            $return_url = urlencode($_SERVER['SCRIPT_URI']);
        }

        return $return_url;
    }

    private static function _stripTags($string, $isHTML=false) {
        return !$isHTML ? strip_tags(trim($string)) : trim($string);
    }

    private static function _htmlspecialchars($string, $isHTML=false) {
        return !$isHTML ? htmlspecialchars(trim($string)) : trim($string);
    }

    /**
     * 获取POST中的数据
     * @param $key				POST中的key
     * @param string $key       值类型，当设置为int时，值将格式化为数字
     * @param $default			如果数据不存在，默认返回的值。默认情况下为空
     * @return string
     */
    public static function getPost($key, $default = '', $type='', $isHTML=false) {
        if (array_key_exists($key, $_POST)) {
            if( $type == 'int' ) {
                return intval($_POST[$key]);
            }
            return self::_htmlspecialchars($_POST[$key], $isHTML);
        }
        return $default;
    }

    /**
     * 获取GET中的数据
     * @param $key				GET中的key
     * @param string $key       值类型，当设置为int时，值将格式化为数字
     * @param $default			如果数据不存在，默认返回的值。默认情况下为空
     * @return string
     */
    public static function getGet($key, $default = '', $type='', $isHTML=false) {
        if (array_key_exists($key, $_GET)) {
            if( $type == 'int' ) {
                return intval($_GET[$key]);
            }
            return self::_htmlspecialchars($_GET[$key], $isHTML);
        }
        return $default;
    }

    /**
     * 获取REQUEST中的数据
     * @param $key				REQUEST中的key
     * @param $default			如果数据不存在，默认返回的值。默认情况下为空
     * @param $isHTML          返回的结果中是否允许html标签，默认为false
     * @return string
     * */
    public static function getRequest($key, $default = '', $type='', $isHTML = false) {
        if (array_key_exists($key, $_REQUEST)) {
            if( $type == 'int' ) {
                return intval($_REQUEST[$key]);
            }
            return self::_htmlspecialchars($_REQUEST[$key], $isHTML);
        }
        return $default;
    }

	
	/// 获取COOKIE中的数据
	/// @param $key             COOKIE中的key
	/// @param $default         如果数据不存在，默认返回的值。默认情况下为空
	/// @param $isHTML          返回的结果中是否允许html标签，默认为false
	/// @return string
	public static function getCookie($key, $default = '', $type='', $isHTML = false) {
		if (isset ($_COOKIE[$key])) {
            if( $type == 'int' ) {
                return intval($_COOKIE[$key]);
            }
            return self::_htmlspecialchars($_COOKIE[$key], $isHTML);
		}
		return $default;
	}

    /**
     * 获取用户ip
     * @param $useInt			是否将ip转为int型，默认为true
     * @param $returnAll		如果有多个ip时，是否会部返回。默认情况下为false
     * @return string|array|false
     */
    public static function getIp($useInt = true, $returnAll=false) {
        $ip = getenv('HTTP_CLIENT_IP');
		if($ip && strcasecmp($ip, "unknown") && !preg_match("/192\.168\.\d+\.\d+/", $ip)) {
            return self::_returnIp($ip, $useInt, $returnAll);
		}
        
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        if($ip && strcasecmp($ip, "unknown")) {
            return self::_returnIp($ip, $useInt, $returnAll);
        }

        $ip = getenv('REMOTE_ADDR');
        if($ip && strcasecmp($ip, "unknown")) {
            return self::_returnIp($ip, $useInt, $returnAll);
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if($ip && strcasecmp($ip, "unknown")) {
                return self::_returnIp($ip, $useInt, $returnAll);
            }
        }
        
		return false;
    }

    private static function _returnIp($ip, $useInt, $returnAll) {
        if (!$ip) return false;

        $ips = preg_split("/[，, _]+/", $ip);
        if (!$returnAll) {
            $ip = $ips[count($ips)-1];
            return $useInt ? self::ip2long($ip) : $ip;
        }

        $ret = array();
        foreach ($ips as $ip) {
            $ret[] = $useInt ? self::ip2long($ip) : $ip;
        }
        return $ret;
    }

    /**
    对php原ip2long的封装，原函数在win系统下会出现负数
    @param string $ip
    @return int
    */
    public static function ip2long($ip) {
        return sprintf('%u', ip2long ($ip));
    }

    /**
    对php原long2ip的封装
    @param int $long
    @return string
    */
    public static function long2ip($long) {
        return long2ip($long);
    }
    
    /**
     * 获取当前参数串
     */
    public static function getCurrentQuery() {
        $current = RequestUtil::getCurrentUrl();
        $s = parse_url($current);
        return $s['query'];
    }

}

