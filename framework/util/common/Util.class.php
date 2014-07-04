<?php
/**
 * 工具类
 */

class Util {

    /**
     * 登录跳转助手
     * @author limingyou
     */
    public static function isLogin() {
        /*获取登录用户信息*/
        if( $_COOKIE['_ido_uid'] ) {
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

    
}
