<?php
/**
 * 路由器
 * limingyou
 * @since 2014-06-05
 */

class Router {

    private static $_PARAMS = '';

    private static $_URI = '';

    private static $_ROUTES = array();

    private static $_DEFAULT_ACTION = 'index';

    //路由类型
    const ROUTE_TYPE_STR    = 0;    //静态路由
    const ROUTE_TYPE_WC     = 1;    //wildcard 通配符
    const ROUTE_TYPE_RE     = 2;    //正则

    //路由类型对应key
    const ROUTE_KEY_STR     = 'str';
    const ROUTE_KEY_WC      = 'wc';
    const ROUTE_KEY_RE      = 're';
    
    /**
     * 设置路由参数
     * @author limingyou
     */
    public static function setParams() {
        $uri = self::$_URI = isset($_GET['_ac'])&& $_GET['_ac'] ? rtrim($_GET['_ac'], '/') : '';
        $cParams = self::parseRoute();
        $cParams['get'] = $_GET;
        $cParams['post'] = $_POST;

        self::$_PARAMS = $cParams;
    }

    /**
     * 获取参数
     */
    public static function getParams() {
        if( !self::$_PARAMS ) {
            self::setParams();
        }
        return self::$_PARAMS;
    }

    /**
     * 解析路由
     */
    public static function parseRoute() {
        $uri = self::$_URI;
        $cParams['route'] = ''; //匹配到的路由
        $cParams['uri'] = $uri;
        $cParams['app'] = appConfig::APP_NAME;

        //默认页
        if( $uri == '' ) {
            return self::_setCParams($cParams, '', appConfig::DEF_CONTROLLER, self::$_DEFAULT_ACTION, array());
        }else{
            //str
            if( isset(self::$_ROUTES[self::ROUTE_KEY_STR])
                && array_key_exists($uri, self::$_ROUTES[self::ROUTE_KEY_STR]) ) {
                $cParams['route'] = $uri;
                return self::_setCParams($cParams, 
                    self::$_ROUTES[self::ROUTE_KEY_STR][$uri]['m'], 
                    self::$_ROUTES[self::ROUTE_KEY_STR][$uri]['c'], 
                    self::$_ROUTES[self::ROUTE_KEY_STR][$uri]['a'], 
                    self::$_ROUTES[self::ROUTE_KEY_STR][$uri]['args']);
            }

            //通配符
            
            //preg
            $matchRule = self::_matchRegExp();
            if( $matchRule !== false ) {
                $cParams['route'] = $matchRule;
                return self::_setCParams($cParams, 
                    self::$_ROUTES[self::ROUTE_KEY_RE][$matchRule]['m'], 
                    self::$_ROUTES[self::ROUTE_KEY_RE][$matchRule]['c'], 
                    self::$_ROUTES[self::ROUTE_KEY_RE][$matchRule]['a'], 
                    self::$_ROUTES[self::ROUTE_KEY_RE][$matchRule]['args']);
            }

            //默认通用路由，当所有路由未匹配且配置使用通用路由时触发
            if( appConfig::DEFAULT_ROUTE_RULE && false === strpos($uri, '.') ) {
                $defRule = explode('/', $uri);
                $defRuleCount = count($defRule);
                if( $defRuleCount == 1 ) {
                    return self::_setCParams($cParams, '', $defRule[0], self::$_DEFAULT_ACTION, array());
                }
                if( $defRuleCount == 2 ) {
                    return self::_setCParams($cParams, '', $defRule[0], $defRule[1], array());
                }
                if( $defRuleCount == 3 ) {
                    return self::_setCParams($cParams, $defRule[0], $defRule[1], $defRule[1], array());
                }
            }

            //404
            if( false === strpos($uri, '404') ) {
                header("Location: " . appConfig::PAGE_NOT_FOUND);exit;
            }else{
                throw new BaseException('没有找到对应文件' . $uri);
            }
        }

        return $cParams;
    }

    private static function _setCParams(& $cParams, $model, $controller, $action, $args) {
        $cParams['model'] = $model;
        $cParams['controller'] = $controller;
        $cParams['action'] = $action;
        $cParams['args'] = $args;

        return $cParams;
    }

    /**
     * 匹配正则路由
     */
    private static function _matchRegExp() {
        $uri = self::$_URI;
        preg_match('/^[a-z]+/i', $uri, $firstString);
        if( !empty($firstString) ) {
            $firstString = $firstString[0];
            $rules = array();
            $re = isset(self::$_ROUTES[self::ROUTE_KEY_RE]) ? self::$_ROUTES[self::ROUTE_KEY_RE] : array();
            foreach( $re as $kRule => $v ) {
                if( false !== strpos($kRule, $firstString) ) {
                    $rules[$kRule] = $v;
                }
            }
            $i = 0;
            foreach( $rules as $kRule => $v ) {
                $i++;
                preg_match('/' . $kRule . '/', $uri, $mat);
                if( !empty($mat) ) {
                    self::_reSetArgs(self::$_ROUTES[self::ROUTE_KEY_RE][$kRule]['args'], $mat);
                    return $kRule;
                }
            }
        }else{
            return false;
        }

        return false;
    }

    /**
     * 重设路由参数值
     */
    private static function _reSetArgs(&$args, $match) {
        $newArgs = array();
        foreach( $args as $k=>$val ) {
            $newArgs[$val] = $match[$k+1];
        }
        $args = $newArgs;
        unset($newArgs);
    }

    /**
     * 添加一个get类型路由
     * @author limingyou
     */
    public static function get($url, $values, $type=0) {
        $key = !$type ? self::ROUTE_KEY_STR : ($type==1 ? self::ROUTE_KEY_WC : self::ROUTE_KEY_RE);
        self::$_ROUTES[$key][$url] = $values;
    }

    /**
     * 添加一个post类型路由(暂不实现)
     * @author limingyou
     */
    public static function post($url, $values, $type=0) {
        self::$_ROUTES[$url] = $values;
    }

    /**
     * 添加一个自由类型路由(暂不实现)
     * @author limingyou
     */
    public static function any($url, $values, $type='') {
        self::$_ROUTES[$url] = $values;
    }

    public static function getRoutes() {
        return self::$_ROUTES;
    }

}
    
