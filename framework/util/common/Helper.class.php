<?php

/**
 * 通用辅助类，此类下的函数通常与业务逻辑相近
 * @author limingyou
 */

class Helper {

    /**
     * 判断用户是否登录并返回用户cookie中的基本信息
     */
    public static function getUserCookieInfo() {
        $isLogin = Util::isLogin();
        $userInfo = array();
        if( $isLogin ) {
            $userInfo['userid'] = $userInfo['uid'] = intval(Request::getCookie('_ido_uid', 0, 'int'));
            $userInfo['username'] = Request::getCookie('_ido_username');
        }

        return $userInfo;
    }

}
