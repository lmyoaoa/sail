<?php
/**
 * 工具类，纯功能的函数，放这里，含一些逻辑的代码，放到Helper助手类中
 */

class Util {

    /**
     * 登录跳转助手
     * @author limingyou
     */
    public static function isLogin() {
        /*获取登录用户信息*/
        if( Request::getCookie('_ido_uid', 0, 'int') ) {
            return true;
        }
        return false;
    }

    /**
     * 页面跳转
     * @author limingyou
     * @param string $url 跳转地址
     * @param bool $urldecode 是否使用urldecode
     * @param int $code 跳转代码301,302,404...
     * @return void
     */
    public static function turnTo($url, $urldecode=false, $code=302) {
        if( $urldecode ) {
            $url = urldecode($url);
        }
        header("Location: {$url}");
    }

    /**
     * 格式化输出
     * @param string $string 要输出的字符串
     */
    public static function e($string) {
        echo htmlspecialchars($string);
    }
    
    public static function weekDay( $n ) {
        $array = array('1'=>'一', '二', '三', '四', '五', '六', '日',);
        return $array[$n];
    }

    /**
    传入大写的汉字
    **/
    public static function weekConfig( $n ) {
        $array = array( 
            '一'=>'月',
            '二'=>'火',
            '三'=>'水',
            '四'=>'木',
            '五'=>'金',
            '六'=>'土',
            '日'=>'日',
        );
        //日曜日
        return $array[$n];
    }


}
